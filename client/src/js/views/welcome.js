define(['marionette', 'templates/welcome.html'], function(Marionette, template) {
    
    return Marionette.ItemView.extend({
        template: template,
        templateHelpers: function() {
            return {
                LOGGED_IN: app.token
            }
        },
        
        initialize: function(options) {
            console.log("Welcome page !!")
        },        
    })
    
})
