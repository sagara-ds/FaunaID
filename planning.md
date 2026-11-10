# Planning — Tahap 1: Perencanaan dan Analisis Project FaunaID

## 1. Konsep dan Tujuan Website
FaunaID merupakan website edukasi dan ensiklopedia digital yang bertujuan memperkenalkan keanekaragaman fauna Indonesia. Website ini menyediakan informasi mengenai berbagai jenis hewan, karakteristik, habitat, makanan, persebaran wilayah, dan status konservasi. Tujuan utamanya adalah memudahkan masyarakat, khususnya pelajar, dalam mempelajari fauna Indonesia serta meningkatkan kesadaran terhadap pentingnya pelestarian satwa.

## 2. Target Pengguna
Target pengguna FaunaID meliputi:
- **Pelajar**: Sebagai media pembelajaran mengenai fauna Indonesia.
- **Masyarakat umum**: Untuk memperoleh informasi mengenai berbagai jenis hewan.
- **Pecinta alam**: Untuk mengenal habitat, karakteristik, dan persebaran satwa.
- **Admin**: Untuk mengelola informasi dan konten website.

## 3. Fitur Utama Website
FaunaID memiliki beberapa fitur utama, yaitu:

1. **Homepage**: Menampilkan pengenalan FaunaID, fauna populer, informasi keanekaragaman hayati, dan artikel terbaru.
2. **Katalog Fauna**: Menampilkan daftar hewan Indonesia yang dapat dicari dan difilter berdasarkan kategori, habitat, serta status konservasi.
3. **Detail Fauna**: Menampilkan informasi lengkap mengenai hewan, seperti nama ilmiah, karakteristik, makanan, habitat, persebaran, dan status konservasi.
4. **Peta Interaktif**: Menampilkan persebaran fauna Indonesia yang dibagi menjadi tiga wilayah, yaitu Asiatis, Peralihan, dan Australis.
5. **Artikel Edukasi**: Menyediakan informasi mengenai keanekaragaman hayati, ekosistem, ancaman kepunahan, dan pelestarian satwa.
6. **Akun Pengguna**: Memungkinkan pengguna melakukan registrasi, login, mengelola profil, dan menyimpan fauna favorit.
7. **Dashboard Admin**: Digunakan untuk mengelola data fauna, kategori, habitat, wilayah persebaran, gambar, artikel, dan pengguna.

## 4. Alur Penggunaan Website (User Flow)
Alur penggunaan FaunaID dimulai ketika pengguna membuka website dan masuk ke halaman Homepage. Dari halaman tersebut, pengguna dapat memilih menu katalog fauna untuk mencari dan melihat berbagai jenis hewan, atau membuka peta interaktif untuk menjelajahi persebaran fauna berdasarkan tiga wilayah Indonesia. Setelah memilih hewan, pengguna akan diarahkan ke halaman detail untuk membaca informasi lebih lengkap. Pengguna juga dapat membaca artikel edukasi untuk memperoleh pengetahuan tambahan. Apabila ingin menyimpan hewan favorit, pengguna perlu melakukan registrasi atau login terlebih dahulu.

Sementara itu, admin harus melakukan login menggunakan akun khusus untuk masuk ke dashboard. Admin dapat mengelola data fauna, kategori, habitat, wilayah persebaran, gambar, artikel, dan akun pengguna. Setiap perubahan data akan disimpan ke database dan ditampilkan pada website sesuai dengan data terbaru.

## 5. Rancangan Awal Database
Database FaunaID menggunakan MySQL dengan rancangan tabel berikut:

| Nama tabel | Fungsi |
|---|---|
| users | Menyimpan data akun pengguna dan admin |
| categories | Menyimpan kategori fauna |
| habitats | Menyimpan informasi habitat |
| regions | Menyimpan tiga wilayah persebaran fauna |
| fauna | Menyimpan informasi utama hewan |
| fauna_images | Menyimpan gambar fauna |
| fauna_regions | Menghubungkan fauna dengan wilayah persebaran |
| favorites | Menyimpan daftar fauna favorit pengguna |
| articles | Menyimpan artikel edukasi |

### Hubungan Antar Tabel

| Relasi | Jenis hubungan | Penjelasan |
|---|---|---|
| Categories – Fauna | One to Many | Satu kategori memiliki banyak fauna. |
| Habitats – Fauna | One to Many | Satu habitat dapat menjadi habitat banyak fauna. |
| Fauna – Fauna Images | One to Many | Satu fauna dapat memiliki banyak gambar. |
| Fauna – Regions | Many to Many | Satu fauna dapat tersebar di beberapa wilayah. |
| Users – Favorites | One to Many | Satu pengguna dapat menyimpan banyak favorit. |
| Fauna – Favorites | One to Many | Satu fauna dapat disimpan oleh banyak pengguna. |
| Users – Articles | One to Many | Satu pengguna/admin dapat membuat banyak artikel. |

**Catatan penting**: Relasi Fauna dan Regions menggunakan tabel perantara `fauna_regions`, karena satu spesies bisa ditemukan di beberapa wilayah persebaran.

## 6. Identitas Visual Website
FaunaID menggunakan konsep desain modern nature editorial, yaitu tampilan modern yang terinspirasi dari alam dan keanekaragaman hayati Indonesia.

**Rencana identitas visual:**
- **Forest Green (#173F35)**: Warna utama yang menggambarkan alam dan hutan.
- **Sage Green (#759477)**: Warna pendukung yang memberikan kesan alami.
- **Warm Cream (#F4F0E5)**: Warna latar agar tampilan nyaman dilihat.
- **Earth Orange (#D99B58)**: Warna aksen untuk menonjolkan elemen tertentu.

Desain website akan dibuat sederhana, modern, responsif, dan mudah digunakan pada desktop maupun perangkat mobile.