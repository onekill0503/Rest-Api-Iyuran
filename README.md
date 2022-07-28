# Program REST API Aplikasi Iuran
### Informasi Program
- Menggunakan Bahasa Pemograman PHP Native
- Menggunakan database Mysql

## Tabel Aksi
Key | Deskripsi
--- | ---
`tambah_warga` | Menambah warga baru
`login` | Melakukan Login
`detail_warga` | Melihat data warga tertentu berdasarkan id
`hapus_warga` | Menghapus data warga
`edit_warga` | Mengedit data warga
`detail_pengeluaran` | Melihat Detail Daftar Pengeluaran Tertentu Berdasarkan Id pengeluaran
`hapus_pengeluaran` | Menghapus data pengeluaran berdasarkan Id
`edit_pengeluaran` | Mengedit Data pengeluaran berdasarkan Id
`detail_pengumuman` | Melihat Detail Pengumuman Tertentu Berdasarkan Id pengeluaran
`edit_pengumuman` | Mengedit Data Pengumuman berdasarkan Id
`hapus_pengumuman` | Menghapus data Pengumuman berdasarkan Id
`detail_pengaduan` | Melihat Detail Pengaduan Tertentu Berdasarkan Id pengeluaran
`hapus_pengaduan` | Menghapus data Pengaduan berdasarkan Id
`edit_pengaduan` | Mengedit Data Pengaduan berdasarkan Id
`catat_iyuran` | Untuk menyimpan data iyuran
`daftar_pengeluaran` | Melihat semua daftar pengeluaran
`daftar_pengumuman` | Melihat semua daftar pengumuman
`daftar_pengaduan` | Melihat semua daftar pengaduan
`riwayat_iyuran` | Melihat liwata iyuran berdasarkan Id warga
`daftar_warga` | Melihat Semua akun/data Warga
`pemasukan_iyuran_sampah` | Melihat detail Pemasukan Iyuran Sampah
`pemasukan_iyuran_kematian` | Melihat Detail Pemasukan Iyuran Kematian
`pemasukan_iyuran` | Melihat Detail Pemasukan Semua Iyuran
`tambah_pengeluaran` | Menambah Data Pengeluaran
`buat_laporan` | Memberi Data Laporan Seperti jumlah saldo,jumlah total pemasukan bulan ini,jumlah total pengeluaran bulan ini ,total pemasukan iyuran sampah bulain ini dll
`buat_pengumuman` | Membuat/Menambah data Pengumuman
`buat_pengaduan` | Membuat/Menambah data pengaduan

## Daftar API

yang tidak tecantum pada tabel aksi diatas, hanya memerlukan paramter `aksi`

### Tambah Warga `/Api.php POST`
Key | Deskripsi | Note | Tipe Data
--- | --- | --- | ---
`aksi` | aksi atau proses yang dilakukan | required | `string`
`nama_warga` | nama warga yang akan ditambahkan | required | `string`
`email` | Email warga yang akan ditambahkan | required | `string`
`alamat` | Alamat warga yang akan ditambahkan | required | `string`
`no_telp` | nomor telepon warga yang akan ditambahkan | required | `string`
`password` | password untuk warga login | required | `string`

### Login `/Api.php POST`
Key | Deskripsi | Note | Tipe Data
--- | --- | --- | ---
`aksi` | aksi atau proses yang dilakukan | required | `string`
`email` | Email warga yang terdaftar | required | `string`
`password` | password warga yang terdaftar | required | `string`

### Detail Warga / Pengeluaran / Pengumuman / Pengaduan `/Api.php POST`
Key | Deskripsi | Note | Tipe Data
--- | --- | --- | ---
`aksi` | aksi atau proses yang dilakukan | required | `string`
`id` | Id data Warga  / Pengeluaran / Pengumuman / Pengaduan yang terekam didatabase | required | `integer`

### Hapus Warga / Pengeluaran / Pengumuman / Pengaduan  `/Api.php POST`
Key | Deskripsi | Note | Tipe Data
--- | --- | --- | ---
`aksi` | aksi atau proses yang dilakukan | required | `string`
`id` | Id data Warga  / Pengeluaran / Pengumuman / Pengaduan yang terekam didatabase | required | `integer`

### Edit Warga `/Api.php POST`
Key | Deskripsi | Note | Tipe Data
--- | --- | --- | ---
`aksi` | aksi atau proses yang dilakukan | required | `string`
`id` | Id data warga yang terekam didatabse | required | `integer`
`nama_warga` | nama warga yang akan ubah | required | `string`
`alamat` | Alamat warga yang akan ubah | required | `string`
`no_telp` | nomor telepon warga yang akan ubah | required | `string`

### Edit Pengeluaran `/Api.php POST`
Key | Deskripsi | Note | Tipe Data
--- | --- | --- | ---
`aksi` | aksi atau proses yang dilakukan | required | `string`
`id` | id data pengeluaran yang terekam di database | required | `integer`
`jumlah_pengeluaran` | jumlah pengeluaran yang akan ditambahkan | required | `integer`
`alasan` | Alasan Pengeluaran | required | `string`

### Edit Pengumuman / Pengaduan `/Api.php POST`
Key | Deskripsi | Note | Tipe Data
--- | --- | --- | ---
`aksi` | aksi atau proses yang dilakukan | required | `string`
`id` | id data Pengumuman / Pengaduan yang terekam di database | required | `integer`
`judul` | judul pengumuman / pengaduan | required | `string`
`pembuat` | pembuat pengumuman / pengaduan | required | `string`
`id_kategori` | kategori dari informasi | required | `integer`
`isi` | isi pengumuman / pengaduan | required | `string`
`file` | file image pengumuman / pengaduan | optional | `file`

### Catat Iyuran `/Api.php POST`
Key | Deskripsi | Note | Tipe Data
--- | --- | --- | ---
`aksi` | aksi atau proses yang dilakukan | required | `string`
`jumlah_iyuran` | Jumlah Iyuran yang akan dimasukan | required | `integer`
`periode1` | bulan dimana iyuran dilakukan | required | `date`
`periode2` | bila warga membayar iyuran 2 bulan parameter ini diisi bulan selanjutnya dari periode1 | optional | `date`
`kematian` | diisi `iya` jika iyuran termasuk iyuran kematian | optional | `string`
`id_warga` | Id dari warga yang melakukan Iyuran | required | `integer`

### Riwayat Iyuran `/Api.php POST`
Key | Deskripsi | Note | Tipe Data
--- | --- | --- | ---
`aksi` | aksi atau proses yang dilakukan | required | `string`
`id_warga` | Id dari data warga yang terekam | required | `integer`

### Tambah Pengumuman / Pengaduan `/Api.php POST`
Key | Deskripsi | Note | Tipe Data
--- | --- | --- | ---
`aksi` | aksi atau proses yang dilakukan | required | `string`
`judul` | berisi judul pengumuman atau pengaduan | required | `string`
`isi` | isi dari pengumuman atau pengaduan | required | `string`
`id_kategori` | kategori dari informasi | required | `integer`
`pembuat` | pembuat pengumuman / pengaduan | required | `string`
`file` | file image pengumuman / pengaduan | required | `file`