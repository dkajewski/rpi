require('./bootstrap');

import Vue from 'vue';
import Routes from './routes';
import App from './views/App';

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

const app = new Vue({
    el: '#app',
    router: Routes,
    render: h => h(App),
});

export default app;