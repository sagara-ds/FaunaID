<?php
/**
 * Database Connection Manager (PDO)
 * Mendukung MySQL (Primary) dengan Auto-Fallback SQLite lokal untuk keandalan maksimal
 */

class Database {
    private static ?PDO $instance = null;
    private static string $driver = 'mysql';

    public static function getConnection(): PDO {
        if (self::$instance !== null) {
            return self::$instance;
        }

        $host = DB_HOST;
        $port = DB_PORT;
        $db   = DB_NAME;
        $user = DB_USER;
        $pass = DB_PASS;

        // 1. Coba koneksi ke MySQL server
        try {
            $dsn = "mysql:host={$host};port={$port};charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_TIMEOUT => 2
            ];

            // Koneksi ke host terlebih dahulu
            $pdo = new PDO($dsn, $user, $pass, $options);
            
            // Cek apakah database sudah ada, jika belum otomatis buat dan import skema
            $stmt = $pdo->query("SHOW DATABASES LIKE '{$db}'");
            $exists = $stmt->fetch();
            
            if (!$exists) {
                $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$db}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                $pdo->exec("USE `{$db}`");
                
                $schemaFile = ROOT_PATH . '/database/schema.sql';
                if (file_exists($schemaFile)) {
                    $sql = file_get_contents($schemaFile);
                    $pdo->exec($sql);
                }
            } else {
                $pdo->exec("USE `{$db}`");
            }

