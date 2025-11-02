@extends('app')
@section('title', 'Proceed Payment')

@section('content')
<div class="container py-4">
    <h4>Payment for Order #{{ $order->id }}</h4>

    <form action="{{ route('payment.store', $order->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Payment Method</label>
            <select name="method" class="form-select" required>
                <option value="cash">Cash</option>
                <option value="e-wallet">E-Wallet</option>
                <option value="credit">Credit</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Amount</label>
            <input type="text" class="form-control" value="{{ $order->total_price }}" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label">Upload Payment Proof (optional)</label>
            <input type="file" name="proof" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Submit Payment</button>
    </form>
</div>
@endsection
