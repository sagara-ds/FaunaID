-- ============================================================
-- FaunaID Database Schema & Initial Seed Data
-- Platform Edukasi & Ensiklopedia Digital Fauna Nusantara
-- Arsitektur: MySQL 5.7+ / 8.0+ / MariaDB
-- ============================================================

CREATE DATABASE IF NOT EXISTS `fauna_id` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `fauna_id`;

SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------------------
-- 1. Tabel users
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nama` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin', 'user') NOT NULL DEFAULT 'user',
  `foto_profil` VARCHAR(255) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 2. Tabel categories
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nama_kategori` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL UNIQUE,
  `deskripsi` TEXT NULL,
  `ikon` VARCHAR(50) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 3. Tabel habitats
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `habitats`;
CREATE TABLE `habitats` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nama_habitat` VARCHAR(100) NOT NULL,
  `deskripsi` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 4. Tabel regions (Zona Persebaran Biogeografi)
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `regions`;
CREATE TABLE `regions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nama_wilayah` VARCHAR(100) NOT NULL,
  `zona` ENUM('Asiatis', 'Peralihan', 'Australis') NOT NULL,
  `deskripsi` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 5. Tabel fauna
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `fauna`;
CREATE TABLE `fauna` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nama_lokal` VARCHAR(150) NOT NULL,
  `nama_ilmiah` VARCHAR(150) NOT NULL,
  `kategori_id` INT NOT NULL,
  `habitat_id` INT NOT NULL,
  `status_konservasi` VARCHAR(100) NOT NULL,
  `deskripsi` LONGTEXT NOT NULL,
  `karakteristik` TEXT NULL,
  `makanan` TEXT NULL,
  `populasi_estimasi` VARCHAR(100) NULL,
  `is_featured` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_fauna_category` FOREIGN KEY (`kategori_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_fauna_habitat` FOREIGN KEY (`habitat_id`) REFERENCES `habitats` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 6. Tabel fauna_images
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `fauna_images`;
CREATE TABLE `fauna_images` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `fauna_id` INT NOT NULL,
  `image_url` VARCHAR(255) NOT NULL,
  `caption` VARCHAR(255) NULL,
  `is_primary` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_images_fauna` FOREIGN KEY (`fauna_id`) REFERENCES `fauna` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 7. Tabel fauna_regions (Many-to-Many Fauna & Regions)
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `fauna_regions`;
CREATE TABLE `fauna_regions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `fauna_id` INT NOT NULL,
  `region_id` INT NOT NULL,
  CONSTRAINT `fk_fr_fauna` FOREIGN KEY (`fauna_id`) REFERENCES `fauna` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_fr_region` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`) ON DELETE CASCADE,
  UNIQUE KEY `unique_fauna_region` (`fauna_id`, `region_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 8. Tabel favorites
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `favorites`;
CREATE TABLE `favorites` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `fauna_id` INT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_fav_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_fav_fauna` FOREIGN KEY (`fauna_id`) REFERENCES `fauna` (`id`) ON DELETE CASCADE,
  UNIQUE KEY `unique_user_fauna` (`user_id`, `fauna_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 9. Tabel articles
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `articles`;
CREATE TABLE `articles` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `judul` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `ringkasan` TEXT NULL,
  `konten` LONGTEXT NOT NULL,
  `thumbnail` VARCHAR(255) NULL,
  `status` ENUM('draft', 'published') NOT NULL DEFAULT 'published',
  `views` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_articles_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- SEED DATA AWAL
-- ============================================================

-- Data Users (admin123 & user123)
INSERT INTO `users` (`id`, `nama`, `email`, `password`, `role`, `foto_profil`) VALUES
(1, 'Administrator FaunaID', 'admin@fauna.id', '$2y$12$iQ3dFJ9HzHoTGcjVJK3cMeII9BAqKV0fjKnmYVgn5Q7kPFwNjStRy', 'admin', 'admin-avatar.jpg'),
(2, 'Budi Santoso', 'budi@gmail.com', '$2y$12$n4C98BceeAoTHRgqxRdr7uGFsB3xy3m8bnRIwVhbKStWmsTjSttuO', 'user', 'user-avatar.jpg'),
(3, 'Siti Rahmawati', 'siti@gmail.com', '$2y$12$n4C98BceeAoTHRgqxRdr7uGFsB3xy3m8bnRIwVhbKStWmsTjSttuO', 'user', 'user-avatar-2.jpg');

