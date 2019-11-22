define(['marionette',
    'modules/notebook/views/vue-notebook',
    ], function(Marionette, NotebookView) {
    
    var bc = { title: 'Notebook' }
    
    var controller = {
        
        notebook:  function() {
            app.loading()
            app.bc.reset([bc]),
            app.content.show(new NotebookView())
        },
        
    }

    return controller
})