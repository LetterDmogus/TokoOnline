@extends('app')

@section('title', 'Edit Product')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">{{ $data ? 'Edit Product' : 'Tambah product' }}</h2>
    <form action="/product/{{ $data ? $data->id : 'tambah' }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" value="{{ $data ? $data->id : '' }}" name="id">
        <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" class="form-control" id="nama" name="name" value="{{ $data ? $data->name : '' }}"
                required>
        </div>
        <div class="mb-3">
            <label for="harga" class="form-label">Harga</label>
            <input type="number" step="0.01" class="form-control" id="harga" name="price"
                value="{{ $data ? $data->price : '' }}" required>
        </div>
        <div class="mb-3">
            <label for="desc" class="form-label">Deskripsi</label>
            <textarea class="form-control" id="desc" name="desc">{{ $data ? $data->description : '' }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label ">Is it available?</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="available" id="yes" value="1" @if($data && $data->available == '1')checked @endif>
                <label class="form-check-label" for="yes">
                    Yes
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="available" id="no" value="0" @if($data && $data->available == '0')checked @endif>
                <label class="form-check-label" for="no">
                    No
                </label>
            </div>
        </div>
        <div class="mb-3">
            <label for="kategori" class="form-label">Kategori</label>
            <select class="form-control" id="kategori" name="kategori" required>
                <option value="<?= !$data ? '' : $data->category?>"><?= !$data ? '' : $data->catname?></option>
                <?php foreach($kategori as $i): ?>
                @if($data)
                    @if($i->id != $data->category)
                        <option value="<?= $i->id ?>"><?= $i->name ?></option>
                    @endif
                @else
                    <option value="<?= $i->id ?>"><?= $i->name ?></option>
                @endif
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
                <label for="image" class="text-white form-label">Image</label>
                @if ($data && $data->image_url)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $data->image_url) }}"
                            style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px;">
                    </div>
                @endif
                <input type="file" name="image" id="image" class="form-control" accept="image/*">
                <small class="text-muted">Leave empty if you don't want to change the photo</small>
            </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
    <a href="/product"><button class="btn btn-primary btn-danger">Kembali</button></a>
</div>
@endsection