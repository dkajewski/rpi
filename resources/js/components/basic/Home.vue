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
            <weather></weather>
        </div>
        <div class="col-md-6">
            <notes></notes>
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
            this.getAllFutureNotes();
            window.Echo.channel('default-channel')
                .listen('.default-event', (response) => {
                    this.handleEvent(response.data.data, response.data.event_type);
                });

            setInterval(this.updateClock, 1000);
            setInterval(this.updateDate, 1000);
        },

        methods: {
            handleEvent: function(data, eventType) {
                switch(eventType) {
                    case 'futureNotes': this.futureNotes = data; break;
                }
            },

            updateClock: function() {
                this.clock = new Date().toTimeString().slice(0, 8);
            },

            updateDate: function() {
                this.date = this.convertTimestampToDate(Date.now()/1000, true).substr(0, 10);
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
        },
    }
</script>
