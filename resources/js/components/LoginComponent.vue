<template>

    <div class="login-form-holder">
        <h2 class="login-header">Who are you??</h2>
        <form class="login-form" method="POST" action="/login">
            <div class="form-group">
                <label for="email">Email address</label>
                <input name="email" type="email" class="form-control" id="email" required aria-describedby="emailHelp"
                       placeholder="Enter email">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input name="password" type="password" class="form-control" id="password" required placeholder="Password">
            </div>
            <div class="form-group text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>

            <input type="hidden" name="_token" :value="csrf">

        </form>

    </div>

</template>


<script>

    export default {
        data() {
            return {
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }
    }

</script>