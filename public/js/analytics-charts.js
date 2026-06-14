/**
 * analytics-charts.js
 *
 * Fetch data dari endpoint analytics & render Chart.js
 * dengan palet warna monokrom (grayscale) konsisten dengan tema.
 */

(function () {
    'use strict';

    if (typeof Chart === 'undefined') {
        console.warn('[Qodha GIS] Chart.js belum termuat.');
        return;
    }

    // Palet grayscale untuk dataset
    const GRAYSCALE = ['#ffffff', '#aaaaaa', '#666666', '#333333', '#888888'];

    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                labels: { color: '#a0a0a0', font: { family: 'Inter', size: 11 } },
            },
            tooltip: {
                backgroundColor: '#1a1a1a',
                titleColor: '#fff',
                bodyColor: '#e8e8e8',
                borderColor: '#2a2a2a',
                borderWidth: 1,
            },
        },
        scales: {
            x: {
                ticks: { color: '#707070', font: { size: 10 } },
                grid: { color: '#1a1a1a' },
            },
            y: {
                ticks: { color: '#707070', font: { size: 10 } },
                grid: { color: '#1a1a1a' },
                beginAtZero: true,
            },
        },
    };

    // ----------------------------------------------------------------
    // Chart 1: Tren Pertumbuhan Mitra (line chart, kumulatif)
    // ----------------------------------------------------------------
    async function renderGrowthChart() {
        const canvas = document.getElementById('chart-growth');
        if (!canvas) return;

        try {
            const res = await fetch(window.QODHA_CONFIG.monthlyGrowthUrl);
            const data = await res.json();

            if (!data.labels || data.labels.length === 0) {
                canvas.parentElement.innerHTML = '<p class="text-xs text-ink-500 text-center pt-20">Belum ada data tren pertumbuhan.</p>';
                return;
            }

            new Chart(canvas, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Total Mitra (Kumulatif)',
                        data: data.data,
                        borderColor: '#ffffff',
                        backgroundColor: 'rgba(255,255,255,0.08)',
                        fill: true,
                        tension: 0.3,
                        pointRadius: 3,
                        pointBackgroundColor: '#ffffff',
                    }],
                },
                options: commonOptions,
            });
        } catch (err) {
            console.error('[Qodha GIS] Error loading growth chart:', err);
        }
    }

    // ----------------------------------------------------------------
    // Chart 2: Breakdown Kategori per Wilayah (stacked bar chart)
    // ----------------------------------------------------------------
    async function renderKategoriWilayahChart() {
        const canvas = document.getElementById('chart-kategori-wilayah');
        if (!canvas) return;

        try {
            const res = await fetch(window.QODHA_CONFIG.kategoriPerWilayahUrl);
            const data = await res.json();

            if (!data.labels || data.labels.length === 0) {
                canvas.parentElement.innerHTML = '<p class="text-xs text-ink-500 text-center pt-20">Belum ada data wilayah.</p>';
                return;
            }

            const datasets = data.kategoris.map((kategori, idx) => ({
                label: kategori,
                data: data.labels.map(wilayah => data.datasets[wilayah]?.[kategori] ?? 0),
                backgroundColor: GRAYSCALE[idx % GRAYSCALE.length],
            }));

            new Chart(canvas, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: datasets,
                },
                options: {
                    ...commonOptions,
                    scales: {
                        x: { ...commonOptions.scales.x, stacked: true },
                        y: { ...commonOptions.scales.y, stacked: true },
                    },
                },
            });
        } catch (err) {
            console.error('[Qodha GIS] Error loading kategori-wilayah chart:', err);
        }
    }

    // ----------------------------------------------------------------
    // Init
    // ----------------------------------------------------------------
    renderGrowthChart();
    renderKategoriWilayahChart();
})();
