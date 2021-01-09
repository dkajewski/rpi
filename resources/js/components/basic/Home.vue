<template>
    <div class="content row">
        <div class="fixed-bottom alert text-center alert-dismissible fade mx-2 show" v-show="alertActive" v-bind:class="[alertClass]">
            <button type="button" class="close" v-on:click="alertActive = false">&times;</button>
            <strong>{{alertText}}</strong>
        </div>
        <div class="d-md-none d-xs-flex col-12">
            <div class="box row">
                <div class="col-12 text-center header">{{$t('basic.add-event')}}</div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="description">{{$t('basic.name')}}</label>
                        <input class="form-control" name="description" id="description" type="text" v-model="description"/>
                    </div>
                    <div class="form-group">
                        <label  for="start-at">{{$t('basic.beginning')}}</label>
                        <input class="form-control" name="start-at" id="start-at" type="date" v-model="startAt"/>
                    </div>
                    <div class="form-group">
                        <label for="end-at">{{$t('basic.ending')}}</label>
                        <input class="form-control" name="start-at" id="end-at" type="date" v-model="endAt"/>
                    </div>
                    <div class="form-group text-center">
                        <button class="btn btn-primary" v-on:click="saveNote">{{$t('basic.save')}}</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-md-none d-xs-flex col-12">
            <div class="box row" v-cloak>
                <div class="col-12 text-center header">{{$t('basic.events-list')}}</div>
                <div class="col-12 text-center" v-show="!this.futureNotes">{{$t('basic.no-events')}}</div>
                <table class="table">
                    <tr v-for="futureNote in futureNotes">
                        <td class="align-middle col-10">{{futureNote.description}}</td>
                        <td class="col-2">
                            <button class="btn btn-danger" v-on:click="deleteNote(futureNote.id)">&times;</button>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box clock row">
                <div class="col-md-12 text-center header">{{$t('basic.clock')}}</div>
                <div class="col-md-12 text-center">
                    <div class="col-md-12 py-2 clock-container">{{clock}}</div>
                </div>
            </div>
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
        </div>
        <div class="col-md-6">
            <div class="box notes row">
                <div class="col-md-12 text-center header">{{$t('basic.events')}}</div>
                <div class="col-md-12" v-show="!notes.length">{{$t('basic.no-events')}}</div>
                <div class="col-md-12" v-cloak>
                    <div class="row note py-1 px-1 my-1" v-for="note in notes" v-bind:class="{'upcoming': note.upcoming, 'taking-place': note.taking_place}">
                        <span>{{note.start_at}}</span>
                        <span v-show="note.end_at !== 0"> - {{note.end_at}}</span>
                        <span>: {{note.description}}</span>
                    </div>
                </div>
            </div>
            <div class="box sensors row">
                <div class="col-md-12 text-center header">{{$t('basic.date')}}</div>
                <div class="col-md-12 text-center">
                    <div class="col-md-12 py-2 clock-container">{{date}}</div>
                </div>
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
                    icon: 'smile-beam',
                    updated_at: '00.00 00:00',
                },

                notes: [],
                clock: '',
                description: '',
                startAt: '',
                endAt: '',
                futureNotes: [],
                alertActive: false,
                alertClass: '',
                alertText: '',
                date: '',
            }
        },

        created() {
            this.getRequest('/api/getCurrentWeather').then(response => {
                this.handleWeatherEvent(response.data);
            });

            this.setNotes();
            this.getAllFutureNotes();
            window.Echo.channel('home-channel')
                .listen('.home-event', (response) => {
                    this.handleEvent(response.data.data, response.data.event_type);
                });

            setInterval(this.updateClock, 1000);
            setInterval(this.updateDate, 1000);
        },

        methods: {
            handleEvent: function(data, eventType) {
                switch(eventType) {
                    case 'weather': this.handleWeatherEvent(data); break;
                    case 'notes': this.handleNotesEvent(data); break;
                    case 'futureNotes': this.futureNotes = data; break;
                }
            },

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
                    default: this.weather.icon = 'smile-beam';
                }
            },

            handleNotesEvent: function(data) {
                this.notes = data;
                let nowTemp = new Date();
                let now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate());
                let hourNotSet = ' 0:00:00';
                for (let note in this.notes) {
                    let noteDateTemp = new Date(this.notes[note].start_at*1000);
                    let noteDate = new Date(noteDateTemp.getFullYear(), noteDateTemp.getMonth(), noteDateTemp.getDate());
                    let diff = new Date(noteDate.getTime() - now.getTime());
                    this.notes[note].start_at = this.convertTimestampToDate(this.notes[note].start_at, true);
                    if (hourNotSet === this.notes[note].start_at.substr(-8)) {
                        this.notes[note].start_at = this.notes[note].start_at.substr(0, 10).trim();
                    }

                    if (this.notes[note].end_at !== 0) {
                        this.notes[note].end_at = this.convertTimestampToDate(this.notes[note].end_at, true);
                    }

                    let diffDays = (diff.getTime()/1000)/(3600*24);
                    if (diffDays === 1) {
                        this.notes[note].upcoming = true;
                    }

                    if (diffDays === 0) {
                        this.notes[note].taking_place = true;
                    }
                }
            },

            updateClock: function() {
                this.clock = new Date().toTimeString().slice(0, 8);
            },

            updateDate: function() {
                this.date = this.convertTimestampToDate(Date.now()/1000, true).substr(0, 10);
            },

            setNotes: function() {
                this.getRequest('/api/getNotes').then(response => {
                    this.handleNotesEvent(response.data);
                });
            },

            saveNote: function() {
                let formData = {
                    'description': this.description,
                    'start_at': this.startAt,
                    'end_at': this.endAt,
                };
                this.postRequest('/api/saveNote', formData).then(response => {
                    this.alertActive = true;
                    this.alertText = response.message;
                    if (response.type === 'success') {
                        this.alertClass = 'alert-success';
                    } else {
                        this.alertClass = 'alert-danger';
                    }
                });
            },

            getAllFutureNotes: function() {
                this.getRequest('/api/getAllFutureNotes').then(response => {
                    this.futureNotes = response.data;
                });
            },

            deleteNote: function(id) {
                this.postRequest('/api/deleteNote', {id: id}).then(response => {
                    this.alertActive = true;
                    this.alertText = response.message;
                    if (response.type === 'success') {
                        this.alertClass = 'alert-success';
                    } else {
                        this.alertClass = 'alert-danger';
                    }
                });
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
        },
    }
    import { library } from '@fortawesome/fontawesome-svg-core'
    import { faCloudRain, faCloudSunRain, faCloudMoonRain, faSun, faCloudSun, faCloud, faSmog, faCloudShowersHeavy, faSnowflake, faCloudMoon, faSmileBeam, faBolt } from '@fortawesome/free-solid-svg-icons'
    library.add(faCloudRain, faCloudSunRain, faCloudMoonRain, faSun, faCloudSun, faCloud, faSmog, faCloudShowersHeavy, faSnowflake, faCloudMoon, faSmileBeam, faBolt);
</script>
