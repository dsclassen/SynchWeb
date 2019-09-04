define(['vue',
        'utils/vuewrapper',
        'views/components/welcome/welcome.vue',
        'templates/vue/welcome/welcome.html',
    ], function(Vue, VueWrapper, Welcome, tmpl) {

        return VueWrapper.extend({
            vueView: Vue.extend({
                template: tmpl,

                components: {
                    'welcome-page': Welcome.default,
                },
            })
        })
    }
)