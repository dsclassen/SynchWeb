define(['vue',
    'veevalidate',
    'promise',
    'utils/vuewrapper',
    'modules/notebook/views/vue-notebookHandler.vue',
    ], function(Vue, VeeValidate, Promise, VueWrapper, NotebookHandler) {

    // Promise is not used, but required for IE if we want to use vee-validate
    Vue.use(VeeValidate)

    return VueWrapper.extend({
        vueView: Vue.extend({
            template: '<section class="content"><h1>Notebook</h1><notebookhandler></notebookhandler></section>',
            components: {
                'notebookhandler': NotebookHandler.default
            },
        })
    })
})