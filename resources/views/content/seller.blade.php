@extends('app')

@section('title', 'Seller Management')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-center">Seller Management</h2>


    {{-- Search Form --}}
    <form method="GET" action="/seller" class="d-flex mb-4 justify-content-center">
        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search sellers..." class="form-control me-2" style="max-width: 300px;">
        <button type="submit" class="btn btn-outline-primary">Search</button>
    </form>

    {{-- Seller Table --}}
    <div class="table-responsive">
        <table class="table table-bordered text-white align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Tier</th>
                    <th>Joined At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sellers as $row)
                    <tr>
                        <td>{{ $row->full_name }}</td>
                        <td>{{ $row->email }}</td>
                        <td>{{ $row->tiername ?? 'Unassigned' }}</td>
                        <td>{{ $row->created_at }}</td>
                        <td>
                            <a href="/seller/detail/{{ $row->id }}" class="btn btn-sm btn-outline-primary">Details</a>
                            <a href="/seller/delete/{{ $row->id }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this seller?')">Delete</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-muted">No sellers found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Back Button --}}
    <div class="text-center mt-4">
        <a href="/" class="btn btn-outline-secondary">Back</a>
    </div>
</div>
@endsection