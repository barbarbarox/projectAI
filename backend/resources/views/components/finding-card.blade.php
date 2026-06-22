@props(['temuan'])
<div class="rounded-xl bg-[#0a0e1a] border border-[#1e2d4a] overflow-hidden" x-data="{ buka: false }">
    <button @click="buka = !buka" class="w-full px-5 py-4 flex items-center justify-between hover:bg-[#1e2d4a]/30 transition-colors text-left">
        <div class="flex items-center gap-3 flex-1 min-w-0">
            <x-severity-badge :tingkat="$temuan->tingkat_keparahan" />
            <div class="min-w-0">
                <p class="text-sm font-medium truncate">{{ $temuan->judul }}</p>
                <p class="text-xs text-[#64748b] mt-0.5">
                    {{ $temuan->lokasi }}
                    @if($temuan->cwe_id) · {{ $temuan->cwe_id }} @endif
                    @if($temuan->cve_id) · {{ $temuan->cve_id }} @endif
                </p>
            </div>
        </div>
        <svg class="w-5 h-5 text-[#64748b] transition-transform" :class="buka && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7"/></svg>
    </button>
    <div x-show="buka" x-collapse class="px-5 pb-5 space-y-4 border-t border-[#1e2d4a]">
        @if($temuan->deskripsi)
        <div class="pt-4">
            <h4 class="text-xs font-semibold text-[#64748b] uppercase mb-2">Deskripsi</h4>
            <p class="text-sm text-[#94a3b8]">{{ $temuan->deskripsi }}</p>
        </div>
        @endif
        @if($temuan->kode_rentan || $temuan->kode_aman)
        <div class="grid md:grid-cols-2 gap-3">
            @if($temuan->kode_rentan)
            <div>
                <h4 class="text-xs font-semibold text-red-400 mb-2">❌ Kode Rentan</h4>
                <pre class="p-3 rounded-lg bg-red-500/5 border border-red-500/20 text-xs text-red-300 overflow-x-auto"><code>{{ $temuan->kode_rentan }}</code></pre>
            </div>
            @endif
            @if($temuan->kode_aman)
            <div>
                <h4 class="text-xs font-semibold text-green-400 mb-2">✅ Kode Aman</h4>
                <pre class="p-3 rounded-lg bg-green-500/5 border border-green-500/20 text-xs text-green-300 overflow-x-auto"><code>{{ $temuan->kode_aman }}</code></pre>
            </div>
            @endif
        </div>
        @endif
        @if($temuan->remediasi)
        <div>
            <h4 class="text-xs font-semibold text-[#00d4ff] mb-2">🔧 Remediasi</h4>
            <p class="text-sm text-[#94a3b8]">{{ $temuan->remediasi }}</p>
        </div>
        @endif
        <div class="flex items-center gap-4 text-xs text-[#64748b]">
            @if($temuan->estimasi_usaha)<span>Usaha: <b class="text-[#94a3b8]">{{ ucfirst($temuan->estimasi_usaha) }}</b></span>@endif
            @if($temuan->tingkat_kepercayaan)<span>Kepercayaan: <b class="text-[#94a3b8]">{{ ucfirst($temuan->tingkat_kepercayaan) }}</b></span>@endif
        </div>
    </div>
</div>
