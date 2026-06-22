# Penjelasan Entity Relationship Diagram (ERD) RedSim

Dokumen ini menjelaskan kegunaan dari masing-masing entitas (tabel) beserta atribut (kolom) yang membentuk arsitektur *database* sistem RedSim berdasarkan ERD (Entity Relationship Diagram) versi terbaru.

---

## 1. Entitas: `users`
**Fungsi:** Menyimpan informasi dan identitas dari seluruh pengguna platform RedSim, baik untuk level pengguna biasa maupun Administrator.

*   **`id` (PK):** Identifier unik untuk setiap akun pengguna.
*   **`tier`:** Menunjukkan level akses pengguna (contoh: `gratis`, `premium`, `admin`). Atribut ini digunakan secara ekstensif pada pengecekan *rate limiting* dan limitasi fitur (contoh: *Middleware CekRateLimitScan*).
*   **`name`:** Nama singkat atau *username* dari pengguna.
*   **`nama_lengkap`:** Nama panjang/asli pengguna untuk keperluan sertifikat atau profil.
*   **`email`:** Alamat email utama yang digunakan untuk *login* dan sarana komunikasi.
*   **`google_id`:** ID unik token autentikasi apabila pengguna memilih pendaftaran via SSO (Single Sign-On) Google.
*   **`is_verified`:** Status penanda *approval*. Hanya pengguna yang disetujui (diverifikasi) oleh Admin yang diperbolehkan masuk/beraktivitas di dalam platform.
*   **`scan_count_today`:** Jumlah pemindaian (scan) yang telah dilakukan pengguna pada hari bersangkutan untuk memonitor kuota *rate limit*.
*   **`last_login`:** Merekam riwayat jam masuk (waktu *login*) pengguna terakhir kali.

---

## 2. Entitas: `scans`
**Fungsi:** Bertindak sebagai tabel *log* transaksi utama. Menyimpan *metadata* tingkat atas untuk setiap eksekusi pemindaian (scan) keamanan yang dilakukan.

*   **`id` (PK):** Identifier unik log pemindaian.
*   **`user_id` (FK):** Merujuk kepada pengguna (`users.id`) yang melakukan inisiasi *scanning*.
*   **`tipe_scan`:** Jenis pemindaian (contoh: `URL`, `ZIP`, `Log`, `Code`).
*   **`mode_scan`:** Mode intensitas pemindaian (contoh: `biasa`, `intens`).
*   **`target`:** String yang menunjukkan objek target yang discan (contoh: `https://example.com` atau `app.zip`).
*   **`status`:** Melacak kemajuan proses pemindaian (contoh: `pending`, `processing`, `success`, `failed`).
*   **`skor_keamanan`:** Nilai agregat (0-100) hasil kalkulasi AI yang merepresentasikan tingkat kesehatan keamanan target.
*   **`verdict`:** Kesimpulan status keamanan dari AI (contoh: `aman`, `perhatian`, `berbahaya`).
*   **`ringkasan_eksekutif`:** Narasi deskriptif dari AI LLM yang merangkum hasil analisis dalam bahasa profesional.
*   **`teknologi_terdeteksi`:** Menyimpan *array* JSON berisikan pustaka, kerangka kerja (*framework*), dan tipe peladen (server) target.

---

## 3. Entitas: `temuan`
**Fungsi:** Menyimpan detail *item-per-item* kerentanan (*vulnerability*) atau miskonfigurasi yang ditemukan dari pemindaian. Satu entri `scans` dapat menghasilkan banyak entri `temuan`.

