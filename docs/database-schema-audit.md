# Audit Struktur Database SILARIS

Tanggal audit: 4 September 2026  
Database lokal: `u873657946_silaris_kum`  
Versi migrasi tervalidasi: `15`

## Ringkasan keputusan

- `aauth_users` tetap menjadi satu-satunya sumber autentikasi. Field `data_notaris.password` telah dihapus karena menggandakan kredensial dan berisiko disalahgunakan.
- `data_notaris` adalah sumber data induk Notaris. Akun role Notaris tetap mengikuti status keberadaan Notaris pada tabel ini.
- `data_mpd` adalah sumber profil dan verifikasi MPD; `mpd_wilayah` adalah relasi many-to-many sehingga satu MPD dapat mengawasi beberapa kabupaten/kota.
- `wilayah` adalah referensi wilayah resmi. `kode_wilayah` wajib menggunakan nilai dari tabel ini dan bukan teks bebas.
- `laporan`, `fidusia`, `daftar_proses`, `legalisasi`, `reportorium`, dan `waarmerking` adalah tabel transaksi aktif.
- Rekap harus membaca tabel transaksi aktif melalui pembatasan role/wilayah. Rekap bukan tempat mengunggah atau menggandakan data.
- Field `owner_user_id` menjadi identitas pemilik permanen seluruh transaksi. `username` dan `nama_notaris` sementara dipertahankan sebagai snapshot kompatibilitas data lama.

## Klasifikasi 57 tabel

### Inti aplikasi dan masih digunakan

`aauth_users`, `aauth_groups`, `aauth_user_to_group`, `aauth_group_to_group`, `aauth_perms`, `aauth_perm_to_group`, `aauth_perm_to_user`, `aauth_login_attempts`, `aauth_pms`, `aauth_user_variables`, `data_notaris`, `data_mpd`, `mpd_wilayah`, `wilayah`, `laporan`, `fidusia`, `daftar_proses`, `legalisasi`, `reportorium`, `waarmerking`, `menu`, `menu_type`, `migrations`, `cc_options`, `captcha`.

### Infrastruktur generator yang masih diperlukan

`crud`, `crud_field`, `crud_field_validation`, `crud_custom_option`, `crud_input_type`, `crud_input_validation`.

Tabel ini tidak boleh dihapus selama TMC CRUD masih dipakai. Metadata generator harus mengikuti aturan form agar regenerasi tidak mengembalikan tampilan/validasi lama.

### Konten publik/organisasi

`blog`, `blog_category`, `wilayah_kepala`, `wilayah_perangkat`, `wilayah_profil`.

Tabel tersebut dipertahankan karena memiliki data atau digunakan halaman publik.

### Legacy atau kosong, tetapi belum aman untuk langsung dihapus

`aauth_user`, `cc_session`, `jumlah_per_wilayah`, `kd_kanwil`, `keys`, `laporan_2023`, `laporan_2024`, `laporan_2025`, `laporan_bulan_2023`, `laporan_bulan_2024`, `laporan_bulan_2025`, `laporan_per_2023`, `laporan_per_bulan`, `laporan_per_bulan_2023`, `page`, `page_block_element`, `rest`, `rest_field`, `rest_field_validation`, `rest_input_type`, `setup_satker`, `rekap_daftar_proses`, `rekap_laporan`, `rekap_laporan_bulanan`, `rekap_legalisasi`, `rekap_reportorium`, `rekap_waarmerking`.

Sebagian tabel ini masih dirujuk controller, model, menu, atau fallback lama. Menghapusnya sekarang dapat merusak route lama. Tahap berikut yang aman adalah memindahkan seluruh pembaca rekap ke tabel transaksi aktif, menghapus route/fallback lama, menjalankan uji regresi, baru menghapus tabel melalui migrasi tersendiri.

### Referensi transisi

`wil` dan `laporan_bulanan` masih memiliki data lama. `wilayah` sudah menjadi referensi wilayah utama. Kedua tabel transisi ini baru boleh dilepas setelah semua pembacanya dipindahkan dan hasil dibandingkan.

## Struktur dan validasi data inti

