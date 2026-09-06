<?php $tahun_panduan = date('Y'); ?>

<main id="main">

  <section class="guest-hero page-hero">
    <span class="hero-orb hero-orb-1" aria-hidden="true"></span>
    <span class="hero-orb hero-orb-2" aria-hidden="true"></span>
    <span class="hero-orb hero-orb-3" aria-hidden="true"></span>
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-9 text-center">
          <span class="hero-badge" data-aos="fade-up" data-aos-delay="100"><i class="dot"></i> PUSAT BANTUAN <i class="dot"></i></span>
          <h1 class="hero-title" data-aos="fade-up" data-aos-delay="200">PANDUAN <span class="highlight">PENGGUNAAN</span></h1>
          <p class="lead" data-aos="fade-up" data-aos-delay="300">Panduan resmi alur pencarian notaris, pemeriksaan kepatuhan, pengiriman laporan, pemantauan wilayah, dan pengawasan data pada SILARIS.</p>
          <a href="#alur-sistem" class="btn-download-pdf" data-aos="fade-up" data-aos-delay="400">
            <i class="icofont-navigation-menu"></i> Mulai Baca Panduan
          </a>
        </div>
      </div>
    </div>
  </section>

  <section id="alur-sistem" class="services panduan-section">
    <div class="container">

      <div class="section-title text-start" data-aos="fade-up">
        <span class="eyebrow"><i class="icofont-direction-sign"></i> Alur Sistem</span>
        <h2>Mulai Sesuai Kebutuhan Anda</h2>
        <p>SILARIS memisahkan akses publik dan akses internal. Data serta tindakan yang tersedia setelah login mengikuti Group/Role akun.</p>
      </div>

      <div class="panduan-report-flow panduan-flow-4" data-aos="fade-up" data-aos-delay="100">
        <article class="panduan-report-step">
          <div class="panduan-report-node"><i class="icofont-globe" aria-hidden="true"></i><span>1</span></div>
          <div class="panduan-report-content"><h4>Akses Informasi Publik</h4><p>Cari notaris aktif dan periksa status pelaporan tahun berjalan tanpa harus masuk ke akun.</p></div>
        </article>
        <article class="panduan-report-step">
          <div class="panduan-report-node"><i class="icofont-login" aria-hidden="true"></i><span>2</span></div>
          <div class="panduan-report-content"><h4>Masuk Sesuai Role</h4><p>Pilih Group/Role, kemudian isi username, password, dan captcha dengan benar.</p></div>
        </article>
        <article class="panduan-report-step">
          <div class="panduan-report-node"><i class="icofont-tasks-alt" aria-hidden="true"></i><span>3</span></div>
          <div class="panduan-report-content"><h4>Kelola atau Pantau</h4><p>Gunakan menu yang tersedia sesuai fungsi dan kewenangan akun Anda.</p></div>
        </article>
        <article class="panduan-report-step">
          <div class="panduan-report-node"><i class="icofont-chart-flow" aria-hidden="true"></i><span>4</span></div>
          <div class="panduan-report-content"><h4>Laporan Menjadi Rekap</h4><p>Laporan tersimpan langsung tersedia pada Rekap sesuai cakupan akses pengguna.</p></div>
        </article>
      </div>

      <div class="section-title text-start panduan-section-gap" data-aos="fade-up">
        <span class="eyebrow"><i class="icofont-search-2"></i> Akses Publik</span>
        <h2>Mencari Notaris dan Memeriksa Kepatuhan</h2>
        <p>Informasi publik bersumber dari data aktif yang dikelola di dalam sistem.</p>
      </div>

      <div class="panduan-report-flow panduan-flow-2" data-aos="fade-up" data-aos-delay="100">
        <article class="panduan-report-step">
          <div class="panduan-report-node"><i class="icofont-search-2" aria-hidden="true"></i><span>1</span></div>
          <div class="panduan-report-content">
            <h4>Cari Notaris Aktif</h4>
            <p>Buka <a href="<?= site_url('home'); ?>">Beranda</a>, pilih kabupaten/kota, lalu telusuri nama Notaris. Sistem hanya menampilkan Notaris aktif beserta informasi publiknya.</p>
          </div>
        </article>
        <article class="panduan-report-step">
          <div class="panduan-report-node"><i class="icofont-verification-check" aria-hidden="true"></i><span>2</span></div>
          <div class="panduan-report-content">
            <h4>Periksa Kepatuhan Tahun <?= html_escape($tahun_panduan); ?></h4>
            <p>Buka <a href="<?= site_url('kepatuhan'); ?>">Kepatuhan Notaris</a> dan cari nama Notaris. Status dihitung dari laporan valid pada tahun <?= html_escape($tahun_panduan); ?>.</p>
          </div>
        </article>
      </div>

      <div class="panduan-note" data-aos="fade-up">
        <i class="icofont-info-circle"></i>
        <div><strong>Arti status kepatuhan:</strong> “Aktif Melapor” berarti sedikitnya satu laporan tahun <?= html_escape($tahun_panduan); ?> telah tercatat. “Belum Melapor” berarti belum ada laporan pada tahun <?= html_escape($tahun_panduan); ?> dan tidak selalu berarti notaris tersebut belum pernah melapor pada tahun sebelumnya.</div>
      </div>

      <div class="section-title text-start panduan-section-gap" data-aos="fade-up">
        <span class="eyebrow"><i class="icofont-login"></i> Akses Akun</span>
        <h2>Cara Masuk ke SILARIS</h2>
        <p>Akun dibuat dan dikelola sesuai kewenangan administrasi. Pastikan data login dan role yang dipilih benar.</p>
      </div>

      <div class="panduan-report-flow panduan-flow-4" data-aos="fade-up" data-aos-delay="100">
        <article class="panduan-report-step">
          <div class="panduan-report-node"><i class="icofont-login" aria-hidden="true"></i><span>1</span></div>
          <div class="panduan-report-content"><h4>Buka Halaman Login</h4><p>Klik tombol <a href="<?= site_url('login'); ?>">Login</a> pada navigasi halaman publik.</p></div>
        </article>
        <article class="panduan-report-step">
          <div class="panduan-report-node"><i class="icofont-users-social" aria-hidden="true"></i><span>2</span></div>
          <div class="panduan-report-content"><h4>Pilih Group/Role</h4><p>Pilih group yang sama dengan group yang melekat pada akun Anda.</p></div>
        </article>
        <article class="panduan-report-step">
          <div class="panduan-report-node"><i class="icofont-key" aria-hidden="true"></i><span>3</span></div>
          <div class="panduan-report-content"><h4>Isi Kredensial</h4><p>Masukkan username, password, dan captcha. Muat ulang captcha jika sulit dibaca.</p></div>
        </article>
        <article class="panduan-report-step">
          <div class="panduan-report-node"><i class="icofont-dashboard-web" aria-hidden="true"></i><span>4</span></div>
          <div class="panduan-report-content"><h4>Masuk ke Dashboard</h4><p>Klik Masuk. Sistem akan menampilkan dashboard dan menu sesuai akun Anda.</p></div>
        </article>
      </div>

      <div class="section-title text-start panduan-section-gap" data-aos="fade-up">
        <span class="eyebrow"><i class="icofont-law-document"></i> Untuk Notaris</span>
        <h2>Mengirim Laporan Bulanan</h2>
        <p>Setiap laporan dikaitkan langsung dengan akun Notaris yang mengunggahnya sehingga tidak tercampur dengan laporan Notaris lain.</p>
      </div>

      <div class="panduan-report-flow" data-aos="fade-up" data-aos-delay="100">
        <article class="panduan-report-step">
          <div class="panduan-report-node">
            <i class="icofont-navigation-menu" aria-hidden="true"></i>
            <span>1</span>
          </div>
          <div class="panduan-report-content">
            <h4>Buka Laporan Bulanan</h4>
            <p>Login sebagai User/Notaris, kemudian buka <strong>Laporan → Laporan Bulanan</strong>. Daftar hanya memuat laporan milik akun Anda.</p>
          </div>
        </article>

        <article class="panduan-report-step">
          <div class="panduan-report-node">
            <i class="icofont-plus-circle" aria-hidden="true"></i>
            <span>2</span>
          </div>
          <div class="panduan-report-content">
            <h4>Tambahkan Laporan</h4>
            <p>Klik <strong>Tambah Data</strong>, isi tanggal laporan yang benar, lalu pilih dokumen yang akan dikirim.</p>
          </div>
        </article>

        <article class="panduan-report-step">
          <div class="panduan-report-node">
            <i class="icofont-file-document" aria-hidden="true"></i>
            <span>3</span>
          </div>
          <div class="panduan-report-content">
            <h4>Periksa Dokumen</h4>
            <p>Pastikan format didukung, ukuran maksimal 10 MB, dan dokumen dapat dibuka sebelum diunggah.</p>
          </div>
        </article>

        <article class="panduan-report-step">
          <div class="panduan-report-node">
            <i class="icofont-verification-check" aria-hidden="true"></i>
            <span>4</span>
          </div>
          <div class="panduan-report-content">
            <h4>Simpan dan Verifikasi</h4>
            <p>Simpan data, pastikan laporan muncul pada daftar, lalu buka detail untuk memeriksa tanggal dan dokumennya.</p>
          </div>
        </article>

        <article class="panduan-report-step">
          <div class="panduan-report-node">
            <i class="icofont-edit" aria-hidden="true"></i>
            <span>5</span>
          </div>
          <div class="panduan-report-content">
            <h4>Perbaiki Jika Diperlukan</h4>
            <p>Gunakan <strong>Edit</strong> untuk memperbarui atau <strong>Hapus</strong> jika data salah, lalu periksa kembali hasilnya.</p>
          </div>
        </article>
      </div>

      <div class="panduan-note panduan-note-warning" data-aos="fade-up">
        <i class="icofont-shield"></i>
        <div><strong>Penting:</strong> jangan menggunakan akun Notaris lain dan jangan membagikan password. Wilayah resmi Notaris tidak diubah melalui profil pribadi; hubungi Admin jika identitas atau wilayah perlu diperbaiki.</div>
      </div>

      <div class="section-title text-start panduan-section-gap" data-aos="fade-up">
        <span class="eyebrow"><i class="icofont-people"></i> Untuk MPD</span>
        <h2>Pengawasan Laporan Beberapa Wilayah</h2>
        <p>Data MPD menjadi sumber resmi identitas dan cakupan pengawasan. Satu akun MPD dapat ditugaskan pada satu atau beberapa kabupaten/kota tanpa mencampurkan data di luar wilayahnya.</p>
      </div>

      <div class="panduan-report-flow" data-aos="fade-up" data-aos-delay="100">
        <article class="panduan-report-step">
          <div class="panduan-report-node"><i class="icofont-user-alt-3" aria-hidden="true"></i><span>1</span></div>
          <div class="panduan-report-content"><h4>Buat Akun MPD</h4><p>Admin membuat akun melalui <strong>Administrator &rarr; User</strong> dan memberikan role MPD. Akun baru tetap nonaktif sampai data resminya selesai diverifikasi.</p></div>
        </article>
        <article class="panduan-report-step">
          <div class="panduan-report-node"><i class="icofont-id-card" aria-hidden="true"></i><span>2</span></div>
          <div class="panduan-report-content"><h4>Lengkapi Data MPD</h4><p>Buka <strong>Setup &rarr; Data MPD</strong>, hubungkan akun, lalu isi nama, jabatan, kontak, nomor SK, dan masa jabatan.</p></div>
        </article>
        <article class="panduan-report-step">
          <div class="panduan-report-node"><i class="icofont-map-pins" aria-hidden="true"></i><span>3</span></div>
          <div class="panduan-report-content"><h4>Tentukan Beberapa Wilayah</h4><p>Pilih seluruh kabupaten/kota yang menjadi wilayah kerja. Penambahan atau pengurangan wilayah langsung mengubah cakupan laporan dan rekap MPD.</p></div>
        </article>
        <article class="panduan-report-step">
          <div class="panduan-report-node"><i class="icofont-verification-check" aria-hidden="true"></i><span>4</span></div>
          <div class="panduan-report-content"><h4>Verifikasi dan Aktifkan</h4><p>Admin atau Kanwil mencentang verifikasi setelah data diperiksa, kemudian mengaktifkan akun pada daftar pengguna. MPD yang belum terdaftar atau belum terverifikasi tidak dapat login.</p></div>
        </article>
        <article class="panduan-report-step">
          <div class="panduan-report-node"><i class="icofont-eye-alt" aria-hidden="true"></i><span>5</span></div>
          <div class="panduan-report-content"><h4>Pantau Laporan Wilayah</h4><p>MPD login dan membuka menu Laporan atau Rekap. Sistem otomatis hanya menampilkan laporan Notaris dari seluruh wilayah yang ditugaskan kepadanya.</p></div>
        </article>
        <article class="panduan-report-step">
          <div class="panduan-report-node"><i class="icofont-refresh" aria-hidden="true"></i><span>6</span></div>
          <div class="panduan-report-content"><h4>Perbarui Penugasan</h4><p>Jika susunan MPD atau wilayah berubah, Admin memperbarui Data MPD. Saat role dicabut atau data dihapus, hubungan wilayah dilepas dan akun terkait dinonaktifkan.</p></div>
        </article>
      </div>

      <div class="panduan-note" data-aos="fade-up">
        <i class="icofont-lock"></i>
        <div><strong>Batas akses MPD:</strong> MPD tidak mengunggah data atas nama Notaris dan tidak dapat melihat kabupaten/kota di luar penugasannya. Admin dan Kanwil tetap dapat melihat seluruh wilayah.</div>
      </div>

      <div class="section-title text-start panduan-section-gap" data-aos="fade-up">
        <span class="eyebrow"><i class="icofont-question-circle"></i> FAQ</span>
        <h2>Pertanyaan yang Sering Diajukan</h2>
      </div>

      <div class="accordion panduan-faq" id="faqAccordion" data-aos="fade-up" data-aos-delay="100">
        <div class="accordion-item">
          <h3 class="accordion-header"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">Apakah pencarian notaris dan pemeriksaan kepatuhan berbayar?</button></h3>
          <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion"><div class="accordion-body">Tidak. Informasi publik SILARIS dapat diakses tanpa biaya dan tanpa login.</div></div>
        </div>
        <div class="accordion-item">
          <h3 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">Mengapa saya tidak dapat login padahal username dan password benar?</button></h3>
          <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">Pastikan Group/Role yang dipilih sama dengan group akun, captcha benar, dan tidak ada spasi tambahan pada username. Jika tetap gagal, gunakan <a href="<?= site_url('administrator/forgot-password'); ?>">Lupa Password</a> atau hubungi Admin. Jangan membuat akun duplikat.</div></div>
        </div>
        <div class="accordion-item">
          <h3 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">Mengapa laporan Notaris lain tidak muncul pada akun saya?</button></h3>
          <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">Itu adalah perilaku yang benar. Akun User/Notaris hanya dapat melihat dan mengelola laporan miliknya. MPD hanya melihat wilayah penugasan, sedangkan Admin dan Kanwil dapat melihat seluruh laporan.</div></div>
        </div>
        <div class="accordion-item">
          <h3 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">Apakah Rekap Layanan merupakan data atau unggahan terpisah?</button></h3>
          <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">Tidak. Rekap Layanan adalah tampilan pemantauan hanya-baca yang menggabungkan seluruh jenis layanan dari sumber data aslinya. Perubahan data tetap dilakukan melalui menu Laporan.</div></div>
        </div>
        <div class="accordion-item">
          <h3 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">Dokumen sudah dipilih tetapi tidak dapat dibuka, apa yang harus diperiksa?</button></h3>
          <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">Pastikan penyimpanan laporan berhasil, nama file tampil pada detail, format didukung, ukuran tidak melebihi 10 MB, dan berkas tersedia pada penyimpanan server. Jika data ada tetapi berkas tidak tersedia, hubungi Admin dengan menyertakan nama Notaris, tanggal laporan, dan nama file.</div></div>
        </div>
      </div>

      <div class="panduan-help-card" data-aos="zoom-in" data-aos-delay="100">
        <div>
          <h4>Masih membutuhkan bantuan?</h4>
          <p>Sertakan nama akun, role, waktu kejadian, dan tangkapan layar agar kendala dapat ditelusuri lebih cepat. Jangan pernah mengirimkan password.</p>
        </div>
        <div class="panduan-help-actions">
          <a href="https://wa.me/6281140044555" target="_blank" rel="noopener" class="btn-help"><i class="icofont-whatsapp"></i> WhatsApp</a>
          <a href="mailto:kanwilsultra@kemenkum.go.id" class="btn-help btn-help-outline"><i class="icofont-envelope"></i> Email</a>
        </div>
      </div>

    </div>
  </section>

</main>
