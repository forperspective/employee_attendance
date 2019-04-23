<template>
    <section>
        <button v-if="!givenIntime" @click="takeIntime" class="btn btn-success">Office in</button>
        <button v-if="!givenOutime" @click="takeOuttime" class="btn btn-success">Office out</button>
    </section>
</template>

<script>
    import axios from 'axios';
    export default {
        data: function() {
            return {
                givenIntime: false,
                givenOutime: false
            }
        },
        methods: {
            takeIntime: function() {
                this.givenIntime = true;
                axios.post('/save-time-in').then(function (res) {
                    console.log(res.data)
                }).catch(function (err) {
                    console.log('Error occured: ',err);
                });
                this.$emit('updateAttendance');
            },
            takeOuttime: function() {
                this.givenOutime = true;
                axios.post('/save-time-out', {

                }).then(function (res) {
                    console.log(res.data)
                }).catch(function (err) {
                    console.log('Error occured: ',err);
                });
                this.$emit('updateAttendance');
            },
            checkAttendanceStatus: function() {
                axios.get('/check-attendance').then((response) => {
                    this.givenIntime = response.data.intime;
                    this.givenOutime = response.data.outtime;
                }).catch(function(err) {
                    console.log('error', err);
                })
            }
        },

        mounted() {
            this.checkAttendanceStatus();
        }


    }

</script>