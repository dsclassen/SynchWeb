define([
    'marionette',
    'modules/types/em/scipion/controller'
], function (
    Marionette,
    c
) {
    let Router = Marionette.AppRouter.extend({
        appRoutes: {
<<<<<<< HEAD
            'em/process/scipion/visit/:visit_str': 'scipion',
=======
            'em/process/visit/:visit_str': 'scipion',
>>>>>>> nwilson/migrate/notebook
        }
    });

    return new Router({
        controller: c
    })
});