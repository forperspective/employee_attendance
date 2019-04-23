@extends('master')

@section('content')

    <div class="attendance-page col-md-12" id="app">

        <h2 style="text-align: center">Employee attendance book</h2>
        <a class="btn btn-danger" href="{{route('logout')}}">Logout</a>
        <section class="col-12 text-center">
            <attendance></attendance>
        </section>
    </div>

@endsection