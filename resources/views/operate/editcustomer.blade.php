@extends('app')

@section('title', 'Edit Customer')

@section('content')
    <div class="container mt-5">
        <form action="/customer" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden"name="id" value="{{ $data->user_id }}"">
            <div class="mb-3">
                <label for="username" class="form-label ">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="{{ $data->name }}"
                    required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label ">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $data->email }}"
                    required>
            </div>
            <h4 class="mt-4 mb-3">Additional Information</h4>
            <div class="mb-3">
                <label for="fn" class="form-label ">Full Name</label>
                <input type="text" class="form-control" id="fn" name="fn" value="{{ $data->full_name }}"
                    required>
            </div>
            <div class="mb-3">
                <label for="bd" class="form-label ">Birth Date</label>
                <input type="date" class="form-control" id="bd" name="bd" value="{{ $data->birth_date }}"
                    required>
            </div>
            <div class="mb-3">
                <label for="ad" class="form-label ">Address</label>
                <input type="text" class="form-control" id="ad" name="ad" value="{{ $data->address }}"
                    required>
            </div>
            <div class="mb-3">
                <label for="gender" class="form-label ">Gender</label>

                <select class="form-control" id="gender" name="gender">
                    <option value="{{ $data->gender }}">{{ $data->gender }}</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                    <option value="PNS">Prefer not to say</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label ">Phone Number</label>
                <input type="phone" class="form-control" id="phone" name="phone" value="{{ $data->phone }}"
                    required>
            </div>
            <div class="mb-3">
                <label for="image" class="text-white form-label">Image</label>
                @if ($data && $data->profile_url)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $data->profile_url) }}"
                            style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px;">
                    </div>
                @endif
                <input type="file" name="image" id="image" class="form-control" accept="image/*">
                <small class="text-muted">Leave empty if you don't want to change the photo</small>
            </div>
            <button type="submit" class="btn btn-primary form-control">Save</button>
        </form>
        <a href="/customer"><button class="btn btn-primary btn-danger">Kembali</button></a>
    </div>
@endsection
