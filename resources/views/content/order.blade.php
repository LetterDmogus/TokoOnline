@extends('app')

@section('title', 'Order Management')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-3">Order Management</h2>
        @if (session('success'))
            <span class="text-success">{{session('success')}}</span>
        @endif
        <form method="GET" action="{{ route('orders') }}" class="d-flex mb-3">
            <select name="selectedorder" class="form-select me-2" onchange="this.form.submit()">
                <option value="">All Status</option>
                @foreach ($orders as $role)
                    <option value="{{ $role->id }}" {{ $role->id == $selectedrole ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>

            <input type="text" name="search" value="{{ $search }}" placeholder="Search orders" class="form-control me-2">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
        <table class="table table-bordered table-hover align-middle">
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
                @foreach($data as $row)
                    <tr>
                        <td>{{$row->customername}}</td>
                        <td>{{$row->status}}</td>
                        <td>{{$row->total_price}}</td>
                        <td>{{$row->created_at}}</td>
                        <td class="text-center">
                            <a class="btn btn-sm btn-primary" href="/detail/{{$row->id}}">Details</a>
                            <a class="btn btn-sm btn-danger" href="/delete/orders/{{$row->id}}">Delete</a>
                        </td>
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