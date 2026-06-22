const puppeteer = require('puppeteer');
const fs = require('fs');

(async () => {
    const baseUrl = 'http://127.0.0.1:8000';
    const screenshotDir = './screenshot';
    
    if (!fs.existsSync(screenshotDir)) {
        fs.mkdirSync(screenshotDir);
    }

    // Launch browser
    const browser = await puppeteer.launch({
        args: ['--no-sandbox', '--disable-setuid-sandbox'],
        defaultViewport: { width: 1366, height: 768 }
    });
    
    const page = await browser.newPage();
    
    const sleep = ms => new Promise(r => setTimeout(r, ms));
    
    console.log("Capturing Landing Page...");
    await page.goto(baseUrl + '/');
    await sleep(2000);
    await page.screenshot({ path: `${screenshotDir}/01_Beranda.png`, fullPage: true });

    console.log("Capturing Halaman Masuk...");
    await page.goto(baseUrl + '/masuk');
    await sleep(1000);
    await page.screenshot({ path: `${screenshotDir}/02_Masuk.png` });

    console.log("Capturing Halaman Daftar...");
    await page.goto(baseUrl + '/daftar');
    await sleep(1000);
    await page.screenshot({ path: `${screenshotDir}/03_Daftar.png` });

    console.log("Logging in via /auto-login...");
    await page.goto(baseUrl + '/auto-login');
    await sleep(2000); // Wait for redirect
    
    console.log("Capturing Dashboard...");
    await page.screenshot({ path: `${screenshotDir}/04_Dashboard.png`, fullPage: true });

    console.log("Capturing Analisis Kode...");
    await page.goto(baseUrl + '/analisis/kode');
    await sleep(2000);
    await page.screenshot({ path: `${screenshotDir}/05_Analisis_Kode.png`, fullPage: true });

    console.log("Capturing Analisis URL...");
    await page.goto(baseUrl + '/analisis/url');
    await sleep(2000);
    await page.screenshot({ path: `${screenshotDir}/06_Analisis_URL.png`, fullPage: true });
    
    console.log("Capturing Analisis ZIP...");
    await page.goto(baseUrl + '/analisis/zip');
    await sleep(2000);
    await page.screenshot({ path: `${screenshotDir}/07_Analisis_ZIP.png`, fullPage: true });

    console.log("Capturing Laporan...");
    await page.goto(baseUrl + '/laporan');
    await sleep(2000);
    await page.screenshot({ path: `${screenshotDir}/08_Laporan.png`, fullPage: true });

    console.log("Capturing Edukasi...");
    await page.goto(baseUrl + '/edukasi');
    await sleep(2000);
    await page.screenshot({ path: `${screenshotDir}/09_Edukasi.png`, fullPage: true });

    console.log("Capturing Admin Dashboard...");
    await page.goto(baseUrl + '/admin');
    await sleep(2000);
    await page.screenshot({ path: `${screenshotDir}/10_Admin_Dashboard.png`, fullPage: true });

    await browser.close();
    console.log("Capture completed!");
})();
