function SingleBootstrapUploader(options){

  var self = this;

  var default_options = {
    'mime': ['image/png','image/jpg','image/jpeg'],
    'element': '.sbup',
    'success': function(){},
    'error': function(){},
    'cancel': false,
    'csrf_token': false,
    'autosend': true
  };

  this.initialize = function(){
    self.options = jQuery.extend(default_options, options);

    self.elm = $(self.options['element']);
    self.form = self.elm.find('.sbup-form');
    self.status_msg_elm = self.elm.find('.sbup-status-message');
    self.upload_btn = self.elm.find('.sbup-upload-btn');
    self.xsrf_field = self.elm.find('.sbup-xsrf-token');
    self.upload_btn.click(self.upload);
    self.select_btn = self.elm.find('.sbup-select-btn');
    self.select_btn.click(self.selecting);
    self.max_file_size = self.elm.find('.sbup-max-file-size-field').val();
    self.progress = self.elm.find('.sbup-progress');
    self.file_field = self.elm.find('.sbup-field');

    if(self.options.cancel){
      self.cancel_btn = $('.sbup-cancel-btn');
      self.cancel_btn.removeClass('hidden').click(self.options.cancel);
    }

    if(self.options['autosend']){
      // Upload as soon as file is selected
      self.select_btn.change(function(){self.upload_btn.click()});
    }
  }

  this.bytesToSize = function(bytes) {
    var sizes = ['Bytes', 'KB', 'MB'];
    if (bytes == 0) return 'n/a';
    var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
    return (bytes / Math.pow(1024, i)).toFixed(1) + ' ' + sizes[i];
  };

  this.JSONparse = function(json) {
    try {
      return JSON.parse(json);
    } catch(err) {
      console.log("Error parsing JSON: ",err);
      return {status: 500, message: 'Error parsing JSON response', data:{}};
    }
  }

  this.setmessage = function(msg){
    self.status_msg_elm.text(msg);
  }

  this.selecting = function(){
    self.setmessage("Selecting...");
    self.reset();
  }

  this.reset = function(){
    self.file_field.val('');
    self.progress.removeClass('progress-bar-success');
    self.progress.removeClass('progress-bar-danger');
    self.progress.addClass('notransition').css({'width': 0});
    // Execute immediately (timeout 1 msec) but allow to redraw document first.
    setTimeout(function(){ self.progress.removeClass('notransition'); },1);
  }

  this.upload = function(){
    // if(self.options.csrf_token){
    //   self.xsrf_field.val(self.options.csrf_token);
    // }

    var formData = new FormData(self.form[0]);
    var url = self.form.attr('action');
    var file_field = self.file_field[0];

    self.progress.css({'width': 0});

    if(file_field.files.length){
      var file = file_field.files[0];
    } else {
      self.setmessage("Error: No file selected.");
      return false;
    }


    if(file.size > self.max_file_size) {
      self.setmessage("The file is too large.  Max: "+
                  self.bytesToSize(self.max_file_size)+".");
    }

    // create XMLHttpRequest object
    var request = window.XMLHttpRequest ?
                  new XMLHttpRequest() :
                  new ActiveXObject("Microsoft.XMLHTTP");

    if (request.upload && self.options['mime'].indexOf(file.type) > -1
        && file.size <= self.max_file_size)
    {

      // progress bar
      request.upload.addEventListener('progress', function(e) {
        var pc = parseInt(e.loaded / e.total * 100);
        self.progress.css({'width': pc+'%'});
      }, false);

      // file received/failed
      request.onreadystatechange = function(e) {
        if (request.readyState == 4) {
          setTimeout(function(){ self.progress.addClass('progress-bar-'+(request.status == 200 ? "success" : "danger")); },500);
        }
        if (request.readyState == 4) {
          var msg;
          switch (request.status) {
            case 200: msg = "File uploaded successfully."; setTimeout(function(){self.options.success(self.JSONparse(request.responseText))},1); break;
            case 406: msg = "Error: No file selected."; break;
            case 404: msg = "Error: Upload feature not working."; setTimeout(function(){self.options.error(self.JSONparse(request.responseText))},1); break;
            default:  msg = "File validation failure, check file contents."; setTimeout(function(){self.options.error(self.JSONparse(request.responseText))},1); break;
          }
          self.setmessage(msg);
        }
        else {
          //alert( "Unexpected error:  " + this.statusText + ".\nPlease try again");
        }
      };

      // start upload
      request.open("POST", url, true);

      if(self.options.csrf_token){
        request.setRequestHeader('X-CSRF-TOKEN', self.options.csrf_token);
        request.setRequestHeader('X-XSRF-TOKEN', self.options.csrf_token);
      }

      request.setRequestHeader("X_FILENAME", file.name);
      request.send(formData);
    }

  }

  this.initialize();
}
