# Konfigurasi Email Transaksional SILARIS

Fitur lupa password dan konfirmasi pendaftaran menggunakan mail server bawaan hosting apabila SMTP khusus tidak dikonfigurasi. Untuk pengiriman yang lebih konsisten, tetapkan environment variable berikut pada server production:

| Variable | Contoh | Keterangan |
| --- | --- | --- |
| `SILARIS_SMTP_HOST` | `smtp.example.go.id` | Host SMTP; jika kosong, sistem memakai PHP `mail` |
| `SILARIS_SMTP_USER` | `noreply@example.go.id` | Username SMTP |
| `SILARIS_SMTP_PASS` | `********` | Password SMTP; jangan simpan di Git |
| `SILARIS_SMTP_PORT` | `587` | Port SMTP |
| `SILARIS_SMTP_CRYPTO` | `tls` | Pilihan `tls` atau `ssl` |
| `SILARIS_MAIL_FROM` | `noreply@example.go.id` | Alamat pengirim yang diizinkan SMTP |
| `SILARIS_MAIL_FROM_NAME` | `SILARIS Kemenkum Sultra` | Nama pengirim |

Setelah deployment, jalankan migrasi sampai versi 29. Uji dengan alamat email internal melalui halaman **Lupa Password**. Sistem hanya menampilkan pesan berhasil apabila transport email mengembalikan status sukses; kegagalan dicatat pada log aplikasi tanpa menulis kredensial atau alamat email lengkap.

Alur registrasi publik:

1. Akun dibuat sebagai role `User` dengan status **Menunggu Verifikasi** dan tidak dapat login.
2. Admin memastikan identitas akun sesuai dengan **Data Notaris**.
3. Admin mengaktifkan status akun. Tindakan tersebut sekaligus mencatat waktu dan ID admin yang memverifikasi.
