@extends('app')

@section('title', 'Store')

@section('content')<div class="container mt-4">
    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success mt-2">
                {{ session('success') }}
            </div>
        @endif

        <h2 class="mb-4 text-center text-white">Our Products</h2>

        <form method="GET" action="{{ route('products') }}" class="row g-2 mb-4 justify-content-center">
            <div class="col-md-3">
                <select name="category" class="form-select" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $cat->id == $category ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5">
                <input type="text" name="search" value="{{ $search }}" placeholder="Search products"
                    class="form-control">
            </div>
            <div class="col-md-auto">
                <button type="submit" class="btn btn-primary w-100">Search</button>
            </div>
        </form>

        <div class="row">
            @foreach ($products as $product)
                <div class="col-md-3 mb-4">
                    <div class="card shadow-lg h-100 text-white bg-dark ">
                        <div class="ratio ratio-1x1">
                            <img src="{{ $product->image_url ? asset('storage/' . $product->image_url) : asset('storage/images/Default.png') }}"
                                class="card-img-top object-fit-cover rounded-top" alt="product image">
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="text-muted mb-1"><small>{{ $product->categoryname }}</small></p>
                            <p class="card-text small flex-grow-1">{{ Str::limit($product->description, 60) }}</p>
                            <h6 class="fw-bold mb-2">${{ number_format($product->price, 2) }}</h6>
                            <span class="badge {{ $product->available ? 'bg-success' : 'bg-danger' }}">
                                {{ $product->available ? 'Available' : 'Not available' }}
                            </span>
                            <a href="/cart/add/{{ $product->id }}" class="btn btn-primary mt-3">Order Now</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

@endsection