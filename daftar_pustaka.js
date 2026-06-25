const fs = require('fs');
const {
  Document, Packer, Paragraph, TextRun,
  HeadingLevel, AlignmentType, TabStopPosition, TabStopType,
} = require('docx');

// ─── HELPER ─────────────────────────────────────────────────

function h1(text) {
  return new Paragraph({
    text,
    heading: HeadingLevel.HEADING_1,
    alignment: AlignmentType.CENTER,
    spacing: { after: 200 },
  });
}

function gap() {
  return new Paragraph({ text: '', spacing: { after: 100 } });
}

/**
 * Buat satu item daftar pustaka dengan format hanging indent.
 * Format: [nomor] Isi referensi
 */
function ref(nomor, isi) {
  return new Paragraph({
    indent: { left: 720, hanging: 720 }, // Hanging indent 0.5 inch
    spacing: { after: 160 },
    children: [
      new TextRun({ text: `[${nomor}]\t`, bold: true, size: 24, font: 'Times New Roman' }),
      ...parseRef(isi),
    ],
  });
}

/**
 * Parse referensi string menjadi TextRun array.
 * Teks di antara ** akan di-bold, teks di antara * akan di-italic.
 */
function parseRef(text) {
  const runs = [];
  // Regex untuk menangkap **bold** dan *italic*
  const regex = /\*\*(.+?)\*\*|\*(.+?)\*/g;
  let lastIndex = 0;
  let match;

  while ((match = regex.exec(text)) !== null) {
    // Tambahkan teks sebelum match
    if (match.index > lastIndex) {
      runs.push(new TextRun({
        text: text.substring(lastIndex, match.index),
        size: 24,
        font: 'Times New Roman',
      }));
    }

    if (match[1]) {
      // Bold **text**
      runs.push(new TextRun({
        text: match[1],
        bold: true,
        size: 24,
        font: 'Times New Roman',
      }));
    } else if (match[2]) {
      // Italic *text*
      runs.push(new TextRun({
        text: match[2],
        italics: true,
        size: 24,
        font: 'Times New Roman',
      }));
    }

    lastIndex = regex.lastIndex;
  }

  // Tambahkan sisa teks setelah match terakhir
  if (lastIndex < text.length) {
    runs.push(new TextRun({
      text: text.substring(lastIndex),
      size: 24,
      font: 'Times New Roman',
    }));
  }

  return runs;
}

function subHeading(text) {
  return new Paragraph({
    spacing: { before: 300, after: 150 },
    children: [
      new TextRun({ text, bold: true, size: 24, font: 'Times New Roman' }),
    ],
  });
}

// ─── DAFTAR PUSTAKA ─────────────────────────────────────────

