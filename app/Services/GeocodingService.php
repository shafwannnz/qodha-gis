<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * GeocodingService
 *
 * Mengubah alamat teks menjadi koordinat lat/long menggunakan
 * Nominatim (OpenStreetMap) — gratis, tanpa API key.
 *
 * Rate limit Nominatim: maksimal 1 request/detik.
 * Untuk batch geocoding banyak data, tambahkan sleep(1) di antara request.
 */
class GeocodingService
{
    private const BASE_URL = 'https://nominatim.openstreetmap.org/search';

    /**
     * Geocode alamat menjadi [latitude, longitude].
     * Return null jika tidak ditemukan / gagal.
     */
    public function geocode(string $alamat): ?array
    {
        if (trim($alamat) === '') {
            return null;
        }

        try {
            $response = Http::withHeaders([
                    // Nominatim mewajibkan User-Agent yang jelas
                    'User-Agent' => 'QodhaGIS/1.0 (admin@qodha.id)',
                ])
                ->timeout(10)
                ->get(self::BASE_URL, [
                    'q'            => $alamat,
                    'format'       => 'json',
                    'limit'        => 1,
                    'countrycodes' => 'id,my', // Indonesia & Malaysia
                ]);

            if (! $response->successful()) {
                Log::warning('Geocoding gagal: HTTP ' . $response->status(), ['alamat' => $alamat]);
                return null;
            }

            $data = $response->json();

            if (empty($data) || !isset($data[0]['lat'], $data[0]['lon'])) {
                Log::info('Geocoding: alamat tidak ditemukan', ['alamat' => $alamat]);
                return null;
            }

            return [
                'latitude'  => round((float) $data[0]['lat'], 7),
                'longitude' => round((float) $data[0]['lon'], 7),
            ];
        } catch (\Throwable $e) {
            Log::error('Geocoding error: ' . $e->getMessage(), ['alamat' => $alamat]);
            return null;
        }
    }

    /**
     * Geocode dengan fallback: coba alamat lengkap dahulu,
     * jika gagal coba dengan kombinasi "wilayah, Indonesia" saja.
     */
    public function geocodeWithFallback(string $alamatLengkap, ?string $wilayah = null): ?array
    {
        $result = $this->geocode($alamatLengkap);

        if ($result === null && $wilayah) {
            // beri jeda sedikit agar tidak melanggar rate limit
            usleep(1100000); // 1.1 detik
            $result = $this->geocode($wilayah . ', Indonesia');
        }

        return $result;
    }
}
