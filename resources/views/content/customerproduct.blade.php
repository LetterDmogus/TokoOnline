@extends('app')

@section('title', 'Store')

@section('content')<div class="container mt-4">
    <div class="container mt-4">

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
                    <a href="/productdetail/{{ $product->id }}" class="text-white"style="text-decoration:none;">
                    <div class="card shadow-lg h-100 text-white bg-dark ">
                        <div class="ratio ratio-1x1">
                            <img src="{{ $product->image_url ? asset('storage/' . $product->image_url) : asset('storage/images/Default.png') }}"
                                class="card-img-top object-fit-cover rounded-top" alt="product image">
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="text-muted mb-1"><small>{{ $product->categoryname }}</small></p>
                            <p class="card-text small flex-grow-1">{{ $product->shop_name }}</p>
                            <h6 class="fw-bold mb-2">${{ number_format($product->price, 2) }}</h6>
                            <small class="text-secondary mb-2">Stock: {{ $product->stock }}</small>
                            <span class="badge {{ $product->available ? 'bg-success' : 'bg-danger' }}">
                                {{ $product->available ? 'Available' : 'Not available' }}
                            </span>
                            @if($product->available == 1)
                            <a href="/cart/add/{{ $product->id }}" class="btn btn-primary mt-3">Add to cart</a>
                            @else
                            <button class="btn btn-secondary mt-3" disabled>Sold Out</button>
                            @endif
                            
                        </div>
                    </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

@endsection