const content = [
  h1('DAFTAR PUSTAKA'),
  gap(),

  // ──────────────────────────────────────────────────────────
  subHeading('A. Jurnal dan Artikel Ilmiah'),
  // ──────────────────────────────────────────────────────────

  ref(1,
    'Pratama, A. (2020). "Analisis Perbandingan Metode Pencegahan SQL Injection pada Framework CodeIgniter dan Laravel." *Jurnal Teknik Informatika*, Universitas Telkom. Tersedia di: https://openlibrary.telkomuniversity.ac.id.'
  ),

  ref(2,
    'Sodikin, R. A., & Hikmawan, R. (2023). "Analysis of Gamification in Cybersecurity Education for Students: A Systematic Literature Review." *Educative: Journal of Educational Studies*, 8(2), pp. 168–183. UIN Bukittinggi.'
  ),

  ref(3,
    'Naqi, H., et al. (2024). "Kajian Literatur: Gamifikasi Edukasi Keamanan Siber dengan Konsep Capture the Flag." *Prosiding Seminar Nasional SNESTIK*, STMIK Indonesia Mandiri, Bandung.'
  ),

  ref(4,
    'Nasution, A. B., et al. (2024). "Implementation of OTP Code as Application Login Verification Via Whatsapp." *International Journal of Health, Economics, and Social Sciences (IJHESS)*, 6(1). Formosa Publisher.'
  ),

  ref(5,
    'Manik, A. R., et al. (2025). "Academic Portal with MFA (WhatsApp OTP via Fonnte), Role-Based Access Control, and Logging System." *Jurnal Sistem Informasi dan Informatika*, Politeknik LP3I Jakarta.'
  ),

  ref(6,
    'Wijaya, R. F., & Cahyadi, D. (2024). "Analisis dan Implementasi Fitur Keamanan Aplikasi pada Framework Laravel." *Jurnal Informatika*, Universitas Muhammadiyah Tangerang.'
  ),

  ref(7,
    'Susanto, A., & Putra, R. (2024). "Implementasi Keamanan Website Dari Serangan Cross Site Request Forgery Menggunakan Algoritma HMAC-SHA256 Pada Framework Laravel." *Hello World: Jurnal Ilmu Komputer*, 3(1). Ilmu Bersama.'
  ),

  ref(8,
    'Hidayat, M. R., et al. (2024). "Pengembangan Sistem Identifikasi Ancaman Keamanan pada Aplikasi Web dengan Middleware Kustom Berbasis Framework Laravel." *COREAI: Journal of Artificial Intelligence*, Universitas Nurul Jadid.'
  ),

  ref(9,
    'Tan, T., et al. (2024). "Kesadaran Keamanan Siber pada Kalangan Mahasiswa Universitas di Kota Batam." *JATI: Jurnal Teknologi dan Informasi*, 14(1).'
  ),

  ref(10,
    'Fikri, A., & Raharjo, S. (2024). "Analisis Kerentanan Aplikasi Web Menggunakan OWASP ZAP dan Penetration Testing: Studi Kasus Portal Akademik." *Jurnal Cyber Security dan Forensik Digital (CSFD)*, 3(2).'
  ),

  ref(11,
    'Putra, I. G. N. A., et al. (2023). "Platform Edukasi Keamanan Siber Berbasis Web Berdasarkan Kerangka Global Literasi Digital UNESCO." *INTEKNA: Jurnal Informasi Teknik dan Niaga*, Politeknik Negeri Banjarmasin.'
  ),

  ref(12,
    'Lewis, P., et al. (2020). "Retrieval-Augmented Generation for Knowledge-Intensive NLP Tasks." *Advances in Neural Information Processing Systems (NeurIPS)*, 33, pp. 9459–9474.'
  ),

  ref(13,
    'Badan Siber dan Sandi Negara (BSSN). (2024). *Lanskap Keamanan Siber Indonesia 2024*. Jakarta: BSSN. Tersedia di: https://www.bssn.go.id.'
  ),

  ref(14,
    'Nugraha, D. Y., et al. (2024). "Implementasi Retrieval-Augmented Generation (RAG) untuk Konsultan Cerdas Kesadaran Keamanan Siber." *International Journal of Artificial Intelligence and Digital Innovation (IJADIS)*, 2(3).'
  ),

  ref(15,
    'Pratiwi, S. N., & Wibowo, A. (2024). "Implementasi RAG untuk Klasifikasi Alamat Jaringan IPv4 Berbasis CIDR." *Jurnal Ilmu Komputer*, Universitas Gadjah Mada.'
  ),

  gap(),

  // ──────────────────────────────────────────────────────────
  subHeading('B. Dokumentasi Resmi dan Panduan Teknis'),
  // ──────────────────────────────────────────────────────────

  ref(16,
    'Laravel. (2025). *Laravel 13.x Documentation*. Tersedia di: https://laravel.com/docs/13.x [Diakses 20 Juni 2026].'
  ),

  ref(17,
    'Laravel. (2025). *Laravel Eloquent ORM: Getting Started*. Tersedia di: https://laravel.com/docs/13.x/eloquent [Diakses 20 Juni 2026].'
  ),

  ref(18,
    'Laravel. (2025). *Laravel Authentication: Protecting Routes*. Tersedia di: https://laravel.com/docs/13.x/authentication [Diakses 20 Juni 2026].'
  ),

  ref(19,
    'Laravel. (2025). *Laravel Middleware*. Tersedia di: https://laravel.com/docs/13.x/middleware [Diakses 20 Juni 2026].'
  ),

  ref(20,
    'Laravel. (2025). *Laravel Rate Limiting*. Tersedia di: https://laravel.com/docs/13.x/routing#rate-limiting [Diakses 20 Juni 2026].'
  ),

  ref(21,
    'Laravel. (2025). *Laravel Hashing (bcrypt)*. Tersedia di: https://laravel.com/docs/13.x/hashing [Diakses 20 Juni 2026].'
  ),

  ref(22,
    'Laravel. (2025). *Laravel Queues: Running Queue Workers*. Tersedia di: https://laravel.com/docs/13.x/queues [Diakses 20 Juni 2026].'
  ),

  ref(23,
    'Supabase. (2025). *Supabase Documentation: Database (PostgreSQL)*. Tersedia di: https://supabase.com/docs/guides/database [Diakses 20 Juni 2026].'
  ),

  ref(24,
    'Supabase. (2025). *Supabase Connection Pooling with PgBouncer*. Tersedia di: https://supabase.com/docs/guides/database/connecting-to-postgres#connection-pooler [Diakses 20 Juni 2026].'
  ),

  ref(25,
    'Pinecone. (2025). *Pinecone Documentation: Serverless Indexes*. Tersedia di: https://docs.pinecone.io/guides/indexes/create-an-index [Diakses 20 Juni 2026].'
  ),

  ref(26,
    'Pinecone. (2025). *Pinecone Integrated Inference: Embed and Search in One API Call*. Tersedia di: https://docs.pinecone.io/guides/inference/integrated-inference [Diakses 20 Juni 2026].'
  ),

  ref(27,
    'Pinecone. (2025). *Pinecone: Understanding Vector Databases for RAG*. Tersedia di: https://www.pinecone.io/learn/vector-database/ [Diakses 20 Juni 2026].'
  ),

  ref(28,
    'Google. (2025). *reCAPTCHA v3 Developer Guide*. Tersedia di: https://developers.google.com/recaptcha/docs/v3 [Diakses 20 Juni 2026].'
  ),

  ref(29,
    'Google. (2025). *Google Identity: Using OAuth 2.0 for Web Server Applications*. Tersedia di: https://developers.google.com/identity/protocols/oauth2/web-server [Diakses 20 Juni 2026].'
  ),

  ref(30,
    'Groq. (2025). *Groq API Documentation: LPU Inference Engine*. Tersedia di: https://console.groq.com/docs [Diakses 20 Juni 2026].'
  ),

  ref(31,
    'VirusTotal. (2025). *VirusTotal API v3 Documentation*. Tersedia di: https://docs.virustotal.com/reference/overview [Diakses 20 Juni 2026].'
  ),

  ref(32,
    'urlscan.io. (2025). *urlscan.io API Documentation*. Tersedia di: https://urlscan.io/docs/api/ [Diakses 20 Juni 2026].'
  ),

  ref(33,
    'Fonnte. (2025). *Fonnte WhatsApp API Documentation*. Tersedia di: https://fonnte.com/api [Diakses 20 Juni 2026].'
  ),

  ref(34,
    'Laravel Socialite. (2025). *Laravel Socialite: Social Authentication*. Tersedia di: https://laravel.com/docs/13.x/socialite [Diakses 20 Juni 2026].'
  ),

  ref(35,
    'Vite.js. (2025). *Vite: Next Generation Frontend Tooling*. Tersedia di: https://vitejs.dev/guide/ [Diakses 20 Juni 2026].'
  ),

  ref(36,
    'Tailwind CSS. (2025). *Tailwind CSS Documentation*. Tersedia di: https://tailwindcss.com/docs [Diakses 20 Juni 2026].'
  ),

  gap(),

  // ──────────────────────────────────────────────────────────
  subHeading('C. Standar dan Kerangka Kerja Keamanan'),
  // ──────────────────────────────────────────────────────────

  ref(37,
    'OWASP Foundation. (2021). *OWASP Top 10 – 2021: The Ten Most Critical Web Application Security Risks*. Tersedia di: https://owasp.org/Top10/ [Diakses 20 Juni 2026].'
  ),

  ref(38,
    'OWASP Foundation. (2023). *OWASP Risk Rating Methodology*. Tersedia di: https://owasp.org/www-community/OWASP_Risk_Rating_Methodology [Diakses 20 Juni 2026].'
  ),

  ref(39,
    'MITRE Corporation. (2025). *MITRE ATT&CK® Framework: Enterprise Techniques*. Tersedia di: https://attack.mitre.org/ [Diakses 20 Juni 2026].'
  ),

  ref(40,
    'MITRE Corporation. (2025). *Common Weakness Enumeration (CWE): A Community-Developed List of Software Weakness Types*. Tersedia di: https://cwe.mitre.org/ [Diakses 20 Juni 2026].'
  ),

  ref(41,
    'NIST. (2025). *National Vulnerability Database (NVD)*. Tersedia di: https://nvd.nist.gov/ [Diakses 20 Juni 2026].'
  ),

  ref(42,
    'CISA. (2025). *Known Exploited Vulnerabilities (KEV) Catalog*. Tersedia di: https://www.cisa.gov/known-exploited-vulnerabilities-catalog [Diakses 20 Juni 2026].'
  ),

  gap(),
];

// ─── GENERATE DOCX ───────────────────────────────────────────

const doc = new Document({
  creator: 'RedSim System',
  title: 'Daftar Pustaka - Laporan RedSim',
  styles: {
    default: {
      document: {
        run: {
          font: 'Times New Roman',
          size: 24, // 12pt
        },
      },
    },
  },
  sections: [{
    properties: {
      page: {
        margin: {
          top: 1440,    // 1 inch
          right: 1440,
          bottom: 1440,
          left: 1440,
        },
      },
    },
    children: content,
  }],
});

Packer.toBuffer(doc).then((buf) => {
  const filename = 'Daftar_Pustaka_RedSim.docx';
  fs.writeFileSync(filename, buf);
  console.log(`✅ ${filename} berhasil dibuat! (${(buf.length / 1024).toFixed(1)} KB)`);
  console.log(`   Total referensi: 42 sumber`);
  console.log(`   - Jurnal & Artikel Ilmiah: 15`);
  console.log(`   - Dokumentasi Resmi & Panduan Teknis: 21`);
  console.log(`   - Standar & Kerangka Kerja Keamanan: 6`);
}).catch(err => console.error('❌ Error:', err));
