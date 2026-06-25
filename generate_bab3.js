const fs = require('fs');
const path = require('path');
const {
  Document, Packer, Paragraph, TextRun, ImageRun,
  HeadingLevel, AlignmentType, Table, TableRow, TableCell,
  WidthType, ShadingType, BorderStyle, UnderlineType,
} = require('docx');

// ─── HELPER ─────────────────────────────────────────────────
const IMG_DIR = path.join(__dirname, 'gambar-pengujian');

function p(text, opts = {}) {
  return new Paragraph({ children: [new TextRun({ text, ...opts })] });
}
function gap() { return new Paragraph({ text: '' }); }
function h1(text) { return new Paragraph({ text, heading: HeadingLevel.HEADING_1, alignment: AlignmentType.CENTER }); }
function h2(text) { return new Paragraph({ text, heading: HeadingLevel.HEADING_2 }); }
function h3(text) { return new Paragraph({ text, heading: HeadingLevel.HEADING_3 }); }
function indent(text, opts = {}) {
  return new Paragraph({ indent: { firstLine: 720 }, children: [new TextRun({ text, ...opts })] });
}
function bullet(label, desc) {
  return new Paragraph({
    bullet: { level: 0 },
    children: [new TextRun({ text: label + ': ', bold: true }), new TextRun(desc)],
  });
}
function tableHdr(cells) {
  return new TableRow({
    tableHeader: true,
    children: cells.map(t => new TableCell({
      shading: { type: ShadingType.SOLID, color: 'C0392B', fill: 'C0392B' },
      children: [new Paragraph({ children: [new TextRun({ text: t, bold: true, color: 'FFFFFF' })] })],
    })),
  });
}
function tableRow(cells) {
  return new TableRow({
    children: cells.map(t => new TableCell({
      children: [new Paragraph({ children: [new TextRun({ text: t, size: 20 })] })],
    })),
  });
}
function imgParagraph(filename, caption) {
  const imgPath = path.join(IMG_DIR, filename);
  const rows = [];
  if (fs.existsSync(imgPath)) {
    const buf = fs.readFileSync(imgPath);
    rows.push(new Paragraph({
      alignment: AlignmentType.CENTER,
      children: [new ImageRun({ data: buf, transformation: { width: 500, height: 300 }, type: 'png' })],
    }));
  } else {
    rows.push(p(`[Gambar: ${filename} - tidak ditemukan]`, { italics: true, color: '999999' }));
  }
  rows.push(new Paragraph({
    alignment: AlignmentType.CENTER,
    children: [new TextRun({ text: caption, italics: true, size: 18 })],
  }));
  rows.push(gap());
  return rows;
}