-- Data Categories
INSERT INTO `categories` (`id`, `nama_kategori`, `slug`, `deskripsi`, `ikon`) VALUES
(1, 'Mamalia', 'mamalia', 'Hewan menyusui yang umumnya berdarah panas dan melahirkan anak.', 'paw'),
(2, 'Burung (Aves)', 'burung', 'Hewan berbulu dan bersayap dengan keanekaragaman kicauan dan warna yang indah.', 'feather'),
(3, 'Reptil', 'reptil', 'Hewan melata berdarah dingin yang bernapas dengan paru-paru dan bersisik.', 'shield'),
(4, 'Amfibi', 'amfibi', 'Hewan yang mampu hidup di dua alam, yaitu daratan dan perairan.', 'droplet'),
(5, 'Biota Laut & Ikan', 'biota-laut', 'Keanekaragaman fauna perairan tawar maupun laut tropis kepulauan Indonesia.', 'fish');

-- Data Habitats
INSERT INTO `habitats` (`id`, `nama_habitat`, `deskripsi`) VALUES
(1, 'Hutan Hujan Tropis', 'Hutan lebat dengan kanopi tinggi, kelembapan stabil, dan biodiversitas flora-fauna terkaya.'),
(2, 'Sabana & Padang Rumput', 'Hamparan padang rumput beriklim kering dengan semak belukar yang diselingi pepohonan.'),
(3, 'Perairan Laut & Terumbu Karang', 'Ekosistem laut dangkal berterumbu karang hingga palung laut dalam kepulauan nusantara.'),
(4, 'Sungai & Lahan Basah', 'Daerah aliran sungai air tawar, danau, rawa gambut, dan delta perairan pedalaman.'),
(5, 'Hutan Mangrove', 'Zona peralihan pasang surut air laut yang berfungsi sebagai penahan abrasi pantai.'),
(6, 'Pegunungan & Dataran Tinggi', 'Area berketinggian di atas 1.000 mdpl dengan suhu sejuk dan vegetasi lumut.');

-- Data Regions (Zona Biogeografi Indonesia)
INSERT INTO `regions` (`id`, `nama_wilayah`, `zona`, `deskripsi`) VALUES
(1, 'Sumatra', 'Asiatis', 'Wilayah barat paparan Sunda dengan hutan hujan lebat habitat harimau dan gajah.'),
(2, 'Jawa & Bali', 'Asiatis', 'Pusat keanekaragaman satwa endemik pulau padat seperti badak jawa dan jalak bali.'),
(3, 'Kalimantan', 'Asiatis', 'Jantung hutan hujan Borneo yang menjadi rumah bagi orangutan dan bekantan.'),
(4, 'Sulawesi', 'Peralihan', 'Zona Wallacea unik yang memadukan karakteristik fauna Asia dan Australasia.'),
(5, 'Kepulauan Nusa Tenggara', 'Peralihan', 'Gugusan pulau kering dengan fauna purba legendaris biawak komodo.'),
(6, 'Kepulauan Maluku', 'Peralihan', 'Kepulauan rempah dengan fauna endemik burung kakatua dan serangga khas.'),
(7, 'Papua & Sekitarnya', 'Australis', 'Paparan Sahul dengan fauna berkantung dan burung cenderawasih surga.');

-- Data Fauna
INSERT INTO `fauna` (`id`, `nama_lokal`, `nama_ilmiah`, `kategori_id`, `habitat_id`, `status_konservasi`, `deskripsi`, `karakteristik`, `makanan`, `populasi_estimasi`, `is_featured`) VALUES
(1, 'Harimau Sumatera', 'Panthera tigris sumatrae', 1, 1, 'Kritis (Critically Endangered)', 
 'Harimau Sumatera merupakan subspesies harimau terakhir yang tersisa di Indonesia setelah kepunahan harimau jawa dan harimau bali. Memiliki loreng lebih rapat dan warna bulu oranye kemerahan yang pekat untuk berkamuflase di lebatnya hutan hujan tropis Sumatra.',
 'Tubuh relatif lebih kecil dibanding subspesies harimau lain, memiliki janggut tipis di rahang bawah, dan kemampuan berenang yang sangat baik.',
 'Karnivora predator puncak (rusa, babi hutan, kancil, tapir).',
 'Kurang dari 400 - 600 ekor di alam liar', 1),

