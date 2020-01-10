define(['marionette',
    // 'modules/feedback/views/feedback',
    'modules/summary/views/vue-summary',
    ], function(Marionette, SummaryView) {
    
    var bc = { title: 'Feedback' }
    
    var controller = {
        
        summary:  function() {
            app.loading()
            app.bc.reset([bc]),
            app.content.show(new SummaryView())
        },
        
    }

    return controller
})