@extends('app')

@section('title', 'Edit Seller')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">{{ $data ? 'Edit Seller' : 'Tambah Seller' }}</h2>
    <form action="/seller/{{ $data ? $data->id : 'tambah' }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" value="{{ $data ? $data->id : '' }}" name="id">
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" id="nama" name="name" value="{{ $data ? $data->full_name : '' }}"
                required>
        </div>
        <div class="mb-3">
            <label for="un" class="form-label">Username</label>
            <input type="text" class="form-control" id="un" name="username" value="{{ $data ? $data->username : '' }}"
                required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email"
                value="{{ $data ? $data->email : '' }}" required>
        </div>
        <div class="mb-3">
            <label for="birthdate" class="form-label">Birthdate</label>
            <input type="date" class="form-control" id="birthdate" name="birthdate"
                value="{{ $data ? $data->birth_date : '' }}" required>
        </div>
        <div class="mb-3">
            <label for="adress" class="form-label">Adress</label>
            <input type="text" class="form-control" id="adress" name="adress" value="{{ $data ? $data->adress : '' }}">
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone" value="{{ $data ? $data->phone : '' }}">
        </div>
        <div class="mb-3">
            <label for="gender" class="form-label">Gender</label>
            <input type="radio" class="form-control" id="gender" name="gender" value="Male" {{ $data ? ($data->gender=="Male" ? "Male":"Female") : '' }}>
            <input type="radio" class="form-control" id="gender" name="gender" value="Male" {{ $data ? ($data->gender=="Male" ? "Male":"Female") : '' }}>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
    <a href="/product"><button class="btn btn-primary btn-danger">Kembali</button></a>
</div>
@endsection