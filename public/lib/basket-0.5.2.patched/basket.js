/*!
 * basket.js
 * v0.5.2 - 2015-02-07
 * http://addyosmani.github.com/basket.js
 * (c) Addy Osmani;  License
 * Created by: Addy Osmani, Sindre Sorhus, Andrée Hansson, Mat Scales
 * Contributors: Ironsjp, Mathias Bynens, Rick Waldron, Felipe Morais
 * Uses rsvp.js, https://github.com/tildeio/rsvp.js
 */(function( window, document ) {
   'use strict';

   var head = document.head || document.getElementsByTagName('head')[0];
   var storagePrefix = 'basket-';
   var defaultExpiration = 5000;
   var inBasket = [];

   var addLocalStorage = function( key, storeObj ) {
     try {
       localStorage.setItem( storagePrefix + key, JSON.stringify( storeObj ) );
       return true;
     } catch( e ) {
       if ( e.name.toUpperCase().indexOf('QUOTA') >= 0 ) {
         var item;
         var tempScripts = [];

         for ( item in localStorage ) {
           if ( item.indexOf( storagePrefix ) === 0 ) {
             tempScripts.push( JSON.parse( localStorage[ item ] ) );
           }
         }

         if ( tempScripts.length ) {
           tempScripts.sort(function( a, b ) {
             return a.stamp - b.stamp;
           });

           basket.remove( tempScripts[ 0 ].key );

           return addLocalStorage( key, storeObj );

         } else {
           // no files to remove. Larger than available quota
           return;
         }

       } else {
         // some other error
         return;
       }
     }

   };

   var getUrl = function( url ) {
     var promise = new RSVP.Promise( function( resolve, reject ){

       var xhr = new XMLHttpRequest();
       xhr.open( 'GET', url );

       xhr.onreadystatechange = function() {
         if ( xhr.readyState === 4 ) {
           if ( ( xhr.status === 200 ) ||
               ( ( xhr.status === 0 ) && xhr.responseText ) ) {
             resolve( {
               content: xhr.responseText,
               type: xhr.getResponseHeader('content-type')
             } );
           } else {
             reject( new Error( xhr.statusText ) );
           }
         }
       };

       // By default XHRs never timeout, and even Chrome doesn't implement the
       // spec for xhr.timeout. So we do it ourselves.
       setTimeout( function () {
         if( xhr.readyState < 4 ) {
           xhr.abort();
         }
       }, basket.timeout );

       xhr.send();
     });

     return promise;
   };

   var saveUrl = function( obj ) {
     return getUrl( obj.url ).then( function( result ) {
       var storeObj = wrapStoreData( obj, result );

       if (!obj.skipCache) {
         addLocalStorage( obj.key , storeObj );
       }

       return storeObj;
     });
   };

   var wrapStoreData = function( obj, data ) {
     var now = +new Date();
     obj.data = data.content;
     obj.originalType = data.type;
     obj.type = obj.type || data.type;
     obj.skipCache = obj.skipCache || false;
     obj.stamp = now;
     obj.expire = now + ( ( obj.expire || defaultExpiration ) * 60 * 60 * 1000 );

     return obj;
   };

   var isCacheValid = function(source, obj) {
     return !source ||
       source.expire - +new Date() < 0  ||
       obj.unique !== source.unique ||
       (basket.isValidItem && !basket.isValidItem(source, obj));
   };

   var handleStackObject = function( obj ) {
     var source, promise, shouldFetch;

     if ( !obj.url ) {
       return;
     }

     obj.key =  ( obj.key || obj.url );
     source = basket.get( obj.key );

     obj.execute = obj.execute !== false;

     shouldFetch = isCacheValid(source, obj);

     if( obj.live || shouldFetch ) {
       if ( obj.unique ) {
         // set parameter to prevent browser cache
         obj.url += ( ( obj.url.indexOf('?') > 0 ) ? '&' : '?' ) + 'basket-unique=' + obj.unique;
       }
       promise = saveUrl( obj );

       if( obj.live && !shouldFetch ) {
         promise = promise
           .then( function( result ) {
             // If we succeed, just return the value
             // RSVP doesn't have a .fail convenience method
             return result;
           }, function() {
             return source;
           });
       }
     } else {
       source.type = obj.type || source.originalType;
       source.execute = obj.execute;
       promise = new RSVP.Promise( function( resolve ){
         resolve( source );
       });
     }

     return promise;
   };

   var injectScript = function( obj ) {
     var script = document.createElement('script');
     script.defer = true;
     // Have to use .text, since we support IE8,
     // which won't allow appending to a script
     script.text = obj.data;
     head.appendChild( script );
   };

    var injectCSS = function( obj ) {
      var script = document.createElement('style');
      // have to correct requires to point to actual folder
      var transformed = obj.data;

      // url\(\"(.+)\"\)
      // url\(([^\"\'].+[^\"\']\))

      //transformed = "1: url('/.config/../test.css');  2: url('/config./../test.css');  3: url('/config.dir/../test.css'); \n 4: url('./test.css'); \n 5: url(test.css); \n 6: url(/test.css); \n 7: url(../test.css); \n 8: url(../../test.css); \n 9: url('test.css'); \n A: url('/test.css'); \n B: url('../test.css'); \n C: url(\'../../test.css'); \n D: url(\"test.css\"); \n E: url(\"/test.css\"); \n F: url(\"../test.css\"); \n G: url(\"http://a/b/../../test.css\"); \n ";

      var transform = function(text, src, f, delimiter){
        var search_delimiter = "";
        var first_char = "";
        var match_this = "";
        var replace_delimiter = "";
        if(delimiter != '') {
          search_delimiter = '\\'+delimiter;
          first_char = '';
          replace_delimiter = delimiter;
          match_this = '[^\\\\'+delimiter+']';
        }

        //src = "/saraza/";

        if(delimiter == '') {
          search_delimiter = '';
          first_char = '[^\\\'\\\"]';
          replace_delimiter = '';
          match_this = '.'
        }
        return text.replace(new RegExp(f+'\\('+search_delimiter+'('+first_char+match_this+'+)'+search_delimiter+'\\)','g'), function(v) {
          var o = v;
          if((v[f.length+1]!='/') && (v.indexOf("://")<0 || v.indexOf("://")>10)) {
            o = f+'('+replace_delimiter+
                simplify(src+ (new RegExp(f+"\\("+search_delimiter+"("+match_this+"+)"+search_delimiter+"\\)","g")).exec(v)[1])
                +replace_delimiter+')';
          }
          //console.log(v+" ==> "+o);
          return o;
        });

      }

      var simplify = function(text){
        text = text.replace( /\/\.\//g,                    '/');  //  /./       =>  /
        text = text.replace( /\/[^\/\.]+[^\/]*\/\.\.\//g,  '/');  //  /xx./../  =>  /
        text = text.replace( /\/[^\/]*[^\/\.]+\/\.\.\//g,  '/');  //  /.xx/../  =>  /
        text = text.replace( /([^:])\/\//g,              '$1/');  //  //        =>  /  (excepto en ://)
        return text;
      }

      var path = function(file){
        var o = file;
        o = (new RegExp("^([^\?\#]+/)*(.+)$")).exec(o)[1];
        //console.log("path(\""+file+"\") ==> " + o );
        if(o === undefined) o = "";
        if(o == "" && file[0] == "/") o = "/";
        return o;
      }

         transformed = transform(transformed,path(obj.url),'url','\'');
         transformed = transform(transformed,path(obj.url),'url','\"');
         transformed = transform(transformed,path(obj.url),'url','');
         //console.log(transformed);

         script.innerHTML = transformed;
         head.appendChild( script );
   };

         var handlers = {
           'default': injectScript,
           'css': injectCSS
         };

         var execute = function( obj ) {
           if( obj.type && handlers[ obj.type ] ) {
             return handlers[ obj.type ]( obj );
           }

           return handlers['default']( obj ); // 'default' is a reserved word
         };

         var performActions = function( resources ) {
           return resources.map( function( obj ) {
             if( obj.execute ) {
               execute( obj );
             }

             return obj;
           } );
         };

         var fetch = function() {
           var i, l, promises = [];

           for ( i = 0, l = arguments.length; i < l; i++ ) {
             promises.push( handleStackObject( arguments[ i ] ) );
           }

           return RSVP.all( promises );
         };

         var thenRequire = function() {
           var resources = fetch.apply( null, arguments );
           var promise = this.then( function() {
             return resources;
           }).then( performActions );
           promise.thenRequire = thenRequire;
           return promise;
         };

         window.basket = {

           preload:function(scripts){
             for(var a=0,b=scripts.length;b>a;a++) scripts[a].execute=false;
             return this.require(scripts);
           },

           execute:function(scripts){
             for(var a=0,b=scripts.length;b>a;a++) scripts[a].execute=true;
             return this.require(scripts);
           },

           css:function(scripts){
             for(var a=0,b=scripts.length;b>a;a++) {
               scripts[a].execute=true;
               scripts[a].type='css';
             }
             return this.require(scripts);
           },

           require: function(scripts) {
             for ( var a = 0, l = scripts.length; a < l; a++ ) {
               scripts[a].execute = scripts[a].execute !== false;

               if ( scripts[a].once && inBasket.indexOf(scripts[a].url) >= 0 ) {
                 scripts[a].execute = false;
               } else if ( scripts[a].execute !== false && inBasket.indexOf(scripts[a].url) < 0 ) {
                 inBasket.push(scripts[a].url);
               }
             }

             var promise = fetch.apply( null, scripts ).then( performActions );

             promise.thenRequire = thenRequire;
             return promise;
           },

           remove: function( key ) {
             localStorage.removeItem( storagePrefix + key );
             return this;
           },

           get: function( key ) {
             var item = localStorage.getItem( storagePrefix + key );
             try	{
               return JSON.parse( item || 'false' );
             } catch( e ) {
               return false;
             }
           },

           clear: function( expired ) {
             var item, key;
             var now = +new Date();

             for ( item in localStorage ) {
               key = item.split( storagePrefix )[ 1 ];
               if ( key && ( !expired || this.get( key ).expire <= now ) ) {
                 this.remove( key );
               }
             }

             return this;
           },

           isValidItem: null,

           timeout: 50000,

           addHandler: function( types, handler ) {
             if( !Array.isArray( types ) ) {
               types = [ types ];
             }
             types.forEach( function( type ) {
               handlers[ type ] = handler;
             });
           },

           removeHandler: function( types ) {
             basket.addHandler( types, undefined );
           }
         };

         // delete expired keys
         basket.clear( true );

 })( this, document );
