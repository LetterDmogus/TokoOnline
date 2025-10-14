@extends('app')

@section('title', 'Monitoring {{session("back")}}')

@section('content')
    <div class="container mt-5">
        <h1>Monitoring {{session("back")}}</h1>
        <ul class="list-group">
            @foreach ($data as $data)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $data->name }}
                    <span class="badge bg-primary rounded-pill">{{ $data->total_items }}</span>
                </li>
            @endforeach
        </ul>
        <a href="/{{session("back")}}" class="mt-3 btn btn-danger">Back</a>
    </div>
@endsection