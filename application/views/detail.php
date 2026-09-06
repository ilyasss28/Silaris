<main id="main">

    <!-- ======= Services Section ======= -->
    <section id="services" class="services">
      <div class="container">

        <div class="section-title">
          <h2>Notaris</h2>
        </div>


        <ul class="breadcrumb"><li><a class="homeLink" href="<?= site_url('home'); ?>"><i class="icofont-home"></i> Beranda/</a></li>
<li class="active"> Profil <?php echo $nama_notaris; ?></li>
</ul>      <!-- NOTARIS -->
      <div class="row">
      <div class="col-lg-8 mt-8 mt-lg-0  align-items-stretch">
        <div class="row"> 
          <div class="col-lg-3 col-md-3 align-items-stretch" style="text-align: center;">
              <div style="border: 1px solid #d6d6d6; border-radius: 5px; padding: 5px; margin: auto; display: inline-block;">
                      <img style="border: 1px solid #d6d6d6;" src="<?= _ent($photo_url); ?>" alt="Foto <?= _ent($nama_notaris); ?>" height="190">
              </div>

              <div class="rate bg-warning py-3 text-white mt-3">
                <h6 class="mb-0">Rating Notaris</h6>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
              </div>

              

              <div class="rate bg-success py-3 text-white mt-3">
                <h6 class="mb-0">Tingkat Kepatuhan Notaris</h6>
                <div class="rating">
                </div>
              </div>
          </div>

          <div class="col-lg-8 col-md-8 align-items-stretch">
            <div class="profilinfo">
                <div class="nama">
                  <i class="icofont-user"></i>
                  <h4><?php echo $nama_notaris; ?></h4>
                  <p>Notaris </p>
                </div>

                <div class="phone">
                  <i class="icofont-plus"></i>
                  <h4>Berkedudukan di</h4>
                  <p><?php echo $wilayah; ?></p>
                </div>

                <div class="phone">
                  <i class="icofont-phone"></i>
                  <h4>Telepon kantor:</h4>
                  <p><?= _ent(format_phone_number($no_telepon)); ?></p>
                </div>
                 
                <div class="email">
                  <i class="icofont-envelope"></i>
                  <h4>Email:</h4>
                  <p><?php echo $email; ?></p>
                </div>
              
                <div class="address">
                  <i class="icofont-google-map"></i>
                  <h4>Alamat:</h4>
                  <p><?php echo $alamat_kantor; ?></p>
                </div>

              
          </div> 
        </div> 

        <!-- table 2 -->
           <div class="col-lg-12 col-md-12 align-items-stretch"> 
            <iframe src="https://maps.google.com/maps?q=<?php echo $lat; ?>&amp;t=&amp;z=17&amp;ie=UTF8&amp;iwloc=&amp;output=embed" frameborder="0" style="border:0; width: 100%; height: 290px;" allowfullscreen></iframe>            
                      </div> 


          <!-- foto kantor  -->
          <!-- <div class="col-lg-12 col-md-12 align-items-stretch">
                <div id="myCarousel" class="carousel slide" data-ride="carousel">
                    
                                        
                    <ol class="carousel-indicators">
                                                    <li data-bs-target="#myCarousel" data-bs-slide-to="0" class="active"></li>
                                            </ol>

                    
                    <div class="carousel-inner">
                                                    <div class="item active">                        
                                <img src="uploads/foto_kantor/1678692536-734786.png" alt="Foto Kantor">
                            </div>
                                            </div>

                    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#myCarousel" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div> -->
        
        <!-- tutup div  -->
       </div>
      </div>
      <div class="col-lg-4 d-flex align-items-stretch">
        <div class="info">
          <aside class="single_sidebar_widget ">
              <h4 class="widget_title"> Wilayah Notaris </h4>
              <ul class="list cat-list">            
                <?php foreach ($area as $area) : ?>
                <li>
                    <a href="<?php echo base_url('daftar/').$area['kode_wilayah']?>" class="d-flex justify-content-between">
                        <p><?php echo $area['wilayah']; ?></p>
                        <i><?php echo $area['jumlah']; ?></i>
                    </a>
                </li>
                <?php endforeach;?>
              </ul>
          </aside>
        </div>

      </div>




    </section>
    
    <!-- End Services Section -->


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
