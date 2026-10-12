<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Kategori;
use App\Models\Fasilitas;
use App\Models\Wisata;
use App\Models\LogRekomendasi;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Users
        $admin = User::firstOrCreate(
            ['email' => 'admin@sirekowis.id'],
            [
                'name' => 'Administrator DISPORABUDPAR',
                'password' => 'password',
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        $operator = User::firstOrCreate(
            ['email' => 'operator@sirekowis.id'],
            [
                'name' => 'Operator Pariwisata',
                'password' => 'password',
                'role' => 'operator',
                'email_verified_at' => now(),
            ]
        );

        // 2. Kategori
        $kategoriAlam = Kategori::firstOrCreate(
            ['slug' => 'alam'],
            ['nama_kategori' => 'Wisata Alam', 'deskripsi' => 'Destinasi keindahan panorama bentang alam, perairan, dan kehutanan asri di Banjarbaru.']
        );

        $kategoriEdukasi = Kategori::firstOrCreate(
            ['slug' => 'edukasi'],
            ['nama_kategori' => 'Wisata Edukasi', 'deskripsi' => 'Kawasan konservasi, botani, serta pusat pembelajaran lingkungan hidup dan sains.']
        );

        $kategoriBudaya = Kategori::firstOrCreate(
            ['slug' => 'budaya'],
            ['nama_kategori' => 'Wisata Budaya & Sejarah', 'deskripsi' => 'Pusat pelestarian cagar budaya, museum peninggalan sejarah kerajaan Banjar, dan etnografi Banjar-Dayak.']
        );

        $kategoriBuatan = Kategori::firstOrCreate(
            ['slug' => 'buatan'],
            ['nama_kategori' => 'Wisata Buatan & Rekreasi', 'deskripsi' => 'Wahana rekreasi kreatif, taman bermain keluarga, dan spot swafoto tematik perkotaan.']
        );

        // 3. Fasilitas
        $fasilitasMap = [
            'Parkir' => 'fa-car',
            'Toilet' => 'fa-restroom',
            'Mushola' => 'fa-mosque',
            'Gazebo' => 'fa-umbrella-beach',
            'Warung Makan' => 'fa-utensils',
            'Spot Foto' => 'fa-camera',
            'Jalur Trekking' => 'fa-hiking',
            'Wahana Bermain' => 'fa-shapes',
            'Resto' => 'fa-bowl-food',
            'Ruang Pameran' => 'fa-landmark',
            'Pemandu' => 'fa-user-tie',
            'Area Santai' => 'fa-couch',
            'Perahu Wisata' => 'fa-ship',
        ];

        $fasilitasModels = [];
        foreach ($fasilitasMap as $nama => $icon) {
            $fasilitasModels[$nama] = Fasilitas::firstOrCreate(
                ['nama_fasilitas' => $nama],
                ['icon' => $icon]
            );
        }

        // 4. Wisata (6 Wisata Nyata Kota Banjarbaru)
        $wisataData = [
            [
                'nama_wisata' => 'Danau Seran',
                'slug' => 'danau-seran',
                'kategori_id' => $kategoriAlam->id,
                'kecamatan' => 'Landasan Ulin',
                'alamat' => 'Jl. Danau Seran, Kelurahan Guntung Manggis, Kec. Landasan Ulin, Kota Banjarbaru, Kalimantan Selatan 70721',
                'latitude' => -3.4867119,
                'longitude' => 114.7796035,
                'harga_tiket' => 10000,
                'rating_google' => 4.3,
                'jumlah_review_google' => 2850,
                'jam_operasional' => '08:00 - 18:00 WITA',
                'foto_utama' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1000&q=80',
                'status' => 'aktif',
                'deskripsi' => 'Danau Seran adalah bekas area penambangan intan dan pasir yang kini bermetamorfosis menjadi danau eksotis berair jernih kehijauan dengan pulau kecil di tengahnya. Destinasi alam favorit di Banjarbaru untuk menikmati pemandangan perairan, bersantai di gazebo tepi danau, serta menyeberang menggunakan perahu wisata tradisional ke pulau asmara.',
                'fasilitas' => ['Parkir', 'Warung Makan', 'Gazebo', 'Spot Foto', 'Perahu Wisata', 'Toilet']
            ],
            [
                'nama_wisata' => 'Kebun Raya Banua',
                'slug' => 'kebun-raya-banua',
                'kategori_id' => $kategoriEdukasi->id,
                'kecamatan' => 'Cempaka',
                'alamat' => 'Kawasan Pusat Perkantoran Pemprov Kalsel, Jl. Aneka Tambang, Kel. Palam, Kec. Cempaka, Kota Banjarbaru, Kalimantan Selatan 70733',
                'latitude' => -3.4916893,
                'longitude' => 114.8516086,
                'harga_tiket' => 15000,
                'rating_google' => 4.5,
                'jumlah_review_google' => 4120,
                'jam_operasional' => '08:00 - 17:30 WITA',
                'foto_utama' => 'https://images.unsplash.com/photo-1585320806297-9794b3e4eeae?auto=format&fit=crop&w=1000&q=80',
                'status' => 'aktif',
                'deskripsi' => 'Kebun Raya Banua merupakan kawasan konservasi botani dan plasma nutfah seluas 100 hektar yang berada di kawasan pusat pemerintahan Kalimantan Selatan. Menawarkan zona tumbuhan obat herbal Kalimantan, konservasi buah langka khas Borneo, taman labirin raksasa hijau, embung air buatan yang asri, serta wahana edukasi botani bagi pelajar dan keluarga.',
                'fasilitas' => ['Parkir', 'Toilet', 'Mushola', 'Jalur Trekking', 'Spot Foto', 'Gazebo', 'Pemandu']
            ],
            [
                'nama_wisata' => 'Hutan Pinus Mentaos',
                'slug' => 'hutan-pinus-mentaos',
                'kategori_id' => $kategoriAlam->id,
                'kecamatan' => 'Banjarbaru Utara',
                'alamat' => 'Jl. Suriansyah Ujung, Kel. Mentaos, Kec. Banjarbaru Utara, Kota Banjarbaru, Kalimantan Selatan 70711',
                'latitude' => -3.4357491,
                'longitude' => 114.8385208,
                'harga_tiket' => 5000,
                'rating_google' => 4.2,
                'jumlah_review_google' => 3200,
                'jam_operasional' => '07:30 - 18:00 WITA',
                'foto_utama' => 'https://images.unsplash.com/photo-1448375240586-882707db888b?auto=format&fit=crop&w=1000&q=80',
                'status' => 'aktif',
                'deskripsi' => 'Hutan Pinus Mentaos adalah oase hijau rindang di tengah perkotaan Banjarbaru yang dipenuhi jajaran pohon pinus menjulang tinggi nan asri. Dilengkapi hammock gantung santai, kanopi payung warna-warni artistik, serta panggung mini seni terbuka. Tempat ini sangat digemari untuk relaksasi keluarga, rekreasi santai, dan berswafoto alami.',
                'fasilitas' => ['Parkir', 'Warung Makan', 'Gazebo', 'Area Santai', 'Spot Foto', 'Toilet']
            ],
            [
                'nama_wisata' => 'Museum Lambung Mangkurat',
                'slug' => 'museum-lambung-mangkurat',
                'kategori_id' => $kategoriBudaya->id,
                'kecamatan' => 'Banjarbaru Utara',
                'alamat' => 'Jl. Jenderal Ahmad Yani Km. 36 No. 5, Kel. Komet, Kec. Banjarbaru Utara, Kota Banjarbaru, Kalimantan Selatan 70711',
                'latitude' => -3.4418641,
                'longitude' => 114.8398188,
                'harga_tiket' => 3000,
                'rating_google' => 4.4,
                'jumlah_review_google' => 1950,
                'jam_operasional' => '08:30 - 15:00 WITA (Selasa-Minggu)',
                'foto_utama' => 'https://images.unsplash.com/photo-1566127444979-b3d2b654e3d7?auto=format&fit=crop&w=1000&q=80',
                'status' => 'aktif',
                'deskripsi' => 'Museum Lambung Mangkurat adalah museum provinsi Kalimantan Selatan dengan ikon arsitektur Rumah Adat Banjar Bubungan Tinggi. Menyimpan lebih dari 12.000 artefak bersejarah, peninggalan Kesultanan Banjar, etnografi suku Dayak dan Banjar, pusaka tradisional keris kalsel, fosil purbakala, serta koleksi edukasi cagar budaya yang bernilai tinggi.',
                'fasilitas' => ['Parkir', 'Toilet', 'Ruang Pameran', 'Pemandu', 'Mushola']
            ],
            [
                'nama_wisata' => 'Kampung Pelangi Banjarbaru',
                'slug' => 'kampung-pelangi',
                'kategori_id' => $kategoriBuatan->id,
                'kecamatan' => 'Banjarbaru Selatan',
                'alamat' => 'Jl. Kemuning, Kel. Guntung Paikat, Kec. Banjarbaru Selatan, Kota Banjarbaru, Kalimantan Selatan 70714',
                'latitude' => -3.4475432,
                'longitude' => 114.8319681,
                'harga_tiket' => 0,
                'rating_google' => 4.0,
                'jumlah_review_google' => 1480,
                'jam_operasional' => '24 Jam (Disarankan 08:00 - 18:00 WITA)',
                'foto_utama' => 'https://images.unsplash.com/photo-1518684079-3c830dcef090?auto=format&fit=crop&w=1000&q=80',
                'status' => 'aktif',
                'deskripsi' => 'Kampung Pelangi Banjarbaru merupakan inisiatif wisata perkampungan kreatif dan tematik di sepanjang bantaran Sungai Kemuning. Rumah-rumah penduduk, jembatan gantung, dan jalan setapak dihiasi aneka lukisan mural 3 dimensi serta cat beraneka warna yang sangat cerah, menjadikannya destinasi swafoto instagramable favorit di pusat kota.',
                'fasilitas' => ['Parkir', 'Spot Foto', 'Warung Makan', 'Toilet', 'Area Santai']
            ],
            [
                'nama_wisata' => 'Amanah Park Banjarbaru',
                'slug' => 'amanah-park',
                'kategori_id' => $kategoriBuatan->id,
                'kecamatan' => 'Banjarbaru Selatan',
                'alamat' => 'Jl. Dahlia No. 45, Kel. Guntung Paikat, Kec. Banjarbaru Selatan, Kota Banjarbaru, Kalimantan Selatan 70714',
                'latitude' => -3.4556102,
                'longitude' => 114.8423145,
                'harga_tiket' => 20000,
                'rating_google' => 4.1,
                'jumlah_review_google' => 1820,
                'jam_operasional' => '09:00 - 17:00 WITA',
                'foto_utama' => 'https://images.unsplash.com/photo-1576013551627-0cc20b96c2a7?auto=format&fit=crop&w=1000&q=80',
                'status' => 'aktif',
                'deskripsi' => 'Amanah Park Banjarbaru adalah destinasi rekreasi keluarga terpadu bernuansa taman tropis asri. Menghadirkan kolam renang waterpark anak, arena playground permainan interaktif, fasilitas outbond mini, gazebo santai teduh, serta kafe dan resto keluarga yang nyaman untuk pesta dan gathering akhir pekan.',
                'fasilitas' => ['Parkir', 'Toilet', 'Mushola', 'Wahana Bermain', 'Resto', 'Spot Foto', 'Gazebo']
            ],
        ];

        $createdWisatas = [];
        foreach ($wisataData as $item) {
            $fasilitasNames = $item['fasilitas'];
            unset($item['fasilitas']);

            $wisata = Wisata::updateOrCreate(
                ['slug' => $item['slug']],
                $item
            );

            // Sync fasilitas
            $fasilitasIds = [];
            foreach ($fasilitasNames as $fName) {
                if (isset($fasilitasModels[$fName])) {
                    $fasilitasIds[] = $fasilitasModels[$fName]->id;
                }
            }
            $wisata->fasilitases()->sync($fasilitasIds);
            $createdWisatas[$item['slug']] = $wisata;
        }

        // 5. Seed Initial Log Rekomendasi (for Reports & Analysis)
        if (isset($createdWisatas['danau-seran']) && isset($createdWisatas['hutan-pinus-mentaos'])) {
            LogRekomendasi::firstOrCreate([
                'wisata_asal_id' => $createdWisatas['danau-seran']->id,
                'wisata_rekomendasi_id' => $createdWisatas['hutan-pinus-mentaos']->id,
            ], [
                'similarity_score' => 0.7850,
                'keyword_pencarian' => 'alam rekreasi',
                'ip_address' => '127.0.0.1',
                'created_at' => now()->subDays(2),
            ]);
        }

        if (isset($createdWisatas['danau-seran']) && isset($createdWisatas['kebun-raya-banua'])) {
            LogRekomendasi::firstOrCreate([
                'wisata_asal_id' => $createdWisatas['danau-seran']->id,
                'wisata_rekomendasi_id' => $createdWisatas['kebun-raya-banua']->id,
            ], [
                'similarity_score' => 0.7120,
                'keyword_pencarian' => 'danau alam',
                'ip_address' => '127.0.0.1',
                'created_at' => now()->subDays(1),
            ]);
        }

        if (isset($createdWisatas['kebun-raya-banua']) && isset($createdWisatas['museum-lambung-mangkurat'])) {
            LogRekomendasi::firstOrCreate([
                'wisata_asal_id' => $createdWisatas['kebun-raya-banua']->id,
                'wisata_rekomendasi_id' => $createdWisatas['museum-lambung-mangkurat']->id,
            ], [
                'similarity_score' => 0.6540,
                'keyword_pencarian' => 'edukasi',
                'ip_address' => '127.0.0.1',
                'created_at' => now()->subHours(12),
            ]);
        }

        if (isset($createdWisatas['kampung-pelangi']) && isset($createdWisatas['amanah-park'])) {
            LogRekomendasi::firstOrCreate([
                'wisata_asal_id' => $createdWisatas['kampung-pelangi']->id,
                'wisata_rekomendasi_id' => $createdWisatas['amanah-park']->id,
            ], [
                'similarity_score' => 0.7410,
                'keyword_pencarian' => 'rekreasi buatan',
                'ip_address' => '127.0.0.1',
                'created_at' => now()->subHours(5),
            ]);
        }
    }
}
