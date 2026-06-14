{{--
    Komponen Peta Leaflet
--}}

<div class="border border-ink-700 bg-ink-800/50 rounded-lg overflow-hidden">
    <div class="flex items-center justify-between px-4 lg:px-5 py-3 border-b border-ink-700">
        <h3 class="font-display font-semibold text-sm uppercase tracking-widest text-ink-200">
            Peta Persebaran Mitra
        </h3>
        <div class="flex items-center gap-2">
            <span id="map-loading" class="text-[11px] text-ink-400 hidden">Memuat...</span>
            <button id="fit-bounds-btn" type="button"
                class="text-[11px] px-2.5 py-1 border border-ink-600 rounded text-ink-300 hover:text-white hover:border-ink-400 transition-colors">
                Reset Tampilan
            </button>
        </div>
    </div>

    <div id="map" class="w-full h-[400px] sm:h-[500px] lg:h-[640px]"></div>
</div>
