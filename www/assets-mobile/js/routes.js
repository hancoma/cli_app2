routes = [
    {
        path: '/',
        url: '/mysites',
    },
    {
        path: '/dashboard/a/:id',
        async: function(routeTo, routeFrom, resolve, reject) {

            var id = routeTo.params.id;

            app.preloader.show();
            app.request.get('/dashboard/domaindata/' + id, function(data) {
                app.preloader.hide();

                resolve({
                    componentUrl: '/dashboard/a_mobile'
                }, {
                    context: {
                        domain: JSON.parse(data)
                    }
                });
            });
        }
    },
    {
        path: '/settings/:id',
        componentUrl: '/settings/mobile'
    },
    {
        path: '/myaccount',
        componentUrl: '/myaccount'
    },
    {
        path: '/alarm',
        componentUrl: '/myaccount/alarm'
    },
    // Default route (404 page). MUST BE THE LAST
    {
        path: '(.*)',
        url: '/404.html',
    },
];
