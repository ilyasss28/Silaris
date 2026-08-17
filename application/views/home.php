<main id="main">

    <!-- ======= Hero / Search ======= -->
    <section class="guest-hero">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-8 text-center">
            <span class="eyebrow"><i class="icofont-verification-check"></i> Kanwil Kemenkumham Sulawesi Tenggara</span>
            <h1>Cari Notaris di Sulawesi Tenggara</h1>
            <p class="lead">Temukan data notaris terdaftar berdasarkan wilayah kerja secara cepat dan mudah.</p>

            <form id="w0" action="<?php echo base_url().'home/index'?>" method="get" class="search-card">
              <input type="hidden" name="r" value="site/detail-notaris">
              <input type="text" id="nama" placeholder="Cari nama notaris...">
              <input type="hidden" id="id_notaris" name="id">
              <input type="submit" value="Cari">
            </form>
          </div>
        </div>
      </div>
    </section><!-- End Hero -->

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
