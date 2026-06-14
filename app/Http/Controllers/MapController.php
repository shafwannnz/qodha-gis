<?php

namespace App\Http\Controllers;

use App\Services\MitraFilterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MapController extends Controller
{
    public function __construct(
        private readonly MitraFilterService $filterService
    ) {}

    /**
     * Halaman utama dashboard peta.
     */
    public function index(): View
    {
        $stats      = $this->filterService->statistics();
        $wilayahs   = $this->filterService->wilayahList();
        $kategoris  = ['Super Distributor', 'Distributor', 'Reseller', 'Agen'];

        return view('pages.map', compact('stats', 'wilayahs', 'kategoris'));
    }

    /**
     * API: GeoJSON berdasarkan filter.
     * GET /api/mitras/geojson?status=Aktif&wilayah=Bogor&kategori=Reseller&search=...
     */
    public function geojson(Request $request): JsonResponse
    {
        $mitras  = $this->filterService->filter($request);
        $geojson = $this->filterService->toGeoJson($mitras);

        return response()->json($geojson);
    }

    /**
     * API: statistik untuk cards.
     * GET /api/mitras/stats
     */
    public function stats(): JsonResponse
    {
        return response()->json($this->filterService->statistics());
    }

    /**
     * API: jumlah mitra per wilayah (untuk choropleth map).
     * GET /api/mitras/wilayah-counts
     */
    public function wilayahCounts(): JsonResponse
    {
        return response()->json($this->filterService->wilayahCounts());
    }

    /**
     * API: data pertumbuhan mitra per bulan (untuk chart tren).
     * GET /api/mitras/monthly-growth
     */
    public function monthlyGrowth(): JsonResponse
    {
        return response()->json($this->filterService->monthlyGrowth());
    }

    /**
     * API: breakdown kategori per wilayah (untuk stacked bar chart).
     * GET /api/mitras/kategori-per-wilayah
     */
    public function kategoriPerWilayah(): JsonResponse
    {
        return response()->json($this->filterService->kategoriPerWilayah());
    }
}
