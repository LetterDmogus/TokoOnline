@extends('app')

@section('title', 'Product Management')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-3">Product Management</h2>
        @if (session('success'))
            <span class="text-success">{{session('success')}}</span>
        @endif
        <a href="/monitor/category" class="mb-3 btn form-control btn-success">Monitor the categories!</a>
        <form method="GET" action="{{ route('products') }}" class="d-flex mb-3">
            <select name="category" class="form-select me-2" onchange="this.form.submit()">
                <option value="">All Categories</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $cat->id == $category ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>

            <input type="text" name="search" value="{{ $search }}" placeholder="Search products" class="form-control me-2">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
        <div class="mb-3">
            <a href="/"><button class="btn btn-secondary">Kembali</button></a>
            <a href="/product/tambah"><button class="btn btn-primary">Tambah</button></a>
        </div>
        <table class="table table-bordered align-middle text-white">
            <thead class="table-primary">
                <tr>
                    <th scope="col">Image</th>
                    <th scope="col">Name</th>
                    <th scope="col" width="15%">Description</th>
                    <th scope="col">Category</th>
                    <th scope="col">Price</th>
                    <th scope="col">Status</th>
                    <th scope="col" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $row)
                    <tr>
                        <td>
                            <img src="{{ $row->image_url ? asset('storage/' . $row->image_url) : asset(path: 'storage/images/Default.png') }}"
                                class="img-thumbnail" width="60" alt="product item">
                        </td>
                        <td>{{$row->name}}</td>
                        <td>{{$row->description}}</td>
                        <td>&lt;{{$row->category}}&gt; {{$row->categoryname}}</td>
                        <td>${{$row->price}}</td>
                        <td>@if($row->available == 1)<span class="badge bg-success">Available</span> @else <span
                        class="badge bg-danger">Not available</span> @endif</td>
                        <td class="text-center">
                            <a class="btn btn-sm btn-primary" href="/product/{{$row->id}}">Edit</a>
                            <a class="btn btn-sm btn-danger" href="/delete/products/{{$row->id}}">Delete</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </div>
@endsection