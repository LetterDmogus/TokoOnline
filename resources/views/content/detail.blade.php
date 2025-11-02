@extends('app')

@section('title', 'Detail Information')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ $title ?? 'Detail Information' }}</h5>
            <a href="{{ url()->previous() }}" class="btn btn-light btn-sm">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>

        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4 text-center">
                    @if(!empty($data->profile_url))
                        <img src="{{ asset('storage/' . $data->profile_url)}}" alt="Image" class="img-fluid rounded border" style="max-height: 200px;">
                    @else
                        <img src="{{ asset('storage/images/Default.png') }}" alt="No Image" class="img-fluid rounded border" style="max-height: 200px;">
                    @endif
                </div>
                <div class="col-md-8">
                    <table class="table table-borderless">
                        @foreach($fields as $label => $value)
                            <tr>
                                <th style="width: 30%">{{ ucfirst($label) }}</th>
                                <td>: {{ $value ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>

        <div class="card-footer text-end">
            <a href="/edit/{{ $table }}/{{ $data->id }}" class="btn btn-warning">
                <i class="bi bi-pencil-square"></i> Edit
            </a>
            <a href="/detaildelete/{{ $table }}/{{ $data->user_id }}" class="btn btn-danger">
                <i class="bi bi-trash"></i> Delete
            </a>
        </div>
    </div>
</div>
@endsection
