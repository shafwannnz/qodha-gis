<?php

namespace Database\Seeders;

use App\Models\Mitra;
use Illuminate\Database\Seeder;

class MitraSeeder extends Seeder
{
    public function run(): void
    {
        $path     = public_path('geojson/mitra.geojson');
        $contents = file_get_contents($path);
        $geojson  = json_decode($contents, true);

        if (! $geojson || ! isset($geojson['features'])) {
            $this->command->error('GeoJSON file not found or invalid.');
            return;
        }

        Mitra::truncate();

        $count = 0;
        foreach ($geojson['features'] as $feature) {
            $props  = $feature['properties'];
            $coords = $feature['geometry']['coordinates'] ?? [null, null];

            Mitra::create([
                'nama_mitra'     => $props['nama_mitra']     ?? '',
                'nama_toko'      => $props['nama_toko']      ?? null,
                'no_hp'          => $props['no_hp']          ?? null,
                'kategori'       => $props['kategori']       ?? 'Reseller',
                'status'         => $props['status']         ?? 'Aktif',
                'wilayah'        => $props['wilayah']        ?? null,
                'alamat_lengkap' => $props['alamat_lengkap'] ?? null,
                'latitude'       => $coords[1]               ?? null,
                'longitude'      => $coords[0]               ?? null,
                'keterangan'     => $props['keterangan']     ?? null,
            ]);
            $count++;
        }

        $this->command->info("Seeded {$count} mitra records.");
    }
}
