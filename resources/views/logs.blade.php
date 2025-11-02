@extends('app')

@section('title', 'System Logs')

@section('content')
<div class="container mt-4">
    <h3 class="text-center mb-3 text-light">System Logs</h3>

    <div class="bg-dark text-success p-3 rounded" style="font-family: monospace; height: 70vh; overflow-y: auto;">
        @forelse ($logs as $log)
            <div class="mb-1">
                <span class="text-secondary">[{{ $log->created_at }}]</span>
                <span class="text-info">{{ $log->username ?? 'Guest' }}</span>
                <span class="text-light">~</span>
                <span>{{ $log->action }}</span>
                @if ($log->details)
                    <span class="text-warning">({{ $log->details }})</span>
                @endif
            </div>
        @empty
            <p class="text-danger">No logs found...</p>
        @endforelse
    </div>
</div>
@endsection
