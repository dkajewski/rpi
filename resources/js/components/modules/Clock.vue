<template>
    <div class="box clock row">
        <div class="col-md-12 text-center header">{{$t('basic.clock')}}</div>
        <div class="col-md-12 text-center">
            <div class="col-md-12 py-2 clock-container">{{clock}}</div>
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                clock: '',
            }
        },

        created() {
            setInterval(this.updateClock, 1000);
        },

        methods: {
            updateClock: function() {
                this.clock = new Date().toTimeString().slice(0, 8);
            },
        }
    }
</script>