*   **`id` (PK):** Identifier unik untuk suatu temuan keamanan.
*   **`scan_id` (FK):** Menghubungkan temuan ini dengan log `scans` yang memicunya.
*   **`tipe`:** Klasifikasi teknis kerentanan (contoh: `injeksi_sql`, `xss`, `miskonfigurasi_ssl`).
*   **`tingkat_keparahan`:** Level keparahan ancaman keamanan (contoh: `Rendah`, `Sedang`, `Tinggi`, `Kritis`).
*   **`judul`:** Nama atau deskripsi ringkas tentang kerentanan.
*   **`lokasi`:** Tempat persis di mana anomali ditemukan (misal: *path* URL spesifik, atau baris kode nomor 42).
*   **`tingkat_kepercayaan`:** Metrik probabilistik AI terhadap akurasi bahwa temuan tersebut *bukan false positive*.
*   **`cve_id`:** Kode referensi *Common Vulnerabilities and Exposures* (jika ada kemiripan/cocok dengan kelemahan global yang sudah diketahui).
*   **`cwe_id`:** Kode taksonomi *Common Weakness Enumeration*.
*   **`remediasi`:** Saran perbaikan yang dapat segera diterapkan pengguna (*actionable recommendation*).

---

## 4. Entitas: `simulasi_serangan`
**Fungsi:** Menyimpan data *attack scenario* (*threat modeling*) prediktif yang dihasilkan oleh RedSim. Tabel ini menceritakan "bagaimana seorang peretas bisa mengeksploitasi sistem Anda".

*   **`id` (PK):** Identifier unik simulasi.
*   **`scan_id` (FK):** Relasi kepada data `scans`.
*   **`nama_skenario`:** Judul skenario (contoh: "Eksploitasi XSS Lintas Situs menuju Pembajakan Akun").
*   **`profil_penyerang`:** Deskripsi mengenai jenis/motivasi *hacker* (contoh: *Script Kiddie*, *Insider Threat*).
*   **`skor_kemungkinan`:** Estmasi tingkat *probability* serangan tersebut berhasil dilancarkan.
*   **`skor_dampak`:** Estimasi tingkat kerusakan (*damage impact*) bisnis jika serangan berhasil.
*   **`rantai_serangan`:** Narasi teknis mengenai fase-fase (langkah-demi-langkah) si peretas mengambil alih sistem.
*   **`fase_attck`:** Kategori taktik yang dipetakan langsung dengan matriks MITRE ATT&CK (contoh: `Initial Access`, `Persistence`, `Exfiltration`).

---

## 5. Entitas: `domain_verifications`
**Fungsi:** Mengelola data verifikasi kepemilikan domain (sebelum fitur Scan URL Intensif yang destruktif dapat dieksekusi).

*   **`id` (PK):** Identifier unik log verifikasi.
*   **`verified_at`:** Waktu saat domain sukses terverifikasi.
*   **`domain`:** Nama domain penuh (*hostname*) yang diklaim (contoh: `redsim.id`).
*   **`status`:** Tahapan status (contoh: `pending`, `verified`, `failed`).
*   **`token`:** String hash acak (kode tantangan) yang harus dipasang oleh user pada *DNS TXT record* atau `meta tag` HTML di situs web tersebut.
*   **`expires_at`:** Batas waktu kedaluwarsa verifikasi atau batas waktu bagi user untuk memasang `token`.
*   **`user_id` (FK):** Pengguna mana yang melakukan klaim domain.

---

## 6. Entitas: `tantangan`
**Fungsi:** Berfungsi sebagai repositori atau basis data soal-soal Capture The Flag (CTF) dan modul pembelajaran pada Hub Edukasi.

*   **`id` (PK):** Identifier unik soal.
*   **`judul`:** Judul utama dari modul tantangan.
*   **`kategori`:** Klasifikasi materi (contoh: `Web Exploitation`, `Cryptography`, `Reverse Engineering`).
*   **`tipe`:** Jenis input/pertanyaan (contoh: isian bendera/flag, pilihan ganda, menulis skrip kode).
*   **`is_aktif`:** Penanda bagi Admin untuk mematikan/menyembunyikan tantangan (contoh: *maintenance*).
*   **`tingkat_kesulitan`:** Level kemampuan (contoh: `Pemula`, `Menengah`, `Ahli`).
*   **`poin`:** Nominal imbalan koin/poin (*reward*) jika berhasil diselesaikan.
*   **`jawaban_benar`:** Kata kunci referensi sistem untuk memvalidasi *input* user (bisa berupa teks *flag* CTF).
*   **`bahasa_pemrograman`:** Parameter (opsional) untuk soal-soal analitik sintaks bahasa (*Python*, *PHP*, *Javascript*, dll).

