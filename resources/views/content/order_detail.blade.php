@extends('app')

@section('title', 'Order Details')

@section('content')
    <div class="container mt-4">
        <div class="mb-4">
            @if (session('role') == 1)
                @if ($payment)
                    @if ($payment->status == 'failed')
                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                            <i class="bi bi-exclamation-octagon-fill me-2 fs-5"></i>
                            <div>Your last payment is not accepted, please resend the proof</div>
                        </div>
                        <a href="/order/{{ $order->id }}/payment" class="form-control btn btn-primary">Pay now</a>
                    @elseif ($payment->status == 'pending')
                        <div class="alert alert-warning d-flex align-items-center" role="alert">
                            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                            <div>Waiting for confirmation</div>
                        </div>
                    @else
                        <div class="alert alert-success d-flex align-items-center" role="alert">
                            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                            <div>Payment success at {{ $payment->paid_at }}</div>
                        </div>
                    @endif
                @else
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <i class="bi bi-exclamation-octagon-fill me-2 fs-5"></i>
                        <div>Order is not purchased yet, please proceed the payment to continue</div>
                    </div>
                    <a href="/order/{{ $order->id }}/payment" class="form-control btn btn-primary">Pay now</a>
                @endif
            @endif
        </div>
        <div class="card bg-secondary shadow-sm mb-4">
            <div class="card-header text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Order #{{ $order->id }}</h5>
                <a href="{{ url()->previous() }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>

            <div class="card-body bg-dark text-white">
                <table class="table table-dark table-borderless">
                    <tr>
                        <th style="width: 25%">Customer</th>
                        <td>: {{ $order->name }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if ($order->status === 'Completed')
                                <span class="badge bg-success">{{ $order->status }}</span>
                            @elseif($order->status === 'Pending')
                                <span class="badge bg-warning text-dark">{{ $order->status }}</span>
                            @else
                                <span class="badge bg-secondary">{{ $order->status }}</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Total Price</th>
                        <td>: ${{ number_format($order->total_price, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Order Date</th>
                        <td>: {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Payment Method</th>
                        <td>: {{ $order->payment_method ?? 'Not specified' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Order Items Table -->
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                <h6 class="mb-0">Order Items</h6>
            </div>
            <div class="card-body bg-dark">
                <table class="table table-dark table-hover align-middle">
                    <thead class="tab">
                        <tr>
                            <th>Product</th>
                            <th>Description</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orderDetails as $item)
                            <tr>
                                <td>{{ $item->product_name }}</td>
                                <td>{{ $item->description ?? '-' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>${{ number_format($item->price, 2) }}</td>
                                <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="tabl">
                        <tr>
                            <th colspan="4" class="text-end">Total:</th>
                            <th>${{ number_format($order->total_price, 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
