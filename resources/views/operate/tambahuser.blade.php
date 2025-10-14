@extends('app')

@section('title', 'Add User')

@section('content')<div class="container mt-5">
    <h2 class="mb-4">Tambah User</h2>
    <form action="/user/tambah" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" class="form-control" id="nama" name="name" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="text" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-control" id="role" name="role" required>
                <?php foreach($role as $i): ?>
                <option value="<?= $i->id ?>"><?= $i->name ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Tambah</button>
    </form>
    <a href="/user"><button class="btn btn-primary btn-danger">Kembali</button></a>
</div>
