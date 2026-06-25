// ============================================================
//  generate_architecture.js
//  Script untuk generate Arsitektur_RedSim.docx
//  Jalankan dengan: node generate_architecture.js
// ============================================================

const fs = require('fs');
const {
  Document, Packer, Paragraph, TextRun,
  HeadingLevel, AlignmentType, Table, TableRow, TableCell,
  WidthType, ShadingType, BorderStyle, UnderlineType,
} = require('docx');

// ============================================================
// HELPER FUNCTIONS
// ============================================================

/** Buat paragraf teks biasa */
function p(text, opts = {}) {
  return new Paragraph({
    children: [new TextRun({ text, ...opts })],
  });
}

/** Buat paragraf kosong (spasi antar section) */
function gap() {
  return new Paragraph({ text: '' });
}

/** Buat Heading level 1 */
function h1(text) {
  return new Paragraph({ text, heading: HeadingLevel.HEADING_1 });
}

/** Buat Heading level 2 */
function h2(text) {
  return new Paragraph({ text, heading: HeadingLevel.HEADING_2 });
}

/** Buat Heading level 3 */
function h3(text) {
  return new Paragraph({ text, heading: HeadingLevel.HEADING_3 });
}

/** Buat paragraf dengan bullet point */
function bullet(label, desc) {
  return new Paragraph({
    bullet: { level: 0 },
    children: [
      new TextRun({ text: label + ': ', bold: true }),
      new TextRun(desc),
    ],
  });
}

/** Buat paragraf dengan penomoran / langkah */
function step(nomor, desc) {
  return new Paragraph({
    children: [
      new TextRun({ text: `  ${nomor}. `, bold: true }),
      new TextRun(desc),
    ],
  });
}

/** Buat kotak diagram ASCII sederhana */
function diagramBox(lines) {
  return lines.map(line =>
    new Paragraph({
      children: [new TextRun({ text: line, font: 'Courier New', size: 18 })],
    })
  );
}

/** Buat baris tabel */
function tableRow(cells, isHeader = false) {
  return new TableRow({
    children: cells.map(text =>
      new TableCell({
        children: [new Paragraph({
          children: [new TextRun({ text, bold: isHeader })],
        })],
        shading: isHeader ? { type: ShadingType.SOLID, color: 'E74C3C', fill: 'E74C3C' } : undefined,
      })
    ),
  });
}

// ============================================================
// KONTEN DOKUMEN
// ============================================================

