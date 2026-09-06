<?php
/**
 * @var array $notaris
 * @var array $area
 */
?>
<main id="main">

<!-- ======= Services Section ======= -->
<section id="services" class="services">
  <div class="container">

    <div class="section-title">
      <h2>Notaris</h2>
      <p>Sebaran Notaris di  Kabupaten / Kota Wilayah Sulawei Tenggara</p>
    </div>
    <ul class="breadcrumb"><li><a class="homeLink" href="<?= site_url('home'); ?>"><i class="icofont-home"></i> Beranda /</a></li>
      <li class="active">Daftar Notaris </li>
    </ul>       
      <!-- NOTARIS -->
    <div class="row">
      <div class="col-lg-8 mt-8 mt-lg-0 ">
        <div class="row"> 
          <?php foreach ($notaris as $notaris) { 
            $id_notaris=$notaris->id_notaris;
            $nama_notaris=$notaris->nama_notaris;
            $photo_url=$notaris->photo_url;
            $wilayah=$notaris->wilayah;
            ?>
          <div class="col-lg-3 mt-4 mt-lg-0 ">
            <div class="member">
              <a href="<?= site_url('notaris/'.$id_notaris); ?>">
                  <img src="<?= _ent($photo_url); ?>" alt="Foto <?= _ent($nama_notaris); ?>" loading="lazy">
              </a>
              <a href="<?= site_url('notaris/'.$id_notaris); ?>"><h4><?php echo $nama_notaris; ?></h4></a>
              <span>Notaris <?php echo $wilayah; ?></span>
              <div class="social">
                <a href="#"><i class="icofont-whatsapp"></i></a>
                <a href="#"><i class="icofont-facebook"></i></a>
                <a href="#"><i class="icofont-instagram"></i></a>
              </div>
            </div>
          </div>
          <?php }?> 
        </div>
        
      </div>

      <div class="col-lg-4 notary-region-column">
        <div class="info notary-region-box">
          <div class="address">
          </div>

            <aside class="single_sidebar_widget notary-region-sidebar">
                <h4 class="widget_title"> Wilayah Notaris </h4>
                <ul class="list cat-list">
                
                <?php foreach ($area as $area) : ?>
                    <li>
                        <a href="<?= site_url('daftar/'.$area['kode_wilayah']); ?>" class="d-flex justify-content-between">
                            <p><?php echo $area['wilayah']; ?></p>
                            <i><?php echo $area['jumlah']; ?></i>
                        </a>
                    </li>
                    <?php endforeach;?>
                  </ul>
                <div class="br">
                </div>
            </aside>
          </div>

        </div>

      </div>
    </div>
  </div>
</section><!-- End Services Section -->


<!-- ======= Our Clients Section ======= -->
<section id="clients" class="clients">
  <div class="container">

    <!-- <div class="section-title">
      <h2>Our Clients</h2>
      <p>Magnam dolores commodi suscipit. Necessitatibus eius consequatur ex aliquid fuga eum quidem. Sit sint consectetur velit. Quisquam quos quisquam cupiditate. Et nemo qui impedit suscipit alias ea. Quia fugiat sit in iste officiis commodi quidem hic quas.</p>
    </div> -->

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

</main><!-- End #main -->            </div>
<!-- <section> -->
