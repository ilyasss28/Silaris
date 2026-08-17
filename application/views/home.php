<main id="main">

    <!-- ======= Featured Services Section ======= -->
    <section id="featured-services" class="featured-services section-bg" 
      style="
      padding: 50px 0;
      background: #f6f6ff;
      text-align: center;
      font-size: 15px;
    ">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-6" style="text-align: left;">
            <h4><strong> Cari Notaris ?</strong></h4>
            <p>Temukan Notaris di sekitar area Sulawesi Tenggara</p>
          </div>
          <div class="col-lg-6" style="padding: initial;">
          <form id="w0" action="<?php echo base_url().'home/index'?>" method="get" style="margin-top: 30px;
            background: #fff;
            padding: 0px 0px;
            position: relative;
            border-radius: 4px;
            box-shadow: 0px 2px 15px rgb(0 0 0 / 10%);
            text-align: left;">
<input type="hidden" name="r" value="site/detail-notaris">            <!-- <form 
            action = "/tmp_kemenkumham/web/index.php?r=site/detail-notaris"
            method="get" 
            style="
                margin-top: 30px;
                background: #fff;
                padding: 6px 10px;
                position: relative;
                border-radius: 4px;
                box-shadow: 0px 2px 15px rgb(0 0 0 / 10%);
                text-align: left;
              "> -->
        
              <!-- <input type="text" name="email"  placeholder="Nama Notaris" style="
              border: 0;
              padding: 4px 4px;
              width: calc(100% - 100px);
              "> -->

           
                       
            <input type="text" id="nama" class="form-control" placeholder="Nama Notaris" style="
                            border: 0;
                            padding: 25px 20px;
                            width: calc(100% - 100px);
                        ">
            <input type="hidden" id="id_notaris" name="id">

            <input type="submit" value="Cari" 
            style="
                position: absolute;
                top: 0;
                right: 0;
                bottom: 0;
                border: 0;
                background: none;
                font-size: 16px;
                padding: 0 20px;
                background: #252459;
                color: #fff;
                transition: 0.3s;
                border-radius: 4px;
                box-shadow: 0px 2px 15px rgb(0 0 0 / 10%);
            ">
            
            <!-- </form> -->
            </form>          </div>
        </div>
      </div>
    </section><!-- End Featured Services Section -->


    <!-- ======= Services Section ======= -->
    <section id="services" class="services">
      <div class="container">

        <div class="section-title">
          <h2>Notaris</h2>
          <p>Sebaran Notaris di  Kabupaten / Kota Wilayah Sulawesi Tenggara</p>
        </div>
       

        <div class="row">
        <?php foreach ($wilayah as $wilayah) : ?>
                <div class="col-lg-4">
                  <div class="icon-box2">
                    <h3><a href="<?php echo base_url('daftar/').$wilayah['kode_wilayah']?>"><?php echo $wilayah['wilayah']; ?></a></h3>
                    <hr>
                    <div class="row">
                      <div class="col-lg-1">
                        <i class="icofont-users"></i> 
                      </div>
                      <div class="col-lg-9">
                        <p><b><?php echo $wilayah['jumlah']; ?></b> Notaris</p>
                      </div>
                      <div class="col-lg-1">
                      <a href="<?php echo base_url('daftar/').$wilayah['kode_wilayah']?>"><i class="icofont-circled-right"></i> </a>
                      </div>
                    </div> 
                  </div>
                </div>
                

                <?php endforeach;?>
                
            </div>


      <!-- peta -->

      <div class="section-title">
        <h2>Peta</h2>
        <p>Sebaran Notaris Dalam Peta</p>
      </div>
      <iframe src="https://www.google.com/maps/d/u/0/embed?mid=1PsHUAFrHwxpJ0lW8Gpo8ojdFrG5ugm4&ehbc=2E312F" width="1100" height="600"></iframe>
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

  </main><!-- End #main -->