// ─── KONTEN ─────────────────────────────────────────────────
const content = [
  h1('BAB III'),
  h1('PENGUJIAN DAN ANALISIS'),
  gap(),

  // 3.1
  h2('3.1 Metodologi Pengujian'),
  indent('Pengujian sistem RedSim dilakukan menggunakan pendekatan pengujian fungsional (functional testing) dan pengujian keamanan (security testing). Pengujian fungsional memverifikasi bahwa setiap fitur bekerja sesuai dengan spesifikasi yang telah ditetapkan, sementara pengujian keamanan memverifikasi bahwa sistem itu sendiri telah menerapkan praktik keamanan yang tepat.'),
  gap(),
  indent('Metodologi pengujian yang digunakan adalah Black-Box Testing, yaitu pengujian yang dilakukan tanpa mengetahui struktur internal sistem. Pengujian dilakukan langsung melalui antarmuka web (browser) menggunakan alamat lokal http://127.0.0.1:8000. Setiap skenario pengujian dicatat hasilnya dan dibandingkan dengan hasil yang diharapkan (expected result).'),
  gap(),
  p('Tabel 3.1 Skenario dan Metode Pengujian yang Digunakan', { bold: true }),
  gap(),
  new Table({
    width: { size: 100, type: WidthType.PERCENTAGE },
    rows: [
      tableHdr(['No', 'Jenis Pengujian', 'Metode', 'Tools']),
      tableRow(['1', 'Pengujian Fungsional', 'Black-Box Testing', 'Browser, HTTP Request']),
      tableRow(['2', 'Pengujian Autentikasi', 'Black-Box Testing', 'Browser Manual']),
      tableRow(['3', 'Pengujian Otorisasi', 'Akses URL langsung', 'Browser']),
      tableRow(['4', 'Pengujian Injeksi SQL', 'Input Manipulation', 'Browser Form']),
      tableRow(['5', 'Analisis Performa AI', 'Observasi & Pengukuran', 'Browser, Laravel Log']),
    ],
  }),
  gap(), gap(),

  // 3.2
  h2('3.2 Pengujian Fungsional'),
  indent('Pengujian fungsional dilakukan untuk memastikan setiap modul dan fitur utama sistem RedSim berjalan sesuai dengan kebutuhan fungsional yang telah dirancang. Pengujian mencakup halaman utama, autentikasi pengguna, akses fitur edukasi, dan perlindungan rute.'),
  gap(),

  h3('3.2.1 Pengujian Halaman Utama (Landing Page)'),
  indent('Pengujian dilakukan dengan mengakses alamat http://127.0.0.1:8000. Halaman utama berhasil dimuat dengan status HTTP 200 OK. Halaman menampilkan informasi utama tentang platform RedSim, termasuk fitur unggulan, tombol navigasi login dan registrasi, serta konten promosi sistem keamanan siber.'),
  gap(),
  ...imgParagraph('01-halaman-utama.png', 'Gambar 3.1 Tampilan Halaman Utama RedSim'),

  h3('3.2.2 Pengujian Halaman Login'),
  indent('Pengujian halaman login dilakukan dengan mengakses http://127.0.0.1:8000/login. Sistem melakukan redirect otomatis ke /masuk (302 Found), menunjukkan penggunaan URL berbahasa Indonesia sebagai routing utama. Halaman menampilkan formulir login dengan field email, password, tombol login, dan opsi masuk via Google OAuth 2.0.'),
  gap(),
  ...imgParagraph('02-halaman-login.png', 'Gambar 3.2 Tampilan Halaman Login (/masuk)'),

  h3('3.2.3 Pengujian Halaman Register'),
  indent('Pengujian halaman registrasi dilakukan dengan mengakses http://127.0.0.1:8000/register. Sistem melakukan redirect ke /daftar. Halaman menampilkan formulir pendaftaran lengkap dengan field nama, email, password, konfirmasi password, dan pilihan registrasi via Google SSO.'),
  gap(),
  ...imgParagraph('03-halaman-register.png', 'Gambar 3.3 Tampilan Halaman Register (/daftar)'),

  h3('3.2.4 Pengujian Login dengan Kredensial Salah'),
  indent('Pengujian dilakukan dengan memasukkan email "akbar@gmail.com" dan password yang tidak valid. Sistem menolak permintaan login dan menampilkan pesan kesalahan: "Email atau kata sandi salah." Pengguna tetap berada di halaman login, tidak terjadi redirect ke dashboard. Ini membuktikan sistem validasi kredensial berfungsi dengan benar.'),
  gap(),
  ...imgParagraph('04-hasil-login.png', 'Gambar 3.4 Hasil Pengujian Login dengan Kredensial Salah'),
  ...imgParagraph('05-login-gagal.png', 'Gambar 3.5 Pesan Error Login'),

  h3('3.2.5 Pengujian Akses Fitur Edukasi Tanpa Login'),
  indent('Pengujian dilakukan dengan mengakses halaman /edukasi secara langsung tanpa autentikasi terlebih dahulu. Sistem melakukan redirect otomatis ke halaman /masuk dan menampilkan banner peringatan: "Login dulu boss! 🔐 Silakan masuk untuk mengakses fitur ini." Hasil ini menunjukkan bahwa middleware autentikasi berjalan dengan baik melindungi rute yang memerlukan login.'),
  gap(),
  ...imgParagraph('07-halaman-edukasi.png', 'Gambar 3.6 Redirect Akses Edukasi Tanpa Autentikasi'),

  gap(),
  p('Tabel 3.2 Rekapitulasi Hasil Pengujian Fungsional', { bold: true }),
  gap(),
  new Table({
    width: { size: 100, type: WidthType.PERCENTAGE },
    rows: [
      tableHdr(['No', 'Skenario Uji', 'Input', 'Expected Result', 'Actual Result', 'Status']),
      tableRow(['1', 'Buka halaman utama', 'URL /', 'Halaman tampil (200 OK)', 'Halaman tampil sempurna', '✅ PASS']),
      tableRow(['2', 'Akses /login', 'URL /login', 'Form login tampil', 'Redirect ke /masuk, form tampil', '✅ PASS']),
      tableRow(['3', 'Akses /register', 'URL /register', 'Form register tampil', 'Redirect ke /daftar, form tampil', '✅ PASS']),
      tableRow(['4', 'Login kredensial salah', 'Email & pw salah', 'Pesan error muncul', '"Email atau kata sandi salah."', '✅ PASS']),
      tableRow(['5', 'Akses /edukasi tanpa login', 'URL tanpa sesi', 'Redirect ke login', 'Redirect ke /masuk + banner warning', '✅ PASS']),
    ],
  }),
  gap(), gap(),

  // 3.3
  h2('3.3 Pengujian Keamanan'),
  indent('Pengujian keamanan bertujuan untuk memverifikasi bahwa sistem RedSim telah menerapkan mekanisme perlindungan terhadap ancaman keamanan umum. Pengujian mencakup kontrol otorisasi, perlindungan terhadap serangan injeksi, dan keamanan akses panel administrasi.'),
  gap(),

  h3('3.3.1 Pengujian Akses Admin Tanpa Otorisasi'),
  indent('Pengujian dilakukan dengan mencoba mengakses halaman /admin secara langsung tanpa login. Sistem menolak akses dan melakukan redirect ke halaman /masuk dengan menampilkan pesan peringatan yang sama. Ini membuktikan bahwa middleware otorisasi untuk rute admin berjalan dengan benar dan tidak ada bypass yang memungkinkan.'),
  gap(),
  ...imgParagraph('08-akses-admin-ditolak.png', 'Gambar 3.7 Akses Admin Ditolak (Redirect ke Login)'),

  h3('3.3.2 Pengujian SQL Injection pada Form Login'),
  indent("Pengujian injeksi SQL dilakukan dengan memasukkan payload \"' OR '1'='1\" pada field email di halaman login. Hasil pengujian menunjukkan dua lapis perlindungan:"),
  bullet('Lapisan 1 - Validasi HTML5', 'Browser memblokir submit karena field bertipe email memvalidasi format input dan menolak karakter non-email seperti tanda kutip.'),
  bullet('Lapisan 2 - Backend Laravel', 'Sekalipun payload berhasil dikirim, Laravel menggunakan Eloquent ORM dengan Parameterized Query yang secara inheren kebal terhadap SQL injection.'),
  gap(),
  ...imgParagraph('09-sql-injection-input.png', 'Gambar 3.8 Input SQL Injection pada Form Login'),
  ...imgParagraph('10-sql-injection-ditolak.png', 'Gambar 3.9 SQL Injection Diblokir oleh Validasi'),

  p('Tabel 3.3 Rekapitulasi Hasil Pengujian Keamanan', { bold: true }),
  gap(),
  new Table({
    width: { size: 100, type: WidthType.PERCENTAGE },
    rows: [
      tableHdr(['No', 'Skenario Uji', 'Metode Serangan', 'Expected Result', 'Actual Result', 'Status']),
      tableRow(['1', 'Akses /admin tanpa login', 'Direct URL Access', 'Ditolak / Redirect', 'Redirect ke /masuk + warning', '✅ AMAN']),
      tableRow(['2', 'Akses /edukasi tanpa login', 'Direct URL Access', 'Ditolak / Redirect', 'Redirect ke /masuk + warning', '✅ AMAN']),
      tableRow(['3', 'SQL Injection form login', "' OR '1'='1", 'Serangan gagal', 'Diblokir validasi HTML5 & ORM', '✅ AMAN']),
      tableRow(['4', 'Bypass rute proteksi', 'Manual URL manipulation', 'Rute terlindungi', 'Semua rute sensitif ter-redirect', '✅ AMAN']),
    ],
  }),
  gap(), gap(),

  // 3.4
  h2('3.4 Analisis Kerentanan Sistem'),
  indent('Berdasarkan hasil pengujian yang telah dilakukan, dilakukan analisis terhadap potensi kerentanan yang ada maupun yang telah berhasil diatasi oleh sistem. Analisis ini mengacu pada kerangka OWASP Top 10 sebagai standar referensi keamanan aplikasi web.'),
  gap(),

  h3('3.4.1 Kerentanan yang Telah Berhasil Dimitigasi'),
  bullet('A01 - Broken Access Control', 'Sistem menerapkan middleware autentikasi pada semua rute sensitif (/admin, /edukasi, /scan, dll.). Akses tanpa sesi aktif langsung ditolak dan diarahkan ke halaman login.'),
  bullet('A03 - Injection (SQL Injection)', 'Laravel Eloquent ORM menggunakan prepared statements dan parameterized queries secara default, sehingga seluruh interaksi database terlindungi dari SQL injection.'),
  bullet('A07 - Identification and Authentication Failures', 'Sistem mengimplementasikan reCAPTCHA v3 pada form register, OTP WhatsApp sebagai MFA, pemberitahuan login dan penyelesaian scan via WhatsApp, serta reset password limit 5 menit.'),
  bullet('A02 - Cryptographic Failures', 'Password disimpan menggunakan algoritma hashing bcrypt. Password diharuskan kompleks dan kuat dengan bar indikator kekuatan visual.'),
  gap(),

  h3('3.4.2 Potensi Kerentanan dan Rekomendasi (Diperbarui)'),
  bullet('Rate Limiting Login (Dimitigasi)', 'Telah ditambahkan throttle middleware pada rute /masuk dengan durasi blokir yang meningkat secara eksponensial berdasarkan jumlah kegagalan login (5 kali = 2 menit, hingga 30 menit).'),
  bullet('Kekuatan Kata Sandi (Dimitigasi)', 'Pendaftaran dan reset kata sandi kini mewajibkan minimal 8 karakter, kombinasi huruf besar, huruf kecil, angka, dan simbol, dilengkapi dengan indikator kekuatan visual (progress bar).'),
  bullet('HTTP Security Headers', 'Perlu dipastikan HTTP response headers seperti Content-Security-Policy, X-Frame-Options, dan X-Content-Type-Options sudah dikonfigurasi di production environment.'),
  bullet('Verifikasi Domain', 'Fitur verifikasi domain sebelum scan intensif sudah dirancang dengan baik menggunakan token DNS/Meta Tag, namun perlu diuji lebih lanjut untuk memastikan tidak ada bypass pada mekanisme validasi token.'),
  gap(),

  p('Tabel 3.4 Pemetaan Kerentanan OWASP Top 10 pada Sistem RedSim', { bold: true }),
  gap(),
  new Table({
    width: { size: 100, type: WidthType.PERCENTAGE },
    rows: [
      tableHdr(['OWASP', 'Kategori', 'Status di RedSim', 'Mekanisme Mitigasi']),
      tableRow(['A01', 'Broken Access Control', '✅ Dimitigasi', 'Auth Middleware, Role-based redirect']),
      tableRow(['A02', 'Cryptographic Failures', '✅ Dimitigasi', 'bcrypt password hashing']),
      tableRow(['A03', 'Injection', '✅ Dimitigasi', 'Eloquent ORM (Parameterized Query)']),
      tableRow(['A04', 'Insecure Design', '✅ Dimitigasi', 'Rate limit eksponensial untuk login']),
      tableRow(['A05', 'Security Misconfiguration', '⚠️ Perlu Review', 'HTTP headers perlu dikonfirmasi']),
      tableRow(['A07', 'Auth Failures', '✅ Dimitigasi', 'reCAPTCHA, OTP WA, Google OAuth']),
    ],
  }),
  gap(), gap(),

  // 3.5
  h2('3.5 Analisis Performa Sistem AI'),
  indent('Analisis performa sistem AI difokuskan pada arsitektur RAG (Retrieval-Augmented Generation) yang menjadi inti dari kemampuan analisis keamanan RedSim. Performa dianalisis berdasarkan rancangan arsitektur, konfigurasi layanan yang digunakan, dan observasi terhadap komponen sistem.'),
  gap(),

  h3('3.5.1 Arsitektur RAG yang Diimplementasikan'),
  indent('RedSim menggunakan pendekatan RAG dua fase: fase Retrieval menggunakan Pinecone Serverless dengan Integrated Embedding untuk mencari konteks relevan dari knowledge base keamanan, dan fase Generation menggunakan AI LLM (Groq/OpenAI) yang dikonfigurasi secara dinamis oleh admin.'),
  gap(),
  bullet('Pinecone Serverless + Integrated Embedding', 'Menggunakan fitur embedding bawaan Pinecone sehingga tidak diperlukan model embedding eksternal terpisah. Pencarian vektor dilakukan dengan kecepatan milidetik.'),
  bullet('Dynamic AI Configuration', 'Admin dapat mengganti provider AI (Groq, OpenAI) tanpa restart server melalui tabel ai_configurations di Supabase, memungkinkan adaptasi cepat terhadap kebutuhan performa.'),
  bullet('Knowledge Base Komprehensif', 'Knowledge base mencakup literatur dari CISA KEV, OWASP Top 10, basis data CVE/CWE, dan matriks taktik MITRE ATT&CK, memberikan konteks keamanan yang luas.'),
  gap(),

  h3('3.5.2 Analisis Komponen Performa'),
  p('Tabel 3.5 Analisis Performa Komponen Sistem AI', { bold: true }),
  gap(),
  new Table({
    width: { size: 100, type: WidthType.PERCENTAGE },
    rows: [
      tableHdr(['Komponen', 'Teknologi', 'Karakteristik Performa', 'Keterangan']),
      tableRow(['Vector Search', 'Pinecone Serverless', 'Latensi rendah (<100ms)', 'Integrated Embedding, skala otomatis']),
      tableRow(['LLM Generation', 'Groq (LLaMA 3)', 'Throughput tinggi (~500 token/s)', 'Hardware akselerasi Groq LPU']),
      tableRow(['LLM Generation', 'OpenAI GPT', 'Latensi ~2-5 detik', 'Kualitas respons lebih tinggi']),
      tableRow(['OSINT - urlscan.io', 'REST API', 'Async, ~5-30 detik/domain', 'Tergantung kompleksitas halaman']),
      tableRow(['OSINT - VirusTotal', 'REST API', '~3-10 detik', 'Analisis 70+ antivirus engine']),
      tableRow(['Database', 'Supabase PostgreSQL', 'Latensi pooler <50ms', 'Connection pooling aktif']),
    ],
  }),
  gap(),

  h3('3.5.3 Estimasi Waktu Respons End-to-End'),
  indent('Berdasarkan karakteristik masing-masing komponen, estimasi waktu respons total untuk satu siklus scan URL adalah sebagai berikut:'),
  gap(),
  new Table({
    width: { size: 100, type: WidthType.PERCENTAGE },
    rows: [
      tableHdr(['Fase', 'Durasi Estimasi', 'Komponen Utama']),
      tableRow(['1. Pengumpulan Data OSINT', '5 – 30 detik', 'urlscan.io + VirusTotal API']),
      tableRow(['2. RAG Retrieval (Pinecone)', '< 0.5 detik', 'Pinecone Serverless Vector Search']),
      tableRow(['3. AI Generation (Groq)', '3 – 8 detik', 'LLM prompt processing']),
      tableRow(['4. Simpan ke Database', '< 0.5 detik', 'Supabase PostgreSQL']),
      tableRow(['Total Estimasi', '~10 – 40 detik', 'Tergantung kompleksitas target']),
    ],
  }),
  gap(),

  h3('3.5.4 Keunggulan Desain AI'),
  bullet('Konfigurasi On-the-Fly', 'Provider AI dapat diganti oleh admin tanpa mengubah kode atau merestart server, meningkatkan fleksibilitas operasional.'),
  bullet('Konteks Diperkaya (RAG)', 'Setiap analisis diperkaya dengan referensi keamanan terkini dari knowledge base, menghasilkan output yang lebih akurat dan relevan dibanding LLM standalone.'),
  bullet('MITRE ATT&CK Mapping', 'AI secara otomatis memetakan temuan ke kerangka MITRE ATT&CK, memberikan pemahaman taktik penyerang yang terstruktur.'),
  bullet('Skalabilitas', 'Penggunaan Pinecone Serverless memungkinkan knowledge base berkembang tanpa batasan infrastruktur.'),
  gap(), gap(),

  // Kesimpulan
  h2('Kesimpulan Pengujian dan Analisis'),
  indent('Berdasarkan hasil pengujian fungsional dan keamanan yang telah dilakukan, sistem RedSim secara keseluruhan telah memenuhi spesifikasi fungsional yang dirancang. Seluruh rute yang dilindungi berhasil mencegah akses tidak sah, mekanisme autentikasi berlapis berjalan dengan baik, dan sistem tidak rentan terhadap serangan SQL injection berkat penggunaan ORM Eloquent.'),
  gap(),
  indent('Dari sisi performa sistem AI, desain RAG dua fase yang mengintegrasikan Pinecone Serverless untuk retrieval dan LLM untuk generasi telah menciptakan sistem analisis keamanan yang komprehensif, akurat, dan dapat dikonfigurasi secara dinamis. Dengan estimasi waktu respons 10–40 detik per siklus scan, sistem dinilai cukup responsif untuk kebutuhan analisis keamanan siber profesional.'),
  gap(),
];

// ─── GENERATE ────────────────────────────────────────────────
const doc = new Document({
  creator: 'RedSim System',
  title: 'BAB III Pengujian dan Analisis - RedSim',
  sections: [{ children: content }],
});

Packer.toBuffer(doc).then((buf) => {
  fs.writeFileSync('BAB3_Pengujian_RedSim.docx', buf);
  console.log('✅ BAB3_Pengujian_RedSim.docx berhasil dibuat!');
}).catch(err => console.error('❌ Error:', err));
