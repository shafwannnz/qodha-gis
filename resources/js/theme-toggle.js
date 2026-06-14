/**
 * theme-toggle.js
 *
 * Mengelola perpindahan dark mode <-> light mode.
 * - Preferensi disimpan di localStorage (key: "qodha-theme")
 * - Default: dark mode (sesuai desain awal project)
 * - Class "dark" ditambahkan/dihapus dari <html> (Tailwind darkMode: 'class')
 * - Dijalankan SEGERA (bukan menunggu DOMContentLoaded) untuk
 *   menghindari flash/flicker saat halaman pertama kali dimuat.
 */

(function () {
    'use strict';

    const STORAGE_KEY = 'qodha-theme';
    const html = document.documentElement;

    // ----------------------------------------------------------------
    // 1. Terapkan tema tersimpan SEGERA (sebelum DOM lain dirender)
    // ----------------------------------------------------------------
    function applyStoredTheme() {
        const stored = localStorage.getItem(STORAGE_KEY);

        if (stored === 'light') {
            html.classList.remove('dark');
        } else {
            // default: dark (termasuk jika belum pernah diset)
            html.classList.add('dark');
        }
    }

    applyStoredTheme();

    // ----------------------------------------------------------------
    // 2. Setup toggle button setelah DOM siap
    // ----------------------------------------------------------------
    function setupToggleButton() {
        const btn = document.getElementById('theme-toggle-btn');
        if (!btn) return;

        btn.addEventListener('click', () => {
            const isDark = html.classList.contains('dark');

            if (isDark) {
                html.classList.remove('dark');
                localStorage.setItem(STORAGE_KEY, 'light');
            } else {
                html.classList.add('dark');
                localStorage.setItem(STORAGE_KEY, 'dark');
            }

            // Refresh tile layer Leaflet jika peta sudah aktif
            // (filter CSS tile bergantung pada --tile-filter yang
            //  berubah sesuai tema; Leaflet tidak otomatis re-render
            //  tile tapi filter CSS cukup karena diterapkan via class .leaflet-tile)
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', setupToggleButton);
    } else {
        setupToggleButton();
    }
})();
