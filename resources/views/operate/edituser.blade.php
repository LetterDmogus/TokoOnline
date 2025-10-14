@extends('app')

@section('title', 'Edit User')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Edit User</h2>
    <form action="/user/{{ $data->id }}" method="POST">
        @csrf
        <input type="hidden" value="{{ $data->id }}" name="id">
        <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" class="form-control" id="nama" name="name" value="{{ $data->name }}"
                required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email"
                value="{{ $data->email }}" required>
        </div>
        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <label for="kategori" class="form-label">Kategori</label>
            <select class="form-control" id="kategori" name="kategori" required>
                <option value="<?= $data->role?>"><?= $data->rolename?></option>
                <?php foreach($role as $i): ?>
                    @if($i->id != $data->role)
                        <option value="<?= $i->id ?>"><?= $i->name ?></option>
                    @endif
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
    <a href="/user"><button class="btn btn-primary btn-danger">Kembali</button></a>
</div>
@endsection