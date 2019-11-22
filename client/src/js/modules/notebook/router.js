// define(['marionette', 'modules/feedback/controller'], function(Marionette, c) {
    define(['utils/lazyrouter'], function(LazyRouter) {
        // var Router = Marionette.AppRouter.extend({
        var Router = LazyRouter.extend({
            appRoutes: {
                'notebook': 'notebook',
            },
            loadModule: function(loadedCallback) {
                import(/* webpackChunkName: "help" */ 'modules/notebook/controller').then(controller => {
                    // Trigger the passed callback
                    loadedCallback(controller)
                })
            }
        })
           
        return new Router()
    })