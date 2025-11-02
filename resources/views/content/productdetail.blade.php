@extends('app')

@section('title', $product->name)

@section('content')
    <div class="container mt-4">
        <div class="card mb-3 mx-auto bg-dark" style="max-width: 800px;">
            <div class="row g-0">
                <div class="col-md-5 text-center p-3">
                    <img src="{{ $product->image_url ? asset('storage/' . $product->image_url) : asset('storage/images/Default.png') }}"
                        class="img-fluid rounded" alt="{{ $product->name }}">
                </div>
                <div class="col-md-7">
                    <div class="card-body">
                        <h3 class="card-title">{{ $product->name }}</h3>
                        <p class="text-muted mb-1">Shop: {{ $product->shop_name }}</p>
                        <p class="text-muted mb-1">Category: {{ $product->category }}</p>
                        <h5 class="text-primary mb-3">${{ number_format($product->price, 2) }}</h5>
                        <p class="card-text">{{ $product->description }}</p>
                        <p class="mb-2">
                            <strong>Availability:</strong>
                            @if ($product->available == 1)
                                <span class="text-success">In Stock</span>
                            @else
                                <span class="text-danger">Out of Stock</span>
                            @endif
                        </p>

                        @if ($product->available == 1)
                            <a href="/cart/add/{{ $product->id }}" class="btn btn-success">Add to cart</a>
                        @else
                            <button class="btn btn-secondary" disabled>Out of Stock</button>
                        @endif
                        <hr class="my-4">

                        <h4>Customer Feedback</h4>

                        {{-- Feedback Form --}}
                        @if (session('id'))
                            @php
                                // Get the user's orders that contain this product
$userOrders = DB::table('order_details')
    ->join('orders', 'orders.id', '=', 'order_details.order_id')
    ->where('orders.customer_id', session('id'))
    ->where('order_details.product_id', $product->id)
    ->select('orders.id')
                                    ->get();
                            @endphp

                            @if ($userOrders->isNotEmpty())
                                <form method="POST" action="/feedback/add" class="mb-4">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                                    <div class="mb-3">
                                        <label class="form-label">Select Order:</label>
                                        <select name="order_id" class="form-select" required>
                                            <option value="">Choose Order</option>
                                            @foreach ($userOrders as $order)
                                                <option value="{{ $order->id }}">Order #{{ $order->id }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Rating:</label>
                                        <select name="rating" class="form-select" required>
                                            <option value="">Select Rating</option>
                                            @for ($i = 1; $i <= 5; $i++)
                                                <option value="{{ $i }}">{{ $i }}
                                                    Star{{ $i > 1 ? 's' : '' }}</option>
                                            @endfor
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Comment:</label>
                                        <textarea name="comment" class="form-control" rows="2" placeholder="Write your review..."></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Submit Feedback</button>
                                </form>
                            @else
                                <p class="text-warning">You must purchase this product before leaving a review.</p>
                            @endif
                        @endif
                        {{-- Display Reviews --}}
                        @foreach ($feedbacks as $fb)
                            <div class="border rounded p-3 mb-3 bg-dark-subtle">
                                <strong>{{ $fb->username }}</strong>
                                <div>
                                    @for ($i = 1; $i <= 5; $i++)
                                        <span class="{{ $i <= $fb->rating ? 'text-warning' : 'text-secondary' }}">★</span>
                                    @endfor
                                </div>
                                <p class="mb-1">{{ $fb->comment }}</p>
                                <small class="text-muted">{{ $fb->created_at }}</small>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>

        <a href="/" class="btn btn-outline-secondary">Back to Home</a>
    </div>
@endsection
