@extends('app')

@section('title', 'Restock Management')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-3">Restock Management</h2>

        <form method="GET" action="{{ route('restocks') }}" class="d-flex mb-3">
            <input type="date" name="startDate" class="form-control me-2" value="{{ request('startDate') }}">
            <input type="date" name="endDate" class="form-control me-2" value="{{ request('endDate') }}">
            <button type="submit" class="btn btn-primary">Filter</button>
        </form>
        <a href="/restock/tambah" class="btn btn-primary mb-3">Restock</a>
        <table class="table text-white table-bordered align-middle">
            <thead class="table-primary">
                <tr>
                    <th scope="col">Manager</th>
                    <th scope="col">Product</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Date Created</th>
                    <th scope="col" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $row)
                    <tr>
                        <td>{{ $row->managername }}</td>
                        <td>{{ $row->productname }}</td>
                        <td>{{ $row->quantity }}</td>
                        <td>{{ $row->created_at }}</td>
                        <td class="text-center">
                            <a class="btn btn-sm btn-primary" href="/delete/restock/{{ $row->id }}">Delete</a>
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
