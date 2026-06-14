<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mitra extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_mitra',
        'nama_toko',
        'no_hp',
        'kategori',
        'status',
        'wilayah',
        'alamat_lengkap',
        'latitude',
        'longitude',
        'keterangan',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'latitude'  => 'float',
        'longitude' => 'float',
    ];

    // ================================================================
    // RELASI
    // ================================================================

    /**
     * Relasi: Admin yang membuat data mitra ini.
     * (mitras.created_by -> admins.id)
     *
     * Bisa NULL untuk data hasil seeding awal (import GeoJSON/Excel)
     * yang tidak dibuat melalui form admin.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    /**
     * Relasi: Admin yang terakhir mengubah data mitra ini.
     * (mitras.updated_by -> admins.id)
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    // ================================================================
    // SCOPES
    // ================================================================

    public function scopeAktif($query)
    {
        return $query->where('status', 'Aktif');
    }

    public function scopeByWilayah($query, $wilayah)
    {
        return $query->where('wilayah', $wilayah);
    }

    public function scopeByKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('nama_mitra', 'like', "%{$keyword}%")
              ->orWhere('nama_toko', 'like', "%{$keyword}%")
              ->orWhere('wilayah', 'like', "%{$keyword}%")
              ->orWhere('alamat_lengkap', 'like', "%{$keyword}%");
        });
    }

    public function toGeoJsonFeature(): array
    {
        return [
            'type'       => 'Feature',
            'geometry'   => [
                'type'        => 'Point',
                'coordinates' => [(float) $this->longitude, (float) $this->latitude],
            ],
            'properties' => [
                'id'             => $this->id,
                'nama_mitra'     => $this->nama_mitra,
                'nama_toko'      => $this->nama_toko,
                'no_hp'          => $this->no_hp,
                'kategori'       => $this->kategori,
                'status'         => $this->status,
                'wilayah'        => $this->wilayah,
                'alamat_lengkap' => $this->alamat_lengkap,
                'keterangan'     => $this->keterangan,
            ],
        ];
    }
}
