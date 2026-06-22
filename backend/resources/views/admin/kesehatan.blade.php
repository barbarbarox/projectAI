@extends('layouts.app')
@section('title', 'Kesehatan Sistem — Admin RedSim')
@section('header', 'Kesehatan Sistem')
@section('subheader', 'Pantau status API dan aktivitas pengguna')

@section('content')
<div class="space-y-6">
    
    {{-- Status Pengguna --}}
    <div class="grid md:grid-cols-2 gap-4">
        <div class="rounded-2xl bg-[#0f1629] border border-[#1e2d4a] p-6 flex items-center justify-between">
            <div>
                <p class="text-sm text-[#94a3b8] mb-1">Pengguna Online (15m terakhir)</p>
                <div class="text-3xl font-black text-green-400">{{ $usersOnline }}</div>
            </div>
            <div class="w-12 h-12 rounded-full bg-green-500/20 flex items-center justify-center text-green-400 text-2xl">
                👤
            </div>
        </div>
        <div class="rounded-2xl bg-[#0f1629] border border-[#1e2d4a] p-6 flex items-center justify-between">
            <div>
                <p class="text-sm text-[#94a3b8] mb-1">Pengguna Offline</p>
                <div class="text-3xl font-black text-[#64748b]">{{ $usersOffline }}</div>
            </div>
            <div class="w-12 h-12 rounded-full bg-[#1e2d4a] flex items-center justify-center text-[#94a3b8] text-2xl">
                💤
            </div>
        </div>
    </div>

    {{-- Status API & Layanan Eksternal --}}
    <div class="rounded-2xl bg-[#0f1629] border border-[#1e2d4a] p-6">
        <h3 class="text-sm font-semibold text-[#00d4ff] mb-4">🔌 Status Layanan & API Eksternal</h3>
        <div class="space-y-3">
            
            @php
                $statusColor = function($status) {
                    return match($status) {
                        'ok' => 'text-green-400 bg-green-500/10 border-green-500/20',
                        'warning' => 'text-yellow-400 bg-yellow-500/10 border-yellow-500/20',
                        default => 'text-red-400 bg-red-500/10 border-red-500/20',
                    };
                };
                
                $statusIcon = function($status) {
                    return match($status) {
                        'ok' => '✅',
                        'warning' => '⚠️',
                        default => '❌',
                    };
                };
            @endphp

            {{-- AI Provider --}}
            <div class="flex items-center justify-between p-4 rounded-xl border {{ $statusColor($healthStatus['ai_provider']['status']) }}">
                <div>
                    <h4 class="font-semibold text-sm mb-1">Model AI Utama</h4>
                    <p class="text-xs opacity-80">{{ $healthStatus['ai_provider']['message'] }}</p>
                </div>
                <div class="text-xl">{{ $statusIcon($healthStatus['ai_provider']['status']) }}</div>
            </div>

            {{-- VirusTotal --}}
            <div class="flex items-center justify-between p-4 rounded-xl border {{ $statusColor($healthStatus['virustotal']['status']) }}">
                <div>
                    <h4 class="font-semibold text-sm mb-1">VirusTotal API</h4>
                    <p class="text-xs opacity-80">{{ $healthStatus['virustotal']['message'] }}</p>
                </div>
                <div class="text-xl">{{ $statusIcon($healthStatus['virustotal']['status']) }}</div>
            </div>

            {{-- URLScan --}}
            <div class="flex items-center justify-between p-4 rounded-xl border {{ $statusColor($healthStatus['urlscan']['status']) }}">
                <div>
                    <h4 class="font-semibold text-sm mb-1">URLScan.io API</h4>
                    <p class="text-xs opacity-80">{{ $healthStatus['urlscan']['message'] }}</p>
                </div>
                <div class="text-xl">{{ $statusIcon($healthStatus['urlscan']['status']) }}</div>
            </div>

            {{-- Integrated Embedding --}}
            <div class="flex items-center justify-between p-4 rounded-xl border {{ $statusColor($healthStatus['embedding_service']['status']) }}">
                <div>
                    <h4 class="font-semibold text-sm mb-1">Pinecone Integrated Embedding</h4>
                    <p class="text-xs opacity-80">{{ $healthStatus['embedding_service']['message'] }}</p>
                </div>
                <div class="text-xl">{{ $statusIcon($healthStatus['embedding_service']['status']) }}</div>
            </div>

            {{-- Pinecone Vector DB --}}
            <div class="flex items-center justify-between p-4 rounded-xl border {{ $statusColor($healthStatus['vector_database']['status']) }}">
                <div>
                    <h4 class="font-semibold text-sm mb-1">Pinecone Vector Database</h4>
                    <p class="text-xs opacity-80">{{ $healthStatus['vector_database']['message'] }}</p>
                </div>
                <div class="text-xl">{{ $statusIcon($healthStatus['vector_database']['status']) }}</div>
            </div>

        </div>
    </div>
</div>
@endsection
