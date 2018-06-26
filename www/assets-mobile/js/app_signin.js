// Dom7
var $$ = Dom7;

// Framework7 App main instance
var app = new Framework7({
    root: '#app', // App root element
    id: 'io.framework7.testapp', // App bundle ID
    name: 'Framework7', // App name
    theme: 'md', // Automatic theme detection
    on: {
        init: function () {
            var app = this,
                $$form = $$('#login-form');

            app.loginScreen.open('.login-screen');

            $$form.on('submit', function(){
                app.loginScreen.close();
                app.preloader.show();
            });
        }
    }
});