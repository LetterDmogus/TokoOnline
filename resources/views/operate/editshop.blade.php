@extends('app')
@section('title', 'Create Shop')
@section('content')
<div class="container py-4">
    <div class="card bg-dark text-white">
        <div class="card-header">
            <h4>Create New Shop</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('seller.shop.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Shop Name</label>
                    <input type="text" name="name" class="form-control" placeholder="Enter your shop name" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" placeholder="Describe your shop"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Shop Logo</label>
                    <input type="file" name="logo" class="form-control">
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success">Create Shop</button>
                    <a href="/" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