            self::$instance = $pdo;
            self::$driver = 'mysql';
            return self::$instance;
        } catch (PDOException $e) {
            // 2. Jika MySQL tidak berjalan / socket ditolak, fallback otomatis ke SQLite
            $sqliteDir = ROOT_PATH . DIRECTORY_SEPARATOR . 'database';
            if (!is_dir($sqliteDir)) {
                mkdir($sqliteDir, 0777, true);
            }
            $sqlitePath = $sqliteDir . DIRECTORY_SEPARATOR . 'fauna_id.sqlite';
            $isNew = !file_exists($sqlitePath) || filesize($sqlitePath) === 0;

            try {
                $pdo = new PDO("sqlite:" . $sqlitePath, null, null, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);
                $pdo->exec("PRAGMA foreign_keys = ON;");

                self::$instance = $pdo;
                self::$driver = 'sqlite';

                if ($isNew) {
                    self::initSqliteDatabase($pdo);
                }

                return self::$instance;
            } catch (Exception $sqle) {
                die("Gagal menghubungkan ke Database: " . $sqle->getMessage());
            }
        }
    }

    public static function getDriver(): string {
        return self::$driver;
    }

    /**
     * Inisialisasi struktur dan seed data untuk SQLite lokal jika MySQL belum aktif
     */
    public static function initSqliteDatabase(PDO $pdo): void {
        $queries = [
            // Users
            "CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nama TEXT NOT NULL,
                email TEXT NOT NULL UNIQUE,
                password TEXT NOT NULL,
                role TEXT NOT NULL DEFAULT 'user',
                foto_profil TEXT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )",
            // Categories
            "CREATE TABLE IF NOT EXISTS categories (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nama_kategori TEXT NOT NULL,
                slug TEXT NOT NULL UNIQUE,
                deskripsi TEXT NULL,
                ikon TEXT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )",
            // Habitats
            "CREATE TABLE IF NOT EXISTS habitats (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nama_habitat TEXT NOT NULL,
                deskripsi TEXT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )",
            // Regions
            "CREATE TABLE IF NOT EXISTS regions (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nama_wilayah TEXT NOT NULL,
                zona TEXT NOT NULL,
                deskripsi TEXT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )",
            // Fauna
            "CREATE TABLE IF NOT EXISTS fauna (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nama_lokal TEXT NOT NULL,
                nama_ilmiah TEXT NOT NULL,
                kategori_id INTEGER NOT NULL REFERENCES categories(id) ON DELETE CASCADE,
                habitat_id INTEGER NOT NULL REFERENCES habitats(id) ON DELETE CASCADE,
                status_konservasi TEXT NOT NULL,
                deskripsi TEXT NOT NULL,
                karakteristik TEXT NULL,
                makanan TEXT NULL,
                populasi_estimasi TEXT NULL,
                is_featured INTEGER DEFAULT 0,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )",
            // Fauna Images
            "CREATE TABLE IF NOT EXISTS fauna_images (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                fauna_id INTEGER NOT NULL REFERENCES fauna(id) ON DELETE CASCADE,
                image_url TEXT NOT NULL,
                caption TEXT NULL,
                is_primary INTEGER DEFAULT 0,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )",
            // Fauna Regions
            "CREATE TABLE IF NOT EXISTS fauna_regions (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                fauna_id INTEGER NOT NULL REFERENCES fauna(id) ON DELETE CASCADE,
                region_id INTEGER NOT NULL REFERENCES regions(id) ON DELETE CASCADE,
                UNIQUE(fauna_id, region_id)
            )",
            // Favorites
            "CREATE TABLE IF NOT EXISTS favorites (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
                fauna_id INTEGER NOT NULL REFERENCES fauna(id) ON DELETE CASCADE,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                UNIQUE(user_id, fauna_id)
            )",
            // Articles
            "CREATE TABLE IF NOT EXISTS articles (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
                judul TEXT NOT NULL,
                slug TEXT NOT NULL UNIQUE,
                ringkasan TEXT NULL,
                konten TEXT NOT NULL,
                thumbnail TEXT NULL,
                status TEXT NOT NULL DEFAULT 'published',
                views INTEGER DEFAULT 0,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )"
        ];

        foreach ($queries as $q) {
            $pdo->exec($q);
        }

        // Insert Seeder Data
        $adminPass = password_hash('admin123', PASSWORD_BCRYPT);
        $userPass  = password_hash('user123', PASSWORD_BCRYPT);

        // Users
        $userStmt = $pdo->prepare("INSERT OR IGNORE INTO users (id, nama, email, password, role) VALUES (?, ?, ?, ?, ?)");
        $userStmt->execute([1, 'Administrator FaunaID', 'admin@fauna.id', $adminPass, 'admin']);
        $userStmt->execute([2, 'Budi Santoso', 'budi@gmail.com', $userPass, 'user']);
        $userStmt->execute([3, 'Siti Rahmawati', 'siti@gmail.com', $userPass, 'user']);

        // Categories
        $catStmt = $pdo->prepare("INSERT OR IGNORE INTO categories (id, nama_kategori, slug, deskripsi, ikon) VALUES (?, ?, ?, ?, ?)");
        $catStmt->execute([1, 'Mamalia', 'mamalia', 'Hewan menyusui yang umumnya berdarah panas dan melahirkan anak.', 'paw']);
        $catStmt->execute([2, 'Burung (Aves)', 'burung', 'Hewan berbulu dan bersayap dengan keanekaragaman kicauan dan warna yang indah.', 'feather']);
        $catStmt->execute([3, 'Reptil', 'reptil', 'Hewan melata berdarah dingin yang bernapas dengan paru-paru dan bersisik.', 'shield']);
        $catStmt->execute([4, 'Amfibi', 'amfibi', 'Hewan yang mampu hidup di dua alam, yaitu daratan dan perairan.', 'droplet']);
        $catStmt->execute([5, 'Biota Laut & Ikan', 'biota-laut', 'Keanekaragaman fauna perairan tawar maupun laut tropis kepulauan Indonesia.', 'fish']);

        // Habitats
        $habStmt = $pdo->prepare("INSERT OR IGNORE INTO habitats (id, nama_habitat, deskripsi) VALUES (?, ?, ?)");
        $habStmt->execute([1, 'Hutan Hujan Tropis', 'Hutan lebat dengan kanopi tinggi, kelembapan stabil, dan biodiversitas flora-fauna terkaya.']);
        $habStmt->execute([2, 'Sabana & Padang Rumput', 'Hamparan padang rumput beriklim kering dengan semak belukar yang diselingi pepohonan.']);
        $habStmt->execute([3, 'Perairan Laut & Terumbu Karang', 'Ekosistem laut dangkal berterumbu karang hingga palung laut dalam kepulauan nusantara.']);
        $habStmt->execute([4, 'Sungai & Lahan Basah', 'Daerah aliran sungai air tawar, danau, rawa gambut, dan delta perairan pedalaman.']);
        $habStmt->execute([5, 'Hutan Mangrove', 'Zona peralihan pasang surut air laut yang berfungsi sebagai penahan abrasi pantai.']);
        $habStmt->execute([6, 'Pegunungan & Dataran Tinggi', 'Area berketinggian di atas 1.000 mdpl dengan suhu sejuk dan vegetasi lumut.']);

        // Regions
        $regStmt = $pdo->prepare("INSERT OR IGNORE INTO regions (id, nama_wilayah, zona, deskripsi) VALUES (?, ?, ?, ?)");
        $regStmt->execute([1, 'Sumatra', 'Asiatis', 'Wilayah barat paparan Sunda dengan hutan hujan lebat habitat harimau dan gajah.']);
        $regStmt->execute([2, 'Jawa & Bali', 'Asiatis', 'Pusat keanekaragaman satwa endemik pulau padat seperti badak jawa dan jalak bali.']);
        $regStmt->execute([3, 'Kalimantan', 'Asiatis', 'Jantung hutan hujan Borneo yang menjadi rumah bagi orangutan dan bekantan.']);
        $regStmt->execute([4, 'Sulawesi', 'Peralihan', 'Zona Wallacea unik yang memadukan karakteristik fauna Asia dan Australasia.']);
        $regStmt->execute([5, 'Kepulauan Nusa Tenggara', 'Peralihan', 'Gugusan pulau kering dengan fauna purba legendaris biawak komodo.']);
        $regStmt->execute([6, 'Kepulauan Maluku', 'Peralihan', 'Kepulauan rempah dengan fauna endemik burung kakatua dan serangga khas.']);
        $regStmt->execute([7, 'Papua & Sekitarnya', 'Australis', 'Paparan Sahul dengan fauna berkantung dan burung cenderawasih surga.']);

        // Fauna
        $faunaData = [
            [1, 'Harimau Sumatera', 'Panthera tigris sumatrae', 1, 1, 'Kritis (Critically Endangered)', 
             'Harimau Sumatera merupakan subspesies harimau terakhir yang tersisa di Indonesia setelah kepunahan harimau jawa dan harimau bali. Memiliki loreng lebih rapat dan warna bulu oranye kemerahan yang pekat untuk berkamuflase di lebatnya hutan hujan tropis Sumatra.',
             'Tubuh relatif lebih kecil dibanding subspesies harimau lain, memiliki janggut tipis di rahang bawah, dan kemampuan berenang yang sangat baik.',
             'Karnivora predator puncak (rusa, babi hutan, kancil, tapir).', 'Kurang dari 400 - 600 ekor di alam liar', 1],
            [2, 'Orangutan Kalimantan', 'Pongo pygmaeus', 1, 1, 'Kritis (Critically Endangered)', 
             'Orangutan Kalimantan adalah primata endemik arboreal terbesar di dunia. Spesies ini memainkan peran penting sebagai penyebar biji pohon hutan tropis guna menjaga regenerasi kanopi hutan rimba Borneo.',
             'Memiliki rambut kemerahan panjang, bantalan pipi lebar pada jantan dewasa (flange), lengan panjang dan kuat untuk bergelantungan.',
             'Herbivora / Frugivora (buah-buahan liar, daun muda, kulit kayu, madu).', 'Sekitar 57.000 ekor tersisa', 1],
            [3, 'Komodo', 'Varanus komodoensis', 3, 2, 'Genting (Endangered)', 
             'Komodo merupakan spesies kadal terbesar dan terberat di muka bumi yang telah bertahan hidup sejak zaman purba. Hidup alami di kawasan Taman Nasional Komodo, Pulau Rinca, Flores, dan Gili Motang.',
             'Panjang tubuh mencapai 3 meter dengan berat 70 kg, lidah bercabang kuning, dan air liur mengandung racun kelenjar antikoagulan.',
             'Karnivora oportunistik (rusa timor, babi hutan, kerbau, bangkai binatang).', 'Sekitar 3.000 - 3.300 ekor', 1],
            [4, 'Cenderawasih Kuning Besar', 'Paradisaea apoda', 2, 1, 'Risiko Rendah (Least Concern)', 
             'Sering dijuluki sebagai "Bird of Paradise" atau burung surga karena bulu hiasnya yang luar biasa menawan. Burung jantan menari di ranting pohon kanopi tinggi untuk memikat betina.',
             'Memiliki bulu ekor panjang berwarna kuning keemasan, kepala berbulu kuning cerah dengan tengkuk zamrud berkilau.',
             'Omnivora pemakan buah liar, buah ara (ficus), dan serangga kanopi.', 'Stabil di habitat hutan primer Papua', 1],
            [5, 'Badak Jawa', 'Rhinoceros sondaicus', 1, 1, 'Kritis (Critically Endangered)', 
             'Badak Jawa adalah salah satu mamalia terlangka di dunia. Saat ini populasi alaminya hanya tersisa di semenanjung Taman Nasional Ujung Kulon, Banten.',
             'Memiliki satu cula berukuran 20-25 cm pada jantan, kulit berlipat-lipat seperti baju zirah pelindung.',
             'Herbivora pemakan tunas daun, ranting lunak, dan buah yang jatuh.', 'Sekitar 75 - 80 individu terdata', 1],
            [6, 'Anoa Dataran Rendah', 'Bubalus depressicornis', 1, 1, 'Genting (Endangered)', 
             'Anoa merupakan kerbau kerdil endemik pulau Sulawesi. Sering disebut sapi hutan karena penampilannya yang menyerupai kerbau mini dengan tanduk lurus meruncing ke belakang.',
             'Tinggi bahu sekitar 70-80 cm, tanduk lurus tajam segitiga, hidup soliter di dalam rimba terpencil.',
             'Herbivora pemakan rumput hutan, pakis, lumut, dan rebung bambu liar.', 'Kurang dari 2.500 ekor di alam bebas', 0],
            [7, 'Babirusa Sulawesi', 'Babyrousa babyrussa', 1, 1, 'Rentan (Vulnerable)', 
             'Babirusa adalah mamalia unik keluarga babi liar yang terkenal karena taring jantan yang mencuat menembus moncong atas dan melengkung ke arah dahi.',
             'Kulit keabu-abuan dengan sedikit rambut, taring atas panjang melengkung khas sebagai pertahanan dan ritual persaingan.',
             'Omnivora pemakan buah fallen fruits, jamur hutan, dedaunan lunak, dan larva serangga.', 'Sekitar 4.000 individu', 0],
            [8, 'Jalak Bali', 'Leucopsar rothschildi', 2, 2, 'Kritis (Critically Endangered)', 
             'Jalak Bali adalah burung pengicau berukuran sedang yang menjadi maskot fauna Provinsi Bali dengan bulu putih bersih dan mata berlingkar biru.',
             'Seluruh bulu berwarna putih bersih dengan ujung sayap dan ekor hitam legam, memiliki lingkaran kulit biru cerah mengelilingi mata.',
             'Omnivora pemakan serangga, ulat, cacing tanah, serta nektar bunga.', 'Sekitar 350 - 450 ekor', 1],
            [9, 'Bekantan', 'Nasalis larvatus', 1, 5, 'Genting (Endangered)', 
             'Bekantan atau kera hidung panjang adalah monyet endemik pulau Kalimantan. Mereka sangat mahir berenang dan kerap melompat dari dahan pohon ke air payau.',
             'Hidung menggantung besar pada jantan dewasa, perut buncit khas pemakan daun berkantung fermentasi.',
             'Folivora pemakan daun mangrove muda, pucuk pohon, biji berkulit tipis, dan buah mentah.', 'Sekitar 15.000 - 20.000 ekor', 0],
            [10, 'Gajah Sumatera', 'Elephas maximus sumatranus', 1, 1, 'Kritis (Critically Endangered)', 
             'Gajah Sumatera adalah subspesies dari gajah asia yang bertubuh sedikit lebih ramping. Mereka hidup dalam kawanan sosial yang dipimpin oleh gajah betina tertua.',
             'Memiliki sepasang gading pada jantan, telinga bundar lebar, serta belalai multifungsi untuk makan dan berkomunikasi infrasonik.',
             'Herbivora besar yang memakan rumput, bambu, dan aneka dedaunan hutan.', 'Kurang dari 2.000 ekor tersisa di alam', 0],
            [11, 'Pesut Mahakam', 'Orcaella brevirostris', 5, 4, 'Kritis (Critically Endangered)', 
             'Pesut Mahakam adalah lumba-lumba air tawar langka yang mendiami Daerah Aliran Sungai Mahakam di Kalimantan Timur.',
             'Kepala membulat tanpa paruh panjang seperti lumba-lumba laut, sirip punggung kecil, serta kulit abu-abu cerah mengilap.',
             'Karnivora pemakan ikan air tawar kecil, udang sungai, dan moluska.', 'Kurang dari 70 - 80 ekor', 0],
            [12, 'Kasuari Gelambir Ganda', 'Casuarius casuarius', 2, 1, 'Risiko Rendah (Least Concern)', 
             'Burung raksasa tidak terbang asli hutan belantara Papua dengan peran penting sebagai pemencar biji pohon hutan berukuran besar.',
             'Memiliki helm tanduk keratin keras di kepala, dua gelambir merah beruntai di leher, serta cakar kaki tengah menyerupai belati tajam.',
             'Frugivora pemakan buah-buahan hutan yang jatuh, jamur liar, dan vertebrata kecil.', 'Relatif stabil di pedalaman Papua', 0]
        ];

        $faunaStmt = $pdo->prepare("INSERT OR IGNORE INTO fauna (id, nama_lokal, nama_ilmiah, kategori_id, habitat_id, status_konservasi, deskripsi, karakteristik, makanan, populasi_estimasi, is_featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($faunaData as $f) {
            $faunaStmt->execute($f);
        }

        // Fauna Images — Sumber: Wikimedia Commons (CC BY-SA / CC0), tersalin lokal ke public/uploads agar tidak bergantung CDN
        $imgData = [
            [1, 'fauna_1_tiger_sumatra_1.jpg', 'Harimau Sumatera dewasa bertengger di tunggul pohon', 1],
            [1, 'fauna_1_tiger_sumatra_2.jpg', 'Harimau Sumatera beristirahat di rumput konservasi', 0],
            [2, 'fauna_2_orangutan_1.jpg', 'Orangutan jantan dewasa beristirahat di hutan Kalimantan', 1],
            [3, 'fauna_3_komodo_1.jpg', 'Komodo menjulurkan lidah bercabang untuk mencium bau jauh', 1],
            [4, 'fauna_4_cenderawasih.jpg', 'Cenderawasih besar menampakkan bulu hias kuning keemasan', 1],
            [5, 'fauna_5_badak_jawa.jpg', 'Badak Jawa satu cula (dokumentasi historis specimen)', 1],
            [6, 'fauna_6_anoa.jpg', 'Anoa dataran rendah dengan tanduk lurus meruncing', 1],
            [7, 'fauna_7_babirusa.jpg', 'Babirusa jantan dengan taring atas yang menembus moncong', 1],
            [8, 'fauna_8_jalak_bali.jpg', 'Jalak Bali berbulu putih mulus di dahan ranting', 1],
            [9, 'fauna_9_bekantan.jpg', 'Bekantan jantan dewasa bermahkota hidung panjang', 1],
            [10, 'fauna_10_gajah_sumatera.jpg', 'Kawanan gajah sumatera melintasi padang rumput', 1],
            [11, 'fauna_11_pesut.jpg', 'Pesut air tawar melompat di muara sungai', 1],
            [12, 'fauna_12_kasuari.jpg', 'Kasuari gelambir ganda dengan helm tanduk di kepala', 1]
        ];
        $imgStmt = $pdo->prepare("INSERT OR IGNORE INTO fauna_images (fauna_id, image_url, caption, is_primary) VALUES (?, ?, ?, ?)");
        foreach ($imgData as $im) {
            $imgStmt->execute($im);
        }

        // Fauna Regions
        $frData = [
            [1, 1], [2, 3], [3, 5], [4, 7], [5, 2], [6, 4],
            [7, 4], [8, 2], [9, 3], [10, 1], [11, 3], [12, 7]
        ];
        $frStmt = $pdo->prepare("INSERT OR IGNORE INTO fauna_regions (fauna_id, region_id) VALUES (?, ?)");
        foreach ($frData as $fr) {
            $frStmt->execute($fr);
        }

        // Favorites
        $favStmt = $pdo->prepare("INSERT OR IGNORE INTO favorites (user_id, fauna_id) VALUES (?, ?)");
        $favStmt->execute([2, 1]);
        $favStmt->execute([2, 3]);
        $favStmt->execute([2, 4]);

        // Articles
        $artData = [
            [1, 1, 'Garis Wallace dan Weber: Jejak Keajaiban Biogeografi Nusantara', 'garis-wallace-dan-weber-jejak-keajaiban-biogeografi-nusantara',
             'Memahami bagaimana pergeseran lempeng bumi ribuan tahun silam melahirkan tiga zona persebaran fauna unik yang tiada duanya di dunia.',
             '<p>Indonesia merupakan negeri yang memiliki fenomena biogeografi paling mengagumkan di dunia. Diapit oleh Benua Asia dan Benua Australia, kepulauan nusantara menjadi titik pertemuan evolusi satwa yang dipisahkan oleh garis khayal imajiner: <strong>Garis Wallace</strong>, <strong>Garis Weber</strong>, dan <strong>Garis Lydekker</strong>.</p><h3>1. Zona Asiatis (Paparan Sunda)</h3><p>Mencakup pulau Sumatra, Jawa, Bali, dan Kalimantan. Fauna di kawasan ini didominasi oleh mamalia berukuran besar (gajah, badak, harimau), primata cerdas, dan burung-burung dengan kicauan merdu.</p><h3>2. Zona Peralihan (Wallacea)</h3><p>Meliputi Sulawesi, Kepulauan Maluku, dan Nusa Tenggara. Zona ini tidak pernah menyatu dengan benua Asia maupun Australia, sehingga melahirkan satwa-satwa endemik purba yang hanya bisa ditemukan di sini, seperti Komodo, Anoa, Babirusa, dan Burung Maleo.</p><h3>3. Zona Australis (Paparan Sahul)</h3><p>Mencakup Papua dan kepulauan di sekitarnya yang dahulu terhubung dengan benua Australia. Satwa di wilayah ini memiliki karakteristik mamalia berkantung (marsupial) dan burung dengan warna bulu luar biasa mempesona seperti Cenderawasih dan Kasuari.</p>',
             'art_wallace_landscape.jpg', 'published', 248],
            [2, 1, 'Menjaga Benteng Terakhir Habitat Harimau Sumatera', 'menjaga-benteng-terakhir-habitat-harimau-sumatera',
             'Upaya konservasi bersama masyarakat adat dan jagawana dalam memerangi deforestasi serta perburuan liar di kawasan Bukit Barisan.',
             '<p>Dengan statusnya yang kini terkategori <em>Critically Endangered</em> (Kritis) dalam daftar merah IUCN, Harimau Sumatera berada di ambang batas kelangsungan hidupnya. Hilangnya koridor hutan akibat pembukaan lahan perkebunan dan fragmentasi habitat menjadi ancaman paling nyata.</p>',
             'fauna_1_tiger_sumatra_1.jpg', 'published', 195],
            [3, 1, 'Kisah Sukses Penangkaran dan Pelepasliaran Jalak Bali', 'kisah-sukses-penangkaran-dan-pelepasliaran-jalak-bali',
             'Dari jurang kepunahan hingga terbang bebas kembali di alam liar Taman Nasional Bali Barat berkat keterlibatan komunitas lokal.',
             '<p>Pada dekade 1990-an, populasi Jalak Bali di alam liar sempat menyusut hingga di bawah 15 individu saja akibat maraknya perburuan burung hias. Melalui penangkaran terpadu di Taman Nasional Bali Barat, populasinya kini perlahan membaik dan menari bebas di alam terbuka.</p>',
             'fauna_8_jalak_bali.jpg', 'published', 142]
        ];

        $artStmt = $pdo->prepare("INSERT OR IGNORE INTO articles (id, user_id, judul, slug, ringkasan, konten, thumbnail, status, views) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($artData as $art) {
            $artStmt->execute($art);
        }
    }
}

