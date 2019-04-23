<template>
    <section>
        <div class="form-group">
            <select v-if="isAdmin" style="width: 300px" class="form-control" id="userdropdown" v-model="selectedUser" @change="getUserRecord">
                <option value="" disabled selected>Select a user</option>
                <option v-for="user in users" :value = "user['id']">{{user['name']}}</option>
            </select>
        </div>
        <section class="row col-md-12">
            <section class="tableSec col-md-5">
                <table class="table table-sm table-dark" v-if="attendances.length > 0">
                    <thead>
                    <tr class="bg-success">
                        <th class="bg-info" scope="col">Date</th>
                        <th class="bg-success" scope="col">Office Enter</th>
                        <th class="bg-danger" scope="col">Office Exit</th>
                        <th class="bg-info" scope="col">Office Hours</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="bg-primary" v-for="atten in getTime()">
                        <td class="bg-info">{{atten['date']}}</td>
                        <td class="bg-success">{{atten['enterAt']}}</td>
                        <td class="bg-danger">{{atten['exitAt']}}</td>
                        <td class="bg-info">{{atten['spentHours']}}</td>
                    </tr>
                    </tbody>
                </table>
                <section v-else>
                    <h4>No records found</h4>
                </section>
            </section>
            <section class="user-profile col-md-7 text-center">
                <user-profile-component :isAdmin="isAdmin" :selectedUser="selectedUser"></user-profile-component>
            </section>
        </section>

    </section>


</template>

<script>
    import axios from 'axios';
    import moment from 'moment';
    import UserProfileComponent from "./UserProfileComponent";

    export default {
        props: ['attendancesprop','isAdmin'],
        data() {
            return {
                attendances: this.attendancesprop ? this.attendancesprop : [],
                preParedAttendance: [],
                isUserAdmin: false,
                users: [],
                selectedUser: ''
            }
        },
        watch: {
            attendancesprop: function (value) {
                this.attendances = value;
            },
            users: function(value) {
                this.users = value;
            }
        },
        components: {
            "user-profile-component": UserProfileComponent
        },
        methods: {
            getTime: function () {
                let preparedData = [];
                let loopOnmonth = this.attendances[0]['created_at'];

                let daysInMonth = moment(loopOnmonth).daysInMonth();

                let currentDateInMonthYear = moment().format('MM-YYYY');
                let loopedDateInMonthYear = moment(loopOnmonth, 'YYYY-MM-DD HH:mm:ss').format('MM-YYYY');

                if (currentDateInMonthYear === loopedDateInMonthYear) daysInMonth = Number(moment().format('DD'));
                console.log('this attendances: ', this.attendances);
                for (let n = 1; n <= daysInMonth; n++) {
                    let myCurrentDate = '';
                    let date = n < 10 ? "0" + n : n;
                    date = date + "-" + moment(loopOnmonth, 'YYYY-MM-DD HH:mm:ss').format('MM-YYYY');
                    let attendanceStatus = {date: date};

                    this.attendances.forEach(function (attendance) {
                        let record_date = moment(attendance['created_at'], 'YYYY-MM-DD HH:mm:ss').format('DD-MM-YYYY');

                        if (moment(record_date, 'DD-MM-YYYY').diff(moment(date, 'DD-MM-YYYY'), 'days') === 0) {
                            myCurrentDate = attendance;
                        }
                    });

                    if (myCurrentDate) {
                        console.log('my cur: ', myCurrentDate);
                        attendanceStatus.enterAt = moment(myCurrentDate['office_enter']).format('hh:mm A');
                        if (myCurrentDate['office_out']) {
                            attendanceStatus.exitAt = moment(myCurrentDate['office_out']).format('hh:mm A');
                            attendanceStatus.spentHours = this.getOfficeHours(myCurrentDate['office_enter'], myCurrentDate['office_out']);
                        } else {
                            attendanceStatus.exitAt = 'N/A';
                            attendanceStatus.spentHours = 'N/A';
                        }


                    } else {
                        attendanceStatus.enterAt = 'absent';
                        attendanceStatus.exitAt = 'absent';
                        attendanceStatus.spentHours = 'absent';
                    }
                    preparedData.push(attendanceStatus);
                }

                return preparedData;
            },
            getOfficeHours: function (from, to) {
                if (from && to) {
                    let totalMinute = moment(to).diff(moment(from), 'minutes');
                    return Math.round(totalMinute / 60) + " hour " + totalMinute % 60 + " minute";
                } else {
                    return 'N/A';

                }
            },
            getAllUsers: function () {
                axios.get('/all-user')
                    .then((response) => {
                        console.log('All user: ', response.data);
                        this.users = response.data;
                    }).catch(function (err) {
                    console.log('Could not fetch data for all user', err);
                });

                console.log("users: ",this.users);
            },
            getUserRecord: function() {
                this.$emit('userSelected',this.selectedUser);
            }
        },
        mounted() {
            //this.isAdimn();
            this.getAllUsers();
        }

    }
</script>