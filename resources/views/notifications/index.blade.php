@extends('layouts.app')

@section('title', 'Notifikasi — AI-Connect')

@section('content')
<div class="container py-5">
    <div class="page-header">
        <div class="container">
            <h1>Notifikasi Saya</h1>
            <p>Daftar notifikasi terbaru untuk akun Anda.</p>
        </div>
    </div>

    <div class="card" style="border-radius: 18px; overflow: hidden;">
        <div class="card-body">
            @if ($notifications->isEmpty())
                <div class="text-center py-5">
                    <p class="text-muted mb-0">Belum ada notifikasi.</p>
                </div>
            @else
                <div class="list-group">
                    @foreach ($notifications as $notification)
                        <a href="{{ $notification['url'] }}" class="list-group-item list-group-item-action {{ $notification['unread'] ? 'bg-light' : '' }}" style="border-radius: 12px; margin-bottom: 0.75rem;">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="mb-1" style="font-weight: 600;">{{ $notification['message'] }}</p>
                                    <small class="text-muted">{{ $notification['time'] }}</small>
                                </div>
                                @if ($notification['unread'])
                                    <span class="badge bg-primary">Baru</span>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
