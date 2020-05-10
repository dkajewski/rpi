<template>
    <div class="content">
        <h1>Home content</h1>
        <div v-show="weather">
            <p>temp: {{(weather.main.temp-273.15).toFixed(2)}}&deg;C</p>
            <p>feels like: {{(weather.main.feels_like-273.15).toFixed(2)}}&deg;C</p>
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                weather: null,
            }
        },

        created() {
            let params = {
                stopEvent: 1
            };
            this.getRequest('/api/getCurrentWeather', params).then(response => {
                console.log(response);
                this.weather = response.data;
            });

            window.Echo.channel('home-channel')
                .listen('.home-event', (e) => {
                    this.handleEvent(e);
                });
        },

        methods: {
            handleEvent: function(data) {
                console.log(data);
            },
        },
    }
</script>