(2, 'Orangutan Kalimantan', 'Pongo pygmaeus', 1, 1, 'Kritis (Critically Endangered)', 
 'Orangutan Kalimantan adalah primata endemik arboreal terbesar di dunia. Spesies ini memainkan peran penting sebagai penyebar biji pohon hutan tropis guna menjaga keseimbangan dan regenerasi kanopi hutan rimba Borneo.',
 'Memiliki rambut kemerahan panjang, bantalan pipi lebar pada jantan dewasa (flange), lengan panjang dan kuat untuk bergelantungan.',
 'Herbivora / Frugivora (buah-buahan hutan liar, daun muda, kulit kayu, madu, dan serangga kecil).',
 'Sekitar 57.000 ekor tersisa', 1),

(3, 'Komodo', 'Varanus komodoensis', 3, 2, 'Genting (Endangered)', 
 'Komodo merupakan spesies kadal terbesar dan terberat di muka bumi yang telah bertahan hidup sejak zaman purba. Hewan endemik kebanggaan Indonesia ini hidup alami di kawasan Taman Nasional Komodo, Pulau Rinca, Flores, dan Gili Motang.',
 'Panjang tubuh dapat mencapai 3 meter dengan berat melebihi 70 kg, lidah bercabang kuning untuk mendeteksi bau mangsa, serta gigitan beracun dengan kelenjar antikoagulan.',
 'Karnivora oportunistik (rusa timor, babi hutan, kerbau, bangkai binatang).',
 'Sekitar 3.000 - 3.300 ekor', 1),

(4, 'Cenderawasih Kuning Besar', 'Paradisaea apoda', 2, 1, 'Risiko Rendah (Least Concern)', 
 'Sering dijuluki sebagai "Bird of Paradise" atau burung surga karena bulu hiasnya yang luar biasa menawan. Burung jantan memiliki kebiasaan menari di ranting pohon tinggi untuk memikat perhatian burung betina.',
 'Memiliki bulu ekor panjang berwarna kuning keemasan, kepala berbulu kuning cerah dengan tengkuk zamrud berkilau, dan paruh berwarna biru keabu-abuan.',
 'Omnivora pemakan buah liar, buah ara (ficus), dan serangga kanopi.',
 'Stabil di habitat hutan primer Papua', 1),

(5, 'Badak Jawa', 'Rhinoceros sondaicus', 1, 1, 'Kritis (Critically Endangered)', 
 'Badak Jawa atau badak bercula satu kecil adalah salah satu mamalia terlangka di dunia. Saat ini populasi alaminya hanya tersisa di semenanjung Taman Nasional Ujung Kulon, Banten.',
 'Memiliki satu cula berukuran 20-25 cm pada jantan, kulit berlipat-lipat seperti baju zirah pelindung dari benturan ranting keras.',
 'Herbivora pemakan tunas daun, ranting lunak, dan buah-buahan yang jatuh ke tanah.',
 'Sekitar 75 - 80 individu terdata', 1),

(6, 'Anoa Dataran Rendah', 'Bubalus depressicornis', 1, 1, 'Genting (Endangered)', 
 'Anoa merupakan kerbau kerdil endemik pulau Sulawesi. Sering disebut sapi hutan karena penampilannya yang menyerupai kerbau mini dengan tanduk lurus meruncing ke belakang.',
 'Tinggi bahu hanya sekitar 70-80 cm, tanduk lurus tajam segitiga, hidup soliter atau berpasangan di dalam rimba terpencil.',
 'Herbivora pemakan rumput hutan, pakis, lumut, dan rebung bambu liar.',
 'Kurang dari 2.500 ekor di alam bebas', 0),

