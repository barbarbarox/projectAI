@extends('layouts.app')
@section('title', 'Hasil Scan URL — RedSim')
@section('header', 'Hasil Pemindaian URL')
@section('subheader', ucfirst($scan->mode_scan) . ' — ' . $scan->target)

@section('content')
@php
    $data = $scan->data_mentah ?? [];
    $isIntens = $scan->mode_scan === 'intens';
    $hasAI = $scan->verdict !== null;
@endphp

<div class="space-y-6">
    {{-- Skor Reputasi --}}
    <div class="grid md:grid-cols-4 gap-4">
        <div class="rounded-2xl bg-[#0f1629] border border-[#1e2d4a] p-6 text-center">
            @php
                $skor = $data['skor_reputasi'] ?? 0;
                $color = $skor >= 70 ? '#22c55e' : ($skor >= 50 ? '#eab308' : '#ef4444');
            @endphp
            <div class="text-4xl font-black" style="color: {{ $color }}">{{ $skor }}</div>
            <p class="text-xs text-[#64748b] mt-1">Skor Reputasi</p>
        </div>
        <div class="rounded-2xl bg-[#0f1629] border border-[#1e2d4a] p-6 text-center">
            @php
                $status = $data['status'] ?? 'unknown';
                $statusColor = match($status) { 'aman' => 'text-green-400', 'mencurigakan' => 'text-yellow-400', 'berbahaya' => 'text-red-400', default => 'text-gray-400' };
                $statusLabel = match($status) { 'aman' => '✅ Aman', 'mencurigakan' => '⚠️ Mencurigakan', 'berbahaya' => '🚫 Berbahaya', default => '❓ Tidak Diketahui' };
            @endphp
            <div class="text-lg font-bold {{ $statusColor }}">{{ $statusLabel }}</div>
            <p class="text-xs text-[#64748b] mt-1">Status Keamanan</p>
        </div>
        <div class="rounded-2xl bg-[#0f1629] border border-[#1e2d4a] p-6 text-center">
            <div class="text-lg font-bold text-[#00d4ff]">{{ $data['virustotal']['malicious_count'] ?? 0 }}/{{ $data['virustotal']['total_engines'] ?? 0 }}</div>
            <p class="text-xs text-[#64748b] mt-1">Vendor VT Flag</p>
        </div>
        <div class="rounded-2xl bg-[#0f1629] border border-[#1e2d4a] p-6 text-center">
            <div class="text-lg font-bold {{ ($data['security_headers']['skor'] ?? 0) >= 70 ? 'text-green-400' : (($data['security_headers']['skor'] ?? 0) >= 40 ? 'text-yellow-400' : 'text-red-400') }}">{{ $data['security_headers']['skor'] ?? 0 }}/100</div>
            <p class="text-xs text-[#64748b] mt-1">Skor Headers</p>
        </div>
    </div>

    {{-- SSL Certificate --}}
    <div class="rounded-2xl bg-[#0f1629] border border-[#1e2d4a] p-6">
        <h3 class="text-sm font-semibold text-[#00d4ff] mb-4">🔒 Status SSL/TLS</h3>
        @php $ssl = $data['ssl'] ?? []; @endphp
        <div class="grid md:grid-cols-3 gap-4">
            <div>
                <p class="text-xs text-[#64748b]">Status</p>
                @php
                    $sslStatus = $ssl['status'] ?? 'unknown';
                    $sslColor = match($sslStatus) { 'valid' => 'text-green-400', 'expired' => 'text-red-400', 'self_signed' => 'text-yellow-400', default => 'text-gray-400' };
                    $sslLabel = match($sslStatus) { 'valid' => '✅ Valid', 'expired' => '❌ Expired', 'self_signed' => '⚠️ Self-Signed', 'tidak_https' => '🔓 Tidak HTTPS', default => '❓ Error' };
                @endphp
                <p class="text-sm font-medium {{ $sslColor }}">{{ $sslLabel }}</p>
            </div>
            @if(!empty($ssl['issuer']))
            <div>
                <p class="text-xs text-[#64748b]">Penerbit</p>
                <p class="text-sm font-medium">{{ $ssl['issuer'] }}</p>
            </div>
            @endif
            @if(!empty($ssl['days_until_expiry']))
            <div>
                <p class="text-xs text-[#64748b]">Kedaluwarsa</p>
                <p class="text-sm font-medium {{ $ssl['days_until_expiry'] < 30 ? 'text-yellow-400' : 'text-green-400' }}">{{ $ssl['days_until_expiry'] }} hari lagi</p>
            </div>
            @endif
            @if($isIntens && !empty($ssl['protocol']))
            <div>
                <p class="text-xs text-[#64748b]">Protokol</p>
                <p class="text-sm font-medium">{{ $ssl['protocol'] }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Security Headers --}}
    <div class="rounded-2xl bg-[#0f1629] border border-[#1e2d4a] p-6">
        <h3 class="text-sm font-semibold text-[#00d4ff] mb-4">🛡️ Security Headers</h3>
        @php $headers = $isIntens ? ($data['security_headers']['headers_keamanan'] ?? []) : ($data['security_headers']['headers'] ?? []); @endphp
        <div class="space-y-2">
            @foreach($headers as $name => $info)
            <div class="flex items-center justify-between px-4 py-2.5 rounded-lg {{ ($info['ada'] ?? false) ? 'bg-green-500/5 border border-green-500/20' : 'bg-red-500/5 border border-red-500/20' }}">
                <div class="flex items-center gap-3">
                    <span class="text-sm">{{ ($info['ada'] ?? false) ? '✅' : '❌' }}</span>
                    <span class="text-sm font-medium">{{ $name }}</span>
                </div>
                <div class="flex items-center gap-2">
                    @if($info['ada'] ?? false)
                    <span class="text-xs text-green-400">+{{ $info['poin'] ?? 0 }} poin</span>
                    @else
                    <span class="text-xs text-red-400">Tidak ada</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        @if($isIntens && !empty($data['security_headers']['headers_sensitif']))
        <div class="mt-4 pt-4 border-t border-[#1e2d4a]">
            <h4 class="text-xs font-semibold text-yellow-400 mb-2">⚠️ Headers yang Expose Informasi</h4>
            @foreach($data['security_headers']['headers_sensitif'] as $name => $value)
            <div class="flex items-center justify-between px-4 py-2 rounded-lg bg-yellow-500/5 border border-yellow-500/20 mb-1">
                <span class="text-sm text-yellow-400">{{ $name }}</span>
                <span class="text-xs text-[#94a3b8]">{{ $value }}</span>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Teknologi --}}
    @if(!empty($data['teknologi']))
    <div class="rounded-2xl bg-[#0f1629] border border-[#1e2d4a] p-6">
        <h3 class="text-sm font-semibold text-[#00d4ff] mb-4">🔧 Teknologi Terdeteksi</h3>
        <div class="flex flex-wrap gap-2">
            @foreach($data['teknologi'] as $tech)
            <span class="px-3 py-1.5 rounded-lg bg-[#1e2d4a] text-sm text-[#94a3b8]">{{ $tech }}</span>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Server Info (Intens Only) --}}
    @if($isIntens && !empty($data['server_info']))
    <div class="rounded-2xl bg-[#0f1629] border border-[#1e2d4a] p-6">
        <h3 class="text-sm font-semibold text-[#7c3aed] mb-4">🖥️ Informasi Server</h3>
        <div class="grid md:grid-cols-2 gap-4">
            @foreach(['ip' => 'IP Address', 'asn' => 'ASN', 'asnname' => 'Nama ASN', 'country' => 'Negara', 'server' => 'Server Software'] as $key => $label)
            @if(!empty($data['server_info'][$key]))
            <div>
                <p class="text-xs text-[#64748b]">{{ $label }}</p>
                <p class="text-sm font-medium">{{ $data['server_info'][$key] }}</p>
            </div>
            @endif
            @endforeach
        </div>
    </div>
    @endif

    {{-- Common Files (Intens Only) --}}
    @if($isIntens && !empty($data['common_files']))
    <div class="rounded-2xl bg-[#0f1629] border border-[#1e2d4a] p-6">
        <h3 class="text-sm font-semibold text-[#7c3aed] mb-4">📂 Pemeriksaan File Umum</h3>
        <div class="space-y-2">
            @foreach($data['common_files'] as $file)
            <div class="flex items-center justify-between px-4 py-2.5 rounded-lg {{ ($file['terekspos'] ?? false) ? 'bg-red-500/5 border border-red-500/20' : 'bg-[#0a0e1a] border border-[#1e2d4a]' }}">
                <code class="text-sm {{ ($file['terekspos'] ?? false) ? 'text-red-400' : 'text-[#94a3b8]' }}">{{ $file['path'] }}</code>
                <span class="text-xs {{ ($file['terekspos'] ?? false) ? 'text-red-400 font-semibold' : 'text-green-400' }}">
                    {{ ($file['terekspos'] ?? false) ? '🚨 TEREKSPOS' : '✅ Aman' }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Screenshot --}}
    @if(!empty($data['screenshot_url']))
    <div class="rounded-2xl bg-[#0f1629] border border-[#1e2d4a] p-6">
        <h3 class="text-sm font-semibold text-[#00d4ff] mb-4">📸 Screenshot Website</h3>
        <div class="rounded-xl overflow-hidden border border-[#1e2d4a]">
            <img src="{{ $data['screenshot_url'] }}" alt="Screenshot {{ $scan->target }}" class="w-full" loading="lazy" onerror="this.parentElement.innerHTML='<p class=\'p-8 text-center text-sm text-[#64748b]\'>Screenshot tidak tersedia</p>'">
        </div>
    </div>
    @endif

    {{-- AI Analysis Button / Results --}}
    @if($isIntens || $scan->mode_scan === 'biasa')
    <div class="rounded-2xl bg-[#0f1629] border border-[#1e2d4a] p-6">
        @if($hasAI)
            {{-- AI Results --}}
            <h3 class="text-sm font-semibold text-[#7c3aed] mb-4">🤖 Analisis AI</h3>
            @if($scan->ringkasan_eksekutif)
            <div class="mb-4 p-4 rounded-xl bg-[#0a0e1a] border border-[#1e2d4a]">
                <h4 class="text-xs font-semibold text-[#00d4ff] mb-2">Ringkasan Eksekutif</h4>
                <p class="text-sm text-[#94a3b8] whitespace-pre-line">{{ $scan->ringkasan_eksekutif }}</p>
            </div>
            @endif
            @if($scan->ringkasan_teknis)
            <div class="p-4 rounded-xl bg-[#0a0e1a] border border-[#1e2d4a]">
                <h4 class="text-xs font-semibold text-[#7c3aed] mb-2">Ringkasan Teknis</h4>
                <p class="text-sm text-[#94a3b8] whitespace-pre-line">{{ $scan->ringkasan_teknis }}</p>
            </div>
            @endif

            {{-- Temuan AI --}}
            @if($scan->temuan->count())
            <div class="mt-4">
                <h4 class="text-sm font-semibold mb-3">Temuan ({{ $scan->temuan->count() }})</h4>
                <div class="space-y-3">
                    @foreach($scan->temuan->sortByDesc(fn($t) => ['kritis'=>5,'tinggi'=>4,'sedang'=>3,'rendah'=>2,'info'=>1][$t->tingkat_keparahan] ?? 0) as $temuan)
                        <x-finding-card :temuan="$temuan" />
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Simulasi Serangan --}}
            @if($scan->simulasiSerangan->count())
            <div class="mt-4">
                <h4 class="text-sm font-semibold mb-3">Simulasi Serangan</h4>
                @foreach($scan->simulasiSerangan as $sim)
                <div class="rounded-xl bg-[#0a0e1a] border border-[#1e2d4a] p-4 mb-2">
                    <h5 class="font-semibold text-red-400 text-sm">{{ $sim->nama_skenario }}</h5>
                    @if($sim->narasi_teknis)
                    <p class="text-xs text-[#94a3b8] mt-2">{{ $sim->narasi_teknis }}</p>
                    @endif
                </div>
                @endforeach
            </div>
            @endif
        @else
            {{-- Button to trigger AI --}}
            <div class="text-center">
                <h3 class="text-sm font-semibold mb-2">🤖 Ingin Analisis Lebih Dalam?</h3>
                <p class="text-xs text-[#64748b] mb-4">Gunakan AI + Knowledge Base keamanan untuk analisis mendalam.</p>
                <form method="POST" action="{{ route('analisis.url.ai-analisis', $scan->id) }}">
                    @csrf
                    <button type="submit" class="px-6 py-3 rounded-xl bg-gradient-to-r from-[#7c3aed] to-[#ec4899] text-white font-semibold hover:shadow-lg hover:shadow-[#7c3aed]/25 transition-all duration-300">
                        🧠 Analisis dengan AI
                    </button>
                </form>
            </div>
        @endif
    </div>
    @endif

    {{-- RAG Source Info Box --}}
    <x-rag-source-info :scan="$scan" />

    {{-- Disclaimer --}}
    <div class="rounded-xl bg-yellow-500/5 border border-yellow-500/20 p-4">
        <p class="text-xs text-yellow-400/80">⚠️ Data reputasi bersumber dari VirusTotal dan URLScan.io. @if($hasAI)Analisis AI berbasis knowledge base keamanan publik (NVD, MITRE ATT&CK, OWASP, CWE, CISA KEV). @endif Tidak menggantikan penetration testing profesional.</p>
    </div>

    {{-- PDF Download --}}
    <div class="flex gap-3">
        <a href="{{ route('laporan.detail', $scan) }}" class="px-4 py-2 rounded-lg bg-[#1e2d4a] text-xs text-[#94a3b8] hover:bg-[#2a3a5c] transition-colors">📋 Lihat Laporan Lengkap</a>
        <a href="{{ route('laporan.pdf', $scan) }}" class="px-4 py-2 rounded-lg bg-[#1e2d4a] text-xs text-[#94a3b8] hover:bg-[#2a3a5c] transition-colors">📄 Unduh PDF</a>
    </div>
</div>
@endsection
