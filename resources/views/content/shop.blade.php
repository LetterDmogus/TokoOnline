@extends('app')

@section('title', 'Shop Management')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-center">Shop Management</h2>


    {{-- Search form --}}
    <form method="GET" action="/shop" class="d-flex mb-4 justify-content-center">
        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search shops..." class="form-control me-2" style="max-width: 300px;">
        <button type="submit" class="btn btn-outline-primary">Search</button>
    </form>

    {{-- Shops table --}}
    <div class="table-responsive">
        <table class="table text-white table-bordered align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>Shop Name</th>
                    <th>Owner</th>
                    <th>Category</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($shops as $row)
                    <tr>
                        <td>{{ $row->name }}</td>
                        <td>{{ $row->owner_name ?? 'Unknown' }}</td>
                        <td>{{ $row->tier_name ?? 'Uncategorized' }}</td>
                        <td>{{ $row->created_at }}</td>
                        <td>
                            <a href="/shop/detail/{{ $row->id }}" class="btn btn-sm btn-outline-primary">Details</a>
                            <a href="/shop/delete/{{ $row->id }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this shop?')">Delete</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-muted">No shops found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Back button --}}
    <div class="text-center mt-4">
        <a href="/" class="btn btn-outline-secondary">Back</a>
    </div>
</div>
@endsection
