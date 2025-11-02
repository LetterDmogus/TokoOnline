@extends('app')
@section('title', 'Seller Dashboard')
@section('content')
    <div class="container py-4">
        <h2 class="mb-4">Welcome, {{ session('username') }}</h2>
        <a href="/seller/create/shop" class="btn btn-primary mb-3">
            + New Shop
        </a>

        <!-- Shops List -->
        @if ($shops->isEmpty())
            <p class="text-muted">You don’t have any shops yet.</p>
        @else
            <div class="list-group">
                @foreach ($shops as $shop)
                    <div
                        class="bg-glass-card text-white rounded-pill list-group-item d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('storage/' . $shop->logo) }}" alt="Logo" width="35" height="35"
                                class="rounded me-3" style="object-fit: cover;">
                            <div>
                                <h5 class="mb-1">{{ $shop->name }}</h5>
                                <small class="text-white">Products: 5</small>
                            </div>
                        </div>
                        <a href="/seller/shop/{{ $shop->id }}" class="btn rounded-pill btn-outline-primary">
                            Manage
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