| Entitas/field | Wajib | Aturan server | Bentuk input |
|---|---:|---|---|
| Akun `username` | Ya | unik, 3-100 karakter, hanya huruf/angka/`.`/`_`/`-` | teks, autocomplete username |
| Akun `email` | Ya | email valid, maksimal 100, unik untuk akun baru maupun edit | email |
| Akun `full_name` | Ya | maksimal 200 | teks |
| Akun `phone_number` | Ya | format lokal `08`, 10-13 digit | tel + numeric keypad |
| Akun `password` | Ya saat tambah | 8-72 karakter; opsional saat edit | password |
| Akun `kd_wilayah` | Ya | harus ada di `wilayah.kd_wilayah` | single select |
| Akun `group[]` | Ya | minimal satu ID group valid | multi-select |
| Notaris `nama_notaris` | Ya | maksimal 100 | teks |
| Notaris `jenis_kelamin` | Ya | `Laki-laki` atau `Perempuan` | select |
| Notaris `email` | Ya | email valid, maksimal 150 | email |
| Notaris `kode_wilayah` | Ya | foreign-reference logis ke `wilayah` | single select kabupaten/kota |
| Notaris `no_telepon` | Ya | `08`, 10-13 digit | tel + numeric keypad |
| Notaris `tanggal_lahir`, `tanggal_bap` | Tidak | tanggal valid dan tidak melebihi hari ini | date |
| Notaris `nomor_ktp` | Tidak | tepat 16 digit | numeric text, bukan number agar nol awal aman |
| Notaris `npwp` | Tidak | 15 atau 16 digit | numeric text |
| Notaris `lat` | Tidak | desimal -90 sampai 90 | number step any |
| Notaris `long` | Tidak | desimal -180 sampai 180 | number step any |
| Notaris `status_notaris` | Ya | enum aplikasi: aktif, nonaktif, cuti, pindah, meninggal | select |
| MPD `user_id` | Ya | akun role MPD, satu akun untuk satu profil | select akun |
| MPD `wilayah[]` | Ya | minimal satu dan seluruh kode ada di referensi | multi-select kabupaten/kota |
| MPD `nama_mpd`, `jabatan` | Ya | maksimal 150/100 | teks |
| MPD `email`, `no_telepon` | Ya | email valid dan telepon `08` 10-13 digit | email/tel |
| MPD masa jabatan | Bersyarat | tanggal selesai tidak boleh sebelum tanggal mulai | date |
| MPD verifikasi | Bersyarat | verifikasi membutuhkan akun, wilayah, SK, email, telepon, serta masa jabatan lengkap | checkbox setelah data lengkap |
| Laporan bulanan tanggal | Ya | tanggal valid, tidak di masa depan | date |
| Laporan bulanan dokumen | Ya saat tambah | file yang diizinkan sistem, satu file per periode | file uploader |
| Layanan `nomor_akta` | Ya | integer 0-2.147.483.647; `0` berarti Nihil | number min 0 |
| Layanan `tanggal_akta` | Ya | tanggal valid, tidak di masa depan | date |
| Layanan sifat/penghadap | Ya | maksimal 100 | teks |
| Fidusia para pihak/sertifikat | Ya | maksimal 255; boleh berisi `Nihil` | teks |

Validasi dilakukan di server. Atribut HTML hanya membantu pengalaman input dan bukan lapisan keamanan utama.

## Perubahan skema yang telah diterapkan

- Migrasi 13: menambah dan mengisi `owner_user_id` pada empat tabel layanan; menormalkan kode wilayah, gender, status, telepon, NPWP, dan KTP; menghapus password Data Notaris; menambah unique index username serta kode wilayah.
- Migrasi 14: menambah index wilayah dan index gabungan pemilik+tanggal agar filter dashboard/rekap efisien; menormalkan telepon akun.
- Migrasi 15: memisahkan 138 koordinat lama yang tersimpan sebagai `lat, long`; mengubah tanggal lahir menjadi `DATE`; mengubah koordinat menjadi `DECIMAL`; memperketat nullability nama, gender, dan status.

## Data historis yang tidak boleh ditebak

- Email `sudirman@notaris.com` masih dipakai oleh dua akun. Validasi baru menolak duplikasi baru dan mengharuskan email diperbaiki ketika salah satu akun diedit. Unique index email belum dipasang agar migrasi tidak gagal atau menghapus akun secara sepihak.
- 26 baris `reportorium` tahun 2022 memiliki `username = 0` dan tidak memiliki nama Notaris. Isi aktanya nyata, sehingga tidak dihapus. Administrator harus menentukan pemiliknya berdasarkan arsip sumber; setelah itu isi `owner_user_id` akun yang benar.
- Tujuh record Data Notaris memiliki setidaknya satu nilai lama yang panjangnya tidak valid dan perlu diperiksa dari dokumen asli:
  - ID 36 dan 51: NPWP 14 digit.
  - ID 55: NIK 15 digit.
  - ID 63: NIK 17 digit.
  - ID 70: nomor telepon terlalu pendek.
  - ID 77: NIK 15 digit.
  - ID 109: NPWP 11 digit.

Nilai tersebut sengaja tidak dilengkapi atau dipotong otomatis karena dapat mengubah identitas orang. Validasi baru mencegah nilai serupa tersimpan saat record diedit.

## Prosedur production

1. Backup database dan folder `uploads` dari production dalam satu timestamp yang sama.
2. Deploy kode aplikasi terlebih dahulu dalam maintenance window.
3. Pastikan konfigurasi database menunjuk database production yang benar.
4. Jalankan `php index.php migrate` dari root aplikasi. Hasil harus berhenti pada versi 15.
5. Verifikasi login seluruh role, daftar Data Notaris, Data MPD, satu record tiap layanan, rekap, dashboard, serta buka/unduh dokumen.
6. Cocokkan jumlah record sebelum dan sesudah. Migrasi tidak menghapus transaksi laporan.
7. Koreksi tujuh record Data Notaris dan tetapkan pemilik 26 record reportorium berdasarkan arsip resmi.

Jangan mengimpor ulang dump lokal ke production setelah production menerima data baru. Gunakan migrasi untuk perubahan struktur agar data production tidak tertimpa.
