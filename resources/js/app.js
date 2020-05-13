require('./bootstrap');

import Vue from 'vue';
import Routes from './routes';
import App from './views/App';
import VueInternationalization from 'vue-i18n';
import Locale from './vue-i18n-locales.generated';

Vue.use(VueInternationalization);
Vue.mixin({
    methods: {
        getRequest: async function(url = '', data = '') {
            if (data) {
                data = new URLSearchParams(data);
                url = url+'?'+data.toString();
            }

            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
            });

            return response.json();
        },

        postRequest: async function() {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            });

            return response.json();
        },
    }
});

const lang = document.documentElement.lang.substr(0, 2);
const i18n = new VueInternationalization({
    locale: lang,
    messages: Locale
});

const app = new Vue({
    el: '#app',
    router: Routes,
    render: h => h(App),
    i18n,
});

export default app;