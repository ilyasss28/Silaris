<?php
/**
 * @var array $wilayah
 */
?>
<main id="main">

    <!-- ======= Hero ======= -->
    <section class="guest-hero hero-showcase">
      <span class="hero-orb hero-orb-1" aria-hidden="true"></span>
      <span class="hero-orb hero-orb-2" aria-hidden="true"></span>
      <span class="hero-orb hero-orb-3" aria-hidden="true"></span>
      <div class="container">
        <div class="row hero-grid">
          <div class="col-lg-6 hero-copy">
            <span class="hero-badge fade-in-up" style="animation-delay: 0.1s;"><i class="dot"></i> Sistem Pelaporan Notaris <i class="dot"></i></span>
            <h1 class="hero-title fade-in-up" style="animation-delay: 0.2s;">Kanwil Kemenkum <span class="highlight">Sultra</span></h1>
            <p class="lead fade-in-up" style="animation-delay: 0.3s;">Kanal resmi pelaporan, pemeriksaan, dan pengawasan kenotariatan di wilayah Sulawesi Tenggara.</p>
          </div>
          <div class="col-lg-6 hero-media fade-in-up" style="animation-delay: 0.4s;">
            <span class="hero-media-glow" aria-hidden="true"></span>
            <img src="<?php echo base_url('assets')?>/assets-guest/img/Model.png" alt="Sistem Pelaporan Notaris - Kanwil Kemenkum Sulawesi Tenggara" class="hero-photo">
          </div>
        </div>
      </div>
    </section><!-- End Hero -->

    <!-- ======= About / Informasi ======= -->
    <section id="about" class="about-section">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-6" data-aos="fade-right">
            <span class="eyebrow eyebrow-dark"><i class="icofont-info-circle"></i> Tentang SILARIS</span>
            <h2>Sistem Pelaporan Notaris Terintegrasi</h2>
            <p>SILARIS adalah kanal resmi Kantor Wilayah Kementerian Hukum dan HAM Sulawesi Tenggara untuk pelaporan dan pemantauan kepatuhan notaris. Melalui sistem ini, masyarakat dapat mencari data notaris terdaftar, sementara notaris dapat menyampaikan laporan bulanan secara daring.</p>
            <ul class="check-list">
              <li><i class="icofont-check-circled"></i> Data notaris resmi dan selalu diperbarui</li>
              <li><i class="icofont-check-circled"></i> Pencarian berdasarkan wilayah kerja</li>
              <li><i class="icofont-check-circled"></i> Pelaporan kepatuhan notaris daring</li>
            </ul>
          </div>
          <div class="col-lg-6" data-aos="fade-left" data-aos-delay="200">
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

        <div class="section-title" data-aos="fade-up">
          <h2>Sebaran Notaris</h2>
          <p>Sebaran Notaris di Kabupaten / Kota Wilayah Sulawesi Tenggara</p>
        </div>

        <div class="row">
        <?php $aos_delay = 100; foreach ($wilayah as $wilayah) : ?>
                <div class="col-lg-4 mb-4" data-aos="zoom-in" data-aos-delay="<?php echo $aos_delay; ?>">
                <?php 
                $nama_wilayah = $wilayah['wilayah'];
                $singkatan = array('Butur', 'Busel', 'Buteng', 'Konut', 'Konsel', 'Kolut', 'Koltim', 'Mubar');
                $panjang = array('Buton Utara', 'Buton Selatan', 'Buton Tengah', 'Konawe Utara', 'Konawe Selatan', 'Kolaka Utara', 'Kolaka Timur', 'Muna Barat');
                $nama_wilayah = str_ireplace($singkatan, $panjang, $nama_wilayah);
                ?>
                <a href="<?= site_url('daftar/'.$wilayah['kode_wilayah']); ?>" style="text-decoration: none; color: inherit; display: block;">
                  <div class="icon-box2">
                    <h3><?php echo $nama_wilayah; ?></h3>
                    <hr>
                    <div class="row align-items-center">
                      <div class="col-lg-1 col-2">
                        <i class="icofont-users"></i>
                      </div>
                      <div class="col-lg-9 col-8">
                        <p><b><?php echo $wilayah['jumlah']; ?></b> Notaris</p>
                      </div>
                      <div class="col-lg-1 col-2">
                        <i class="icofont-circled-right"></i>
                      </div>
                    </div>
                  </div>
                </a>
                </div>
                <?php $aos_delay = ($aos_delay >= 300) ? 100 : $aos_delay + 100; endforeach;?>
        </div>

        <!-- peta -->
        <div class="section-title" style="margin-top: 40px;" data-aos="fade-up">
          <h2>Peta Sebaran</h2>
          <p>Sebaran Notaris Dalam Peta</p>
        </div>
        <div class="map-wrap" data-aos="zoom-in" data-aos-delay="200">
          <iframe src="https://www.google.com/maps/d/u/0/embed?mid=1PsHUAFrHwxpJ0lW8Gpo8ojdFrG5ugm4&ehbc=2E312F" width="100%" height="500"></iframe>
        </div>

      </div>
    </section><!-- End Services Section -->

    <!-- ======= Our Clients Section ======= -->
    <section id="clients" class="clients">
      <div class="container">
        <div class="section-title" data-aos="fade-up">
          <h2>Mitra &amp; Kolaborasi</h2>
        </div>
        <div class="owl-carousel clients-carousel" data-aos="fade-up" data-aos-delay="200">
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
