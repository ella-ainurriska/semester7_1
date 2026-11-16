<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard SIG & SPK Kemiskinan Pamekasan</title>
    <!-- Leaflet & Tailwind CDN -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <style>
        #map { height: 500px; width: 100%; border-radius: 0.75rem; }
    </style>
</head>
<body class="bg-slate-100 font-sans text-slate-800">

    <div class="flex min-h-screen">
        <!-- SIDEBAR NAVIGASI -->
        <aside class="w-64 bg-slate-900 text-white flex flex-col justify-between hidden md:flex shrink-0">
            <div>
                <div class="h-16 flex items-center gap-3 px-6 border-b border-slate-800 bg-slate-950">
                    <i class="fa-solid fa-map-location-dot text-indigo-400 text-xl"></i>
                    <div>
                        <h2 class="font-bold text-sm leading-none">SIG KEMISKINAN</h2>
                        <span class="text-[10px] text-slate-400">SPK TOPSIS Pamekasan</span>
                    </div>
                </div>

                <nav class="p-4 space-y-1.5 text-sm">
                    <p class="text-[11px] font-semibold text-slate-400 px-3 uppercase tracking-wider mb-2">Navigasi Utama</p>
                    <a href="#gis-section" class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-indigo-600 text-white font-medium shadow-sm">
                        <i class="fa-solid fa-earth-asia w-5"></i> Peta Spasial GIS
                    </a>
                    <a href="#ranking-section" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition">
                        <i class="fa-solid fa-ranking-star w-5"></i> Peringkat Prioritas
                    </a>
                    <a href="#topsis-matriks" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition">
                        <i class="fa-solid fa-calculator w-5"></i> Matriks TOPSIS
                    </a>

                    <p class="text-[11px] font-semibold text-slate-400 px-3 uppercase tracking-wider mt-6 mb-2">Manajemen Data</p>
                    <button onclick="alert('Fitur Kelola Kriteria')" class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-slate-300 hover:bg-slate-800 transition">
                        <span class="flex items-center gap-3"><i class="fa-solid fa-sliders w-5"></i> Bobot Kriteria</span>
                        <span class="text-xs bg-slate-800 px-2 py-0.5 rounded border border-slate-700">4</span>
                    </button>
                    <button onclick="alert('Fitur Input Nilai Kecamatan')" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-300 hover:bg-slate-800 transition">
                        <i class="fa-solid fa-database w-5"></i> Input Nilai Indikator
                    </button>
                </nav>
            </div>

            <div class="p-4 border-t border-slate-800 text-xs text-slate-400">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-indigo-500 text-white flex items-center justify-center font-bold">A</div>
                    <div>
                        <p class="font-semibold text-white leading-none">Admin Bappeda</p>
                        <span class="text-[10px]">Pamekasan, Madura</span>
                    </div>
                </div>
            </div>
        </aside>

        <!-- KONTEN UTAMA -->
        <main class="flex-1 flex flex-col min-w-0 overflow-y-auto">
            <!-- TOPBAR -->
            <header class="h-16 bg-white border-b border-slate-200 px-6 flex items-center justify-between sticky top-0 z-30 shadow-xs">
                <h1 class="text-base font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-chart-line text-indigo-600"></i> Dashboard Analisis Penentuan Prioritas Intervensi Kemiskinan
                </h1>
                <div class="flex items-center gap-3">
                    <button onclick="location.reload()" class="flex items-center gap-2 px-3 py-1.5 text-xs font-semibold rounded-lg border border-slate-300 bg-white hover:bg-slate-50 transition">
                        <i class="fa-solid fa-rotate text-slate-500"></i> Hitung Ulang
                    </button>
                    <button onclick="window.print()" class="flex items-center gap-2 px-3.5 py-1.5 text-xs font-semibold rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 shadow-sm transition">
                        <i class="fa-solid fa-file-pdf"></i> Cetak Laporan
                    </button>
                </div>
            </header>

            <div class="p-6 space-y-6 max-w-7xl w-full mx-auto">
                <!-- STATISTIC CARDS -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-slate-500">Total Kecamatan</p>
                            <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ count($data) }}</h3>
                        </div>
                        <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-map"></i>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-slate-500">Prioritas Tinggi (Darurat)</p>
                            <h3 class="text-2xl font-bold text-red-600 mt-1">
                                {{ collect($data)->where('kategori', 'Tinggi')->count() }}
                            </h3>
                        </div>
                        <div class="w-10 h-10 rounded-lg bg-red-50 text-red-600 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-slate-500">Prioritas Sedang</p>
                            <h3 class="text-2xl font-bold text-amber-500 mt-1">
                                {{ collect($data)->where('kategori', 'Sedang')->count() }}
                            </h3>
                        </div>
                        <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-circle-exclamation"></i>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-slate-500">Prioritas Rendah</p>
                            <h3 class="text-2xl font-bold text-green-600 mt-1">
                                {{ collect($data)->where('kategori', 'Rendah')->count() }}
                            </h3>
                        </div>
                        <div class="w-10 h-10 rounded-lg bg-green-50 text-green-600 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-shield-check"></i>
                        </div>
                    </div>
                </div>

                <!-- GIS MAP & LEADERBOARD (SPLIT 65% / 35%) -->
                <div id="gis-section" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Leaflet Card (2 Kolom) -->
                    <div class="lg:col-span-2 bg-white p-4 rounded-xl border border-slate-200 shadow-xs flex flex-col justify-between">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <h2 class="font-bold text-sm text-slate-800">Visualisasi Peta Tematik Kemiskinan</h2>
                                <p class="text-xs text-slate-500">Sebaran spasial berdasarkan preferensi TOPSIS</p>
                            </div>
                            <div class="flex gap-2 text-xs">
                                <button class="px-2.5 py-1 rounded bg-slate-100 hover:bg-slate-200 font-medium">Batas Adm</button>
                                <button class="px-2.5 py-1 rounded bg-indigo-50 text-indigo-600 font-medium border border-indigo-200">Point Centroid</button>
                            </div>
                        </div>

                        <div class="relative">
                            <div id="map"></div>
                            <!-- LEGENDA FLOATING -->
                            <div class="absolute bottom-4 left-4 z-[1000] bg-white/95 backdrop-blur p-3 rounded-lg shadow-md border border-slate-200 text-xs">
                                <p class="font-bold text-slate-700 mb-1.5">Klasifikasi Prioritas:</p>
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2"><span class="w-3 h-3 bg-red-600 rounded-full inline-block"></span> Tinggi ($V_i \ge 0.66$)</div>
                                    <div class="flex items-center gap-2"><span class="w-3 h-3 bg-amber-500 rounded-full inline-block"></span> Sedang ($0.33 \le V_i < 0.66$)</div>
                                    <div class="flex items-center gap-2"><span class="w-3 h-3 bg-green-600 rounded-full inline-block"></span> Rendah ($V_i < 0.33$)</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- LEADERBOARD RANKING (1 Kolom) -->
                    <div id="ranking-section" class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs flex flex-col">
                        <div class="flex items-center justify-between mb-3 border-b border-slate-100 pb-2">
                            <h2 class="font-bold text-sm text-slate-800">Peringkat Intervensi</h2>
                            <span class="text-[11px] text-slate-400">Urutan Prioritas 1 s/d {{ count($data) }}</span>
                        </div>

                        <div class="space-y-2.5 overflow-y-auto max-h-[440px] pr-1">
                            @foreach ($data as $item)
                                @php
                                    $border = $item['kategori'] === 'Tinggi' ? 'border-red-500 bg-red-50/40 text-red-800' :
                                              ($item['kategori'] === 'Sedang' ? 'border-amber-500 bg-amber-50/40 text-amber-800' :
                                              'border-green-500 bg-green-50/40 text-green-800');
                                    $pill = $item['kategori'] === 'Tinggi' ? 'bg-red-600' :
                                            ($item['kategori'] === 'Sedang' ? 'bg-amber-500' : 'bg-green-600');
                                @endphp
                                <div class="p-3 border-l-4 rounded-lg border {{ $border }} flex items-center justify-between">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-sm">#{{ $item['peringkat'] }} Kec. {{ $item['nama'] }}</span>
                                        </div>
                                        <div class="text-[11px] text-slate-500 mt-0.5">
                                            D+ : {{ $item['d_plus'] }} | D- : {{ $item['d_minus'] }}
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-xs font-bold text-white px-2 py-0.5 rounded-full {{ $pill }}">
                                            {{ $item['kategori'] }}
                                        </span>
                                        <p class="text-xs font-semibold text-slate-700 mt-1">V: {{ $item['preferensi'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- TABEL DATA / MATRIKS AKADEMIS (TRANSPARANSI METODE) -->
                <div id="topsis-matriks" class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h2 class="font-bold text-sm text-slate-800">Tabel Hasil Perhitungan Matriks Preferensi (TOPSIS)</h2>
                            <p class="text-xs text-slate-500">Transparansi nilai jarak ideal positif ($D^+$), ideal negatif ($D^-$), dan nilai akhir ($V_i$)</p>
                        </div>
                        <button onclick="alert('Membuka form Tambah Data Alternatif')" class="px-3 py-1.5 bg-slate-900 text-white rounded-lg text-xs font-medium hover:bg-slate-800 flex items-center gap-1.5">
                            <i class="fa-solid fa-plus"></i> Tambah Data Wilayah
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs border border-slate-200 rounded-lg">
                            <thead class="bg-slate-50 text-slate-600 uppercase text-[10px] tracking-wider border-b">
                                <tr>
                                    <th class="p-3">Rank</th>
                                    <th class="p-3">Kecamatan</th>
                                    <th class="p-3">Koordinat (Lat, Lng)</th>
                                    <th class="p-3">Jarak Positif (D+)</th>
                                    <th class="p-3">Jarak Negatif (D-)</th>
                                    <th class="p-3">Nilai Preferensi (V)</th>
                                    <th class="p-3">Status Kategori</th>
                                    <th class="p-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($data as $row)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="p-3 font-bold text-slate-700">#{{ $row['peringkat'] }}</td>
                                    <td class="p-3 font-semibold text-slate-900">Kec. {{ $row['nama'] }}</td>
                                    <td class="p-3 text-slate-500 font-mono">{{ $row['latitude'] }}, {{ $row['longitude'] }}</td>
                                    <td class="p-3 text-slate-600">{{ $row['d_plus'] }}</td>
                                    <td class="p-3 text-slate-600">{{ $row['d_minus'] }}</td>
                                    <td class="p-3 font-bold text-indigo-600 text-sm">{{ $row['preferensi'] }}</td>
                                    <td class="p-3">
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $row['kategori'] === 'Tinggi' ? 'bg-red-100 text-red-700' : ($row['kategori'] === 'Sedang' ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700') }}">
                                            {{ $row['kategori'] }}
                                        </span>
                                    </td>
                                    <td class="p-3 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <button class="p-1.5 hover:bg-slate-200 rounded text-blue-600" title="Edit Nilai"><i class="fa-solid fa-pen-to-square"></i></button>
                                            <button class="p-1.5 hover:bg-slate-200 rounded text-slate-600" title="Detail"><i class="fa-solid fa-eye"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- SCRIPT LEAFLET -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        const map = L.map('map').setView([-7.1350, 113.4800], 11);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const dataKecamatan = @json($data);

        dataKecamatan.forEach(item => {
            let markerColor = item.kategori === 'Tinggi' ? '#dc2626' : (item.kategori === 'Sedang' ? '#f59e0b' : '#16a34a');
            
            L.circleMarker([item.latitude, item.longitude], {
                radius: 12,
                fillColor: markerColor,
                color: '#ffffff',
                weight: 2.5,
                opacity: 1,
                fillOpacity: 0.85
            }).addTo(map)
            .bindPopup(`
                <div class="p-1 font-sans">
                    <h3 class="font-bold text-sm text-slate-900">Kec. ${item.nama}</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Peringkat: <b>#${item.peringkat}</b> (${item.kategori})</p>
                    <p class="text-xs text-indigo-700 font-semibold mt-1">Skor Preferensi: ${item.preferensi}</p>
                    <hr class="my-2 border-slate-200">
                    <button class="w-full text-center py-1 bg-indigo-600 text-white text-[11px] rounded font-medium">Buka Profil Kecamatan</button>
                </div>
            `);
        });
    </script>
</body>
</html>