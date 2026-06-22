const fs = require('fs');
const { marked } = require('marked');
const htmlToDocx = require('html-to-docx');

(async () => {
    try {
        console.log('Membaca file Laporan_RedSim_akbar.docx.md...');
        const mdPath = 'Laporan_RedSim_akbar.docx.md';
        const docxPath = 'Laporan_RedSim_akbar.docx';

        if (!fs.existsSync(mdPath)) {
            console.error(`File ${mdPath} tidak ditemukan!`);
            process.exit(1);
        }

        const mdContent = fs.readFileSync(mdPath, 'utf8');

        console.log('Mengonversi Markdown ke HTML...');
        // Opsi parse: menggunakan GFM (GitHub Flavored Markdown)
        let htmlContent = marked.parse(mdContent, {
            gfm: true,
            breaks: true,
        });

        // Menyisipkan sedikit styling HTML dasar jika diperlukan
        const fullHtml = `
            <!DOCTYPE html>
            <html>
                <head>
                    <meta charset="utf-8">
                    <title>Laporan RedSim</title>
                    <style>
                        body { font-family: 'Times New Roman', serif; font-size: 12pt; line-height: 1.5; }
                        h1 { font-size: 18pt; text-align: center; font-weight: bold; }
                        h2 { font-size: 14pt; font-weight: bold; margin-top: 15pt; }
                        h3 { font-size: 12pt; font-weight: bold; margin-top: 10pt; }
                        p { margin-bottom: 10pt; text-align: justify; }
                        table { width: 100%; border-collapse: collapse; margin-bottom: 10pt; }
                        th, td { border: 1px solid black; padding: 5px; }
                        th { background-color: #f2f2f2; font-weight: bold; }
                    </style>
                </head>
                <body>
                    ${htmlContent}
                </body>
            </html>
        `;

        console.log('Memproses konversi ke format DOCX...');
        
        // Mengubah HTML ke Docx Buffer
        const fileBuffer = await htmlToDocx(fullHtml, null, {
            table: { row: { cantSplit: true } },
            footer: true,
            pageNumber: true,
            margins: {
                top: 1440,    // 1 inch
                right: 1440,  
                bottom: 1440,
                left: 1440
            }
        });

        console.log(`Menyimpan dokumen ke ${docxPath}...`);
        fs.writeFileSync(docxPath, fileBuffer);

        console.log('Selesai! Laporan berhasil di-generate.');
    } catch (err) {
        console.error('Terjadi kesalahan:', err);
    }
})();