---

## 7. Entitas: `poin_user`
**Fungsi:** Tabel transaksional (jembatan *Many-to-Many*) untuk mencatat *submission*/upaya pengguna dalam menjawab dan mengerjakan tantangan. Berperan utama di fitur *Papan Peringkat (Leaderboard)*.

*   **`id` (PK):** Identifier log partisipasi unik.
*   **`is_benar`:** *Boolean* untuk status validasi, `true` jika solusi pengguna dinilai valid oleh sistem.
*   **`poin_diperoleh`:** Imbalan riil akhir yang diberikan ke pengguna (poin bisa berkurang dari aslinya, misal jika *submission* dilakukan terlambat).
*   **`jawaban_user`:** Arsip dari apa yang diketikkan/diinputkan pengguna saat menjawab soal.
*   **`tantangan_id` (FK):** Menghubungkan partisipasi dengan spesifik `tantangan`.
*   **`user_id` (FK):** Menghubungkan partisipasi dengan identitas peserta (`users`).
*   **`selesai_at`:** Cap waktu (timestamp) untuk menilai kecepatan pengerjaan (*time-based resolution*).

---

## 8. Entitas: `knowledge_chunks`
**Fungsi:** Tabel pangkalan pengetahuan (Knowledge Base RAG) untuk RedSim. Menyimpan ribuan keping literatur keamanan siber (CISA, OWASP, CWE).

*   **`id` (PK):** Identifier unik dokumen teks kecil.
*   **`content`:** Teks referensi utuh atau korpus literatur.
*   **`source`:** Organisasi/standar asal data didapatkan (contoh: `mitre-attck`, `cisa-kev`).
*   **`source_id`:** Kode pengidentifikasi asli dari standar (contoh: ID Kerentanan `CVE-2024-55512` atau ID Taktik `T1059`).
*   **`title`:** Judul deskriptif mengenai referensi teks tersebut.
*   **`chunk_index`:** Mengingat satu panduan utuh panjang (besar) dipecah menjadi beberapa bagian, *index* ini menentukan urutan potongan tersebut.
*   **`embedding`:** Merupakan kolom representasi matematis vektor (*vector space*). Dalam arsitektur saat ini sering tidak aktif digunakan atau sekedar format *array kosong* (`[]`) karena RedSim sepenuhnya memanfaatkan **Integrated Embedding Serverless Pinecone Vector Database**.

---

## 9. Entitas: `ai_configurations`
**Fungsi:** Menyimpan konfigurasi API profil berbagai *vendor Large Language Model* (LLM) secara aman tanpa perlu mengubah variabel statis di server (`.env`), guna mendukung peralihan model secara *on-the-fly*.

*   **`id` (PK):** Identifier unik *preset* konfigurasi.
*   **`provider`:** Organisasi penyedia LLM (contoh: `Groq`, `OpenAI`).
*   **`label`:** Nama ramah-pengguna yang muncul di pilihan *dropdown* (contoh: `Groq (Llama-3-70B)`).
*   **`selected_model`:** Alias model teknis spesifik untuk *endpoint* penyedia (contoh: `qwen-32b-chat`).
*   **`is_active`:** Status keterlihatan model pada antarmuka admin/pengguna. Jika `false`, opsi ini akan disembunyikan.
*   **`is_default`:** Hanya bernilai `true` pada maksimal *satu* profil. Akan berfungsi sebagai peladen AI asali (utama) bila antarmuka gagal mengatur model khusus.
*   **`last_verified_at`:** Merekam waktu kapan terakhir sistem memastikan bahwa API key model ini tidak *expired* atau *limit quota* habis.
