<body>
  <!-- ======= Header ======= -->
  <header id="header" class="fixed-top">
    <div class="container d-flex align-items-center">
      <h1 class="logo me-auto">
        <a href="<?php echo site_url('home'); ?>" class="d-flex align-items-center">
          <img src="<?php echo base_url('assets/assets-guest/img/kemenkumham.png'); ?>" alt="SILARIS">
          <span class="logo-text">
            <span class="logo-title">SILARIS</span>
            <span class="logo-subtitle">Sistem Pelaporan Notaris</span>
          </span>
        </a>
      </h1>
      <?php $current_page = strtolower($this->router->fetch_class()); ?>

      <!-- Desktop Navigation -->
      <nav id="navbar" class="navbar" aria-label="Navigasi utama">
        <ul class="nav-list" id="navList">
          <li class="<?= $current_page === 'home' ? 'active' : ''; ?>"><a href="<?php echo site_url('home'); ?>">Beranda</a></li>
          <li class="<?= $current_page === 'panduan' ? 'active' : ''; ?>"><a href="<?php echo site_url('panduan'); ?>">Panduan</a></li>
          <li class="<?= $current_page === 'kepatuhan' ? 'active' : ''; ?>"><a href="<?php echo site_url('kepatuhan'); ?>">Kepatuhan Notaris</a></li>
          <li><a href="<?php echo site_url('login'); ?>" class="btn-login">Login</a></li>
        </ul>

        <!-- Mobile Toggle Button -->
        <button class="mobile-nav-toggle" aria-label="Buka menu navigasi" aria-controls="navList" aria-expanded="false">
          <span class="hamburger-line"></span>
          <span class="hamburger-line"></span>
          <span class="hamburger-line"></span>
        </button>
      </nav>
    </div>
  </header>
  <!-- End Header -->

  <!-- Mobile Nav Overlay -->
  <div class="mobile-nav-overlay" id="mobileNavOverlay"></div>
