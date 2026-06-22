@extends('layouts.app')
@section('title', 'Edit Profil — RedSim')
@section('header', 'Edit Profil')
@section('subheader', 'Kelola informasi pribadi dan pengaturan keamanan Anda')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Informasi Profil -->
    <div class="bg-[#0f1629] rounded-2xl border border-[#1e2d4a] p-8">
        <h3 class="text-lg font-semibold mb-4 text-white">Informasi Profil</h3>
        <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-[#94a3b8] mb-2">Nama Pengguna</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-3 rounded-xl bg-[#0a0e1a] border border-[#1e2d4a] text-white focus:border-[#00d4ff] focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#94a3b8] mb-2">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $user->nama_lengkap) }}" class="w-full px-4 py-3 rounded-xl bg-[#0a0e1a] border border-[#1e2d4a] text-white focus:border-[#00d4ff] focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#94a3b8] mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-4 py-3 rounded-xl bg-[#0a0e1a] border border-[#1e2d4a] text-white focus:border-[#00d4ff] focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#94a3b8] mb-2">Nomor WhatsApp (OTP)</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" required class="w-full px-4 py-3 rounded-xl bg-[#0a0e1a] border border-[#1e2d4a] text-white focus:border-[#25D366] focus:outline-none" placeholder="08xxxx">
                    <p class="text-xs text-[#64748b] mt-1">Nomor ini digunakan untuk masuk menggunakan OTP WhatsApp.</p>
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-[#00d4ff] to-[#7c3aed] text-white font-semibold hover:shadow-lg transition-all">Simpan Perubahan</button>
            </div>
        </form>
    </div>

    <!-- Ubah Kata Sandi -->
    <div class="bg-[#0f1629] rounded-2xl border border-[#1e2d4a] p-8">
        <h3 class="text-lg font-semibold mb-4 text-white">Ubah Kata Sandi</h3>
        <form method="POST" action="{{ route('profile.password') }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-[#94a3b8] mb-2">Kata Sandi Saat Ini</label>
                    <input type="password" name="current_password" required class="w-full md:w-1/2 px-4 py-3 rounded-xl bg-[#0a0e1a] border border-[#1e2d4a] text-white focus:border-[#00d4ff] focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#94a3b8] mb-2">Kata Sandi Baru</label>
                    <input type="password" name="password" required class="w-full md:w-1/2 px-4 py-3 rounded-xl bg-[#0a0e1a] border border-[#1e2d4a] text-white focus:border-[#00d4ff] focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#94a3b8] mb-2">Konfirmasi Kata Sandi Baru</label>
                    <input type="password" name="password_confirmation" required class="w-full md:w-1/2 px-4 py-3 rounded-xl bg-[#0a0e1a] border border-[#1e2d4a] text-white focus:border-[#00d4ff] focus:outline-none">
                </div>
            </div>

            <div class="flex justify-start pt-4">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#1e2d4a] text-white font-semibold hover:bg-gray-700 transition-colors border border-[#1e2d4a]">Ubah Kata Sandi</button>
            </div>
        </form>
    </div>
</div>
@endsection
