<template>
    <section class="employee-section">
        <button v-if="isAdmin" type="button" class="btn btn-info btn-lg new-user" data-toggle="modal" data-target="#myModal">New employee</button>

        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">New employee</h4>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="/save-user">
                            <div class="form-group">
                                <input required type="text" name="name" class="form-control" id="name" v-model="name" placeholder="Enter name">
                            </div>
                            <div class="form-group">
                                <input required type="text" name="department" class="form-control" id="department" placeholder="Enter department">
                            </div>
                            <div class="form-group">
                                <input required type="text" name="designation" class="form-control" id="designation" v-model="designation" placeholder="Enter designation">
                            </div>
                            <div class="form-group">
                                <input required type="email" name="email" class="form-control" id="email" v-model="email" aria-describedby="emailHelp" placeholder="Enter email">
                            </div>
                            <div class="form-group">
                                <input required type="text" name="phone" class="form-control" id="phone" v-model="phone" placeholder="Enter phone">
                            </div>
                            <div class="form-group">
                                <input required type="password" name="password" class="form-control" v-model="password" id="password" placeholder="Password">
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <input type="hidden" name="_token" :value="csrf">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>
        <section v-if="userInfo">
            <h3>Name: {{userInfo['name']}}</h3>
            <h4>Designation: {{userInfo['designation']}}</h4>
            <h4>Contact Number: {{userInfo['contact_number']}}</h4>
            <h4>Department: {{userInfo['department']}}</h4>
            <h4>Email: {{userInfo['email']}}</h4>
        </section>
    </section>

</template>

<script>
    import axios from 'axios';
    export default {
        props: ['selectedUser','isAdmin'],
        data() {
            return {
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                userInfo: '',
                isAdmin: this.isAdmin,
                name: '',
                designation: '',
                phone: '',
                email: '',
                password: ''
            }
        },
        watch: {
          selectedUser: function(value) {
              this.getUserInfo();
          },
          isAdmin: function(value) {
              this.isAdmin = value;
          }
        },
        methods: {
            getUserInfo: function() {
                console.log('user id: ', this.selectedUser);
                axios.get(`/get-userinfo?id=${this.selectedUser}`)
                    .then((res) => {
                        console.log('User info: ', res.data);
                        this.userInfo = res.data;
                    }).catch(function(err) {

                });
            },
            saveUser: function() {
                console.log('Name: ',this.name);
                axios.post('/new-user',{
                    name: this.name,
                    designation: this.designation,
                    email: this.email,
                    phone: this.phone,
                    password: this.password
                }).then(function(response) {
                    console.log("save user: ", response.data);
                }).catch(function(err) {
                    console.log('Could not save user: ',err);
                })
            }
        },
        mounted() {
            this.getUserInfo();
        }
    }
</script>