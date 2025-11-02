@extends('.app')
@section('title', 'Shop Management')
@section('content')
    <div class="container py-4">

        <!-- Shop Info Section -->
        <div class="card mb-4">
            <div class="card-body bg-dark text-white d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    @if (!empty($shop->logo))
                        <img src="{{ asset('storage/' . $shop->logo) }}" alt="Logo" width="80" height="80"
                            class="rounded me-3" style="object-fit: cover;">
                    @else
                        <img src="{{ asset('storage/public/default.png') }}" alt="Logo" width="80" height="80"
                            class="rounded me-3" style="object-fit: cover;">
                    @endif
                    <div>
                        <h4 class="mb-1">{{ $shop->name }}</h4>
                        <p class="text-muted mb-1">{{ $shop->description }}</p>
                        <small class="text-secondary">Address: {{ $shop->address }}</small><br>
                        <small class="text-secondary">Tier: {{ $shop->shop_tier }}</small>
                    </div>
                </div>
                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editShopModal">
                    Edit Shop
                </button>
            </div>
        </div>

        <!-- Product List -->
        <div class="card">
            <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Products</h5>
                <a href="/product/create/{{ $shop->id }}" class="btn btn-success btn-sm">+ Add Product</a>
            </div>
            <div class="card-body bg-dark">
                @if (!$products)
                    <p class="text-muted">No products yet.</p>
                @else
                    <table class="table table-hover table-dark text-white">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $p)
                                <tr>
                                    <td>{{ $p->name }}</td>
                                    <td>${{ $p->price }}</td>
                                    <td>{{ $p->stock }}</td>
                                    <td>
                                        <span class="badge {{ $p->available ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $p->available ? 'Available' : 'Unavailable' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="/product/{{ $p->id }}"
                                            class="btn btn-sm btn-warning">Edit</a>
                                        <a href="/delete/products/{{ $p->id }}" class="btn btn-sm btn-danger">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>

    <!-- Edit Shop Modal -->
    <div class="modal fade " id="editShopModal" tabindex="-1" aria-labelledby="editShopModalLabel" aria-hidden="true">
        <div class="modal-dialog mt-5" >
            <form method="POST" action="{{ route('seller.shop.update', $shop->id) }}" enctype="multipart/form-data"
                class="modal-content">
                @csrf
                <div class="modal-header bg-dark">
                    <h5 class="modal-title" id="editShopModalLabel">Edit Shop</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body bg-dark">
                    <div class="mb-3">
                        <label class="form-label">Shop Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $shop->name }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control">{{ $shop->description }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <input type="text" name="address" class="form-control" value="{{ $shop->address }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Logo</label>
                        <input type="file" name="logo" class="form-control">
                    </div>
                </div>
                <div class="modal-footer bg-dark">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
@endsection

