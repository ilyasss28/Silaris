<!DOCTYPE html>
<html lang="en">
<?php $this->load->view("frontend/include/head"); ?>

<body id="page-top" class="index">

<?php $this->load->view("frontend/include/navigation"); ?>
    <!-- Header -->
    <header>
        <div class="container">
            <div class="intro-text">
                <div class="intro-lead-in">Selamat Datang di Layanan</div>
                <div class="intro-heading">PEJABAT PENGELOLA INFORMASI DAN DOKUMENTASI</div>
                <a href="#services" class="page-scroll btn btn-xl">Kementerian Hukum dan HAM Sulawesi Tenggara</a>
            </div>
        </div>
    </header>

    <!-- Services Section -->
    <section id="services">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">Tentang PPID Kemenkumham Sultra</h2>
                    <h3 class="section-subheading text-muted">PPID adalah kepanjangan dari Pejabat Pengelola Informasi dan Dokumentasi, yang berfungsi sebagai pengelola dan penyampai dokumen yang dimiliki oleh Badan Publik sesuai dengan amanat UU 14/2008 tentang Keterbukaan Informasi Publik.</h3>
                </div>
            </div>
            <div class="row text-center">
                <div class="col-md-4" >
                    <span class="fa-stack fa-4x">
                        <i class="fa fa-circle fa-stack-2x text-primary"></i>
                        <i class="fa fa-laptop fa-stack-1x fa-inverse"></i>
                    </span>
                    <h4 class="service-heading">Informasi Berkala</h4>
                    <p class="text-muted">Informasi publik yang masuk dalam kategori diumumkan secara berkala adalah yang disediakan/diumumkan secara rutin, teratur, dan dalam jangka waktu tertentu.</p>
                </div>
                <div class="col-md-4">
                    <span class="fa-stack fa-4x">
                        <i class="fa fa-circle fa-stack-2x text-primary"></i>
                        <i class="fa fa-book fa-stack-1x fa-inverse"></i>
                    </span>
                    <h4 class="service-heading" >Informasi Serta Merta</h4>
                    <p class="text-muted">Informasi publik yang masuk dalam kategori serta-merta adalah informasi yang wajib diumumkan tanpa penundaan.</p>
                </div>
                <div class="col-md-4">
                    <div class="menu-item">
                    <span class="fa-stack fa-4x">
                        <i class="fa fa-circle fa-stack-2x text-primary"></i>
                        <i class="fa fa-lock fa-stack-1x fa-inverse"></i>
                    </span>
                    <h4 class="service-heading" href="<?php echo base_url("halaman/daftar"); ?>">Informasi Setiap Saat</h4>
                    <p class="text-muted">Informasi publik yang masuk dalam kategori informasi yang tersedia setiap saat merupakan informasi pasif yang untuk memperolehnya harus dilakukan dengan mengajukan permintaan.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

</body>

</html>


<!-- Clients Aside -->
<aside class="clients">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-6">
                    <a href="#">
                        <img src="<?php echo base_url(IMAGES); ?>/logos/1.png" class="img-responsive img-centered" alt="">
                    </a>
                </div>
                <div class="col-md-3 col-sm-6">
                    <a href="#">
                        <img src="<?php echo base_url(IMAGES); ?>/logos/2.png" class="img-responsive img-centered" alt="">
                    </a>
                </div>
                <div class="col-md-3 col-sm-6">
                    <a href="#">
                        <img src="<?php echo base_url(IMAGES); ?>/logos/3.png" class="img-responsive img-centered" alt="">
                    </a>
                </div>
                <div class="col-md-3 col-sm-6">
                    <a href="#">
                        <img src="<?php echo base_url(IMAGES); ?>/logos/4.png" class="img-responsive img-centered" alt="">
                    </a>
                </div>
            </div>
        </div>
    </aside>

    <!-- Contact Section -->
    <section id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">Hubungi Kami</h2>
                    <h3 class="section-subheading text-muted">Kantor Kementerian Hukum dan HAM Sulawesi Tenggara.</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <form name="sentMessage" id="contactForm" novalidate>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Nama *" id="name" required data-validation-required-message="Please enter your name.">
                                    <p class="help-block text-danger"></p>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control" placeholder="Email *" id="email" required data-validation-required-message="Please enter your email address.">
                                    <p class="help-block text-danger"></p>
                                </div>
                                <div class="form-group">
                                    <input type="tel" class="form-control" placeholder="No. Kontak *" id="phone" required data-validation-required-message="Please enter your phone number.">
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <textarea class="form-control" placeholder="Pesan Anda *" id="message" required data-validation-required-message="Please enter a message."></textarea>
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-lg-12 text-center">
                                <div id="success"></div>
                                <button type="submit" class="btn btn-xl">Kirim</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <?php $this->load->view("frontend/include/footer"); ?>
    <?php $this->load->view("frontend/include/jquery"); ?>

    

</body>

</html>
