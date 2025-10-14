@extends('app')

@section('title', 'Home')

@section('content')
    <div class="container mt-5">
        @if (session('username'))
            <h1>Hello, {{ session('username') }}</h1>
        @endif
    </div>
@endsection