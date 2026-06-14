/**
 * map-gate.js
 *
 * Mengontrol tampilan "gate" (cover) section peta:
 * - Saat tombol "Cek Lokasi Mitra Sekarang" diklik:
 *     -> sembunyikan #map-gate
 *     -> tampilkan #map-panel
 *     -> inisialisasi peta Leaflet SECARA LAZY (hanya sekali)
 *        karena Leaflet butuh container yang sudah visible
 *        (div tersembunyi via `hidden` punya width/height 0,
 *        sehingga peta akan blank/rusak jika di-init lebih awal).
 * - Tombol "Tutup Peta" mengembalikan ke tampilan gate.
 *
 * Bergantung pada window.QodhaMap.init() yang diekspos oleh
 * leaflet-map.js (lihat perubahan di file tersebut: inisialisasi
 * dibungkus dalam fungsi init(), bukan auto-run).
 */

(function () {
    'use strict';

    const gate      = document.getElementById('map-gate');
    const panel     = document.getElementById('map-panel');
    const openBtn   = document.getElementById('open-map-btn');
    const closeBtn  = document.getElementById('close-map-btn');

    let mapInitialized = false;

    openBtn?.addEventListener('click', () => {
        gate?.classList.add('hidden');
        panel?.classList.remove('hidden');

        if (!mapInitialized) {
            // Inisialisasi peta hanya sekali, setelah container terlihat
            if (window.QodhaMap && typeof window.QodhaMap.init === 'function') {
                window.QodhaMap.init();
            }
            mapInitialized = true;
        } else if (window.QodhaMap && window.QodhaMap.map) {
            // Jika sudah pernah di-init, perbaiki ukuran peta
            // (Leaflet perlu invalidateSize setelah container berubah dari hidden -> visible)
            setTimeout(() => window.QodhaMap.map.invalidateSize(), 50);
        }

        // Scroll halus ke panel peta
        panel?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });

    closeBtn?.addEventListener('click', () => {
        panel?.classList.add('hidden');
        gate?.classList.remove('hidden');
        gate?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
})();
