define(['utils/lazyrouter'], function(LazyRouter) {
    // var Router = Marionette.AppRouter.extend({
    var Router = LazyRouter.extend({
        appRoutes: {
            'summary': 'summary',
        },
        loadModule: function(loadedCallback) {
            import(/* webpackChunkName: "help" */ 'modules/summary/controller').then(controller => {
                // Trigger the passed callback
                loadedCallback(controller)
            })
        }
    })
        
    return new Router()
})