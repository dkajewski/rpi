<template>
    <div class="content row">
        <div class="fixed-bottom alert text-center alert-dismissible fade mx-2 show" v-show="alertActive" v-bind:class="[alertClass]">
            <button type="button" class="btn btn-danger mx-1 close" v-on:click="alertActive = false">&times;</button>
            <strong>{{alertText}}</strong>
        </div>
        <div class="d-block col-12">
            <div class="box row">
                <div class="col-12 text-center header">{{$t('basic.add-event')}}</div>
                <div class="col-12">
                    <input type="hidden" v-model="id"/>
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
                    <tr v-for="(futureNote, index) in futureNotes">
                        <td class="align-middle col-10">{{futureNote.description + ' (' + getDate(futureNote.start_at) + ')'}}</td>
                        <td class="col-1 text-center">
                            <button type="button" class="btn btn-primary" v-on:click="editNote(index)">&#9998;</button>
                        </td>
                        <td class="col-1 text-center">
                            <button type="button" class="btn btn-danger" v-on:click="deleteNote(futureNote.id)">&times;</button>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                futureNotes: [],
                alertActive: false,
                alertClass: '',
                alertText: '',
                description: '',
                startAt: '',
                endAt: '',
                id: null,
            }
        },

        created() {
            window.Echo.channel('default-channel')
                .listen('.default-event', (response) => {
                    this.handleEvent(response.data.data, response.data.event_type);
                });
            this.getAllFutureNotes();
        },

        methods: {
            handleEvent: function(data, eventType) {
                switch(eventType) {
                    case 'futureNotes': this.futureNotes = data; break;
                }
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

            saveNote: function() {
                let formData = {
                    'description': this.description,
                    'start_at': this.startAt,
                    'end_at': this.endAt,
                };
                if (this.id !== null) {
                    formData.id = this.id;
                }

                this.postRequest('/api/saveNote', formData).then(response => {
                    this.id = null;
                    this.alertActive = true;
                    this.alertText = response.message;
                    if (response.type === 'success') {
                        this.alertClass = 'alert-success';
                    } else {
                        this.alertClass = 'alert-danger';
                    }
                });
            },

            editNote: function(id) {
                this.id = this.futureNotes[id].id;
                this.description = this.futureNotes[id].description;
                this.startAt = this.getDate(this.futureNotes[id].start_at).split(".").reverse().join("-");
                if (this.futureNotes[id].end_at !== 0) {
                    this.endAt = this.getDate(this.futureNotes[id].end_at).split(".").reverse().join("-");
                }
            },

            getDate: function(unixTimestamp) {
                return this.convertTimestampToDate(unixTimestamp, true).substring(0, 10);
            },
        }
    }
</script>