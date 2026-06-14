@extends('layouts.admin')

@section('title', 'Data Mitra')

@section('content')

    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-6">
        <div>
            <p class="text-[11px] uppercase tracking-[0.3em] text-ink-400 font-medium">Manajemen</p>
            <h1 class="font-display font-bold text-2xl lg:text-3xl mt-2 text-white">Data Mitra</h1>
        </div>

        <a href="{{ route('admin.mitras.create') }}" class="inline-flex items-center gap-2 bg-white text-ink-900 font-display font-semibold text-sm px-4 py-2.5 rounded-md hover:bg-ink-100 transition-colors whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Tambah Mitra
        </a>
    </div>

    {{-- Search bar --}}
    <form method="GET" action="{{ route('admin.mitras.index') }}" class="mb-6 flex gap-2 max-w-md">
        <input
            type="text"
            name="q"
            value="{{ request('q') }}"
            placeholder="Cari nama, toko, wilayah, atau alamat..."
            class="dark-input"
        >
        <button type="submit" class="px-4 py-2 border border-ink-600 rounded-md text-sm text-ink-200 hover:text-white hover:border-ink-400 transition-colors whitespace-nowrap">
            Cari
        </button>
        @if (request('q'))
            <a href="{{ route('admin.mitras.index') }}" class="px-4 py-2 border border-ink-700 rounded-md text-sm text-ink-400 hover:text-white transition-colors whitespace-nowrap">
                Reset
            </a>
        @endif
    </form>

    {{-- Table --}}
    <div class="border border-ink-700 rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-ink-800/70 text-left text-[11px] uppercase tracking-wider text-ink-400">
                        <th class="px-4 py-3">Nama Mitra</th>
                        <th class="px-4 py-3">Wilayah</th>
                        <th class="px-4 py-3">Kategori</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">No. HP</th>
                        <th class="px-4 py-3">Koordinat</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-700">
                    @forelse ($mitras as $mitra)
                        <tr class="hover:bg-ink-800/40 transition-colors">
                            <td class="px-4 py-3">
                                <div class="text-white font-medium">{{ $mitra->nama_mitra }}</div>
                                @if ($mitra->nama_toko)
                                    <div class="text-xs text-ink-400">{{ $mitra->nama_toko }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-ink-300">{{ $mitra->wilayah ?? '-' }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $kategoriClass = match($mitra->kategori) {
                                        'Super Distributor' => 'badge-sd',
                                        'Distributor' => 'badge-dist',
                                        'Reseller' => 'badge-res',
                                        'Agen' => 'badge-agen',
                                        default => 'badge-res',
                                    };
                                @endphp
                                <span class="{{ $kategoriClass }} text-[10px] px-2 py-0.5 rounded uppercase tracking-wider">
                                    {{ $mitra->kategori }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @if ($mitra->status === 'Aktif')
                                    <span class="badge-aktif text-[10px] px-2 py-0.5 rounded uppercase tracking-wider">Aktif</span>
                                @else
                                    <span class="badge-nonaktif text-[10px] px-2 py-0.5 rounded uppercase tracking-wider">Non Aktif</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-ink-300 whitespace-nowrap">{{ $mitra->no_hp ?? '-' }}</td>
                            <td class="px-4 py-3 text-ink-400 text-xs whitespace-nowrap">
                                @if ($mitra->latitude && $mitra->longitude)
                                    {{ number_format($mitra->latitude, 4) }}, {{ number_format($mitra->longitude, 4) }}
                                @else
                                    <span class="text-ink-500">Belum ada</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <a href="{{ route('admin.mitras.edit', $mitra) }}" class="text-xs border border-ink-600 rounded px-2.5 py-1 text-ink-200 hover:text-white hover:border-ink-400 transition-colors inline-block">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.mitras.destroy', $mitra) }}" class="inline-block ml-1.5" onsubmit="return confirm('Hapus mitra &quot;{{ $mitra->nama_mitra }}&quot;? Tindakan ini tidak dapat dibatalkan.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs border border-ink-700 rounded px-2.5 py-1 text-ink-400 hover:text-white hover:border-ink-400 transition-colors">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-ink-500 text-sm">
                                @if (request('q'))
                                    Tidak ada mitra yang cocok dengan pencarian "<span class="text-ink-300">{{ request('q') }}</span>".
                                @else
                                    Belum ada data mitra.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $mitras->links() }}
    </div>

@endsection
