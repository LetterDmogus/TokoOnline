@extends('app')

@section('title', 'Register')

@section('content')
    <div class="container mt-5">
        <h2 class=" mb-4">Register</h2>
        <form action="/register" method="POST">
            @csrf
            <div class="mb-3">
                <label for="username" class="form-label ">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label ">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label ">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <button type="submit" class="btn btn-primary form-control">Register</button>
        </form>
        <p>Already have an account? <a href="/login">Click here to login</a></p>
    </div>
@endsection