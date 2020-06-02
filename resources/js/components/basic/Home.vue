<template>
    <div class="content">
        <div class="column">
            <div class="box clock">
                <div class="row text-center">
                    <div class="col-md-12 header">{{$t('basic.clock')}}</div>
                </div>
                <div class="row text-center">
                    <div class="col-md-12 py-2 clock-container">{{clock}}</div>
                </div>
            </div>
            <div class="box weather-widget">
                <div class="row text-center">
                    <div class="col-md-12 header">{{$t('basic.weather')}}</div>
                </div>
                <div class="row">
                    <div class="icon col-md-4">
                        <font-awesome-icon :icon="['fas', weather.icon]"></font-awesome-icon>
                    </div>
                    <div class="temperature-box col-md-8">{{weather.temp.toFixed(1)}}&deg;C</div>
                </div>
                <div class="row text-center">
                    <div class="col-md-12 temperature-feel">{{$t('basic.feels-like')}}: {{weather.feels_like.toFixed(1)}}&deg;C</div>
                </div>
                <div class="row text-center">
                    <div class="col-md-6">
                        <p>{{$t('basic.sunrise')}}: {{this.convertTimestampToDate(weather.sunrise)}}</p>
                    </div>
                    <div class="col-md-6">
                        <p>{{$t('basic.sunset')}}: {{this.convertTimestampToDate(weather.sunset)}}</p>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="col-md-6">
                        <p>{{$t('basic.pressure')}}: {{weather.pressure}}hPa</p>
                    </div>
                    <div class="col-md-6">
                        <p>{{$t('basic.humidity')}}: {{weather.humidity}}%</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <p>{{$t('basic.wind-speed')}}: {{weather.wind_speed}}m/s</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="column">
            <div class="box notes">
                <div class="row text-center">
                    <div class="col-md-12 header">{{$t('basic.events')}}</div>
                </div>
                <div class="row" v-show="!notes[0].id">
                    <div class="col-md-12">{{$t('basic.no-events')}}</div>
                </div>
                <div class="note py-1 px-1 my-1" v-show="notes[0].id" v-for="note in notes" v-bind:class="{'upcoming': note.upcoming, 'taking-place': note.taking_place}">
                    <span>{{note.start_at}}</span>
                    <span v-show="note.end_at !== 0"> - {{note.end_at}}</span>
                    <span>: {{note.description}}</span>
                </div>
            </div>
            <div class="box sensors">
                sensors
            </div>
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
                    sunrise: 0,
                    sunset: 0,
                    icon: 'smile-beam'
                },

                notes: [{
                    id: 0,
                    description: '',
                    start_at: 0,
                    end_at: 0,
                    upcoming: false,
                    taking_place: false,
                }],
                clock: '',
            }
        },

        created() {
            let params = {
                stopEvent: 1
            };
            this.getRequest('/api/getCurrentWeather', params).then(response => {
                this.handleEvent(response.data);
            });

            this.setNotes();
            window.Echo.channel('home-channel')
                .listen('.home-event', (response) => {
                    this.handleEvent(response.data);
                });

            setInterval(this.updateClock, 1000);
        },

        methods: {
            handleEvent: function(data) {
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
                    default: this.weather.icon = 'smile-beam';
                }
            },

            updateClock: function() {
                this.clock = new Date().toTimeString().slice(0, 8);
            },

            setNotes: function() {
                this.getRequest('/api/getNotes').then(response => {
                    this.notes = response.data;
                    let nowTemp = new Date();
                    let now = new Date(nowTemp.getFullYear(), nowTemp.getMonth()+1, nowTemp.getDate());
                    let hourNotSet = ' 2:00:00';
                    for (let note in this.notes) {
                        let noteDateTemp = new Date(this.notes[note].start_at*1000);
                        let noteDate = new Date(noteDateTemp.getFullYear(), noteDateTemp.getMonth()+1, noteDateTemp.getDate());
                        let diff = new Date(noteDate.getTime() - now.getTime());
                        this.notes[note].start_at = this.convertTimestampToDate(this.notes[note].start_at, true);
                        if (hourNotSet === this.notes[note].start_at.substr(-8)) {
                            this.notes[note].start_at = this.notes[note].start_at.substr(0, 10).trim();
                        }

                        if (this.notes[note].end_at !== 0) {
                            this.notes[note].end_at = this.convertTimestampToDate(this.notes[note].end_at, true);
                        }

                        console.log(noteDate.getTime());
                        console.log(now.getTime());
                        let diffDays = diff.getUTCDate() - 1;
                        console.log(diffDays);
                        if (diffDays === 1) {
                            this.notes[note].upcoming = true;
                        }

                        if (diffDays === 0) {
                            this.notes[note].taking_place = true;
                        }
                    }
                });
            },

            convertTimestampToDate: function(timestamp, getYmd = false) {
                let date = new Date(timestamp*1000);
                let result = '';
                if (getYmd) {
                    let year = date.getFullYear();
                    let month = '0'+(date.getMonth()+1);
                    let day = date.getDate();
                    result = day+'.'+month.substr(-2)+'.'+year+' ';
                }

                let hours = date.getHours();
                let minutes = "0"+date.getMinutes();
                let seconds = "0"+date.getSeconds();
                result += hours+':'+minutes.substr(-2)+':'+seconds.substr(-2);

                return result;
            },
        },
    }
    import { library } from '@fortawesome/fontawesome-svg-core'
    import { faCloudRain, faCloudSunRain, faCloudMoonRain, faSun, faCloudSun, faCloud, faSmog, faCloudShowersHeavy, faSnowflake, faCloudMoon, faSmileBeam, faBolt } from '@fortawesome/free-solid-svg-icons'
    library.add(faCloudRain, faCloudSunRain, faCloudMoonRain, faSun, faCloudSun, faCloud, faSmog, faCloudShowersHeavy, faSnowflake, faCloudMoon, faSmileBeam, faBolt);
</script>
