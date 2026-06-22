@extends('layouts.app')
@section('title', 'Pemindaian URL — RedSim')
@section('header', 'Pemindaian URL')
@section('subheader', 'Masukkan URL untuk analisis keamanan')

@section('content')
<div class="max-w-2xl" x-data="{ mode: 'biasa' }">
    <form method="POST" action="{{ route('analisis.url') }}" class="space-y-6">
        @csrf
        {{-- URL Input --}}
        <div class="rounded-2xl bg-[#0f1629] border border-[#1e2d4a] p-6">
            <label for="url" class="block text-sm font-medium text-[#94a3b8] mb-2">URL Target</label>
            <input type="url" id="url" name="url" value="{{ old('url') }}" required
                   class="w-full px-4 py-3 rounded-xl bg-[#0a0e1a] border border-[#1e2d4a] text-white placeholder-[#475569] focus:border-[#00d4ff] focus:ring-1 focus:ring-[#00d4ff]/50 focus:outline-none"
                   placeholder="https://contoh.com">
            <p class="mt-2 text-xs text-[#64748b]">Masukkan URL lengkap termasuk https://</p>
        </div>

        {{-- Mode Selection --}}
        <div class="rounded-2xl bg-[#0f1629] border border-[#1e2d4a] p-6">
            <p class="text-sm font-medium text-[#94a3b8] mb-4">Mode Pemindaian</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Mode 1: Scan Biasa --}}
                <label class="relative cursor-pointer" @click="mode = 'biasa'">
                    <input type="radio" name="mode_scan" value="biasa" class="peer sr-only" checked x-bind:checked="mode === 'biasa'">
                    <div class="rounded-xl border-2 p-4 transition-all duration-200 peer-checked:border-[#00d4ff] peer-checked:bg-[#00d4ff]/5 border-[#1e2d4a] hover:border-[#1e2d4a]/80">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-8 h-8 rounded-lg bg-[#00d4ff]/10 flex items-center justify-center">
                                <span class="text-lg">🔍</span>
                            </div>
                            <h4 class="font-semibold text-sm">Scan Biasa</h4>
                        </div>
                        <p class="text-xs text-[#64748b] leading-relaxed">Pemindaian cepat menggunakan VirusTotal & URLScan. Tidak butuh verifikasi kepemilikan.</p>
                        <div class="mt-3 flex flex-wrap gap-1">
                            <span class="px-2 py-0.5 rounded-full text-[10px] bg-[#00d4ff]/10 text-[#00d4ff]">Reputasi Domain</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] bg-[#00d4ff]/10 text-[#00d4ff]">SSL Check</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] bg-[#00d4ff]/10 text-[#00d4ff]">Security Headers</span>
                        </div>
                    </div>
                </label>

                {{-- Mode 2: Scan Intens --}}
                <label class="relative cursor-pointer" @click="mode = 'intens'">
                    <input type="radio" name="mode_scan" value="intens" class="peer sr-only" x-bind:checked="mode === 'intens'">
                    <div class="rounded-xl border-2 p-4 transition-all duration-200 peer-checked:border-[#7c3aed] peer-checked:bg-[#7c3aed]/5 border-[#1e2d4a] hover:border-[#1e2d4a]/80">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-8 h-8 rounded-lg bg-[#7c3aed]/10 flex items-center justify-center">
                                <span class="text-lg">🛡️</span>
                            </div>
                            <h4 class="font-semibold text-sm">Scan Intens</h4>
                        </div>
                        <p class="text-xs text-[#64748b] leading-relaxed">Pemindaian mendalam. Membutuhkan verifikasi kepemilikan domain terlebih dahulu.</p>
                        <div class="mt-3 flex flex-wrap gap-1">
                            <span class="px-2 py-0.5 rounded-full text-[10px] bg-[#7c3aed]/10 text-[#7c3aed]">File Sensitif</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] bg-[#7c3aed]/10 text-[#7c3aed]">IP & ASN</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] bg-[#7c3aed]/10 text-[#7c3aed]">Analisis AI</span>
                        </div>
                    </div>
                </label>
            </div>

            {{-- Info Mode Intens --}}
            <div x-show="mode === 'intens'" x-transition class="mt-4 p-3 rounded-lg bg-[#7c3aed]/5 border border-[#7c3aed]/20">
                <p class="text-xs text-[#7c3aed]">⚠️ Scan Intens memerlukan verifikasi kepemilikan domain. Anda akan diminta mengupload file HTML ke root directory website.</p>
            </div>
        </div>

        {{-- AI & RAG Options --}}
        @include('components.ai-options')

        <button type="submit" class="px-8 py-3 rounded-xl bg-gradient-to-r from-[#00d4ff] to-[#7c3aed] text-white font-semibold hover:shadow-lg hover:shadow-[#00d4ff]/25 transition-all duration-300">
            🔍 Mulai Pemindaian
        </button>
    </form>
</div>
@endsection
