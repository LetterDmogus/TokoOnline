@extends('app')

@section('title', 'Login')

@section('content')
    <div class="container mt-5">
        <h2 class="mb-4">Login</h2>
        <form action="/login" method="POST">
            @csrf
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
                @if (session('error'))
                    <small class="text-danger">{{ session('error') }}</small>
                @endif
                @if (session('success'))
                    <small class="text-success">{{ session('success') }}</small>
                @endif
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="form-control btn btn-primary">Login</button>
        </form>
        <p>Dont have an account yet? <a href="/register">Create one for free!</a></p>
    </div>
@endsection