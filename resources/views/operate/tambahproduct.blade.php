@extends('app')

@section('title', 'Add Product')

@section('content')<div class="container mt-5">
    <h2 class="mb-4">Tambah product</h2>
    <form action="/product/tambah" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" class="form-control" id="nama" name="name" required>
        </div>
        <div class="mb-3">
            <label for="harga" class="form-label">Harga</label>
            <input type="number" step="0.01" class="form-control" id="harga" name="price" required>
        </div>
        <div class="mb-3">
            <label for="desc" class="form-label">Deskripsi</label>
            <textarea class="form-control" id="desc" name="desc"></textarea>
        </div>
                <div class="mb-3">
            <label class="form-label ">Is it available?</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="available" id="yes" value="1" checked>
                <label class="form-check-label" for="yes">
                    Yes
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="available" id="no" value="0">
                <label class="form-check-label" for="no">
                    No
                </label>
            </div>
        </div>
        <div class="mb-3">
            <label for="kategori" class="form-label">Kategori</label>
            <select class="form-control" id="kategori" name="kategori" required>
                <?php foreach ($kategori as $i): ?>
                <option value="<?= $i->id ?>"><?= $i->name ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Photo</label>
            <input type="file" name="image" id="image" class="form-control" accept="image/*">
        </div>
        <button type="submit" class="btn btn-primary">Tambah</button>
    </form>
    <a href="/product"><button class="btn btn-primary btn-danger">Kembali</button></a>
</div>
@endsection