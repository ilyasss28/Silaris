<main id="main">

  <!-- ======= Hero ======= -->
  <section class="guest-hero page-hero">
    <span class="hero-orb hero-orb-1" aria-hidden="true"></span>
    <span class="hero-orb hero-orb-2" aria-hidden="true"></span>
    <span class="hero-orb hero-orb-3" aria-hidden="true"></span>
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-9 text-center">
          <span class="hero-badge"><i class="dot"></i> PUSAT BANTUAN <i class="dot"></i></span>
          <h1 class="hero-title">PANDUAN <em>PENGGUNAAN</em></h1>
          <p class="lead">Cara menggunakan SILARIS untuk mencari notaris, memeriksa kepatuhan pelaporan, dan &mdash; bagi notaris &mdash; masuk ke akun serta menyampaikan laporan bulanan.</p>

          <a href="https://drive.google.com/file/d/1Uq80Hc8keZSVG9sngEMYPKD2WjebvVoY/view?usp=sharing" target="_blank" rel="noopener" class="btn-download-pdf">
            <i class="icofont-download"></i> Unduh Panduan Resmi (PDF)
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- ======= Untuk Masyarakat ======= -->
  <section id="services" class="services panduan-section">
    <div class="container">

      <ul class="breadcrumb">
        <li><a class="homeLink" href="<?php echo base_url().'home/index'?>"><i class="icofont-home"></i> Beranda /</a></li>
        <li class="active">Panduan</li>
      </ul>

      <div class="section-title text-start">
        <span class="eyebrow"><i class="icofont-users-alt-4"></i> Untuk Masyarakat</span>
        <h2>Mencari Data Notaris</h2>
        <p>Tiga langkah untuk menemukan notaris terdaftar di wilayah Sulawesi Tenggara.</p>
      </div>

      <div class="row g-4 panduan-steps">
        <div class="col-md-4">
          <div class="panduan-card">
            <div class="panduan-num">1</div>
            <h4>Cari atau Pilih Wilayah</h4>
            <p>Buka halaman <a href="<?= site_url('home'); ?>">Beranda</a>, lalu ketik nama notaris pada kolom pencarian, atau pilih salah satu kabupaten/kota pada daftar "Sebaran Notaris".</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="panduan-card">
            <div class="panduan-num">2</div>
            <h4>Telusuri Daftar Notaris</h4>
            <p>Sistem menampilkan daftar notaris yang terdaftar dan aktif di wilayah yang dipilih, lengkap dengan foto dan nama kantor.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="panduan-card">
            <div class="panduan-num">3</div>
            <h4>Lihat Detail &amp; Kontak</h4>
            <p>Klik nama notaris untuk membuka profil lengkap: alamat kantor, wilayah kerja, dan informasi kontak yang dapat dihubungi.</p>
          </div>
        </div>
      </div>

      <div class="section-title text-start panduan-section-gap">
        <span class="eyebrow"><i class="icofont-verification-check"></i> Untuk Masyarakat</span>
        <h2>Memeriksa Kepatuhan Notaris</h2>
        <p>Cek apakah seorang notaris aktif menyampaikan laporan bulanan kepada Kanwil Kemenkum.</p>
      </div>

      <div class="row g-4 panduan-steps">
        <div class="col-md-6">
          <div class="panduan-card">
            <div class="panduan-num">1</div>
            <h4>Buka Halaman Kepatuhan Notaris</h4>
            <p>Klik menu <a href="<?= base_url('kepatuhan/index'); ?>">Kepatuhan Notaris</a> pada navigasi atas.</p>
          </div>
        </div>
        <div class="col-md-6">
          <div class="panduan-card">
            <div class="panduan-num">2</div>
            <h4>Cari Nama &amp; Lihat Status</h4>
            <p>Gunakan kolom pencarian untuk menemukan notaris tertentu. Status <span class="status-badge status-ok d-inline-flex"><i class="icofont-check-circled"></i> Aktif Melapor</span> menandakan notaris tersebut memiliki riwayat laporan bulanan.</p>
          </div>
        </div>
      </div>

      <!-- ======= Untuk Notaris ======= -->
      <div class="section-title text-start panduan-section-gap">
        <span class="eyebrow"><i class="icofont-law-document"></i> Untuk Notaris</span>
        <h2>Masuk &amp; Menyampaikan Laporan</h2>
        <p>Panduan bagi notaris yang telah memiliki akun SILARIS.</p>
      </div>

      <div class="row g-4 panduan-steps">
        <div class="col-md-4">
          <div class="panduan-card">
            <div class="panduan-num">1</div>
            <h4>Masuk ke Akun</h4>
            <p>Klik tombol <a href="<?= base_url('login'); ?>">Login</a> pada pojok kanan atas, lalu masukkan username dan password akun notaris Anda.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="panduan-card">
            <div class="panduan-num">2</div>
            <h4>Belum Punya Akun?</h4>
            <p>Pada halaman login, klik tautan pendaftaran untuk membuat akun baru, lalu lengkapi data yang diminta.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="panduan-card">
            <div class="panduan-num">3</div>
            <h4>Sampaikan Laporan Bulanan</h4>
            <p>Setelah masuk, unggah laporan bulanan Anda melalui menu yang tersedia pada dasbor akun. Laporan yang tersimpan akan langsung tercatat pada status kepatuhan Anda.</p>
          </div>
        </div>
      </div>

      <!-- ======= FAQ ======= -->
      <div class="section-title text-start panduan-section-gap">
        <span class="eyebrow"><i class="icofont-question-circle"></i> FAQ</span>
        <h2>Pertanyaan yang Sering Diajukan</h2>
      </div>

      <div class="accordion panduan-faq" id="faqAccordion">

        <div class="accordion-item">
          <h3 class="accordion-header">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
              Apakah pencarian data notaris berbayar?
            </button>
          </h3>
          <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
            <div class="accordion-body">Tidak. Pencarian dan pemeriksaan data notaris melalui SILARIS sepenuhnya gratis dan dapat diakses kapan saja.</div>
          </div>
        </div>

        <div class="accordion-item">
          <h3 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
              Notaris yang saya cari tidak muncul di daftar, apa artinya?
            </button>
          </h3>
          <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
            <div class="accordion-body">Data notaris pada SILARIS mengikuti pencatatan resmi Kanwil Kemenkum Sulawesi Tenggara. Jika notaris yang Anda cari belum muncul, silakan hubungi kami melalui kontak yang tercantum di bagian bawah halaman ini untuk verifikasi lebih lanjut.</div>
          </div>
        </div>

        <div class="accordion-item">
          <h3 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
              Apa arti status "Belum Melapor" pada halaman Kepatuhan Notaris?
            </button>
          </h3>
          <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
            <div class="accordion-body">Status ini berarti sistem belum mencatat adanya laporan bulanan dari akun notaris tersebut. Status akan otomatis berubah menjadi "Aktif Melapor" setelah notaris menyampaikan laporan melalui akunnya.</div>
          </div>
        </div>

        <div class="accordion-item">
          <h3 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
              Saya lupa password akun notaris saya, bagaimana caranya?
            </button>
          </h3>
          <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
            <div class="accordion-body">Pada halaman <a href="<?= base_url('login'); ?>">Login</a>, gunakan tautan lupa password untuk memulai proses pemulihan akun.</div>
          </div>
        </div>

      </div>

      <!-- ======= Kontak bantuan ======= -->
      <div class="panduan-help-card">
        <div>
          <h4>Masih butuh bantuan?</h4>
          <p>Tim Kanwil Kemenkum Sulawesi Tenggara siap membantu Anda.</p>
        </div>
        <div class="panduan-help-actions">
          <a href="https://wa.me/6281355554600" target="_blank" rel="noopener" class="btn-help"><i class="icofont-whatsapp"></i> WhatsApp</a>
          <a href="mailto:sultra@kemenkum.go.id" class="btn-help btn-help-outline"><i class="icofont-envelope"></i> Email</a>
        </div>
      </div>

    </div>
  </section>

</main>
