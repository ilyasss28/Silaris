# Alur Laporan, Rekap, MPD, dan Backup SILARIS

## Sumber data tunggal

- Data laporan disimpan satu kali di tabel `laporan`.
- Pemilik permanen disimpan di `laporan.owner_user_id`; `username` dipertahankan untuk kompatibilitas data lama.
- Dokumen utama disimpan satu kali di `uploads/laporan/`.
- `uploads/rekap_Laporan/` hanya fallback dokumen lama dan tidak dipakai untuk unggahan baru.
- Rekap Laporan membaca data dan dokumen yang sama; tidak membuat duplikat.

## Matriks akses

| Role | Laporan yang terlihat | Perubahan laporan |
|---|---|---|
| Admin | Semua | Mengikuti izin administrasi |
| Kanwil | Semua | Mengikuti izin administrasi |
| Pimpinan | Semua | Mengikuti izin administrasi |
| MPD | Hanya Notaris dalam wilayah kerja pada `mpd_wilayah` | Read-only |
| User/Notaris | Hanya laporan milik akun sendiri | Tambah, lihat, edit, hapus milik sendiri |

Pembatasan diterapkan di model untuk daftar, hitung, detail, edit, hapus, ekspor, PDF, dokumen, dan manifest. Mengganti ID pada URL tidak boleh melewati aturan ini.

## Pengelolaan akun MPD

Migrasi membuat akun awal `mpd_kendari`, `mpd_baubau`, dan `mpd_konawe`. Ketiganya sengaja nonaktif, memakai identitas bertanda *Belum Diverifikasi*, email lokal tidak valid, dan kata sandi acak yang tidak diketahui.

Sebelum akun digunakan:

1. Buka Administrator > User Management > User, lalu filter group MPD.
2. Edit nama lengkap, username, email, kata sandi, satuan kerja, dan satu atau beberapa Wilayah Kerja MPD.
3. Pastikan hanya group MPD yang sesuai diberikan.
4. Simpan, periksa halaman detail akun, lalu aktifkan akun.
5. Uji bahwa akun MPD tidak melihat kabupaten/kota di luar wilayah kerjanya.

Perubahan identitas atau username akun Notaris tidak memutus laporan lama karena hubungan utama memakai ID pengguna. Akun yang memiliki riwayat laporan tidak boleh dihapus; nonaktifkan akun agar jejak audit tetap utuh.

## Alur operasional

1. Notaris mengunggah laporan dari akun sendiri melalui menu Laporan.
2. Sistem mengambil pemilik dan nama Notaris dari sesi login, bukan input bebas.
3. Admin/Kanwil/Pimpinan memantau seluruh laporan.
4. MPD memantau laporan sesuai wilayah yang ditetapkan administrator.
5. Rekap menyediakan tampilan pengawasan atas sumber laporan yang sama.
6. Koreksi laporan tidak boleh memindahkan pemilik.

## Paket backup standar

```text
silaris-YYYY-MM-DD/
|-- database/silaris-YYYY-MM-DD.sql
|-- documents/laporan.zip
|-- documents/rekap-laporan-legacy.zip
|-- manifest/manifest-laporan-YYYY-MM-DD.csv
`-- checksums/SHA256SUMS.txt
```

Folder legacy hanya disertakan selama masih ada dokumen yang belum dipindahkan ke folder utama.

## Prosedur backup dan pemulihan

1. Hentikan sementara unggahan/edit laporan.
2. Unduh Manifest Backup dari Rekap Laporan.
3. Ekspor seluruh database, termasuk `laporan`, `aauth_users`, `aauth_user_to_group`, dan `mpd_wilayah`.
4. Arsipkan seluruh `uploads/laporan/` dan folder legacy bila masih diperlukan.
5. Buat checksum SHA-256 untuk SQL, ZIP, dan manifest.
6. Simpan sedikitnya dua salinan di lokasi berbeda. Dokumen laporan tidak disimpan di GitHub.
7. Pulihkan paket ke staging dan cocokkan jumlah laporan, status file, serta ukuran file dengan manifest.
8. Uji akun Admin/Kanwil/Pimpinan, satu MPD, dan satu Notaris sebelum backup dinyatakan valid.

Backup dinyatakan valid hanya setelah database, pemetaan MPD, manifest, dan dokumen berhasil dipulihkan bersama.
