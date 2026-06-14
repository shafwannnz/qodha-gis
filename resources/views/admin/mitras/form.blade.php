@extends('layouts.admin')

@section('title', $isEdit ? 'Edit Mitra' : 'Tambah Mitra')

@section('content')

    <div class="mb-6">
        <p class="text-[11px] uppercase tracking-[0.3em] text-ink-400 font-medium">
            {{ $isEdit ? 'Edit Data' : 'Tambah Data' }}
        </p>
        <h1 class="font-display font-bold text-2xl lg:text-3xl mt-2 text-white">
            {{ $isEdit ? 'Edit Mitra' : 'Tambah Mitra Baru' }}
        </h1>
    </div>

    <form method="POST" action="{{ $isEdit ? route('admin.mitras.update', $mitra) : route('admin.mitras.store') }}" class="max-w-3xl">
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        <div class="border border-ink-700 bg-ink-800/50 rounded-lg p-5 sm:p-6 space-y-5">

            {{-- Nama Mitra & Nama Toko --}}
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label for="nama_mitra" class="block text-[11px] uppercase tracking-wider text-ink-400 mb-1.5">
                        Nama Mitra <span class="text-ink-500">*</span>
                    </label>
                    <input type="text" name="nama_mitra" id="nama_mitra" value="{{ old('nama_mitra', $mitra->nama_mitra) }}" class="dark-input" required placeholder="Contoh: Pak Azmi">
                </div>
                <div>
                    <label for="nama_toko" class="block text-[11px] uppercase tracking-wider text-ink-400 mb-1.5">
                        Nama Toko
                    </label>
                    <input type="text" name="nama_toko" id="nama_toko" value="{{ old('nama_toko', $mitra->nama_toko) }}" class="dark-input" placeholder="Contoh: Abdul Parfume">
                </div>
            </div>

            {{-- No HP & Kategori --}}
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label for="no_hp" class="block text-[11px] uppercase tracking-wider text-ink-400 mb-1.5">
                        No. HP / WhatsApp
                    </label>
                    <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp', $mitra->no_hp) }}" class="dark-input" placeholder="0812-xxxx-xxxx">
                </div>
                <div>
                    <label for="kategori" class="block text-[11px] uppercase tracking-wider text-ink-400 mb-1.5">
                        Kategori Mitra <span class="text-ink-500">*</span>
                    </label>
                    <select name="kategori" id="kategori" class="dark-input" required>
                        @foreach ($kategoris as $k)
                            <option value="{{ $k }}" {{ old('kategori', $mitra->kategori) === $k ? 'selected' : '' }}>{{ $k }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Status & Wilayah --}}
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label for="status" class="block text-[11px] uppercase tracking-wider text-ink-400 mb-1.5">
                        Status Mitra <span class="text-ink-500">*</span>
                    </label>
                    <select name="status" id="status" class="dark-input" required>
                        @foreach ($statuses as $s)
                            <option value="{{ $s }}" {{ old('status', $mitra->status) === $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="wilayah" class="block text-[11px] uppercase tracking-wider text-ink-400 mb-1.5">
                        Wilayah
                    </label>
                    <input type="text" name="wilayah" id="wilayah" value="{{ old('wilayah', $mitra->wilayah) }}" class="dark-input" placeholder="Contoh: Bogor">
                </div>
            </div>

            {{-- Alamat Lengkap --}}
            <div>
                <label for="alamat_lengkap" class="block text-[11px] uppercase tracking-wider text-ink-400 mb-1.5">
                    Alamat Lengkap
                </label>
                <textarea name="alamat_lengkap" id="alamat_lengkap" rows="3" class="dark-input" placeholder="Alamat selengkap mungkin agar geocoding lebih akurat...">{{ old('alamat_lengkap', $mitra->alamat_lengkap) }}</textarea>
            </div>

            {{-- Koordinat --}}
            <div class="border border-ink-700 rounded-lg p-4">
                <div class="flex items-center justify-between mb-3">
                    <label class="block text-[11px] uppercase tracking-wider text-ink-400">
                        Koordinat (Latitude / Longitude)
                    </label>
                    @if ($isEdit)
                        <label class="flex items-center gap-2 text-xs text-ink-300 cursor-pointer">
                            <input type="checkbox" name="geocode_ulang" value="1" class="rounded border-ink-600 bg-ink-900 text-white focus:ring-0">
                            Geocode ulang dari alamat
                        </label>
                    @endif
                </div>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <input type="number" step="0.0000001" name="latitude" id="latitude" value="{{ old('latitude', $mitra->latitude) }}" class="dark-input" placeholder="Latitude (contoh: -6.5971)">
                    </div>
                    <div>
                        <input type="number" step="0.0000001" name="longitude" id="longitude" value="{{ old('longitude', $mitra->longitude) }}" class="dark-input" placeholder="Longitude (contoh: 106.8060)">
                    </div>
                </div>
                <p class="text-xs text-ink-500 mt-2">
                    @if ($isEdit)
                        Kosongkan kedua field, atau centang "Geocode ulang dari alamat" di atas, untuk mengambil
                        koordinat baru otomatis dari Alamat Lengkap menggunakan Nominatim (OpenStreetMap).
                    @else
                        Jika dikosongkan, koordinat akan diambil otomatis dari Alamat Lengkap (atau Wilayah)
                        menggunakan geocoding Nominatim (OpenStreetMap).
                    @endif
                </p>
            </div>

            {{-- Keterangan --}}
            <div>
                <label for="keterangan" class="block text-[11px] uppercase tracking-wider text-ink-400 mb-1.5">
                    Keterangan Tambahan
                </label>
                <textarea name="keterangan" id="keterangan" rows="2" class="dark-input" placeholder="Catatan tambahan (opsional)...">{{ old('keterangan', $mitra->keterangan) }}</textarea>
            </div>

        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-3 mt-6">
            <button type="submit" class="bg-white text-ink-900 font-display font-semibold text-sm px-5 py-2.5 rounded-md hover:bg-ink-100 transition-colors">
                {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Mitra' }}
            </button>
            <a href="{{ route('admin.mitras.index') }}" class="border border-ink-600 text-ink-200 font-display font-semibold text-sm px-5 py-2.5 rounded-md hover:text-white hover:border-ink-400 transition-colors">
                Batal
            </a>
        </div>
    </form>

@endsection
