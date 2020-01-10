define(['vue',
    'veevalidate',
    'promise',
    'utils/vuewrapper',
    'modules/summary/views/vue-visit-summary.vue',
    ], function(Vue, VeeValidate, Promise, VueWrapper, VisitSummary) {

    // Promise is not used, but required for IE if we want to use vee-validate
    Vue.use(VeeValidate)

    return VueWrapper.extend({
        vueView: Vue.extend({
            template: '<section class="content"><h1>Visit Summary</h1><visitsummary></visitsummary></section>',
            components: {
                'visitsummary': VisitSummary.default
            }
        })
    })
})