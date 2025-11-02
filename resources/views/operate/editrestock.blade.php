@extends('app')

@section('title', 'Restock Product')

@section('content')
    <div class="container mt-5">
        <h2 class="mb-4">Restock Product</h2>
        <form action="/restock" method="POST">
            @csrf
            <div class="mb-3">
                <label for="amount" class="form-label">Amount</label>
                <input type="number" step="1" class="form-control" id="amount" name="amount" required>
            </div>
            <div class="mb-3">
                <label for="products" class="form-label">Products</label>
                <select class="form-control" id="products" name="products" required>
                    <?php foreach($products as $i): ?>
                    <option value="<?= $i->id ?>"><?= $i->name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
        <a href="/product"><button class="btn btn-primary btn-danger">Kembali</button></a>
    </div>
@endsection
