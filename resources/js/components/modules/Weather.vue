<template>
    <div class="box weather-widget row">
        <div class="col-md-12 text-center header">{{$t('basic.weather')}}</div>
        <div class="col-md-12">
            <div class="row">
                <div class="icon col-md-4">
                    <font-awesome-icon :icon="['fas', weather.icon]"></font-awesome-icon>
                </div>
                <div class="temperature-box col-md-8">{{weather.temp.toFixed(1)}}&deg;C</div>
            </div>
        </div>
        <div class="col-md-12 text-center temperature-feel">
            <div class="row">
                <div class="col-md-6">{{$t('basic.feels-like')}}: {{weather.feels_like.toFixed(1)}}&deg;C</div>
                <div class="col-md-6 weather-updated-at">{{$t('basic.last-update')}}: {{weather.updated_at}}</div>
            </div>
        </div>
        <div class="col-md-12 text-center">
            <div class="row">
                <div class="col-md-6">
                    <p>{{$t('basic.sunrise')}}: {{this.convertTimestampToDate(weather.sunrise)}}</p>
                </div>
                <div class="col-md-6">
                    <p>{{$t('basic.sunset')}}: {{this.convertTimestampToDate(weather.sunset)}}</p>
                </div>
            </div>
        </div>
        <div class="col-md-12 text-center">
            <div class="row">
                <div class="col-md-6">
                    <p>{{$t('basic.pressure')}}: {{weather.pressure}}hPa</p>
                </div>
                <div class="col-md-6">
                    <p>{{$t('basic.humidity')}}: {{weather.humidity}}%</p>
                </div>
            </div>
        </div>
        <div class="col-md-12 text-center"><p>{{$t('basic.wind-speed')}}: {{weather.wind_speed}}m/s</p></div>
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
                    sunrise: 0,
                    sunset: 0,
                    icon: 'smile-beam',
                    updated_at: '00.00 00:00',
                },
            }
        },

        created() {
            window.Echo.channel('default-channel')
                .listen('.weather-event', (response) => {
                    this.handleWeatherEvent(response.data.original.data);
                });

            this.getRequest('/api/getCurrentWeather').then(response => {
                this.handleWeatherEvent(response.data);
            });
        },

        methods: {
            handleWeatherEvent: function(data) {
                this.weather = data;
                switch(data.weather) {
                    case 'Rain':
                        this.weather.icon = 'cloud-showers-heavy';
                        break;
                    case 'Thunderstorm':
                        this.weather.icon = 'bolt';
                        break;
                    case 'Drizzle':
                        this.weather.icon = 'cloud-rain';
                        break;
                    case 'Mist':
                    case 'Fog':
                    case 'Haze':
                        this.weather.icon = 'smog';
                        break;
                    case 'Clear':
                        this.weather.icon = 'sun';
                        break;
                    case 'Clouds':
                        this.weather.icon = 'cloud-sun';
                        break;
                    case 'Snow':
                        this.weather.icon = 'snowman';
                        break;
                    default: this.weather.icon = 'smile-beam';
                }
            },
        },
    }
    import { library } from '@fortawesome/fontawesome-svg-core'
    import { faCloudRain, faCloudSunRain, faCloudMoonRain, faSun, faCloudSun, faCloud, faSmog, faCloudShowersHeavy, faSnowflake, faCloudMoon, faSmileBeam, faBolt, faSnowman } from '@fortawesome/free-solid-svg-icons'
    library.add(faCloudRain, faCloudSunRain, faCloudMoonRain, faSun, faCloudSun, faCloud, faSmog, faCloudShowersHeavy, faSnowflake, faCloudMoon, faSmileBeam, faBolt, faSnowman);
</script>