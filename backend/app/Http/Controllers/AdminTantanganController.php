<?php

namespace App\Http\Controllers;

use App\Models\Tantangan;
use Illuminate\Http\Request;

class AdminTantanganController extends Controller
{
    public function index()
    {
        $tantangan = Tantangan::orderBy('created_at', 'desc')->get();
        return view('admin.tantangan.index', compact('tantangan'));
    }

    public function create()
    {
        return view('admin.tantangan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'poin' => 'required|integer|min:1',
            'tingkat_kesulitan' => 'required|in:mudah,sedang,sulit',
            'pilihan_a' => 'required|string',
            'pilihan_b' => 'required|string',
            'pilihan_c' => 'required|string',
            'pilihan_d' => 'required|string',
            'jawaban_benar' => 'required|in:A,B,C,D',
            'penjelasan' => 'nullable|string',
        ]);

        $pilihan = [
            'A' => $request->pilihan_a,
            'B' => $request->pilihan_b,
            'C' => $request->pilihan_c,
            'D' => $request->pilihan_d,
        ];

        Tantangan::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tipe' => 'pilihan_ganda', // DIWAJIBKAN PILIHAN GANDA
            'kategori' => 'Keamanan Siber',
            'pilihan_jawaban' => $pilihan,
            'jawaban_benar' => $request->jawaban_benar,
            'penjelasan' => $request->penjelasan,
            'poin' => $request->poin,
            'tingkat_kesulitan' => $request->tingkat_kesulitan,
            'is_aktif' => true,
        ]);

        return redirect()->route('admin.tantangan.index')->with('success', 'Soal berhasil ditambahkan.');
    }

    public function edit(Tantangan $tantangan)
    {
        return view('admin.tantangan.edit', compact('tantangan'));
    }

    public function update(Request $request, Tantangan $tantangan)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'poin' => 'required|integer|min:1',
            'tingkat_kesulitan' => 'required|in:mudah,sedang,sulit',
            'pilihan_a' => 'required|string',
            'pilihan_b' => 'required|string',
            'pilihan_c' => 'required|string',
            'pilihan_d' => 'required|string',
            'jawaban_benar' => 'required|in:A,B,C,D',
            'penjelasan' => 'nullable|string',
        ]);

        $pilihan = [
            'A' => $request->pilihan_a,
            'B' => $request->pilihan_b,
            'C' => $request->pilihan_c,
            'D' => $request->pilihan_d,
        ];

        $tantangan->update([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tipe' => 'pilihan_ganda',
            'pilihan_jawaban' => $pilihan,
            'jawaban_benar' => $request->jawaban_benar,
            'penjelasan' => $request->penjelasan,
            'poin' => $request->poin,
            'tingkat_kesulitan' => $request->tingkat_kesulitan,
            'is_aktif' => $request->boolean('is_aktif', true),
        ]);

        return redirect()->route('admin.tantangan.index')->with('success', 'Soal berhasil diperbarui.');
    }

    public function destroy(Tantangan $tantangan)
    {
        $tantangan->delete();
        return redirect()->route('admin.tantangan.index')->with('success', 'Soal berhasil dihapus.');
    }
}
