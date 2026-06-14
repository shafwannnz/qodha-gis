/**
 * leaflet-map.js
 *
 * Inisialisasi peta Leaflet, marker clustering, heatmap, choropleth,
 * analisis radius (Turf.js), lokasi pengguna & pencarian mitra terdekat,
 * serta navigasi WhatsApp/Google Maps dari popup.
 *
 * PERUBAHAN UNTUK MAP GATE:
 * Seluruh logic sebelumnya yang langsung jalan saat file di-load
 * (IIFE auto-run) sekarang dibungkus dalam fungsi `init()` yang
 * diekspos via window.QodhaMap.init(). Ini dipanggil oleh
 * map-gate.js HANYA setelah user klik "Cek Lokasi Mitra Sekarang"
 * dan #map-panel sudah terlihat (Leaflet butuh container dengan
 * dimensi non-zero saat di-instantiate).
 *
 * init() dijaga agar hanya berjalan SEKALI (idempotent) meskipun
 * dipanggil berulang.
 */

(function () {
    'use strict';

    let map = null;
    let markerClusterGroup = null;
    let heatLayer = null;
    let choroplethLayer = null;
    let radiusLayer = null;
    let userMarker = null;
    let userAccuracyCircle = null;
    let currentGeoJsonData = null;
    let initialized = false;

    // ----------------------------------------------------------------
    // Custom marker icon (monokrom: hitam/putih)
    // ----------------------------------------------------------------
    function createIcon(status) {
        const isAktif = status === 'Aktif';
        const fill = isAktif ? '#ffffff' : '#555555';
        const stroke = isAktif ? '#0a0a0a' : '#222222';

        const svg = `
            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="34" viewBox="0 0 26 34">
                <path d="M13 0C5.8 0 0 5.8 0 13c0 9.5 13 21 13 21s13-11.5 13-21C26 5.8 20.2 0 13 0z"
                      fill="${fill}" stroke="${stroke}" stroke-width="1.5"/>
                <circle cx="13" cy="13" r="5" fill="${stroke}"/>
            </svg>`;

        return L.divIcon({
            html: svg,
            className: 'qodha-marker',
            iconSize: [26, 34],
            iconAnchor: [13, 34],
            popupAnchor: [0, -32],
        });
    }

    function kategoriBadgeClass(kategori) {
        switch (kategori) {
            case 'Super Distributor': return 'badge-sd';
            case 'Distributor': return 'badge-dist';
            case 'Reseller': return 'badge-res';
            case 'Agen': return 'badge-agen';
            default: return 'badge-res';
        }
    }

    function escapeHtml(str) {
        if (!str) return '';
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    function buildWhatsAppLink(noHp) {
        if (!noHp) return null;
        let primary = noHp.split('/')[0].trim();
        let digits = primary.replace(/[^0-9]/g, '');
        if (!digits) return null;
        if (digits.startsWith('0')) {
            digits = '62' + digits.substring(1);
        }
        return `https://wa.me/${digits}`;
    }

    function buildGoogleMapsLink(props) {
        const query = props.alamat_lengkap || props.nama_mitra || props.wilayah || '';
        return `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(query)}`;
    }

    function buildPopupContent(props) {
        const statusBadge = props.status === 'Aktif'
            ? '<span class="badge-aktif text-[10px] px-2 py-0.5 rounded uppercase tracking-wider">Aktif</span>'
            : '<span class="badge-nonaktif text-[10px] px-2 py-0.5 rounded uppercase tracking-wider">Non Aktif</span>';

        const kategoriBadge = `<span class="${kategoriBadgeClass(props.kategori)} text-[10px] px-2 py-0.5 rounded uppercase tracking-wider">${escapeHtml(props.kategori || '-')}</span>`;

        const namaToko = props.nama_toko ? `<div class="text-xs text-ink-300 mt-0.5">${escapeHtml(props.nama_toko)}</div>` : '';

        const noHp = props.no_hp ? `
            <div class="flex items-center gap-1.5 mt-2 text-xs text-ink-200">
                <svg class="w-3.5 h-3.5 text-ink-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                </svg>
                ${escapeHtml(props.no_hp)}
            </div>` : '';

        const keterangan = props.keterangan ? `
            <div class="mt-2 pt-2 border-t border-ink-700 text-xs text-ink-400 italic">
                ${escapeHtml(props.keterangan)}
            </div>` : '';

        const waLink = buildWhatsAppLink(props.no_hp);
        const mapsLink = buildGoogleMapsLink(props);

        const actionButtons = `
            <div class="flex gap-2 mt-3 pt-2 border-t border-ink-700">
                ${waLink ? `
                <a href="${waLink}" target="_blank" rel="noopener noreferrer"
                   class="flex-1 text-center text-[11px] py-1.5 rounded border border-ink-600 text-ink-200 hover:bg-white hover:text-ink-900 transition-colors">
                    WhatsApp
                </a>` : ''}
                <a href="${mapsLink}" target="_blank" rel="noopener noreferrer"
                   class="flex-1 text-center text-[11px] py-1.5 rounded border border-ink-600 text-ink-200 hover:bg-white hover:text-ink-900 transition-colors">
                    Google Maps
                </a>
            </div>
        `;

        const radiusButton = props.kategori === 'Super Distributor' ? `
            <button class="qodha-radius-btn w-full mt-2 text-[11px] py-1.5 rounded border border-ink-600 text-ink-300 hover:text-white hover:border-ink-400 transition-colors">
                Tampilkan Radius Jangkauan (50km)
            </button>
        ` : '';

        return `
            <div class="font-body min-w-[220px] max-w-[280px]">
                <div class="font-display font-semibold text-sm text-white leading-tight">
                    ${escapeHtml(props.nama_mitra || '-')}
                </div>
                ${namaToko}
                <div class="flex flex-wrap gap-1.5 mt-2">
                    ${statusBadge}
                    ${kategoriBadge}
                </div>
                <div class="mt-2 text-xs text-ink-300 leading-relaxed">
                    <strong class="text-ink-200">Wilayah:</strong> ${escapeHtml(props.wilayah || '-')}<br/>
                    <strong class="text-ink-200">Alamat:</strong> ${escapeHtml(props.alamat_lengkap || '-')}
                </div>
                ${noHp}
                ${keterangan}
                ${actionButtons}
                ${radiusButton}
            </div>
        `;
    }

    function renderGeoJson(geojson) {
        currentGeoJsonData = geojson;
        markerClusterGroup.clearLayers();

        const layer = L.geoJSON(geojson, {
            pointToLayer: (feature, latlng) => {
                return L.marker(latlng, {
                    icon: createIcon(feature.properties.status),
                });
            },
            onEachFeature: (feature, layer) => {
                layer.bindPopup(buildPopupContent(feature.properties));

                layer.on('popupopen', (e) => {
                    const popupEl = e.popup.getElement();
                    const radiusBtn = popupEl?.querySelector('.qodha-radius-btn');
                    if (radiusBtn) {
                        radiusBtn.addEventListener('click', () => {
                            showRadiusBuffer(layer.getLatLng(), 50);
                        });
                    }
                });
            },
        });

        markerClusterGroup.addLayer(layer);

        const featureCount = geojson.features ? geojson.features.length : 0;
        if (featureCount > 0) {
            const bounds = layer.getBounds();
            if (bounds.isValid()) {
                map.fitBounds(bounds, { padding: [40, 40], maxZoom: 12 });
            }
        }

        if (heatLayer) {
            map.removeLayer(heatLayer);
            heatLayer = null;
        }
        if (document.getElementById('toggle-heatmap')?.checked) {
            buildAndShowHeatmap(geojson);
        }

        window.dispatchEvent(new CustomEvent('qodha:data-loaded', {
            detail: { count: featureCount },
        }));

        return featureCount;
    }

    async function loadData(filters = {}) {
        const loadingEl = document.getElementById('map-loading');
        if (loadingEl) loadingEl.classList.remove('hidden');

        try {
            const params = new URLSearchParams();
            Object.entries(filters).forEach(([key, value]) => {
                if (value) params.append(key, value);
            });

            const url = window.QODHA_CONFIG.geojsonUrl + (params.toString() ? '?' + params.toString() : '');
            const response = await fetch(url, {
                headers: { 'Accept': 'application/json' },
            });

            if (!response.ok) throw new Error('Gagal memuat data peta');

            const geojson = await response.json();
            renderGeoJson(geojson);
        } catch (err) {
            console.error('[Qodha GIS] Error loading map data:', err);
        } finally {
            if (loadingEl) loadingEl.classList.add('hidden');
        }
    }

    function buildAndShowHeatmap(geojson) {
        if (!geojson || !geojson.features || geojson.features.length === 0) return;

        const points = geojson.features.map(f => [
            f.geometry.coordinates[1],
            f.geometry.coordinates[0],
            0.5,
        ]);

        heatLayer = L.heatLayer(points, {
            radius: 25,
            blur: 20,
            maxZoom: 10,
            gradient: { 0.2: '#333333', 0.5: '#888888', 1.0: '#ffffff' },
        });

        heatLayer.addTo(map);
    }

    function getChoroplethColor(count, max) {
        if (!count || count <= 0) return '#1a1a1a';
        const ratio = Math.min(count / max, 1);
        const shade = Math.round(34 + ratio * (255 - 34));
        return `rgb(${shade},${shade},${shade})`;
    }

    async function loadChoropleth() {
        try {
            const [geojsonRes, countsRes] = await Promise.all([
                fetch(window.QODHA_CONFIG.choroplethGeoJsonUrl),
                fetch(window.QODHA_CONFIG.wilayahCountsUrl),
            ]);

            if (!geojsonRes.ok) {
                console.warn('[Qodha GIS] File batas-wilayah.geojson belum tersedia di public/geojson/');
                return;
            }

            const geojson = await geojsonRes.json();
            const counts = await countsRes.json();

            const max = Math.max(...Object.values(counts), 1);

            choroplethLayer = L.geoJSON(geojson, {
                style: (feature) => {
                    const name = feature.properties.NAME_1 || feature.properties.name || feature.properties.WADMPR || '';
                    const count = counts[name] || 0;
                    return {
                        fillColor: getChoroplethColor(count, max),
                        fillOpacity: 0.35,
                        color: '#444444',
                        weight: 1,
                    };
                },
                onEachFeature: (feature, layer) => {
                    const name = feature.properties.NAME_1 || feature.properties.name || feature.properties.WADMPR || 'Wilayah';
                    const count = counts[name] || 0;
                    layer.bindTooltip(`${escapeHtml(name)}: ${count} mitra`, { sticky: true });
                },
            });

            choroplethLayer.addTo(map);
        } catch (err) {
            console.error('[Qodha GIS] Error loading choropleth:', err);
        }
    }

    function showRadiusBuffer(latlng, radiusKm = 50) {
        if (typeof turf === 'undefined') {
            console.warn('[Qodha GIS] Turf.js belum termuat.');
            return;
        }

        if (radiusLayer) {
            map.removeLayer(radiusLayer);
            radiusLayer = null;
        }

        const center = turf.point([latlng.lng, latlng.lat]);
        const buffered = turf.buffer(center, radiusKm, { units: 'kilometers' });

        radiusLayer = L.geoJSON(buffered, {
            style: { color: '#ffffff', weight: 1.5, dashArray: '4', fillOpacity: 0.05, fillColor: '#ffffff' },
        }).addTo(map);

        map.fitBounds(radiusLayer.getBounds(), { padding: [30, 30] });
    }

    function showUserLocation(latlng, accuracy) {
        if (userMarker) map.removeLayer(userMarker);
        if (userAccuracyCircle) map.removeLayer(userAccuracyCircle);

        userMarker = L.marker(latlng, {
            icon: L.divIcon({
                html: `<div class="user-location-pulse" style="width:14px;height:14px;background:#fff;border:2px solid #000;border-radius:50%;"></div>`,
                className: '',
                iconSize: [14, 14],
                iconAnchor: [7, 7],
            }),
        }).addTo(map).bindPopup('Lokasi Anda saat ini');

        if (accuracy) {
            userAccuracyCircle = L.circle(latlng, {
                radius: accuracy,
                color: '#888',
                fillColor: '#888',
                fillOpacity: 0.08,
                weight: 1,
            }).addTo(map);
        }
    }

    function findNearestMitra(userLatLng) {
        if (typeof turf === 'undefined' || !currentGeoJsonData) return null;

        const userPoint = turf.point([userLatLng.lng, userLatLng.lat]);
        let nearest = null;
        let minDist = Infinity;

        currentGeoJsonData.features.forEach((feature) => {
            const coords = feature.geometry.coordinates;
            const dist = turf.distance(userPoint, turf.point(coords), { units: 'kilometers' });
            if (dist < minDist) {
                minDist = dist;
                nearest = { feature, dist };
            }
        });

        return nearest;
    }

    function locateUser() {
        const label = document.getElementById('locate-me-label');
        const resultBox = document.getElementById('nearest-result');

        if (!navigator.geolocation) {
            alert('Geolocation tidak didukung browser ini.');
            return;
        }

        if (label) label.textContent = 'Mencari lokasi...';

        navigator.geolocation.getCurrentPosition(
            (pos) => {
                const { latitude, longitude, accuracy } = pos.coords;
                const userLatLng = L.latLng(latitude, longitude);

                showUserLocation(userLatLng, accuracy);
                map.setView(userLatLng, 11);

                const nearest = findNearestMitra(userLatLng);

                if (label) label.textContent = 'Gunakan Lokasi Saya';

                if (nearest && resultBox) {
                    const props = nearest.feature.properties;
                    resultBox.classList.remove('hidden');
                    resultBox.innerHTML = `
                        Mitra terdekat: <strong class="text-white">${escapeHtml(props.nama_mitra)}</strong><br/>
                        Jarak: <strong class="text-white">${nearest.dist.toFixed(1)} km</strong><br/>
                        Wilayah: ${escapeHtml(props.wilayah || '-')}
                    `;

                    markerClusterGroup.eachLayer((layer) => {
                        if (layer.feature && layer.feature.properties.id === props.id) {
                            markerClusterGroup.zoomToShowLayer(layer, () => layer.openPopup());
                        }
                    });
                }
            },
            () => {
                if (label) label.textContent = 'Gunakan Lokasi Saya';
                alert('Gagal mengambil lokasi. Pastikan izin lokasi diaktifkan di browser Anda.');
            }
        );
    }

    // ----------------------------------------------------------------
    // INIT — dipanggil oleh map-gate.js setelah #map-panel terlihat
    // ----------------------------------------------------------------
    function init() {
        if (initialized) return;
        initialized = true;

        map = L.map('map', {
            zoomControl: true,
            scrollWheelZoom: true,
        }).setView([-2.5, 118], 5);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '&copy; OpenStreetMap contributors',
        }).addTo(map);

        markerClusterGroup = L.markerClusterGroup({
            showCoverageOnHover: false,
            spiderfyOnMaxZoom: true,
            maxClusterRadius: 50,
        });
        map.addLayer(markerClusterGroup);

        // Event listeners (di-attach sekali saat init, elemen sudah ada di DOM)
        document.getElementById('toggle-heatmap')?.addEventListener('change', (e) => {
            if (e.target.checked) {
                if (currentGeoJsonData) buildAndShowHeatmap(currentGeoJsonData);
            } else {
                if (heatLayer) {
                    map.removeLayer(heatLayer);
                    heatLayer = null;
                }
            }
        });

        document.getElementById('toggle-markers')?.addEventListener('change', (e) => {
            if (e.target.checked) {
                map.addLayer(markerClusterGroup);
            } else {
                map.removeLayer(markerClusterGroup);
            }
        });

        document.getElementById('toggle-choropleth')?.addEventListener('change', (e) => {
            if (e.target.checked) {
                loadChoropleth();
            } else {
                if (choroplethLayer) {
                    map.removeLayer(choroplethLayer);
                    choroplethLayer = null;
                }
            }
        });

        document.getElementById('locate-me-btn')?.addEventListener('click', locateUser);

        document.getElementById('fit-bounds-btn')?.addEventListener('click', () => {
            const bounds = markerClusterGroup.getBounds();
            if (bounds.isValid()) {
                map.fitBounds(bounds, { padding: [40, 40], maxZoom: 12 });
            } else {
                map.setView([-2.5, 118], 5);
            }

            if (radiusLayer) {
                map.removeLayer(radiusLayer);
                radiusLayer = null;
            }
        });

        // Muat data pertama kali
        loadData();

        // Pastikan Leaflet menghitung ukuran container dengan benar
        // (container baru saja berubah dari `hidden` ke visible)
        setTimeout(() => map.invalidateSize(), 100);
    }

    // ----------------------------------------------------------------
    // Expose API global
    // ----------------------------------------------------------------
    window.QodhaMap = {
        init,
        loadData,
        get map() { return map; },
    };
})();
