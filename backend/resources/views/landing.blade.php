<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="RedSim — Platform Penilaian Keamanan Sistem Berbasis AI. Analisis kerentanan kode, URL, dan file dengan teknologi AI canggih.">
    <title>RedSim — Platform Penilaian Keamanan Sistem Berbasis AI</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/landing-animations.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#050816] text-[#e2e8f0] antialiased overflow-x-hidden">

    {{-- ========== SPLASH SCREEN ========== --}}
    <div id="splash-screen">
        <div style="display:flex;flex-direction:column;align-items:center;">
            <div class="splash-text"></div>
            <div class="splash-subtitle">Platform Keamanan Berbasis AI</div>
        </div>
    </div>

    {{-- ========== LIGHTFALL BACKGROUND ========== --}}
    <div class="lightfall-wrap" id="lightfall-bg"></div>

    {{-- ========== NAVIGATION ========== --}}
    <nav class="relative z-50 px-6 py-5">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#00d4ff] to-[#7c3aed] flex items-center justify-center shadow-lg shadow-[#00d4ff]/20">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <span class="text-xl font-bold gradient-text-anim">RedSim</span>
            </div>
            <div class="flex items-center gap-4">
                <a href="/edukasi/leaderboard" class="hidden md:flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium text-yellow-400 hover:bg-yellow-400/10 transition-colors">
                    🏆 Leaderboard
                </a>
                @auth
                    <a href="{{ route('dashboard') }}" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#00d4ff] to-[#7c3aed] text-white text-sm font-semibold hover:shadow-lg hover:shadow-[#00d4ff]/25 transition-all duration-300">Dashboard</a>
                @else
                    <a href="{{ route('masuk') }}" class="px-5 py-2.5 rounded-xl text-sm font-medium text-[#94a3b8] hover:text-white transition-colors">Masuk</a>
                    <a href="{{ route('daftar') }}" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#00d4ff] to-[#7c3aed] text-white text-sm font-semibold hover:shadow-lg hover:shadow-[#00d4ff]/25 transition-all duration-300 hover:-translate-y-0.5">Daftar Gratis</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- ========== HERO SECTION ========== --}}
    <section class="relative z-10 px-6 pt-16 pb-24">
        <div class="max-w-4xl mx-auto text-center landing-hero-content">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-[#00d4ff]/10 border border-[#00d4ff]/20 mb-8 glow-pulse">
                <div class="w-2 h-2 rounded-full bg-[#00d4ff] animate-pulse"></div>
                <span class="text-xs font-medium text-[#00d4ff]">Platform Keamanan Berbasis AI</span>
            </div>

            <h1 class="text-5xl md:text-6xl lg:text-7xl font-black leading-tight mb-6">
                <span class="text-white">Penilaian Keamanan</span><br>
                <span class="gradient-text-anim">Sistem Anda</span>
            </h1>

            {{-- Text Typing Welcome --}}
            <div class="text-lg md:text-xl text-[#94a3b8] max-w-2xl mx-auto mb-10 leading-relaxed min-h-[3em]">
                <div id="welcome-texttype" class="texttype-wrap"></div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('daftar') }}" class="group px-8 py-4 rounded-2xl bg-gradient-to-r from-[#00d4ff] to-[#7c3aed] text-white font-bold text-lg hover:shadow-2xl hover:shadow-[#00d4ff]/30 transition-all duration-300 hover:-translate-y-1">
                    Mulai Analisis Gratis
                    <svg class="inline-block w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
                <a href="#fitur" class="px-8 py-4 rounded-2xl border border-[#1e2d4a] text-[#94a3b8] font-medium text-lg hover:bg-[#0f1629] hover:text-white hover:border-[#00d4ff]/30 transition-all duration-300">
                    Lihat Fitur
                </a>
            </div>
        </div>
    </section>

    {{-- ========== FEATURES ========== --}}
    <section id="fitur" class="relative z-10 px-6 py-20">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Fitur Unggulan</h2>
                <p class="text-[#94a3b8] max-w-xl mx-auto">Dilengkapi teknologi AI dan knowledge base keamanan siber dari sumber terpercaya dunia.</p>
            </div>
            <div class="grid md:grid-cols-3 gap-6">
                @php
                $features = [
                    ['icon' => 'M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4', 'title' => 'Analisis Kode', 'desc' => 'Tempel kode sumber dan dapatkan analisis kerentanan mendalam dengan remediasi yang jelas.', 'color' => '#00d4ff'],
                    ['icon' => 'M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9', 'title' => 'Pemindaian URL', 'desc' => 'Pindai URL dan website untuk mendeteksi phishing, malware, dan miskonfigurasi keamanan.', 'color' => '#7c3aed'],
                    ['icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4', 'title' => 'Analisis Proyek ZIP', 'desc' => 'Unggah file ZIP proyek lengkap untuk analisis keamanan komprehensif seluruh kode sumber.', 'color' => '#22c55e'],
                    ['icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z', 'title' => 'Simulasi Serangan', 'desc' => 'Visualisasi skenario serangan berdasarkan kerentanan yang ditemukan dengan framework MITRE ATT&CK.', 'color' => '#f97316'],
                    ['icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'title' => 'Hub Edukasi', 'desc' => 'Pelajari keamanan siber dengan tantangan interaktif, ensiklopedia, dan leaderboard.', 'color' => '#00d4ff'],
                    ['icon' => 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z', 'title' => 'Ekspor Laporan PDF', 'desc' => 'Unduh laporan analisis keamanan lengkap dalam format PDF profesional.', 'color' => '#7c3aed'],
                ];
                @endphp
                @foreach($features as $i => $f)
                <div class="feature-card-anim group p-6 rounded-2xl bg-[#0f1629]/80 backdrop-blur-sm border border-[#1e2d4a] hover:border-[{{ $f['color'] }}]/30 transition-all duration-300 hover:-translate-y-1" style="animation-delay: {{ $i * 0.1 }}s">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-4 transition-colors" style="background: {{ $f['color'] }}15;">
                        <svg class="w-6 h-6" style="color: {{ $f['color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $f['icon'] }}"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">{{ $f['title'] }}</h3>
                    <p class="text-sm text-[#94a3b8] leading-relaxed">{{ $f['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ========== KNOWLEDGE BASE ========== --}}
    <section class="relative z-10 px-6 py-20 border-t border-[#1e2d4a]">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-3xl font-bold mb-4">Didukung Knowledge Base Terpercaya</h2>
            <p class="text-[#94a3b8] mb-12">Analisis kami berbasis data dari sumber keamanan siber internasional.</p>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                @foreach(['MITRE ATT&CK', 'NVD (CVE)', 'OWASP', 'CISA KEV', 'CWE', 'CAPEC'] as $source)
                <div class="p-4 rounded-xl bg-[#0f1629]/80 backdrop-blur-sm border border-[#1e2d4a] text-center hover:border-[#00d4ff]/30 transition-all duration-300">
                    <p class="text-sm font-semibold text-[#00d4ff]">{{ $source }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ========== FOOTER ========== --}}
    <footer class="relative z-10 px-6 py-8 border-t border-[#1e2d4a]">
        <div class="max-w-6xl mx-auto text-center">
            <p class="text-sm text-[#64748b]">&copy; {{ date('Y') }} RedSim. Platform Penilaian Keamanan Sistem Berbasis AI.</p>
        </div>
    </footer>

    {{-- ========== SCRIPTS ========== --}}
    <script src="{{ asset('js/lightfall.js') }}"></script>
    <script src="{{ asset('js/animations.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Init Splash Screen
            initSplash(function() {
                // After splash completes, start lightfall & typing
            });

            // 2. Init Lightfall Background
            var lfContainer = document.getElementById('lightfall-bg');
            if (lfContainer) {
                initLightfall(lfContainer, {
                    colors: ['#00d4ff', '#5227FF', '#7c3aed'],
                    backgroundColor: '#050816',
                    speed: 0.4,
                    streakCount: 3,
                    streakWidth: 1.2,
                    streakLength: 1,
                    glow: 0.8,
                    density: 0.5,
                    twinkle: 0.8,
                    zoom: 3,
                    backgroundGlow: 0.4,
                    opacity: 0.6
                });
            }

            // 3. Init TextType Welcome
            initTextType(
                document.getElementById('welcome-texttype'),
                [
                    'Selamat datang di RedSim — garda terdepan keamanan digital Anda.',
                    'Analisis kerentanan kode, URL, dan proyek secara otomatis dengan AI.',
                    'Temukan celah keamanan sebelum peretas menemukannya.',
                    'Didukung knowledge base dari MITRE, OWASP, dan NVD.',
                    'Lindungi sistem Anda dengan kecerdasan buatan terpercaya.'
                ],
                { typingSpeed: 45, deletingSpeed: 25, pauseDuration: 2500, loop: true }
            );

            // 4. Feature cards scroll animation
            var observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1 });
            document.querySelectorAll('.feature-card-anim').forEach(function(el) {
                observer.observe(el);
            });
        });
    </script>
</body>
</html>
