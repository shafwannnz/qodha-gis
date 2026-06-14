@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

    <div class="mb-8">
        <p class="text-[11px] uppercase tracking-[0.3em] text-ink-400 font-medium">Selamat Datang</p>
        <h1 class="font-display font-bold text-3xl text-white mt-2">
            Halo, {{ auth()->user()->name }}
        </h1>
    </div>

    {{-- Stat cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
        <div class="section-hover border border-ink-700 bg-ink-800/50 rounded-lg p-5">
            <span class="text-[10px] uppercase tracking-widest text-ink-400">Total Mitra</span>
            <div class="font-display text-3xl font-bold text-white mt-2">{{ $stats['total'] }}</div>
        </div>
        <div class="section-hover border border-ink-700 bg-ink-800/50 rounded-lg p-5">
            <span class="text-[10px] uppercase tracking-widest text-ink-400">Aktif</span>
            <div class="font-display text-3xl font-bold text-white mt-2">{{ $stats['aktif'] }}</div>
        </div>
        <div class="section-hover border border-ink-700 bg-ink-800/50 rounded-lg p-5">
            <span class="text-[10px] uppercase tracking-widest text-ink-400">Non Aktif</span>
            <div class="font-display text-3xl font-bold text-ink-300 mt-2">{{ $stats['nonAktif'] }}</div>
        </div>
        <div class="section-hover border border-ink-700 bg-ink-800/50 rounded-lg p-5">
            <span class="text-[10px] uppercase tracking-widest text-ink-400">Wilayah Terbanyak</span>
            <div class="font-display text-xl font-bold text-white mt-2 truncate">
                {{ collect($stats['perWilayah'])->keys()->first() ?? '-' }}
            </div>
        </div>
    </div>

    {{-- Quick action --}}
    <div class="mb-10">
        <a href="{{ route('admin.mitras.create') }}" class="inline-flex items-center gap-2 bg-white text-ink-900 font-display font-semibold text-sm px-4 py-2.5 rounded-md hover:bg-ink-100 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Tambah Mitra Baru
        </a>
        <a href="{{ route('admin.mitras.index') }}" class="inline-flex items-center gap-2 ml-3 border border-ink-600 text-ink-200 font-display font-semibold text-sm px-4 py-2.5 rounded-md hover:text-white hover:border-ink-400 transition-colors">
            Kelola Data Mitra
        </a>
    </div>

    {{-- Charts --}}
    <div class="grid lg:grid-cols-2 gap-6">
        <div class="section-hover border border-ink-700 bg-ink-800/50 rounded-lg p-4 lg:p-5">
            <h3 class="font-display font-semibold text-sm uppercase tracking-widest text-ink-200 mb-4">
                Tren Pertumbuhan Mitra
            </h3>
            <div class="relative h-64">
                <canvas id="chart-growth-admin"></canvas>
            </div>
        </div>

        <div class="section-hover border border-ink-700 bg-ink-800/50 rounded-lg p-4 lg:p-5">
            <h3 class="font-display font-semibold text-sm uppercase tracking-widest text-ink-200 mb-4">
                Kategori Mitra per Wilayah (Top 8)
            </h3>
            <div class="relative h-64">
                <canvas id="chart-kategori-wilayah-admin"></canvas>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    (function () {
        const GRAYSCALE = ['#ffffff', '#aaaaaa', '#666666', '#333333', '#888888'];

        const commonOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { labels: { color: '#a0a0a0', font: { family: 'Inter', size: 11 } } },
                tooltip: {
                    backgroundColor: '#1a1a1a', titleColor: '#fff', bodyColor: '#e8e8e8',
                    borderColor: '#2a2a2a', borderWidth: 1,
                },
            },
            scales: {
                x: { ticks: { color: '#707070', font: { size: 10 } }, grid: { color: '#1a1a1a' } },
                y: { ticks: { color: '#707070', font: { size: 10 } }, grid: { color: '#1a1a1a' }, beginAtZero: true },
            },
        };

        // Data dipassing langsung dari server (sudah dihitung di DashboardController)
        const growthData = @json($monthlyGrowth);
        const kategoriWilayahData = @json($kategoriPerWil);

        // Chart 1: Growth
        const growthCanvas = document.getElementById('chart-growth-admin');
        if (growthCanvas && growthData.labels && growthData.labels.length > 0) {
            new Chart(growthCanvas, {
                type: 'line',
                data: {
                    labels: growthData.labels,
                    datasets: [{
                        label: 'Total Mitra (Kumulatif)',
                        data: growthData.data,
                        borderColor: '#ffffff',
                        backgroundColor: 'rgba(255,255,255,0.08)',
                        fill: true, tension: 0.3, pointRadius: 3, pointBackgroundColor: '#ffffff',
                    }],
                },
                options: commonOptions,
            });
        } else if (growthCanvas) {
            growthCanvas.parentElement.innerHTML = '<p class="text-xs text-ink-500 text-center pt-20">Belum ada data tren pertumbuhan.</p>';
        }

        // Chart 2: Kategori per Wilayah
        const kwCanvas = document.getElementById('chart-kategori-wilayah-admin');
        if (kwCanvas && kategoriWilayahData.labels && kategoriWilayahData.labels.length > 0) {
            const datasets = kategoriWilayahData.kategoris.map((kategori, idx) => ({
                label: kategori,
                data: kategoriWilayahData.labels.map(w => kategoriWilayahData.datasets[w]?.[kategori] ?? 0),
                backgroundColor: GRAYSCALE[idx % GRAYSCALE.length],
            }));

            new Chart(kwCanvas, {
                type: 'bar',
                data: { labels: kategoriWilayahData.labels, datasets },
                options: {
                    ...commonOptions,
                    scales: {
                        x: { ...commonOptions.scales.x, stacked: true },
                        y: { ...commonOptions.scales.y, stacked: true },
                    },
                },
            });
        } else if (kwCanvas) {
            kwCanvas.parentElement.innerHTML = '<p class="text-xs text-ink-500 text-center pt-20">Belum ada data wilayah.</p>';
        }
    })();
</script>
@endpush
