<?php

namespace App\Services;

use App\Models\Mitra;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

/**
 * MitraFilterService
 *
 * Memisahkan logic filter/query dari Controller,
 * sehingga mudah di-test dan di-reuse.
 */
class MitraFilterService
{
    /**
     * Apply semua filter dari request ke query Mitra.
     *
     * BUG FIX: sebelumnya filter "search" tidak bisa dikombinasikan
     * dengan benar bersama filter lain karena scopeSearch() membungkus
     * kondisi OR tanpa grouping yang tepat ketika digabung dengan
     * where() lain — ini menyebabkan hasil filter status/kategori/wilayah
     * terabaikan saat search diisi. Sudah diperbaiki dengan memastikan
     * scopeSearch() membungkus kondisi OR dalam closure (lihat Mitra::scopeSearch),
     * DAN urutan filter di sini diubah agar search selalu diterapkan
     * TERAKHIR setelah filter where lainnya (sehingga AND-grouping benar).
     *
     * BUG FIX 2: trim() ditambahkan pada nilai search & filter dropdown
     * untuk menghindari whitespace tak terlihat dari query string yang
     * menyebabkan hasil kosong (mismatch "Bogor" vs "Bogor ").
     */
    public function filter(Request $request): Collection
    {
        $query = Mitra::query();

        // Filter status (Aktif / Non Aktif)
        $status = trim((string) $request->input('status', ''));
        if ($status !== '') {
            $query->where('status', $status);
        }

        // Filter wilayah
        $wilayah = trim((string) $request->input('wilayah', ''));
        if ($wilayah !== '') {
            $query->where('wilayah', $wilayah);
        }

        // Filter kategori
        $kategori = trim((string) $request->input('kategori', ''));
        if ($kategori !== '') {
            $query->where('kategori', $kategori);
        }

        // Full-text search: nama mitra, nama toko, wilayah, alamat
        // Diterapkan TERAKHIR agar grouping AND-OR benar:
        // WHERE status=? AND wilayah=? AND kategori=? AND (nama LIKE ? OR ... )
        $search = trim((string) $request->input('search', ''));
        if ($search !== '') {
            $query->search($search);
        }

        return $query->orderBy('nama_mitra')->get();
    }

    /**
     * Konversi Collection Mitra ke GeoJSON FeatureCollection.
     */
    public function toGeoJson(Collection $mitras): array
    {
        $features = $mitras
            ->filter(fn($m) => $m->latitude && $m->longitude)
            ->map(fn($m) => $m->toGeoJsonFeature())
            ->values()
            ->toArray();

        return [
            'type'     => 'FeatureCollection',
            'features' => $features,
        ];
    }

    /**
     * Statistik ringkas untuk cards di dashboard.
     */
    public function statistics(): array
    {
        $total    = Mitra::count();
        $aktif    = Mitra::where('status', 'Aktif')->count();
        $nonAktif = Mitra::where('status', 'Non Aktif')->count();

        $perWilayah = Mitra::selectRaw('wilayah, COUNT(*) as total')
            ->whereNotNull('wilayah')
            ->groupBy('wilayah')
            ->orderByDesc('total')
            ->limit(10)
            ->pluck('total', 'wilayah')
            ->toArray();

        $perKategori = Mitra::selectRaw('kategori, COUNT(*) as total')
            ->groupBy('kategori')
            ->orderByDesc('total')
            ->pluck('total', 'kategori')
            ->toArray();

        return compact('total', 'aktif', 'nonAktif', 'perWilayah', 'perKategori');
    }

    /**
     * Daftar wilayah unik untuk dropdown filter.
     */
    public function wilayahList(): array
    {
        return Mitra::whereNotNull('wilayah')
            ->distinct()
            ->orderBy('wilayah')
            ->pluck('wilayah')
            ->toArray();
    }

    /**
     * Jumlah mitra per wilayah (untuk choropleth map).
     */
    public function wilayahCounts(): array
    {
        return Mitra::selectRaw('wilayah, COUNT(*) as total')
            ->whereNotNull('wilayah')
            ->groupBy('wilayah')
            ->pluck('total', 'wilayah')
            ->toArray();
    }

    /**
     * Pertumbuhan jumlah mitra per bulan (berdasarkan created_at).
     * Untuk chart tren pertumbuhan.
     */
    public function monthlyGrowth(): array
    {
        $raw = Mitra::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as bulan, COUNT(*) as total")
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        // Hitung kumulatif agar grafik tren terlihat naik
        $cumulative = [];
        $running = 0;
        foreach ($raw as $bulan => $total) {
            $running += $total;
            $cumulative[$bulan] = $running;
        }

        return [
            'labels'     => array_keys($cumulative),
            'data'       => array_values($cumulative),
            'perBulan'   => array_values($raw),
        ];
    }

    /**
     * Breakdown kategori per wilayah (untuk stacked bar chart).
     * Mengembalikan top N wilayah dengan jumlah mitra terbanyak.
     */
    public function kategoriPerWilayah(int $limit = 8): array
    {
        $topWilayah = array_keys(
            collect($this->wilayahCounts())
                ->sortDesc()
                ->take($limit)
                ->toArray()
        );

        $rows = Mitra::selectRaw('wilayah, kategori, COUNT(*) as total')
            ->whereIn('wilayah', $topWilayah)
            ->groupBy('wilayah', 'kategori')
            ->get();

        $kategoriList = ['Super Distributor', 'Distributor', 'Reseller', 'Agen'];

        $result = [];
        foreach ($topWilayah as $wilayah) {
            $result[$wilayah] = array_fill_keys($kategoriList, 0);
        }

        foreach ($rows as $row) {
            if (isset($result[$row->wilayah][$row->kategori])) {
                $result[$row->wilayah][$row->kategori] = (int) $row->total;
            }
        }

        return [
            'labels'     => $topWilayah,
            'kategoris'  => $kategoriList,
            'datasets'   => $result,
        ];
    }
}
