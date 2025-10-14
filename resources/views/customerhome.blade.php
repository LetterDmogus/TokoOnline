@extends('app')

@section('title', 'Dashboard')

@section('content')
    <div class="container mt-5 pt-4 text-white">

        <!-- Greeting -->
        <div class="mb-4">
            <h2>Hello, {{ session('username') ?? 'Customer' }}</h2>
            <p class="text-secondary">Welcome back to your dashboard.</p>
        </div>

        <!-- Row 1: Profile + Summary -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-dark border-light">
                    <div class="card-body text-center d-flex flex-column align-items-center">
                        <img src="{{ asset('storage/default-avatar.png') }}" alt="Profile" class="rounded-circle mb-3"
                            width="80">
                        <h5>{{ session('username') ?? 'AverageMember' }}</h5>
                        <p class="text-secondary mb-1">Member since 2025</p>
                        <a href="#" class="btn btn-outline-light btn-sm mt-2">Edit Profile</a>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card bg-dark border-light">
                    <div class="card-body">
                        <h5 class="mb-3">Order Summary</h5>
                        <div class="row text-center">
                            <div class="col-4">
                                <h3>{{ $order->total_order }}</h3>
                                <p class="text-secondary">Active Orders</p>
                            </div>
                            <div class="col-4">
                                <h3>12</h3>
                                <p class="text-secondary">Delivered</p>
                            </div>
                            <div class="col-4">
                                <h3>5</h3>
                                <p class="text-secondary">Cancelled</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 2: Quick Actions -->
        <div class="row text-center">
            <div class="col-md-4 mb-3">
                <div class="card bg-primary text-white border-0">
                    <div class="card-body">
                        <i class="bi bi-cart4 display-6"></i>
                        <h5 class="mt-2">Shop Products</h5>
                        <a href="/shop" class="btn btn-light btn-sm mt-2">Go</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card bg-danger text-white border-0">
                    <div class="card-body">
                        <i class="bi bi-box-seam display-6"></i>
                        <h5 class="mt-2">My Orders</h5>
                        <a href="/orders" class="btn btn-light btn-sm mt-2">View</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card bg-success text-white border-0">
                    <div class="card-body">
                        <i class="bi bi-wallet2 display-6"></i>
                        <h5 class="mt-2">Payments</h5>
                        <a href="/payments" class="btn btn-light btn-sm mt-2">Check</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection