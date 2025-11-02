@extends('app')

@section('title', 'Order Management')

@section('content')
    <div class="container mt-4">
        <form method="GET" action="{{ route('orders') }}" class="d-flex mb-3">
            <select name="selectedstatus" class="form-select me-2" onchange="this.form.submit()">
                <option value="">All Status</option>
                <option value="Pending" {{ $selectedstatus == 'Pending' ? 'selected' : '' }}>
                    Pending
                </option>
                <option value="Prepared" {{ $selectedstatus == 'Prepared' ? 'selected' : '' }}>
                    Prepared
                </option>
                <option value="Completed" {{ $selectedstatus == 'Completed' ? 'selected' : '' }}>
                    Completed
                </option>
                <option value="Cancelled" {{ $selectedstatus == 'Cancelled' ? 'selected' : '' }}>
                    Cancelled
                </option>
            </select>

            <input type="text" name="search" value="{{ $search }}" placeholder="Search orders"
                class="form-control me-2">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
        @if (Session('role')== 3 || session('role') == 4)
        <form method="GET" action="sales/report" class="d-flex mb-3">
            <button type="submit" class="btn btn-primary">Generate Report</button>
        </form>
        @endif
        <table class="table text-white table-bordered align-middle">
            <thead class="table-primary">
                <tr>
                    <th scope="col">Customer</th>
                    <th scope="col">Status</th>
                    <th scope="col">Total Price</th>
                    <th scope="col">Date Created</th>
                    <th scope="col" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $row)
                    <tr>
                        <td>{{ $row->full_name }}</td>
                        <td>{{ $row->status }}</td>
                        <td>{{ $row->total_price }}</td>
                        <td>{{ $row->created_at }}</td>
                        <td class="text-center">
                            <a class="btn btn-sm btn-outline-primary" href="/orders/detail/{{$row->id}}">Details</a>
                            <a class="btn btn-sm btn-outline-danger" href="/delete/orders/{{$row->id}}">{{ session('role') == 1 ? 'Cancel' : 'Delete' }}</a>                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="ml-5 mt-5">
        <a href="/"><button class="btn btn-secondary">Kembali</button></a>
    </div>
    </div>
@endsection
