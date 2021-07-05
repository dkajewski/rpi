require('./bootstrap');

import Vue from 'vue';
import Routes from './routes';
import App from './views/App';
import VueInternationalization from 'vue-i18n';
import Locale from './vue-i18n-locales.generated';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

Vue.use(VueInternationalization);
Vue.component('font-awesome-icon', FontAwesomeIcon);
Vue.component('notes', require('./components/modules/Notes').default);
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

        postRequest: async function(url = '', data = '') {
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

        convertTimestampToDate: function(timestamp, getYmd = false) {
            let date = new Date(timestamp*1000);
            let result = '';
            if (getYmd) {
                let year = date.getFullYear();
                let month = '0'+(date.getMonth()+1);
                let day = '0'+date.getDate();
                result = day.substr(-2)+'.'+month.substr(-2)+'.'+year+' ';
            }

            let hours = date.getHours();
            let minutes = "0"+date.getMinutes();
            let seconds = "0"+date.getSeconds();
            result += hours+':'+minutes.substr(-2)+':'+seconds.substr(-2);

            return result;
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