(7, 'Babirusa Sulawesi', 'Babyrousa babyrussa', 1, 1, 'Rentan (Vulnerable)', 
 'Babirusa adalah mamalia unik keluarga babi liar yang terkenal karena taring jantan yang mencuat menembus moncong atas dan melengkung ke arah dahi.',
 'Kulit keabu-abuan dengan sedikit rambut, taring atas panjang melengkung khas sebagai pertahanan dan ritual persaingan pejantan.',
 'Omnivora pemakan buah fallen fruits, jamur hutan, dedaunan lunak, dan larva serangga.',
 'Sekitar 4.000 individu', 0),

(8, 'Jalak Bali', 'Leucopsar rothschildi', 2, 2, 'Kritis (Critically Endangered)', 
 'Jalak Bali adalah burung pengicau berukuran sedang yang menjadi maskot fauna Provinsi Bali. Berkat upaya penangkaran dan konservasi terpadu di Taman Nasional Bali Barat, populasinya kini perlahan membaik.',
 'Seluruh bulu berwarna putih bersih dengan ujung sayap dan ekor berwarna hitam legam, memiliki lingkaran kulit biru cerah mengelilingi mata.',
 'Omnivora pemakan serangga, ulat, cacing tanah, serta nektar bunga.',
 'Sekitar 350 - 450 ekor di alam bebas dan pusat konservasi', 1),

(9, 'Bekantan', 'Nasalis larvatus', 1, 5, 'Genting (Endangered)', 
 'Bekantan atau kera hidung panjang adalah monyet endemik pulau Kalimantan. Mereka sangat mahir berenang dan kerap melompat dari dahan pohon langsung ke dalam air rawa mangrove.',
 'Hidung menggantung besar pada jantan dewasa, perut buncit khas pemakan daun berkantung fermentasi, bulu oranye kemerahan.',
 'Folivora pemakan daun mangrove muda, pucuk pohon, biji berkulit tipis, dan buah mentah.',
 'Sekitar 15.000 - 20.000 ekor', 0),

(10, 'Gajah Sumatera', 'Elephas maximus sumatranus', 1, 1, 'Kritis (Critically Endangered)', 
 'Gajah Sumatera adalah subspesies dari gajah asia yang bertubuh sedikit lebih ramping. Mereka hidup dalam kawanan sosial yang dipimpin oleh gajah betina tertua (matriark).',
 'Memiliki sepasang gading pada jantan, telinga bundar lebar, serta belalai multifungsi untuk makan, minum, dan berkomunikasi dengan infrasonik.',
 'Herbivora besar yang memakan rumput, daun kelapa sawit liar, bambu, dan aneka tanaman hutan.',
 'Kurang dari 2.000 ekor tersisa di alam', 0),

(11, 'Pesut Mahakam', 'Orcaella brevirostris', 5, 4, 'Kritis (Critically Endangered)', 
 'Pesut Mahakam adalah lumba-lumba air tawar langka yang mendiami Daerah Aliran Sungai Mahakam di Kalimantan Timur.',
 'Kepala membulat tanpa paruh panjang seperti lumba-lumba laut, sirip punggung kecil, serta kulit abu-abu cerah mengilap.',
 'Karnivora pemakan ikan air tawar kecil, udang sungai, dan moluska dasar perairan.',
 'Kurang dari 70 - 80 ekor di sepanjang sungai Mahakam', 0),

(12, 'Kasuari Gelambir Ganda', 'Casuarius casuarius', 2, 1, 'Risiko Rendah (Least Concern)', 
 'Burung raksasa tidak terbang asli hutan belantara Papua. Kasuari memiliki peran ekologis krusial sebagai satu-satunya satwa pemencar biji buah hutan berukuran besar.',
 'Memiliki helm tanduk keratin keras di kepala, dua gelambir merah beruntai di leher, serta cakar kaki tengah menyerupai belati tajam.',
 'Frugivora pemakan buah-buahan hutan yang jatuh, jamur liar, dan vertebrata kecil.',
 'Relatif terjaga di pedalaman hutan lindung Papua', 0);

