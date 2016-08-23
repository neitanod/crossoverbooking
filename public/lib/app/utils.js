
/* Asks for confirmation and then fires an HTTP DELETE request (rest) and finally a redirect */
function confirm_delete(url, redirect){
  BootstrapDialog.confirm("Are you sure?", function(proceed){ if(proceed) http_delete(url, redirect); });
}

/* Fires an HTTP DELETE request (rest) and then a redirect */
function http_delete(url, redirect){
  $.ajax({
    'url': url,
    'type': 'delete',
    'success': function(){ top.location.href=redirect; },
    'error': function(){ BootstrapDialog.alert({'title':'ERROR','message':'Could not complete request.','type':'type-danger'}); },
  });
}

/* Calculates the stregth of a password (to be used just as a hint) */
function passwordStrength(pass){
  var score = 1;
  var banned = [
        // see study here: http://smrt.io/JlNfrH
        '', '12345', '123456', '1234567','12345678', '123456789',
        'password', 'iloveyou', 'princess', 'rockyou', 'abc123',
        'nicole', 'daniel', 'babygirl', 'monkey', 'jessica',
        'lovely', 'michael', 'ashley', '654321', 'qwerty',
        'password1', 'welcome', 'welcome1', 'password2', 'password01',
        'password3', 'p@ssw0rd', 'passw0rd', 'password4', 'password123',
        'summer09', 'password6', 'password7', 'password9', 'password8',
        'welcome2', 'welcome01', 'winter12', 'spring2012', 'summer12',
        'summer2012', 'aaaaaa', 'aaaaaaa', 'aaaaaaaa', '000000' ];

  if(pass.length==0)            return 0; //not allowed
  if($.inArray(pass,banned)>-1) return 1; //too common
  if(pass.length<6)             return 2; //too short
  if(pass.search(/[a-z]/)!=-1) score = score * 2;
  if(pass.search(/[A-Z]/)!=-1) score = score * 2;
  if(pass.search(/[0-9]/)!=-1) score = score * 2;
  if(pass.search(/[!@#$%^&*?\{\}\[\]\(\)\\\/\<\>~_]/)!=-1) score = Math.pow(score, 2);
  if(pass.search(/[!@#$%^&*?\{\}\[\]\(\)\\\/\<\>~_].*[!@#$%^&*?\{\}\[\]\(\)\\\/\<\>~_]/)!=-1) score = score * 4;
  if(pass.search(/[!@#$%^&*?\{\}\[\]\(\)\\\/\<\>~_].*[!@#$%^&*?\{\}\[\]\(\)\\\/\<\>~_].*[!@#$%^&*?\{\}\[\]\(\)\\\/\<\>~_]/)!=-1) score = score * 2;
  if(pass.length>8) score = score * Math.pow(pass.length,2);
  return score;
}

/* Express the stregth of a password in a 0-6 scale */
function passwordStrengthLevel(strength){
  if(strength==0) return 0;
  if(strength==1) return 1;
  if(strength==2) return 2;
  if(strength<600) return 3;
  if(strength<800) return 4;
  if(strength<2000) return 5;
  return 6;
}

/* Express the stregth of a password in a scale from 0% to 100% */
function passProgress(pass){
  return Math.max(0,passwordStrengthLevel(passwordStrength(pass))-1)*20;
}

/* Expresa the stregth of a password using the english language (given a password string) */
function passLabel(pass){
  return passwordStrengthLabel(passwordStrengthLevel(passwordStrength(pass)));
}

/* Expresa the stregth of a password using the english language (given an integer in the 0-6 scale) */
function passwordStrengthLabel(strength_level){
  var label = [
    "No permitido",
    "Demasiado común",
    "Demasiado corto",
    "Débil",
    "Medio",
    "Fuerte",
    "Muy Fuerte"
  ];
  return label[strength_level];
}

/* Comprueba si son iguales las dos contraseñas $('#new_pw') y $('#new_pw2')
 * y muestra u oculta el error $('#pw2error') */
function passMatch(){
  if($("#new_pw2").val().length && $("#new_pw").val() != $("#new_pw2").val()){
    $("#pw2error").show();
  } else {
    $("#pw2error").hide();
  }
}

/* Ejemplo de notificación dinámica de pnotify, borrar cuando se haya implementado */
function dyn_notice() {
    var percent = 0;
    var notice = new PNotify({
        text: "Please Wait",
        type: 'info',
        icon: 'fa fa-spinner fa-spin',
        hide: false,
        buttons: {
            closer: false,
            sticker: false
        },
        opacity: .75,
        shadow: false,
        width: "170px"
    });

    setTimeout(function() {
        notice.update({
            title: false
        });
        var interval = setInterval(function() {
            percent += 2;
            var options = {
                text: percent + "% complete."
            };
            if (percent == 80) options.title = "Almost There";
            if (percent >= 100) {
                window.clearInterval(interval);
                options.title = "Done!";
                options.type = "success";
                options.hide = true;
                options.buttons = {
                    closer: true,
                    sticker: true
                };
                options.icon = 'fa fa-check';
                options.opacity = 1;
                options.shadow = true;
                options.width = PNotify.prototype.options.width;
            }
            notice.update(options);
        }, 120);
    }, 2000);
}

function scrollToElement(elm, delay){
  $elm = $(elm);
  delay = !(delay)?1000:delay;
  jQuery('html, body').animate({
      scrollTop: $elm.offset().top
  }, delay);
}

function validEmail(email){
  if(!email || !email.match) return false;
  return email.match(/^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|name|net|org|pro|travel|mobi|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i);
}

function getCookie(name) {
  var value = "; " + document.cookie;
  var parts = value.split("; " + name + "=");
  if (parts.length == 2) return parts.pop().split(";").shift();
}

function getStandIdInternal(){
  return top.STAND_ID_INTERNAL;
}

function getEventId(){
  return top.EVENT_ID;
}

function getAppPath(){
  return top.APP_PATH;
}
/* */
