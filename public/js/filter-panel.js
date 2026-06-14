/**
 * filter-panel.js
 *
 * Logic untuk panel filter & search bar.
 *
 * BUG FIX (sebelumnya):
 * - Sidebar mobile menggunakan banyak class Tailwind ad-hoc yang
 *   ditambahkan/dihapus secara terpisah, menyebabkan background
 *   transparan & overlap dengan section lain saat dibuka.
 *   -> Sekarang menggunakan SATU class "sidebar-mobile-open"
 *      yang didefinisikan di app.blade.php dengan background solid
 *      dan z-index yang benar (60, di atas overlay 50).
 * - Body discroll-lock (class "sidebar-locked") saat sidebar mobile
 *   terbuka agar tidak ada scroll ganda yang membingungkan.
 */

(function () {
    'use strict';

    const searchInput    = document.getElementById('search-input');
    const filterStatus   = document.getElementById('filter-status');
    const filterKategori = document.getElementById('filter-kategori');
    const filterWilayah  = document.getElementById('filter-wilayah');
    const resetBtn       = document.getElementById('reset-filter');
    const resultCount    = document.getElementById('result-count');
    const nearestResult  = document.getElementById('nearest-result');

    const sidebar         = document.getElementById('sidebar');
    const sidebarOverlay  = document.getElementById('sidebar-overlay');
    const filterToggleBtn = document.getElementById('filter-toggle-btn');

    let debounceTimer = null;

    // ----------------------------------------------------------------
    // Kumpulkan nilai filter saat ini
    // ----------------------------------------------------------------
    function getCurrentFilters() {
        return {
            search:   searchInput?.value.trim() || '',
            status:   filterStatus?.value || '',
            kategori: filterKategori?.value || '',
            wilayah:  filterWilayah?.value || '',
        };
    }

    // ----------------------------------------------------------------
    // Trigger reload peta dengan filter saat ini
    // ----------------------------------------------------------------
    function applyFilters() {
        if (window.QodhaMap && typeof window.QodhaMap.loadData === 'function') {
            window.QodhaMap.loadData(getCurrentFilters());
        }
        // Sembunyikan hasil "mitra terdekat" karena daftar sudah berubah
        nearestResult?.classList.add('hidden');
    }

    // ----------------------------------------------------------------
    // Search input dengan debounce 350ms
    // ----------------------------------------------------------------
    searchInput?.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(applyFilters, 350);
    });

    // Trigger juga saat tekan Enter (langsung, tanpa debounce)
    searchInput?.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            clearTimeout(debounceTimer);
            applyFilters();
        }
    });

    // ----------------------------------------------------------------
    // Select filters: langsung trigger
    // ----------------------------------------------------------------
    [filterStatus, filterKategori, filterWilayah].forEach((el) => {
        el?.addEventListener('change', applyFilters);
    });

    // ----------------------------------------------------------------
    // Reset semua filter
    // ----------------------------------------------------------------
    resetBtn?.addEventListener('click', () => {
        if (searchInput) searchInput.value = '';
        if (filterStatus) filterStatus.value = '';
        if (filterKategori) filterKategori.value = '';
        if (filterWilayah) filterWilayah.value = '';
        applyFilters();
    });

    // ----------------------------------------------------------------
    // Update counter hasil ketika data peta selesai dimuat
    // ----------------------------------------------------------------
    window.addEventListener('qodha:data-loaded', (e) => {
        if (resultCount) {
            resultCount.textContent = e.detail.count ?? 0;
        }
    });

    // ----------------------------------------------------------------
    // Mobile: toggle sidebar filter (FIXED)
    // ----------------------------------------------------------------
    function openSidebar() {
        sidebar?.classList.add('sidebar-mobile-open');
        if (sidebarOverlay) sidebarOverlay.style.display = 'block';
        document.body.classList.add('sidebar-locked');
    }

    function closeSidebar() {
        sidebar?.classList.remove('sidebar-mobile-open');
        if (sidebarOverlay) sidebarOverlay.style.display = 'none';
        document.body.classList.remove('sidebar-locked');
    }

    filterToggleBtn?.addEventListener('click', () => {
        const isOpen = sidebar?.classList.contains('sidebar-mobile-open');
        isOpen ? closeSidebar() : openSidebar();
    });

    sidebarOverlay?.addEventListener('click', closeSidebar);

    // Only relevant on mobile
    function checkMobileView() {
        if (window.matchMedia('(min-width: 1024px)').matches) {
            closeSidebar();
            if (filterToggleBtn) filterToggleBtn.style.display = 'none';
        } else {
            if (filterToggleBtn) filterToggleBtn.style.display = 'flex';
        }
    }
    checkMobileView();
    window.addEventListener('resize', checkMobileView);
})();
