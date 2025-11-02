@extends('app')

@section('title', 'Login')

@section('content')
<div class="d-flex justify-content-center align-items-center vh-100 bg-glass">
    <div class="card shadow-lg p-4 text-white bg-glass-card" style="width: 400px;">
        <h3 class="text-center mb-4">Login</h3>
        <form action="/login" method="POST">
            @csrf
            <div class="mb-3">
                <label for="username" class="form-label text-light">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label text-light">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
        <p class="text-center mt-3 text-light">
            Don’t have an account yet? <a href="/register" class="text-info">Create one for free!</a>
        </p>
    </div>
</div>
@endsection
