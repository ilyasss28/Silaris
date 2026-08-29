<style>
.dashboard-welcome{position:relative;overflow:hidden;min-height:190px;margin-bottom:24px;padding:30px;display:flex;align-items:center;justify-content:space-between;gap:28px;border-radius:var(--radius);background:#05063E;color:#fff;box-shadow:var(--shadow)}
.dashboard-welcome:after{content:'';position:absolute;inset:0 0 0 auto;width:9px;background:#FECD08}.dashboard-welcome__content{position:relative;z-index:1;max-width:680px}.dashboard-welcome__eyebrow{display:inline-flex;align-items:center;gap:8px;margin-bottom:15px;color:#FECD08;font-size:12px;font-weight:700;text-transform:uppercase}.dashboard-welcome h2{margin:0 0 10px;color:#fff;font-size:27px;font-weight:800;line-height:1.25}.dashboard-welcome p{max-width:590px;margin:0;color:rgba(255,255,255,.7);font-size:14px;line-height:1.7}.dashboard-welcome__mark{width:104px;height:104px;flex:0 0 104px;display:grid;place-items:center;padding:15px;border-radius:12px;background:#fff}.dashboard-welcome__mark img{width:100%;height:100%;object-fit:contain}
.dashboard-section-title{margin:0 0 14px;color:var(--ink-900);font-size:16px;font-weight:800}.quick-access-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:16px}.quick-access-card{min-height:150px;padding:20px;display:flex;flex-direction:column;align-items:flex-start;border:1px solid var(--border);border-radius:var(--radius-sm);background:#fff;color:var(--ink-900);text-decoration:none;box-shadow:var(--shadow-sm);transition:transform .15s ease,border-color .15s ease,box-shadow .15s ease}.quick-access-card:hover{transform:translateY(-2px);border-color:#C5CAD6;color:var(--brand);box-shadow:var(--shadow)}.quick-access-card__icon{width:42px;height:42px;margin-bottom:17px;display:grid;place-items:center;border-radius:8px;background:var(--brand-tint);color:var(--brand);font-size:18px}.quick-access-card:nth-child(2) .quick-access-card__icon{background:var(--accent-tint);color:#8A6C00}.quick-access-card:nth-child(3) .quick-access-card__icon{background:var(--info-tint);color:var(--info)}.quick-access-card:nth-child(4) .quick-access-card__icon{background:var(--success-tint);color:var(--success)}.quick-access-card strong{margin-bottom:6px;font-size:14px;font-weight:700}.quick-access-card small{color:var(--ink-500);font-size:12px;line-height:1.5}
@media(max-width:991.98px){.quick-access-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}@media(max-width:575.98px){.dashboard-welcome{min-height:0;padding:24px 20px}.dashboard-welcome h2{font-size:22px}.dashboard-welcome__mark{display:none}.quick-access-grid{grid-template-columns:1fr}.quick-access-card{min-height:132px}}
</style>

<section class="content-header">
  <h1><?= cclang('dashboard') ?> <small>Ringkasan dan akses cepat administrasi SILARIS</small></h1>
  <ol class="breadcrumb">
    <li><a href="<?= site_url('administrator/dashboard'); ?>"><i class="fa fa-dashboard"></i> <?= cclang('home') ?></a></li>
    <li class="active"><?= cclang('dashboard') ?></li>
  </ol>
</section>

<section class="content">
  <?php cicool()->eventListen('dashboard_content_top'); ?>
  <div class="dashboard-welcome">
    <div class="dashboard-welcome__content">
      <span class="dashboard-welcome__eyebrow"><i class="fa fa-shield"></i> Sistem Informasi Layanan Administrasi</span>
      <h2>Selamat datang, <?= _ent(ucwords(clean_snake_case(get_user_data('full_name')))); ?></h2>
      <p>Kelola data, laporan, pengguna, dan konfigurasi layanan melalui panel administrasi SILARIS.</p>
    </div>
    <div class="dashboard-welcome__mark"><img src="<?= base_url('assets/assets-guest/img/kemenkumham.png'); ?>" alt="Logo Kementerian Hukum"></div>
  </div>

  <h2 class="dashboard-section-title">Akses Cepat</h2>
  <div class="quick-access-grid">
    <a class="quick-access-card" href="<?= site_url('administrator/user'); ?>"><span class="quick-access-card__icon"><i class="fa fa-users"></i></span><strong>Manajemen Pengguna</strong><small>Kelola akun dan status pengguna sistem.</small></a>
    <a class="quick-access-card" href="<?= site_url('laporan_bulanan'); ?>"><span class="quick-access-card__icon"><i class="fa fa-file-text-o"></i></span><strong>Laporan Bulanan</strong><small>Periksa dan kelola data laporan berkala.</small></a>
    <a class="quick-access-card" href="<?= site_url('data_notaris'); ?>"><span class="quick-access-card__icon"><i class="fa fa-book"></i></span><strong>Data Notaris</strong><small>Akses basis data notaris yang terdaftar.</small></a>
    <a class="quick-access-card" href="<?= site_url('administrator/profile'); ?>"><span class="quick-access-card__icon"><i class="fa fa-user"></i></span><strong>Profil Saya</strong><small>Perbarui informasi dan keamanan akun.</small></a>
  </div>
  <?php cicool()->eventListen('dashboard_content_bottom'); ?>
</section>
