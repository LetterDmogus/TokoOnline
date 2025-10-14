@extends('app')

@section('title', 'User Management')

@section('content') <div class="container mt-4">
        <h2 class="mb-3">User Management</h2>
        @if (session('success'))
            <span class="text-success">{{session('success')}}</span>
        @endif
        <a href="/monitor/user" class="mb-3 btn form-control btn-success">Monitor the categories!</a>
        <form method="GET" action="{{ route('users') }}" class="d-flex mb-3">
            <select name="selectedrole" class="form-select me-2" onchange="this.form.submit()">
                <option value="">All Roles</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" {{ $role->id == $selectedrole ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>

            <input type="text" name="search" value="{{ $search }}" placeholder="Search users" class="form-control me-2">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
        <table class="table table-bordered text-white align-middle">
            <thead class="table-primary">
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Role</th>
                    <th scope="col" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                    <tr>
                        <td>{{$row->name}}</td>
                        <td>{{$row->email}}</td>
                        <td>{{$row->rolename}}</td>
                        <td class="text-center">
                            <a class="btn btn-sm btn-primary" href="/user/{{$row->id}}">Edit</a>
                            <a class="btn btn-sm btn-danger" href="/delete/users/{{$row->id}}">Delete</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="ml-5 mt-5">
        <a href="/"><button class="btn btn-secondary">Kembali</button></a>
        <a href="/user/tambah"><button class="btn btn-primary">Tambah</button></a>
    </div>
    </div>
@endsection