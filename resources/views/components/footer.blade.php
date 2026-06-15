{{--
    ============================================================
    Footer — Logo Qodha (ukuran kecil) + Social Media Links
    ============================================================

    PERBAIKAN BUG (sebelumnya):
    File "qodha-footer.jpg" yang diupload sebenarnya adalah LOGO
    Qodha (portrait, 328x637px) — bukan foto banner lanskap.
    Sebelumnya ditampilkan dengan `w-full max-h-[320px] object-cover`
    sehingga ter-crop/zoom hanya ke bagian kepala kuda oranye dan
    terlihat blur/pecah (lihat referensi bug report).

    SOLUSI:
    Logo ditampilkan dalam ukuran kecil-menengah (w-32, sekitar
    128px) dengan `object-contain` (tidak di-crop, proporsi asli
    terjaga), diposisikan di tengah sebagai elemen branding footer
    — bukan sebagai banner lebar.

    - 3 ikon sosial media (Lucide-style inline SVG):
        1. WhatsApp -> wa.me dengan pesan template pre-filled
        2. Instagram -> https://www.instagram.com/qodha.id/
        3. TikTok -> https://www.tiktok.com/@qodhaaromatic
--}}

@php
    $waNumber = '6281717302223'; // +62 817-1730-2223
    $waMessage = 'Halo kak, Saya tertarik untuk mendaftar menjadi Mitra Qodha Aromatic, boleh tolong jelaskan syarat dan ketentuannya kak?';
    $waLink = 'https://wa.me/' . $waNumber . '?text=' . rawurlencode($waMessage);
@endphp

<footer class="border-t" style="border-color: var(--border-color);">
    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <div class="flex flex-col items-center text-center gap-6">

            {{-- Logo Qodha — ukuran kecil, proporsional, tidak di-crop --}}
            <img src="{{ asset('images/qodha-footer.png') }}" alt="Qodha Aromatic"
                 class="w-28 sm:w-32 h-auto object-contain">

            <div>
                <p class="font-display font-semibold text-base tracking-tight">
                    Qodha <span class="text-ink-400 font-normal">Aromatic</span>
                </p>
                <p class="text-xs text-ink-400 mt-1">Raise The Passion</p>
            </div>

            {{-- Social Media Icons --}}
            <div class="flex items-center gap-3">
                {{-- WhatsApp --}}
                <a href="{{ $waLink }}" target="_blank" rel="noopener noreferrer"
                   class="social-icon-btn" aria-label="Hubungi via WhatsApp" title="Chat WhatsApp">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 21l1.65-3.8a9 9 0 1 1 3.4 3.4z"/>
                        <path d="M9 10a.5.5 0 0 0 1 0V9a.5.5 0 0 0-1 0zm5 0a.5.5 0 0 0 1 0V9a.5.5 0 0 0-1 0z"/>
                        <path d="M9.5 13.5c.5 1 1.5 2 3 2s2.5-.5 3-1"/>
                    </svg>
                </a>

                {{-- Instagram --}}
                <a href="https://www.instagram.com/qodha.id/" target="_blank" rel="noopener noreferrer"
                   class="social-icon-btn" aria-label="Instagram Qodha" title="Instagram @qodha.id">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="20" height="20" x="2" y="2" rx="5" ry="5"/>
                        <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
                        <line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/>
                    </svg>
                </a>

                {{-- TikTok --}}
                <a href="https://www.tiktok.com/@qodhaaromatic?lang=en-GB" target="_blank" rel="noopener noreferrer"
                   class="social-icon-btn" aria-label="TikTok Qodha Aromatic" title="TikTok @qodhaaromatic">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5"/>
                    </svg>
                </a>
            </div>
        </div>

        {{-- Copyright --}}
        <div class="mt-10 pt-6 border-t flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-ink-500" style="border-color: var(--border-color);">
            <p>&copy; {{ date('Y') }} Qodha Aromatic. Sistem Informasi Geografis Persebaran Mitra.</p>
            <p>Dibangun dengan Laravel + Leaflet JS</p>
        </div>
    </div>
</footer>
