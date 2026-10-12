@extends('layouts.admin')

@section('title', 'Edit Destinasi Wisata')
@section('page_title', 'Perbarui Data Destinasi Wisata')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <a href="{{ route('admin.wisata.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900 flex items-center gap-1.5">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Wisata
        </a>
        <a href="{{ route('wisata.detail', $wisata->slug) }}" target="_blank" class="text-xs font-bold text-brand-600 hover:underline flex items-center gap-1">
            Lihat Halaman Publik <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
        </a>
    </div>

    <form action="{{ route('admin.wisata.update', $wisata->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-sm space-y-8">
        @csrf
        @method('PUT')

        @if($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium space-y-1">
                <p class="font-bold">Mohon perbaiki kesalahan berikut:</p>
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="p-5 rounded-2xl bg-emerald-50/70 border border-emerald-100 space-y-2">
            <label class="block text-sm font-bold text-slate-800">🔍 Cari &amp; Import Data dari OpenStreetMap</label>
            <div class="relative">
                <input type="search" id="placeSearch" autocomplete="off" placeholder="Ketik nama tempat wisata di Banjarbaru..." class="w-full px-4 py-3 text-sm rounded-xl border border-slate-300 bg-white focus:ring-2 focus:ring-brand-500 outline-none">
                <div id="placeSearchResults" role="listbox" class="hidden absolute z-[1000] top-full mt-2 w-full overflow-hidden rounded-xl border border-slate-200 bg-white shadow-xl"></div>
            </div>
            <p class="text-[11px] text-slate-500">Pilih hasil untuk mengisi nama, alamat, dan koordinat. Rating Google serta jumlah ulasan tidak tersedia dari OpenStreetMap dan perlu diisi manual.</p>
            <p class="text-[10px] text-slate-400">Hasil pencarian oleh Photon · Data © OpenStreetMap contributors</p>
        </div>

        <!-- Informasi Dasar -->
        <div>
            <h3 class="font-black text-base text-slate-900 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                <i class="fa-solid fa-circle-info text-brand-600"></i> Informasi Identitas Destinasi
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Tempat Wisata *</label>
                    <input type="text" name="nama_wisata" value="{{ old('nama_wisata', $wisata->nama_wisata) }}" required class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-500 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kategori Wisata *</label>
                    <select name="kategori_id" required class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-500 outline-none">
                        @foreach($kategoris as $k)
                            <option value="{{ $k->id }}" {{ old('kategori_id', $wisata->kategori_id) == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Wilayah Kecamatan *</label>
                    <select name="kecamatan" required class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-500 outline-none">
                        @foreach($kecamatans as $kec)
                            <option value="{{ $kec }}" {{ old('kecamatan', $wisata->kecamatan) == $kec ? 'selected' : '' }}>
                                Kec. {{ $kec }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Alamat Fisik Lengkap *</label>
                    <textarea name="alamat" rows="2" required class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-500 outline-none">{{ old('alamat', $wisata->alamat) }}</textarea>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Deskripsi Lengkap *</label>
                    <textarea name="deskripsi" rows="4" required class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-500 outline-none">{{ old('deskripsi', $wisata->deskripsi) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Lokasi Peta & Koordinat -->
        <div>
            <h3 class="font-black text-base text-slate-900 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                <i class="fa-solid fa-map-location-dot text-brand-600"></i> Titik Koordinat Peta Interaktif
            </h3>
            
            <p class="text-xs text-slate-500 mb-3">
                Geser pin atau klik pada peta di bawah ini untuk memperbarui titik koordinat:
            </p>

            <div id="pickerMap" class="w-full h-72 rounded-2xl border border-slate-300 mb-4"></div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Latitude *</label>
                    <input type="text" id="latitudeInput" name="latitude" value="{{ old('latitude', $wisata->latitude) }}" required class="w-full px-4 py-2 text-xs rounded-xl border border-slate-300 font-mono bg-slate-50 focus:ring-2 focus:ring-brand-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Longitude *</label>
                    <input type="text" id="longitudeInput" name="longitude" value="{{ old('longitude', $wisata->longitude) }}" required class="w-full px-4 py-2 text-xs rounded-xl border border-slate-300 font-mono bg-slate-50 focus:ring-2 focus:ring-brand-500 outline-none">
                </div>
            </div>
        </div>

        <!-- Parameter Rekomendasi (Harga, Rating, Jam) -->
        <div>
            <h3 class="font-black text-base text-slate-900 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                <i class="fa-solid fa-calculator text-brand-600"></i> Parameter & Atribut Operasional
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Harga Tiket Masuk (Rp) *</label>
                    <input type="number" name="harga_tiket" value="{{ old('harga_tiket', $wisata->harga_tiket) }}" min="0" step="500" required class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-500 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Rating Google Maps (0.0 - 5.0) *</label>
                    <input type="number" name="rating_google" value="{{ old('rating_google', $wisata->rating_google) }}" min="0" max="5" step="0.1" required class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-500 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Total Ulasan Google *</label>
                    <input type="number" name="jumlah_review_google" value="{{ old('jumlah_review_google', $wisata->jumlah_review_google) }}" min="0" required class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-500 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Jam Operasional</label>
                    <input type="text" name="jam_operasional" value="{{ old('jam_operasional', $wisata->jam_operasional) }}" class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-500 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Status Publikasi *</label>
                    <select name="status" required class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-500 outline-none">
                        <option value="aktif" {{ old('status', $wisata->status) == 'aktif' ? 'selected' : '' }}>Aktif (Tampil Publik)</option>
                        <option value="nonaktif" {{ old('status', $wisata->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif (Arsip)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Foto Utama -->
        <div>
            <h3 class="font-black text-base text-slate-900 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                <i class="fa-solid fa-image text-brand-600"></i> Foto Utama Objek Wisata
            </h3>

            <div class="flex items-center gap-4 mb-4">
                <img src="{{ $wisata->foto_url }}" alt="{{ $wisata->nama_wisata }}" class="w-24 h-24 rounded-2xl object-cover border border-slate-200">
                <div class="text-xs text-slate-500">
                    <p class="font-bold text-slate-700">Foto Saat Ini</p>
                    <p class="text-[11px] truncate max-w-sm">{{ $wisata->foto_utama ?? 'Default Unsplash' }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Ganti dengan File Baru</label>
                    <input type="file" name="foto" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Atau Ganti URL Gambar</label>
                    <input type="url" name="foto_url_input" value="{{ old('foto_url_input') }}" placeholder="https://..." class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-500 outline-none">
                </div>
            </div>
        </div>

        <!-- Sarana & Fasilitas Multi-Select -->
        <div>
            <h3 class="font-black text-base text-slate-900 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                <i class="fa-solid fa-icons text-brand-600"></i> Fasilitas yang Tersedia
            </h3>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                @foreach($fasilitases as $fas)
                    <label class="flex items-center gap-2.5 p-3 rounded-2xl bg-slate-50 hover:bg-slate-100 border border-slate-200 cursor-pointer">
                        <input type="checkbox" name="fasilitas[]" value="{{ $fas->id }}" 
                            {{ in_array($fas->id, old('fasilitas', $selectedFasilitas)) ? 'checked' : '' }}
                            class="rounded text-brand-600 focus:ring-brand-500">
                        <span class="text-xs font-semibold text-slate-700 flex items-center gap-1.5">
                            <i class="fa-solid {{ $fas->icon ?? 'fa-check' }} text-brand-600 text-xs"></i>
                            {{ $fas->nama_fasilitas }}
                        </span>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('admin.wisata.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-600 hover:bg-slate-100 text-xs font-bold">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-md shadow-brand-600/20">
                Perbarui Destinasi Wisata
            </button>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var defaultLat = parseFloat(document.getElementById('latitudeInput').value) || -3.457242;
        var defaultLng = parseFloat(document.getElementById('longitudeInput').value) || 114.810318;

        var map = L.map('pickerMap').setView([defaultLat, defaultLng], 14);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        var marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);

        function updateInputs(lat, lng) {
            document.getElementById('latitudeInput').value = lat.toFixed(7);
            document.getElementById('longitudeInput').value = lng.toFixed(7);
        }

        window.sirekowisMoveMapMarker = function (lat, lng) {
            marker.setLatLng([lat, lng]);
            map.setView([lat, lng], 16);
            updateInputs(lat, lng);
        };

        marker.on('dragend', function (e) {
            var coord = e.target.getLatLng();
            updateInputs(coord.lat, coord.lng);
        });

        map.on('click', function (e) {
            marker.setLatLng(e.latlng);
            updateInputs(e.latlng.lat, e.latlng.lng);
        });

        initializePlaceSearch();
    });

    function initializePlaceSearch() {
        const input = document.getElementById('placeSearch');
        const results = document.getElementById('placeSearchResults');
        let debounceTimer;
        let requestController;

        input.addEventListener('input', () => {
            clearTimeout(debounceTimer);
            if (requestController) requestController.abort();
            const query = input.value.trim();
            if (query.length < 3) {
                results.innerHTML = '';
                results.classList.add('hidden');
                return;
            }
            debounceTimer = setTimeout(() => searchPlaces(query), 600);
        });

        async function searchPlaces(query) {
            requestController = new AbortController();
            results.innerHTML = '<div class="px-4 py-3 text-xs text-slate-500">Mencari tempat…</div>';
            results.classList.remove('hidden');

            try {
                const params = new URLSearchParams({ q: query + ', Banjarbaru', lat: '-3.457242', lon: '114.810318', limit: '6', lang: 'default' });
                const response = await fetch('https://photon.komoot.io/api/?' + params.toString(), { signal: requestController.signal });
                if (!response.ok) throw new Error('Layanan pencarian tidak merespons.');
                const data = await response.json();
                renderResults(data.features || []);
            } catch (error) {
                if (error.name === 'AbortError') return;
                results.innerHTML = '<div class="px-4 py-3 text-xs text-rose-600">Pencarian sementara tidak tersedia. Coba lagi atau isi data secara manual.</div>';
                results.classList.remove('hidden');
            }
        }

        function renderResults(features) {
            if (!features.length) {
                results.innerHTML = '<div class="px-4 py-3 text-xs text-slate-500">Tempat tidak ditemukan. Coba kata kunci lain atau isi manual.</div>';
                results.classList.remove('hidden');
                return;
            }

            results.innerHTML = '';
            features.forEach((feature) => {
                const properties = feature.properties || {};
                const name = properties.name || properties.street || 'Tempat tanpa nama';
                const address = [properties.housenumber, properties.street, properties.district, properties.city, properties.state, properties.postcode, properties.country]
                    .filter(Boolean).filter((value, index, values) => values.indexOf(value) === index).join(', ');
                const option = document.createElement('button');
                option.type = 'button';
                option.setAttribute('role', 'option');
                option.className = 'block w-full border-b border-slate-100 px-4 py-3 text-left last:border-0 hover:bg-emerald-50';
                const title = document.createElement('span');
                title.className = 'block text-sm font-semibold text-slate-800';
                title.textContent = name;
                const subtitle = document.createElement('span');
                subtitle.className = 'mt-1 block text-xs text-slate-500';
                subtitle.textContent = address || 'Alamat tidak tersedia';
                option.append(title, subtitle);
                option.addEventListener('mousedown', (event) => event.preventDefault());
                option.addEventListener('click', () => {
                    const [longitude, latitude] = feature.geometry.coordinates;
                    document.querySelector('[name="nama_wisata"]').value = name;
                    document.querySelector('[name="alamat"]').value = address || name;
                    window.sirekowisMoveMapMarker(latitude, longitude);
                    input.value = name;
                    results.classList.add('hidden');
                });
                results.appendChild(option);
            });
            results.classList.remove('hidden');
        }

        document.addEventListener('click', (event) => {
            if (!event.target.closest('#placeSearch') && !event.target.closest('#placeSearchResults')) results.classList.add('hidden');
        });
    }
</script>
@endpush
