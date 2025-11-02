@extends('app')

@section('title', 'Dashboard')

@section('content')
    <div class="container mt-5 pt-4 text-white">
        <!-- Greeting -->
        <div class="mb-4">
            <h2>Hello, {{ $profile->name ?? 'Customer' }}</h2>
            <p class="text-secondary">Welcome back to your dashboard.</p>
        </div>

        <!-- Row 1: Profile + Summary -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-dark border-light">
                    <div class="card-body text-center d-flex flex-column align-items-center">
                        @if (!empty($profile->profile_url))
                            <img src="{{ asset('storage/' . $profile->profile_url) }}" alt="Image"
                                class="rounded-circle mb-3" width="80">
                        @else
                            <img src="{{ asset('storage/images/Default.png') }}" alt="No Image" class="rounded-circle mb-3"
                                width="80">
                        @endif
                        <h5>{{ $profile->name ?? 'Customer' }}</h5>
                        <p class="text-secondary mb-1">Member since 2025</p>
                        <a href="/edit/customer/{{ Session('id') }}" class="btn btn-outline-primary">
                            <i class="bi bi-pencil-square"></i> Edit
                        </a>
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
                                <h3>{{ $cancelled_order->total_order }}</h3>
                                <p class="text-secondary">Delivered</p>
                            </div>
                            <div class="col-4">
                                <h3>{{ $delivered_order->total_order }}</h3>
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
                <div class="card bg-dark text-white border border-primary">
                    <div class="card-body">
                        <i class="bi bi-cart4 display-6"></i>
                        <h5 class="mt-2">Shop Products</h5>
                        <a href="/product" class="btn btn-primary btn-sm mt-2">Go</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card bg-dark text-white border border-danger">
                    <div class="card-body">
                        <i class="bi bi-box-seam display-6"></i>
                        <h5 class="mt-2">My Orders</h5>
                        <a href="/order" class="btn btn-danger btn-sm mt-2">View</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card bg-dark text-white border border-success">
                    <div class="card-body">
                        <i class="bi bi-wallet2 display-6"></i>
                        <h5 class="mt-2">Payments</h5>
                        <a href="/payments" class="btn btn-success btn-sm mt-2">Check</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
