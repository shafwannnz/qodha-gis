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

    {{-- Footer: foto Qodha + social media (WhatsApp, Instagram, TikTok) --}}
    @include('components.footer')

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
    <script src="{{ asset('js/leaflet-map.js') }}"></script>
    <script src="{{ asset('js/filter-panel.js') }}"></script>
    <script src="{{ asset('js/map-gate.js') }}"></script>
@endpush
