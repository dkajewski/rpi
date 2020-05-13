<template>
    <div class="content">
        <h1>Home content</h1>
        <div v-show="weather">
            <p>{{$t('basic.temperature')}}: {{weather.temp.toFixed(2)}}&deg;C</p>
            <p>{{$t('basic.feels-like')}}: {{weather.feels_like.toFixed()}}&deg;C</p>
            <p>{{$t('basic.sunrise')}}: {{this.convertTimestampToDate(weather.sunrise)}}</p>
            <p>{{$t('basic.sunset')}}: {{this.convertTimestampToDate(weather.sunset)}}</p>
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                weather: {
                    weather: '',
                    weather_description: '',
                    temp: 0,
                    feels_like: 0,
                    pressure: 0,
                    humidity: 0,
                    wind_speed: 0,
                    cloudiness: 0,
                    sunrise: '',
                    sunset: '',
                },
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

            convertTimestampToDate: function(timestamp) {
                let date = new Date(timestamp*1000);
                let hours = date.getHours();
                let minutes = "0"+date.getMinutes();
                let seconds = "0"+date.getSeconds();

                return hours+':'+minutes.substr(-2)+':'+seconds.substr(-2);
            },
        },
    }
</script>
