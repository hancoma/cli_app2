// Dom7
var $$ = Dom7;

// Framework7 App main instance
var app  = new Framework7({
  root: '#app', // App root element
  id: 'io.framework7.testapp', // App bundle ID
  name: 'Framework7', // App name
  theme: 'md', // Automatic theme detection
  // App root data
  data: function () {
    return {

    };
  },
  on: {
      init: function () {
          var app = this,
              $$external_link = $$('.external');
          //외부 링크엔 preloader 표시
          $$external_link.on('click', function(){
              app.preloader.show();
          });
      },
  },
  // App routes
  routes: routes,
});

// Init/Create views
var homeView = app.views.create('#view-home', {
  url: '/'
});

var myaccountView = app.views.create('#view-myaccount', {
    url: '/myaccount'
});

var alarmView = app.views.create('#view-alarm', {
    url: '/alarm'
});
