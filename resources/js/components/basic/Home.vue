<template>
    <div>
        <div class="d-md-none d-sm-block">
            <navbar></navbar>
        </div>
        <div class="content row">
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
    </div>
</template>
<script>
    export default {
        data() {
            return {
                clock: '',
                date: '',
            }
        },

        created() {
            setInterval(this.updateClock, 1000);
            setInterval(this.updateDate, 1000);
        },

        methods: {
            updateClock: function() {
                this.clock = new Date().toTimeString().slice(0, 8);
            },

            updateDate: function() {
                this.date = this.convertTimestampToDate(Date.now()/1000, true).substr(0, 10);
            },
        },
    }
</script>
