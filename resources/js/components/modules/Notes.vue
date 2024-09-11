<template>
    <div class="box notes row">
        <div class="col-md-12 text-center header">{{$t('basic.events')}}</div>
        <div class="col-md-12" v-show="!notes.length">{{$t('basic.no-events')}}</div>
        <div class="col-md-12" v-cloak>
            <div class="row note px-1 my-1" v-for="note in notes" v-bind:class="{'upcoming': note.upcoming, 'taking-place': note.taking_place}">
                <span>{{note.start_at}}</span>
                <span v-show="note.end_at !== 0"> - {{note.end_at}}</span>
                <span>: {{note.description}}</span>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                notes: [],
            }
        },

        created() {
            window.Echo.channel('default-channel')
                .listen('.notes-event', (response) => {
                    this.handleNotesEvent(response.data);
                });

            this.setNotes();
        },

        methods: {
            setNotes: function() {
                this.getRequest('/api/getNotes').then(response => {
                    this.handleNotesEvent(response.data);
                });
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
        }
    }
</script>
