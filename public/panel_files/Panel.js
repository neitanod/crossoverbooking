var Panel = function() {
  var that = this;
  var updateInterval;
  this.initialize = function() {
    // Esta inicialización se ejecuta cuando Basket termina de cargar los
    // archivos javascript y css
    console.log("Initializing");
    query('init',{},function(data){that.avatar(data.avatar);});
    this.updateStatus();
    updateInterval = setInterval(this.updateStatus,3000);

    // Carga info inicial, por ejemplo nombre y avatar del usuario
  };
  this.notifications = function(how_many) {
    if(!how_many) how_many = '';
    $('.panel-notifications-label').text(how_many);
  };
  this.avatar = function(filename) {
    $('.panel-avatar-image-64').attr({'src': '/pics/avatar/crop/64x64/'+filename});
  };
  this.refreshVersion = function() {
    for(var i in window.localStorage){
      if(i.startsWith('basket-')) localStorage.removeItem(i);
    }
  };
  this.updateStatus = function() {
    console.log("Fetching status");
    // Ver si el usuario aún está logeado
    // Ver si hay nuevas notificaciones
    // Ver si hay aviso de nueva versión del panel
  }
}
