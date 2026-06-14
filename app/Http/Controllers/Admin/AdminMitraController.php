<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mitra;
use App\Services\GeocodingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminMitraController extends Controller
{
    public function __construct(
        private readonly GeocodingService $geocodingService
    ) {}

    /**
     * List semua mitra dengan pencarian sederhana.
     * Eager-load relasi creator/updater agar tidak N+1 query
     * saat ditampilkan di tabel index.
     */
    public function index(Request $request): View
    {
        $query = Mitra::query()
            ->with(['creator', 'updater'])
            ->orderBy('nama_mitra');

        if ($request->filled('q')) {
            $query->search(trim($request->input('q')));
        }

        $mitras = $query->paginate(20)->withQueryString();

        return view('admin.mitras.index', compact('mitras'));
    }

    /**
     * Form tambah mitra baru.
     */
    public function create(): View
    {
        $kategoris = ['Super Distributor', 'Distributor', 'Reseller', 'Agen'];
        $statuses  = ['Aktif', 'Non Aktif'];

        return view('admin.mitras.form', [
            'mitra'     => new Mitra(),
            'kategoris' => $kategoris,
            'statuses'  => $statuses,
            'isEdit'    => false,
        ]);
    }

    /**
     * Simpan mitra baru.
     * - Geocoding otomatis jika lat/long kosong.
     * - created_by & updated_by diisi otomatis dari admin yang login.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateRequest($request);
        $validated = $this->autoGeocode($validated);

        $adminId = Auth::id();
        $validated['created_by'] = $adminId;
        $validated['updated_by'] = $adminId;

        Mitra::create($validated);

        return redirect()
            ->route('admin.mitras.index')
            ->with('status', 'Mitra baru berhasil ditambahkan.');
    }

    /**
     * Form edit mitra.
     */
    public function edit(Mitra $mitra): View
    {
        $kategoris = ['Super Distributor', 'Distributor', 'Reseller', 'Agen'];
        $statuses  = ['Aktif', 'Non Aktif'];

        return view('admin.mitras.form', [
            'mitra'     => $mitra,
            'kategoris' => $kategoris,
            'statuses'  => $statuses,
            'isEdit'    => true,
        ]);
    }

    /**
     * Update data mitra.
     * - Geocoding ulang opsional.
     * - updated_by selalu diisi ulang dengan admin yang login saat ini
     *   (mencatat siapa TERAKHIR mengubah data ini).
     */
    public function update(Request $request, Mitra $mitra): RedirectResponse
    {
        $validated = $this->validateRequest($request);

        $forceGeocode = $request->boolean('geocode_ulang');

        if ($forceGeocode || empty($validated['latitude']) || empty($validated['longitude'])) {
            $validated = $this->autoGeocode($validated, force: $forceGeocode);
        }

        $validated['updated_by'] = Auth::id();

        $mitra->update($validated);

        return redirect()
            ->route('admin.mitras.index')
            ->with('status', 'Data mitra berhasil diperbarui.');
    }

    /**
     * Hapus mitra.
     */
    public function destroy(Mitra $mitra): RedirectResponse
    {
        $mitra->delete();

        return redirect()
            ->route('admin.mitras.index')
            ->with('status', 'Mitra berhasil dihapus.');
    }

    /**
     * Validasi input form mitra.
     */
    private function validateRequest(Request $request): array
    {
        return $request->validate([
            'nama_mitra'     => ['required', 'string', 'max:255'],
            'nama_toko'      => ['nullable', 'string', 'max:255'],
            'no_hp'          => ['nullable', 'string', 'max:50'],
            'kategori'       => ['required', 'in:Super Distributor,Distributor,Reseller,Agen'],
            'status'         => ['required', 'in:Aktif,Non Aktif'],
            'wilayah'        => ['nullable', 'string', 'max:255'],
            'alamat_lengkap' => ['nullable', 'string'],
            'latitude'       => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'      => ['nullable', 'numeric', 'between:-180,180'],
            'keterangan'     => ['nullable', 'string'],
        ]);
    }

    /**
     * Jalankan geocoding otomatis jika lat/long kosong (atau dipaksa).
     */
    private function autoGeocode(array $data, bool $force = false): array
    {
        $needsGeocode = $force || empty($data['latitude']) || empty($data['longitude']);

        if (! $needsGeocode) {
            return $data;
        }

        $alamat = trim((string) ($data['alamat_lengkap'] ?? ''));

        if ($alamat === '' && empty($data['wilayah'])) {
            return $data;
        }

        $coords = $this->geocodingService->geocodeWithFallback(
            $alamat !== '' ? $alamat : ($data['wilayah'] . ', Indonesia'),
            $data['wilayah'] ?? null
        );

        if ($coords) {
            $data['latitude']  = $coords['latitude'];
            $data['longitude'] = $coords['longitude'];
        }

        return $data;
    }
}
