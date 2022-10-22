require('./bootstrap');

import {createApp} from 'vue';
import Routes from './routes';
import {createI18n} from 'vue-i18n';
import App from './views/App.vue';
import Locale from './vue-i18n-locales.generated';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

const mixin = ({
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
const i18n = createI18n({
    locale: lang,
    messages: Locale
});

const app = createApp(App);
app.use(Routes);
app.use(i18n);

app.component('font-awesome-icon', FontAwesomeIcon);
app.component('notes', require('./components/modules/Notes').default);
app.component('weather', require('./components/modules/Weather').default);
app.component('clock', require('./components/modules/Clock').default);
app.component('date', require('./components/modules/Date').default);
app.component('navbar', require('./components/menu/Navbar').default);
app.component('notes-admin', require('./components/admin/Notes').default);

app.mixin(mixin);
app.mount('#app');
