# Penjelasan Tampilan Antarmuka (UI) RedSim

Dokumen ini berisi penjelasan dari masing-masing screenshot tampilan antarmuka sistem **RedSim (Platform Penilaian Keamanan Sistem Berbasis AI)**, mulai dari halaman awal pengunjung hingga dashboard pengguna dan administrator.

Semua gambar hasil screenshot tersedia di dalam folder `/screenshot`.

## 1. Halaman Beranda (01_Beranda.png)
**Fungsi:** Merupakan landing page atau halaman utama publik.
**Penjelasan:** Menampilkan pengenalan tentang platform RedSim, fitur-fitur unggulan (seperti analisis kode berbasis AI, analisis URL/malware), keuntungan menggunakan RedSim, dan tombol _Call-to-Action_ (Masuk/Daftar) bagi pengunjung untuk segera menggunakan layanan.

## 2. Halaman Masuk / Login (02_Masuk.png)
**Fungsi:** Halaman otentikasi untuk pengguna yang sudah memiliki akun.
**Penjelasan:** Pengguna diwajibkan memasukkan Email dan Password. Halaman ini juga dilengkapi opsi login menggunakan Google (OAuth), opsi login via OTP WhatsApp, dan dilindungi oleh Google reCAPTCHA v3 untuk mencegah serangan _brute force_ atau bot.

## 3. Halaman Daftar / Register (03_Daftar.png)
**Fungsi:** Halaman pendaftaran bagi pengguna baru.
**Penjelasan:** Calon pengguna diminta untuk melengkapi formulir informasi dasar seperti Nama, Alamat Email, Nomor WhatsApp aktif (untuk OTP/notifikasi), dan Password. Proses ini juga terintegrasi dengan validasi reCAPTCHA.

## 4. Dashboard Pengguna (04_Dashboard.png)
**Fungsi:** Halaman kontrol utama untuk setiap pengguna setelah berhasil masuk.
**Penjelasan:** Menampilkan statistik personal seperti kuota pemindaian harian, total analisis yang dilakukan, grafik ringkasan aktivitas, serta akses cepat (shortcut) menuju menu-menu analisis yang tersedia.

## 5. Analisis Kode (05_Analisis_Kode.png)
**Fungsi:** Fitur pemindaian keamanan kode sumber.
**Penjelasan:** Pengguna dapat menyalin dan menempel (paste) potongan kode (seperti PHP, Javascript, Python, dsb) langsung ke dalam editor yang tersedia di halaman ini. Sistem AI akan mengevaluasi kode tersebut untuk menemukan kerentanan seperti _SQL Injection_, _XSS_, atau pola kode yang tidak aman.

## 6. Analisis URL (06_Analisis_URL.png)
**Fungsi:** Fitur pemeriksaan reputasi dan keamanan tautan situs web.
**Penjelasan:** Pengguna dapat memasukkan URL sebuah website. Sistem akan memeriksa URL tersebut terhadap database intelijen ancaman (seperti VirusTotal / URLScan) dan menganalisis potensi _phishing_, instalasi _malware_, atau reputasi buruk lainnya.

## 7. Analisis ZIP / Proyek (07_Analisis_ZIP.png)
**Fungsi:** Fitur pemindaian arsip atau repositori proyek secara utuh.
**Penjelasan:** Berbeda dengan analisis kode per bagian, fitur ini memungkinkan pengguna mengunggah seluruh direktori proyek dalam format `.zip`. Sistem akan mengekstrak dan memindai semua file di dalamnya sekaligus.

## 8. Halaman Laporan (08_Laporan.png)
**Fungsi:** Halaman riwayat dan hasil pemindaian.
**Penjelasan:** Menampilkan daftar historis dari seluruh aktivitas analisis (baik kode, URL, maupun ZIP) yang pernah dilakukan pengguna. Pengguna dapat melihat detail temuan, persentase tingkat keparahan (severity), serta opsi untuk mengunduh laporan PDF.

## 9. Modul Edukasi (09_Edukasi.png)
**Fungsi:** Pusat literasi keamanan siber pengguna.
**Penjelasan:** Menyediakan artikel-artikel tentang keamanan siber, informasi ensiklopedia jenis-jenis serangan, dan fitur "Tantangan" atau mini-kuis (CBT) yang dirancang agar pengguna bisa mempraktikkan pengetahuan keamanan mereka.

## 10. Dashboard Administrator (10_Admin_Dashboard.png)
**Fungsi:** Halaman kontrol sistem secara menyeluruh bagi Super Admin.
**Penjelasan:** Halaman ini sangat penting untuk operasional, mencakup manajemen persetujuan akun pengguna (_user approval_), pengaturan Model AI (API Key config), pemantauan kesehatan sistem/database, manajemen soal-soal edukasi, dan audit log keseluruhan platform RedSim.