-- Data Fauna Images
INSERT INTO `fauna_images` (`fauna_id`, `image_url`, `caption`, `is_primary`) VALUES
(1, 'https://images.unsplash.com/photo-1549366021-9f761d450615?auto=format&fit=crop&w=1200&q=80', 'Harimau Sumatera di kawasan konservasi hutan rimba', 1),
(1, 'https://images.unsplash.com/photo-1509198397868-475647b2a1e5?auto=format&fit=crop&w=1200&q=80', 'Sorot mata tajam predator puncak nusantara', 0),
(2, 'https://images.unsplash.com/photo-1534188753412-3e26d0d618d6?auto=format&fit=crop&w=1200&q=80', 'Orangutan Kalimantan berayun di pepohonan kanopi', 1),
(2, 'https://images.unsplash.com/photo-1508817628294-5a453fa0b8fb?auto=format&fit=crop&w=1200&q=80', 'Induk orangutan menjaga bayinya dengan penuh kasih', 0),
(3, 'https://images.unsplash.com/photo-1579783900882-c0d3dad7b119?auto=format&fit=crop&w=1200&q=80', 'Komodo berjemur di savana Taman Nasional Komodo', 1),
(3, 'https://images.unsplash.com/photo-1596727147705-61a532a659bd?auto=format&fit=crop&w=1200&q=80', 'Kadal purba terbesar di dunia berjalan di pesisir pantai', 0),
(4, 'https://images.unsplash.com/photo-1552728089-57bdde30beb3?auto=format&fit=crop&w=1200&q=80', 'Keindahan warna ekor burung cenderawasih', 1),
(5, 'https://images.unsplash.com/photo-1575550959106-5a7defe28b56?auto=format&fit=crop&w=1200&q=80', 'Badak bercula satu di habitat kubangan alami', 1),
(6, 'https://images.unsplash.com/photo-1589656966895-2f33e7653819?auto=format&fit=crop&w=1200&q=80', 'Anoa dataran rendah di lebatnya hutan Sulawesi', 1),
(7, 'https://images.unsplash.com/photo-1548767797-d8c844163c4c?auto=format&fit=crop&w=1200&q=80', 'Babirusa jantan dengan taring melengkung khas', 1),
(8, 'https://images.unsplash.com/photo-1522926193341-e9faf685c65f?auto=format&fit=crop&w=1200&q=80', 'Jalak Bali berbulu putih mulus di dahan ranting', 1),
(9, 'https://images.unsplash.com/photo-1540573133985-87b6da6d54a9?auto=format&fit=crop&w=1200&q=80', 'Bekantan hidung panjang di tepi hutan bakau', 1),
(10, 'https://images.unsplash.com/photo-1557050543-4d5f4e07ef46?auto=format&fit=crop&w=1200&q=80', 'Kawanan gajah sumatera melintasi padang rumput', 1),
(11, 'https://images.unsplash.com/photo-1568430462989-44163eb1752f?auto=format&fit=crop&w=1200&q=80', 'Pesut air tawar melompat di muara sungai', 1),
(12, 'https://images.unsplash.com/photo-1546182990-dffeafbe841d?auto=format&fit=crop&w=1200&q=80', 'Kasuari gelambir ganda dengan helm tanduk di kepala', 1);

-- Data Fauna Regions (Pemetaan Wilayah)
INSERT INTO `fauna_regions` (`fauna_id`, `region_id`) VALUES
(1, 1), -- Harimau -> Sumatra
(2, 3), -- Orangutan -> Kalimantan
(3, 5), -- Komodo -> Nusa Tenggara
(4, 7), -- Cenderawasih -> Papua
(5, 2), -- Badak Jawa -> Jawa & Bali
(6, 4), -- Anoa -> Sulawesi
(7, 4), -- Babirusa -> Sulawesi
(8, 2), -- Jalak Bali -> Jawa & Bali
(9, 3), -- Bekantan -> Kalimantan
(10, 1), -- Gajah -> Sumatra
(11, 3), -- Pesut -> Kalimantan
(12, 7); -- Kasuari -> Papua

-- Data Favorites (Contoh Bookmark Pengguna)
INSERT INTO `favorites` (`user_id`, `fauna_id`) VALUES
(2, 1),
(2, 3),
(2, 4);