const content = [

  // =========================================================
  // HALAMAN JUDUL
  // =========================================================
  new Paragraph({
    text: 'ARSITEKTUR SISTEM REDSIM',
    heading: HeadingLevel.HEADING_1,
    alignment: AlignmentType.CENTER,
  }),
  new Paragraph({
    text: 'Retrieval-Augmented Generation (RAG) System',
    alignment: AlignmentType.CENTER,
    children: [new TextRun({ text: 'Retrieval-Augmented Generation (RAG) System', bold: true, size: 28 })],
  }),
  new Paragraph({
    text: 'Platform Keamanan Siber Berbasis AI',
    alignment: AlignmentType.CENTER,
    children: [new TextRun({ text: 'Platform Keamanan Siber Berbasis AI', italics: true, size: 22 })],
  }),
  gap(), gap(),

  // =========================================================
  // BAGIAN 1: PENDAHULUAN
  // =========================================================
  h1('1. Pendahuluan'),
  p('RedSim adalah platform keamanan siber berbasis web yang mengintegrasikan teknologi Artificial Intelligence (AI) dengan pendekatan Retrieval-Augmented Generation (RAG). Sistem ini dirancang untuk membantu pengguna dalam melakukan pemindaian keamanan, simulasi serangan, dan edukasi keamanan siber secara komprehensif.'),
  gap(),
  p('Keunggulan utama RedSim terletak pada kemampuannya menggabungkan data pemindaian real-time dari layanan pihak ketiga (urlscan.io, VirusTotal) dengan pengetahuan keamanan yang tersimpan di Pinecone Vector Database, kemudian menghasilkan analisis mendalam melalui AI LLM.'),
  gap(), gap(),

  // =========================================================
  // BAGIAN 2: KOMPONEN UTAMA & LAYANAN
  // =========================================================
  h1('2. Komponen Utama & Layanan'),

  h2('2.1 Frontend & Backend (Laravel)'),
  p('Lapisan utama aplikasi yang menangani semua interaksi pengguna dan logika bisnis:'),
  bullet('Antarmuka Pengguna (UI)', 'Tampilan web responsif untuk semua aktivitas pengguna dan Admin.'),
  bullet('Middleware Rate Limiter', 'Membatasi jumlah scan per hari sesuai tier pengguna (Gratis, Premium, Admin).'),
  bullet('Controller & Service', 'Mengorkestrasi alur data antara pengguna, API eksternal, database, dan AI.'),
  gap(),

  h2('2.2 Supabase (Database)'),
  p('Infrastruktur database relasional berbasis PostgreSQL yang menyimpan semua data persisten sistem:'),
  bullet('users', 'Data pengguna, tier akses, dan status verifikasi.'),
  bullet('scans', 'Log transaksi setiap pemindaian yang dilakukan.'),
  bullet('temuan', 'Detail kerentanan yang ditemukan per pemindaian.'),
  bullet('simulasi_serangan', 'Skenario attack chain hasil generasi AI.'),
  bullet('domain_verifications', 'Data verifikasi kepemilikan domain pengguna.'),
  bullet('tantangan & poin_user', 'Soal CTF dan rekam jejak poin setiap peserta.'),
  bullet('knowledge_chunks', 'Potongan literatur keamanan (CISA, OWASP, CWE) cadangan.'),
  bullet('ai_configurations', 'Profil konfigurasi provider AI (Groq, OpenAI, dll.).'),
  gap(),

  h2('2.3 Pinecone (Vector Database — RAG Core)'),
  p('Komponen inti sistem RAG. Pinecone Integrated Embedding menyimpan literatur keamanan siber dalam format vektor numerik, sehingga AI dapat mencari referensi yang semantically relevan secara cepat.'),
  bullet('Konten yang Diindeks', 'Panduan OWASP, basis data CVE/CWE, taktik MITRE ATT&CK, dan advisori CISA KEV.'),
  bullet('Cara Kerja', 'Saat ditemukan anomali dalam scan, sistem membuat "query embedding" dari anomali tersebut lalu mencari dokumen yang paling mirip (nearest neighbor) di Pinecone.'),
  bullet('Output', 'Konteks remediasi dan referensi keamanan yang relevan untuk diumpankan ke AI LLM.'),
  gap(),

  h2('2.4 urlscan.io'),
  p('Layanan analitik web pihak ketiga yang dipanggil via API untuk mendukung fitur scan URL:'),
  bullet('Fungsi', 'Menganalisis HTTP headers, struktur DOM halaman, cookie, sertifikat SSL, dan reputasi tautan.'),
  bullet('Kapan Dipanggil', 'Setiap kali pengguna melakukan Scan URL (baik mode biasa maupun intensif).'),
  bullet('Output ke Sistem', 'Data anomali teknis dan metadata keamanan yang dijadikan dasar analisis AI.'),
  gap(),

  h2('2.5 VirusTotal'),
  p('Platform intelijen ancaman kolaboratif yang menggabungkan lebih dari 70 mesin antivirus:'),
  bullet('Fungsi', 'Memeriksa hash file (ZIP, kode), URL, dan domain terhadap basis data malware global.'),
  bullet('Kapan Dipanggil', 'Saat pengguna mengupload file ZIP atau melakukan Scan Code.'),
  bullet('Output ke Sistem', 'Laporan deteksi malware, tingkat kepercayaan, dan flag bahaya yang diintegrasikan ke laporan AI.'),
  gap(),

  h2('2.6 AI LLM Engine (Groq / OpenAI)'),
  p('Mesin generasi bahasa yang bertugas menganalisis dan merangkum seluruh data pemindaian:'),
  bullet('Fungsi', 'Menghasilkan ringkasan eksekutif, skor keamanan, daftar kerentanan, dan simulasi serangan.'),
  bullet('Konfigurasi Dinamis', 'Admin dapat menambah, mengganti, atau menonaktifkan provider AI dari panel Admin tanpa mengubah kode (.env).'),
  bullet('Integrasi RAG', 'Setiap prompt yang dikirim ke LLM sudah diperkaya dengan konteks referensi dari Pinecone.'),
  gap(), gap(),

  // =========================================================
  // BAGIAN 3: FLOW DIAGRAM
  // =========================================================
  h1('3. Flow Diagram Sistem'),

  h2('3.1 Arsitektur Tingkat Tinggi (High-Level Architecture)'),
  p('Gambaran besar bagaimana komponen-komponen sistem saling terhubung:'),
  gap(),
  ...diagramBox([
    '  ┌─────────────────────────────────────────────────────┐',
    '  │                PENGGUNA / ADMIN                     │',
    '  └──────────────────────┬──────────────────────────────┘',
    '                         │',
    '              ┌──────────▼───────────┐',
    '              │   AUTENTIKASI LAYER  │',
    '              │  (Login, Register,   │',
    '              │  Google OAuth, OTP)  │',
    '              └──────────┬───────────┘',
    '                         │',
    '              ┌──────────▼───────────┐',
    '              │   BACKEND LARAVEL    │',
    '              │  (Controller, Rate   │',
    '              │   Limiter, Service)  │',
    '              └──┬────────────────┬──┘',
    '                 │                │',
    '   ┌─────────────▼──┐    ┌───────▼───────────┐',
    '   │  API EKSTERNAL  │    │    RAG PIPELINE    │',
    '   │  urlscan.io     │    │                   │',
    '   │  VirusTotal     │───►│  Pinecone Vector  │',
    '   └─────────────────┘    │  DB + AI LLM      │',
    '                          └────────┬──────────┘',
    '                                   │',
    '                       ┌───────────▼──────────┐',
    '                       │  SUPABASE (Database) │',
    '                       │  scans, temuan,      │',
    '                       │  users, poin, dll.   │',
    '                       └──────────────────────┘',
  ]),
  gap(), gap(),

  h2('3.2 Flow Login & Register'),
  gap(),
  ...diagramBox([
    '  MULAI',
    '    │',
    '    ├──[Belum punya akun]──► REGISTER PAGE',
    '    │                            │',
    '    │               ┌────────────┴────────────┐',
    '    │          [Email/Password]          [Google SSO]',
    '    │               │                         │',
    '    │         Isi Formulir            Google OAuth 2.0',
    '    │               │                         │',
    '    │         reCAPTCHA Check                 │',
    '    │               └────────┬────────────────┘',
    '    │                        │',
    '    │              Simpan ke DB',
    '    │             (is_verified=false)',
    '    │                        │',
    '    │              ⏳ Tunggu Persetujuan Admin',
    '    │                        │',
    '    │              ┌─────────┴────────┐',
    '    │         [Disetujui]        [Ditolak]',
    '    │              │                  │',
    '    │       Notifikasi Email      Akun Ditolak',
    '    │              │',
    '    └──[Sudah punya akun]──► LOGIN PAGE',
    '                             │',
    '                    Validasi Kredensial',
    '                             │',
    '                    Cek is_verified',
    '                             │',
    '              ┌──────────────┴───────────────┐',
    '         [Terverifikasi]              [Belum Verifikasi]',
    '              │                              │',
    '       Cek OTP Aktif?                   ❌ Akses Ditolak',
    '              │',
    '    ┌─────────┴─────────┐',
    '  [Ya]               [Tidak]',
    '    │                   │',
    '  Kirim OTP WA      Dashboard',
    '    │',
    '  Masukkan OTP',
    '    │',
    '  Dashboard ✅',
  ]),
  gap(), gap(),

  h2('3.3 Flow Proses Scanning (RAG Pipeline)'),
  gap(),
  ...diagramBox([
    '  PENGGUNA DI DASHBOARD',
    '    │',
    '    ├── Pilih Tipe Scan:',
    '    │       ├── URL Target',
    '    │       ├── Upload File ZIP / Code',
    '    │       └── Upload File Log',
    '    │',
    '    ├── Pilih Mode Scan:',
    '    │       ├── [BIASA] ──────────────────────────┐',
    '    │       └── [INTENSIF] ─► Verifikasi Domain   │',
    '    │                           (DNS TXT / Meta Tag)│',
    '    │                                              │',
    '    ├── Cek Rate Limit (Tier User) ◄──────────────┘',
    '    │       └── [Kuota Habis] ─► ❌ Tolak Request',
    '    │',
    '    │ ┌─────────────────────────────────────────────────┐',
    '    │ │          PENGUMPULAN DATA (OSINT)                │',
    '    │ │                                                  │',
    '    │ │  urlscan.io ──► Analisis HTTP, DOM, SSL, Cookie  │',
    '    │ │  VirusTotal ──► Scan Hash Malware, Reputasi URL  │',
    '    │ └─────────────────────────────────────────────────┘',
    '    │                         │',
    '    │ ┌───────────────────────▼────────────────────────┐',
    '    │ │               RAG PIPELINE                     │',
    '    │ │                                                │',
    '    │ │  1. Buat Query Embedding dari Temuan Scan      │',
    '    │ │  2. Cari Referensi di Pinecone Vector DB       │',
    '    │ │     (CVE, CWE, OWASP, MITRE ATT&CK, CISA)     │',
    '    │ │  3. Ambil Top-K Konteks Paling Relevan         │',
    '    │ └───────────────────────┬────────────────────────┘',
    '    │                         │',
    '    │ ┌───────────────────────▼────────────────────────┐',
    '    │ │               AI LLM (Groq / OpenAI)           │',
    '    │ │                                                │',
    '    │ │  Prompt = Data Scan Mentah + Konteks Pinecone  │',
    '    │ │  Generate:                                     │',
    '    │ │    - Skor Keamanan (0-100)                     │',
    '    │ │    - Verdict (Aman/Perhatian/Berbahaya)        │',
    '    │ │    - Ringkasan Eksekutif                       │',
    '    │ │    - Daftar Kerentanan + Remediasi             │',
    '    │ │    - Simulasi Serangan (MITRE ATT&CK Chain)    │',
    '    │ └───────────────────────┬────────────────────────┘',
    '    │                         │',
    '    │           Simpan ke Supabase',
    '    │     (scans, temuan, simulasi_serangan)',
    '    │',
    '    └──► Tampilkan Laporan Lengkap ke Pengguna ✅',
  ]),
  gap(), gap(),

  h2('3.4 Flow Hub Edukasi & CTF'),
  gap(),
  ...diagramBox([
    '  PENGGUNA MASUK HUB EDUKASI',
    '    │',
    '    ├── Lihat Daftar Tantangan',
    '    │     (Filter: Kategori, Tingkat Kesulitan)',
    '    │',
    '    ├── Pilih Soal CTF',
    '    │',
    '    ├── Jenis Soal:',
    '    │     ├── Input Flag / Teks',
    '    │     ├── Pilihan Ganda',
    '    │     └── Analisis Kode',
    '    │',
    '    ├── Submit Jawaban',
    '    │       │',
    '    │  Backend Validasi:',
    '    │       ├── [SALAH] ──► Coba Lagi',
    '    │       └── [BENAR]',
    '    │               │',
    '    │         Hitung Poin',
    '    │         (+ Bonus Kecepatan)',
    '    │               │',
    '    │         Simpan ke `poin_user`',
    '    │               │',
    '    │         Update Leaderboard 🏆',
    '    │',
    '    └──► Lanjut ke Tantangan Berikutnya ✅',
  ]),
  gap(), gap(),

  h2('3.5 Flow Admin Panel'),
  gap(),
  ...diagramBox([
    '  ADMIN LOGIN',
    '    │',
    '    ├── [Manajemen Pengguna]',
    '    │     ├── Lihat daftar akun is_verified = false',
    '    │     ├── [SETUJUI] ──► is_verified = true + Notifikasi',
    '    │     └── [TOLAK]   ──► Hapus Akun',
    '    │',
    '    ├── [Konfigurasi AI]',
    '    │     ├── Tambah Provider Baru (Nama, API Key, Model)',
    '    │     ├── Set satu provider sebagai Default',
    '    │     └── Nonaktifkan provider (is_active = false)',
    '    │',
    '    ├── [Kelola Tantangan CTF]',
    '    │     ├── Buat Soal Baru (Judul, Poin, Jawaban, Kategori)',
    '    │     └── Aktifkan / Nonaktifkan Soal (is_aktif)',
    '    │',
    '    └── [Monitor Log Scan]',
    '          └── Lihat semua aktivitas scan + status real-time',
  ]),
  gap(), gap(),

  // =========================================================
  // BAGIAN 4: RINGKASAN TABEL FITUR
  // =========================================================
  h1('4. Ringkasan Fitur & Layanan yang Digunakan'),
  gap(),
  new Table({
    width: { size: 100, type: WidthType.PERCENTAGE },
    rows: [
      tableRow(['Fitur', 'Layanan Digunakan', 'Output Utama'], true),
      tableRow(['Scan URL (Biasa)', 'urlscan.io, Pinecone, AI LLM', 'Laporan keamanan URL + Skor']),
      tableRow(['Scan URL (Intensif)', 'Verifikasi Domain, urlscan.io, Pinecone, AI LLM', 'Analisis mendalam + Simulasi Serangan']),
      tableRow(['Scan File ZIP / Code', 'VirusTotal, Pinecone, AI LLM', 'Deteksi malware + Kerentanan kode']),
      tableRow(['Scan Log File', 'Pinecone, AI LLM', 'Analisis log + Pola anomali']),
      tableRow(['Simulasi Serangan', 'AI LLM + Data Scan', 'MITRE ATT&CK Chain, Profil Penyerang']),
      tableRow(['Register / Login', 'Google OAuth, reCAPTCHA, WhatsApp OTP', 'Sesi pengguna terautentikasi']),
      tableRow(['Hub Edukasi & CTF', 'Supabase, Backend Validator', 'Poin, Leaderboard']),
      tableRow(['Admin: Konfigurasi AI', 'Supabase (ai_configurations)', 'Provider AI aktif tanpa restart server']),
      tableRow(['Admin: Kelola User', 'Supabase (users.is_verified)', 'Akses pengguna dikontrol']),
    ],
  }),
  gap(), gap(),

  // =========================================================
  // BAGIAN 5: CATATAN TEKNIS
  // =========================================================
  h1('5. Catatan Teknis Penting'),
  bullet('Integrated Embedding', 'RedSim menggunakan fitur Integrated Embedding dari Pinecone Serverless, sehingga kolom embedding di tabel knowledge_chunks bersifat kosong (placeholder). Seluruh proses embedding dan pencarian vektor dilakukan sepenuhnya oleh infrastruktur Pinecone.'),
  bullet('Keamanan Berlapis', 'Sistem menerapkan keamanan berlapis: reCAPTCHA di register, OTP WhatsApp di login, verifikasi kepemilikan domain untuk scan intensif, dan persetujuan Admin untuk aktivasi akun.'),
  bullet('Konfigurasi AI On-the-Fly', 'Provider AI dapat diganti tanpa modifikasi kode atau restart server. Perubahan pada tabel ai_configurations di Supabase langsung berlaku.'),
  bullet('Rate Limiting Berbasis Tier', 'Setiap pengguna memiliki kuota scan harian yang dihitung melalui kolom scan_count_today dan direset setiap hari.'),
  gap(),
];

// ============================================================
// GENERATE DOKUMEN
// ============================================================

const doc = new Document({
  creator: 'RedSim System',
  title: 'Arsitektur RedSim (RAG System)',
  description: 'Dokumentasi lengkap arsitektur, komponen, dan flow diagram RedSim',
  sections: [{ children: content }],
});

Packer.toBuffer(doc).then((buffer) => {
  fs.writeFileSync('Arsitektur_RedSim.docx', buffer);
  console.log('✅ Berhasil! File "Arsitektur_RedSim.docx" telah dibuat.');
}).catch((err) => {
  console.error('❌ Terjadi kesalahan:', err);
});
