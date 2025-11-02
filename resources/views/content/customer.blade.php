@extends('app')

@section('title', 'Customer Management')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-center">Customer Management</h2>

    {{-- Success Message --}}

    {{-- Search Form --}}
    <form method="GET" action="/customer" class="d-flex mb-4 justify-content-center">
        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search customers..." class="form-control me-2" style="max-width: 300px;">
        <button type="submit" class="btn btn-primary">Search</button>
    </form>

    {{-- Customer Table --}}
    <div class="table-responsive">
        <table class="table table-bordered text-white align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Gender</th>
                    <th>Birthdate</th>
                    <th>Address</th>
                    <th>Joined At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $row)
                    <tr>
                        <td>{{ $row->full_name }}</td>
                        <td>{{ $row->phone }}</td>
                        <td>{{ ucfirst($row->gender) }}</td>
                        <td>{{ $row->birth_date }}</td>
                        <td>{{ $row->address }}</td>
                        <td>{{ $row->created_at }}</td>
                        <td>
                            <a href="/customer/{{ $row->id }}" class="btn btn-sm btn-primary">Details</a>
                            <a href="/customer/delete/{{ $row->user_id }}" class="btn btn-sm btn-danger" onclick="return confirm('Delete this customer?')">Delete</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-muted">No customers found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Back Button --}}
    <div class="text-center mt-4">
        <a href="/" class="btn btn-secondary">Back</a>
    </div>
</div>
@endsection
