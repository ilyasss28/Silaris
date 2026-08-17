<?php
  $total_notaris = is_array($notaris) ? count($notaris) : 0;
  $total_wilayah = is_array($wilayah) ? count($wilayah) : 0;
?>
<main id="main">

    <!-- ======= Hero / Search ======= -->
    <section class="guest-hero">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-9 text-center">
            <span class="hero-badge"><i class="dot"></i> KANWIL KEMENKUM SULAWESI TENGGARA <i class="dot"></i></span>
            <h1 class="hero-title">TEMUKAN NOTARIS <em>ANDA</em></h1>
            <p class="lead">Cari data notaris terdaftar di Sulawesi Tenggara berdasarkan nama atau wilayah kerja, langsung dari basis data resmi SILARIS.</p>

            <form id="w0" action="<?php echo base_url().'home/index'?>" method="get" class="search-card">
              <input type="hidden" name="r" value="site/detail-notaris">
              <input type="text" id="nama" placeholder="Cari nama notaris...">
              <input type="hidden" id="id_notaris" name="id">
              <input type="submit" value="Cari">
            </form>

            <!-- ======= Quick stats ======= -->
            <div class="row stat-row justify-content-center">
              <div class="col-6 col-md-3">
                <div class="stat-tile">
                  <div class="stat-number"><?php echo $total_notaris; ?>+</div>
                  <div class="stat-label">Notaris Terdaftar</div>
                </div>
              </div>
              <div class="col-6 col-md-3">
                <div class="stat-tile">
                  <div class="stat-number"><?php echo $total_wilayah; ?></div>
                  <div class="stat-label">Kabupaten / Kota</div>
                </div>
              </div>
              <div class="col-6 col-md-3">
                <div class="stat-tile">
                  <div class="stat-number">24/7</div>
                  <div class="stat-label">Layanan Pencarian Online</div>
                </div>
              </div>
              <div class="col-6 col-md-3">
                <div class="stat-tile">
                  <div class="stat-number">Gratis</div>
                  <div class="stat-label">Tanpa Biaya</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section><!-- End Hero -->

    <!-- ======= About / Informasi ======= -->
    <section id="about" class="about-section">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-6">
            <span class="eyebrow eyebrow-dark"><i class="icofont-info-circle"></i> Tentang SILARIS</span>
            <h2>Sistem Pelaporan Notaris Terintegrasi</h2>
            <p>SILARIS adalah kanal resmi Kantor Wilayah Kementerian Hukum dan HAM Sulawesi Tenggara untuk pelaporan dan pemantauan kepatuhan notaris. Melalui sistem ini, masyarakat dapat mencari data notaris terdaftar, sementara notaris dapat menyampaikan laporan bulanan secara daring.</p>
            <ul class="check-list">
              <li><i class="icofont-check-circled"></i> Data notaris resmi dan selalu diperbarui</li>
              <li><i class="icofont-check-circled"></i> Pencarian berdasarkan wilayah kerja</li>
              <li><i class="icofont-check-circled"></i> Pelaporan kepatuhan notaris daring</li>
            </ul>
          </div>
          <div class="col-lg-6">
            <div class="steps">
              <div class="step">
                <div class="step-num">1</div>
                <div>
                  <h4>Cari atau Pilih Wilayah</h4>
                  <p>Gunakan kolom pencarian atau pilih kabupaten/kota pada daftar wilayah di bawah.</p>
                </div>
              </div>
              <div class="step">
                <div class="step-num">2</div>
                <div>
                  <h4>Lihat Daftar Notaris</h4>
                  <p>Telusuri notaris yang terdaftar dan aktif di wilayah tersebut.</p>
                </div>
              </div>
              <div class="step">
                <div class="step-num">3</div>
                <div>
                  <h4>Lihat Detail &amp; Kontak</h4>
                  <p>Buka profil notaris untuk melihat informasi kantor dan kontak lengkap.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section><!-- End About -->

    <!-- ======= Services Section ======= -->
    <section id="services" class="services">
      <div class="container">

        <div class="section-title">
          <h2>Sebaran Notaris</h2>
          <p>Sebaran Notaris di Kabupaten / Kota Wilayah Sulawesi Tenggara</p>
        </div>

        <div class="row">
        <?php foreach ($wilayah as $wilayah) : ?>
                <div class="col-lg-4 mb-4">
                  <div class="icon-box2">
                    <h3><a href="<?php echo base_url('daftar/').$wilayah['kode_wilayah']?>"><?php echo $wilayah['wilayah']; ?></a></h3>
                    <hr>
                    <div class="row align-items-center">
                      <div class="col-lg-1 col-2">
                        <i class="icofont-users"></i>
                      </div>
                      <div class="col-lg-9 col-8">
                        <p><b><?php echo $wilayah['jumlah']; ?></b> Notaris</p>
                      </div>
                      <div class="col-lg-1 col-2">
                        <a href="<?php echo base_url('daftar/').$wilayah['kode_wilayah']?>"><i class="icofont-circled-right"></i></a>
                      </div>
                    </div>
                  </div>
                </div>
                <?php endforeach;?>
        </div>

        <!-- peta -->
        <div class="section-title" style="margin-top: 40px;">
          <h2>Peta Sebaran</h2>
          <p>Sebaran Notaris Dalam Peta</p>
        </div>
        <div class="map-wrap">
          <iframe src="https://www.google.com/maps/d/u/0/embed?mid=1PsHUAFrHwxpJ0lW8Gpo8ojdFrG5ugm4&ehbc=2E312F" width="100%" height="500"></iframe>
        </div>

      </div>
    </section><!-- End Services Section -->

    <!-- ======= Our Clients Section ======= -->
    <section id="clients" class="clients">
      <div class="container">
        <div class="section-title">
          <h2>Mitra &amp; Kolaborasi</h2>
        </div>
        <div class="owl-carousel clients-carousel">
          <img src="<?php echo base_url('assets')?>/assets-guest/img/logo/wbbm.png" alt="">
          <img src="<?php echo base_url('assets')?>/assets-guest/img/logo/kemenkumham.png" alt="">
          <img src="<?php echo base_url('assets')?>/assets-guest/img/logo/coorporate.png" alt="">
          <img src="<?php echo base_url('assets')?>/assets-guest/img/logo/egov.png" alt="">
          <img src="<?php echo base_url('assets')?>/assets-guest/img/logo/always.png" alt="">
          <img src="<?php echo base_url('assets')?>/assets-guest/img/logo/icare.png" alt="">
          <img src="<?php echo base_url('assets')?>/assets-guest/img/logo/reformasi.png" alt="">
        </div>
      </div>
    </section><!-- End Our Clients Section -->

  </main><!-- End #main -->
