@extends('layouts.app')

@section('title', 'Qodha GIS — Persebaran Mitra Qodha Aromatic')

@section('content')

    {{--
        Hero, About, Produk, Kemitraan (3 level), dan
        Syarat & Ketentuan Mitra (pengganti section Statistik).
        Statistik lengkap (chart, dsb) sekarang HANYA di /admin/dashboard.
    --}}
    @include('components.info-sections')

    {{-- Peta dengan Gate/Cover section --}}
    @include('components.map-section', ['wilayahs' => $wilayahs, 'kategoris' => $kategoris, 'stats' => $stats])

    {{-- Footer --}}
    <footer class="border-t border-ink-700">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-ink-500">
            <p>&copy; {{ date('Y') }} Qodha Aromatic. Sistem Informasi Geografis Persebaran Mitra.</p>
            <p>Dibangun dengan Laravel + Leaflet JS</p>
        </div>
    </footer>

@endsection

@push('scripts')
    <script>
        window.QODHA_CONFIG = {
            geojsonUrl:           "{{ route('api.mitras.geojson') }}",
            statsUrl:             "{{ route('api.mitras.stats') }}",
            wilayahCountsUrl:     "{{ route('api.mitras.wilayah-counts') }}",
            monthlyGrowthUrl:     "{{ route('api.mitras.monthly-growth') }}",
            kategoriPerWilayahUrl:"{{ route('api.mitras.kategori-per-wilayah') }}",
            choroplethGeoJsonUrl: "{{ asset('geojson/batas-wilayah.geojson') }}",
        };
    </script>
    {{-- leaflet-map.js sekarang hanya mendefinisikan window.QodhaMap, TIDAK auto-init --}}
    <script src="{{ asset('js/leaflet-map.js') }}"></script>
    <script src="{{ asset('js/filter-panel.js') }}"></script>
    {{-- map-gate.js: kontrol show/hide & memicu QodhaMap.init() --}}
    <script src="{{ asset('js/map-gate.js') }}"></script>
@endpush
