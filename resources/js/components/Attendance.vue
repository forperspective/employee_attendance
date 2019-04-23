<template>
    <section>
        <section class="select_month">
            <vue-monthly-picker placeHolder="Select a month" v-model="selectedMonth"></vue-monthly-picker>
        </section>
        <give-attendance v-if="!isUserAdmin" @updateAttendance="getRecords"></give-attendance>
        <attendance-table :isAdmin="isUserAdmin" :attendancesprop="attendances" @userSelected="userSelected"></attendance-table>
    </section>
</template>

<script>
    import axios from 'axios';
    import moment from 'moment';
    import VueMonthlyPicker from 'vue-monthly-picker'

    import GiveAttendance from './AttendanceComponent';
    import AttendanceTable from './AttendanceTable.vue';

    export default {
        data() {
            return {
                selectedMonth: '',
                selectedUser: '',
                isUserAdmin: false,
                fetchedAttendance: []
            }
        },
        computed: {
            attendances: function () {
                return this.fetchedAttendance;
            }
        },
        watch: {
          selectedMonth: function(value) {
              this.getRecords(value);
          },
          selectedUser: function(value) {
              this.getRecords(this.selectedMonth);
          }
        },
        components: {
            "give-attendance": GiveAttendance,
            "attendance-table": AttendanceTable,
            "vue-monthly-picker": VueMonthlyPicker
        },
        methods: {
            getRecords: function (value = moment()) {
                let monthYear = '';
                if(value) {
                    monthYear = moment(value,'YYYY-MM-DD HH:mm:ss').format('MM-YYYY');
                } else {
                    monthYear = moment().format('MM-YYYY');
                }
                console.log({monthYear});
                console.log("selected user: ", this.selectedUser);
                axios.get(`/attendance-record?selectedMonth=${monthYear}&id=${this.selectedUser}`)
                    .then((response) => {
                    this.fetchedAttendance = response.data;

                    console.log("Response: ", response.data);
                }).catch(function (err) {

                    console.log('Error occured..', err);
                });
            },
            userSelected: function(userId) {
                this.selectedUser = userId;
            },
            isAdimn: function() {
                axios.get('/is-admin')
                    .then((res) => {
                        this.isUserAdmin = res.data.isAdmin;
                    }).catch(function(err) {
                    console.log("an error occured: ", err);
                });
            }
        },
        created() {
            this.isAdimn();
            this.getRecords(moment());
        }
    }
</script>