@extends('app')

@section('title', 'Your Cart')

@section('content')
    <div class="container mt-4">
        <h2 class="text-center mb-4">Your Cart</h2>


        @if (count($cart) > 0)
            <table class="table table-dark table-striped align-middle">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @php $grandTotal = 0; @endphp
                    @foreach ($cart as $id => $item)
                        @php
                            $total = $item['price'] * $item['quantity'];
                            $grandTotal += $total;
                        @endphp
                        <tr>
                            <td>
                                <img src="{{ asset('storage/' . $item['image']) }}" alt="" width="60"
                                    class="me-2 rounded">
                                {{ $item['name'] }}
                            </td>
                            <td>${{ number_format($item['price'], 2) }}</td>
                            <td>{{ $item['quantity'] }}</td>
                            <td>${{ number_format($total, 2) }}</td>
                            <td>
                                <a href="{{ route('cart.remove', $id) }}" class="btn btn-sm btn-danger">
                                    Remove
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end fw-bold">Grand Total:</td>
                        <td colspan="2" class="fw-bold">${{ number_format($grandTotal, 2) }}</td>
                    </tr>
                </tfoot>
            </table>

            <form method="POST" action="{{ route('cart.checkout') }}">
                @csrf
                <div class="text-end">
                    <button type="submit" class="btn btn-success">Checkout</button>
                </div>
            </form>
        @else
            <div class="alert alert-info text-center">
                Your cart is empty. <a href="{{ route('products') }}" class="alert-link">Shop now!</a>
            </div>
        @endif
    </div>
@endsection
