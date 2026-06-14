{{--
    Komponen Dashboard Analytics — Chart.js
    Container untuk grafik tren pertumbuhan & breakdown kategori per wilayah.
    Logic rendering chart ada di public/js/analytics-charts.js
--}}

<div class="grid lg:grid-cols-2 gap-6">

    {{-- Tren Pertumbuhan Mitra --}}
    <div class="section-hover border border-ink-700 bg-ink-800/50 rounded-lg p-4 lg:p-5">
        <h3 class="font-display font-semibold text-sm uppercase tracking-widest text-ink-200 mb-4">
            Tren Pertumbuhan Mitra
        </h3>
        <div class="relative h-64">
            <canvas id="chart-growth"></canvas>
        </div>
    </div>

    {{-- Breakdown Kategori per Wilayah --}}
    <div class="section-hover border border-ink-700 bg-ink-800/50 rounded-lg p-4 lg:p-5">
        <h3 class="font-display font-semibold text-sm uppercase tracking-widest text-ink-200 mb-4">
            Kategori Mitra per Wilayah (Top 8)
        </h3>
        <div class="relative h-64">
            <canvas id="chart-kategori-wilayah"></canvas>
        </div>
    </div>

</div>