-- Data Articles
INSERT INTO `articles` (`id`, `user_id`, `judul`, `slug`, `ringkasan`, `konten`, `thumbnail`, `status`, `views`) VALUES
(1, 1, 'Garis Wallace dan Weber: Jejak Keajaiban Biogeografi Nusantara', 'garis-wallace-dan-weber-jejak-keajaiban-biogeografi-nusantara',
 'Memahami bagaimana pergeseran lempeng bumi ribuan tahun silam melahirkan tiga zona persebaran fauna unik yang tiada duanya di dunia.',
 '<p>Indonesia merupakan negeri yang memiliki fenomena biogeografi paling mengagumkan di dunia. Diapit oleh Benua Asia dan Benua Australia, kepulauan nusantara menjadi titik pertemuan evolusi satwa yang dipisahkan oleh garis khayal imajiner: <strong>Garis Wallace</strong>, <strong>Garis Weber</strong>, dan <strong>Garis Lydekker</strong>.</p>
 <h3>1. Zona Asiatis (Paparan Sunda)</h3>
 <p>Mencakup pulau Sumatra, Jawa, Bali, dan Kalimantan. Pada zaman es, pulau-pulau ini menyatu dengan daratan benua Asia. Akibatnya, fauna di kawasan ini didominasi oleh mamalia berukuran besar (gajah, badak, harimau), primata cerdas, dan burung-burung dengan suara kicauan merdu.</p>
 <h3>2. Zona Peralihan (Wallacea)</h3>
 <p>Meliputi Sulawesi, Kepulauan Maluku, dan Nusa Tenggara. Zona ini tidak pernah menyatu dengan benua Asia maupun Australia, sehingga melahirkan satwa-satwa endemik purba yang hanya bisa ditemukan di sini, seperti Komodo, Anoa, Babirusa, dan Burung Maleo.</p>
 <h3>3. Zona Australis (Paparan Sahul)</h3>
 <p>Mencakup Papua dan kepulauan di sekitarnya yang dahulu terhubung dengan benua Australia. Satwa di wilayah ini memiliki karakteristik mamalia berkantung (marsupial) dan burung dengan warna bulu luar biasa mempesona seperti Cenderawasih dan Kasuari.</p>',
 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1200&q=80', 'published', 248),

(2, 1, 'Menjaga Benteng Terakhir Habitat Harimau Sumatera', 'menjaga-benteng-terakhir-habitat-harimau-sumatera',
 'Upaya konservasi bersama masyarakat adat dan jagawana dalam memerangi deforestasi serta perburuan liar di kawasan Bukit Barisan.',
 '<p>Dengan statusnya yang kini terkategori <em>Critically Endangered</em> (Kritis) dalam daftar merah IUCN, Harimau Sumatera (<em>Panthera tigris sumatrae</em>) berada di ambang batas kelangsungan hidupnya. Hilangnya koridor hutan akibat pembukaan lahan perkebunan dan fragmentasi habitat menjadi ancaman paling nyata.</p>
 <p>Para jagawana hutan bekerja tanpa henti melakukan patroli anti-jerat dan restorasi vegetasi pohon asli demi memberikan ruang jelajah yang aman bagi sang raja rimba nusantara.</p>',
 'https://images.unsplash.com/photo-1549366021-9f761d450615?auto=format&fit=crop&w=1200&q=80', 'published', 195),

(3, 1, 'Kisah Sukses Penangkaran dan Pelepasliaran Jalak Bali', 'kisah-sukses-penangkaran-dan-pelepasliaran-jalak-bali',
 'Dari jurang kepunahan hingga terbang bebas kembali di alam liar Taman Nasional Bali Barat berkat keterlibatan komunitas lokal.',
 '<p>Pada dekade 1990-an, populasi Jalak Bali di alam liar sempat menyusut hingga di bawah 15 individu saja akibat maraknya perburuan burung hias. Namun melalui program penangkaran terpadu (captive breeding) yang mengedukasi masyarakat desa penyangga hutan, burung berselimut bulu putih salju ini perlahan kembali menari bebas di ranting-ranting pohon pantai Bali Barat.</p>',
 'https://images.unsplash.com/photo-1522926193341-e9faf685c65f?auto=format&fit=crop&w=1200&q=80', 'published', 142);

