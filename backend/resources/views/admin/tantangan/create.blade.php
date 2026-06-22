@extends('layouts.app')
@section('title', 'Tambah Soal — Admin RedSim')
@section('header', 'Tambah Soal Edukasi')
@section('subheader', 'Buat soal pilihan ganda baru untuk menguji pengguna')

@section('content')
<div class="max-w-3xl mx-auto bg-[#0f1629] rounded-2xl border border-[#1e2d4a] p-8">
    @if($errors->any())
    <div class="mb-6 px-4 py-3 rounded-lg bg-red-500/10 border border-red-500/30 text-red-400 text-sm">
        <ul class="list-disc pl-4">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.tantangan.store') }}" class="space-y-5">
        @csrf
        <div>
            <label class="block text-sm font-medium text-[#94a3b8] mb-2">Judul Soal</label>
            <input type="text" name="judul" value="{{ old('judul') }}" required class="w-full px-4 py-3 rounded-xl bg-[#0a0e1a] border border-[#1e2d4a] text-white focus:border-[#00d4ff] focus:outline-none">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-[#94a3b8] mb-2">Deskripsi Soal (Cerita/Kasus)</label>
            <textarea name="deskripsi" rows="4" required class="w-full px-4 py-3 rounded-xl bg-[#0a0e1a] border border-[#1e2d4a] text-white focus:border-[#00d4ff] focus:outline-none">{{ old('deskripsi') }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-[#94a3b8] mb-2">Poin</label>
                <input type="number" name="poin" value="{{ old('poin', 10) }}" required class="w-full px-4 py-3 rounded-xl bg-[#0a0e1a] border border-[#1e2d4a] text-white focus:border-[#00d4ff] focus:outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-[#94a3b8] mb-2">Tingkat Kesulitan</label>
                <select name="tingkat_kesulitan" required class="w-full px-4 py-3 rounded-xl bg-[#0a0e1a] border border-[#1e2d4a] text-white focus:border-[#00d4ff] focus:outline-none">
                    <option value="mudah">Mudah</option>
                    <option value="sedang">Sedang</option>
                    <option value="sulit">Sulit</option>
                </select>
            </div>
        </div>

        <div class="space-y-4 pt-4 border-t border-[#1e2d4a]">
            <h4 class="font-medium text-white">Pilihan Jawaban</h4>
            <div class="flex items-center gap-3">
                <span class="font-bold text-[#00d4ff] w-6">A.</span>
                <input type="text" name="pilihan_a" value="{{ old('pilihan_a') }}" required class="flex-1 px-4 py-2 rounded-xl bg-[#0a0e1a] border border-[#1e2d4a] text-sm text-white focus:border-[#00d4ff] focus:outline-none">
            </div>
            <div class="flex items-center gap-3">
                <span class="font-bold text-[#00d4ff] w-6">B.</span>
                <input type="text" name="pilihan_b" value="{{ old('pilihan_b') }}" required class="flex-1 px-4 py-2 rounded-xl bg-[#0a0e1a] border border-[#1e2d4a] text-sm text-white focus:border-[#00d4ff] focus:outline-none">
            </div>
            <div class="flex items-center gap-3">
                <span class="font-bold text-[#00d4ff] w-6">C.</span>
                <input type="text" name="pilihan_c" value="{{ old('pilihan_c') }}" required class="flex-1 px-4 py-2 rounded-xl bg-[#0a0e1a] border border-[#1e2d4a] text-sm text-white focus:border-[#00d4ff] focus:outline-none">
            </div>
            <div class="flex items-center gap-3">
                <span class="font-bold text-[#00d4ff] w-6">D.</span>
                <input type="text" name="pilihan_d" value="{{ old('pilihan_d') }}" required class="flex-1 px-4 py-2 rounded-xl bg-[#0a0e1a] border border-[#1e2d4a] text-sm text-white focus:border-[#00d4ff] focus:outline-none">
            </div>
        </div>

        <div class="pt-4 border-t border-[#1e2d4a]">
            <label class="block text-sm font-medium text-[#94a3b8] mb-2">Jawaban Benar</label>
            <div class="flex gap-4">
                @foreach(['A','B','C','D'] as $opt)
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="jawaban_benar" value="{{ $opt }}" required class="accent-[#00d4ff]">
                    <span class="font-bold">{{ $opt }}</span>
                </label>
                @endforeach
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-[#94a3b8] mb-2">Penjelasan (Opsional)</label>
            <textarea name="penjelasan" rows="2" class="w-full px-4 py-3 rounded-xl bg-[#0a0e1a] border border-[#1e2d4a] text-white focus:border-[#00d4ff] focus:outline-none" placeholder="Penjelasan mengapa jawaban tersebut benar">{{ old('penjelasan') }}</textarea>
        </div>

        <div class="flex gap-3 justify-end pt-4 border-t border-[#1e2d4a]">
            <a href="{{ route('admin.tantangan.index') }}" class="px-6 py-2.5 rounded-xl bg-[#1e2d4a] text-white font-medium hover:bg-gray-700 transition-colors">Batal</a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-[#00d4ff] to-[#7c3aed] text-white font-semibold hover:shadow-lg transition-all">Simpan Soal</button>
        </div>
    </form>
</div>
@endsection
