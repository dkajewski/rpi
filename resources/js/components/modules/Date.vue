<template>
    <div class="box sensors row">
        <div class="col-md-12 text-center header">{{$t('basic.date')}}</div>
        <div class="col-md-12 text-center">
            <div class="col-md-12 py-2 clock-container">{{date}}</div>
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                date: '',
            }
        },

        created() {
            setInterval(this.updateDate, 1000);
        },

        methods: {
            updateDate: function() {
                this.date = this.convertTimestampToDate(Date.now()/1000, true).substr(0, 10);
            },
        },
    }
</script>