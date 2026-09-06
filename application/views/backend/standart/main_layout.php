<?php
/**
 * @var array $template
 */
$sidebar_role_label = 'Notaris';

if ($this->aauth->is_admin()) {
  $sidebar_role_label = 'Admin';
} else {
  $current_user_groups = $this->aauth->get_user_groups();
  $current_group_names = array();

  foreach ($current_user_groups as $current_user_group) {
    $group_name = trim((string) ($current_user_group->name ?? ''));
    $normalized_group_name = strtolower($group_name);

    if ($group_name !== '' && $normalized_group_name !== 'public') {
      $current_group_names[$normalized_group_name] = $group_name;
    }
  }

  $role_priority = array(
    'kanwil' => 'Kanwil',
    'mpd' => 'MPD',
    'notaris' => 'Notaris',
    'user' => 'Notaris',
    'member' => 'Notaris',
    'default' => 'Notaris',
  );

  foreach ($role_priority as $group_key => $role_label) {
    if (isset($current_group_names[$group_key])) {
      $sidebar_role_label = $role_label;
      break;
    }
  }
}

$last_login_value = (string) get_user_data('last_login');
$last_login_timestamp = strtotime($last_login_value);
$last_login_label = $last_login_timestamp
  ? format_date_id($last_login_value) . ' ' . date('H:i', $last_login_timestamp)
  : 'Belum tersedia';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="<?= get_option('site_description'); ?>">
  <meta name="keywords" content="<?= get_option('keywords'); ?>">
  <meta name="author" content="<?= get_option('author'); ?>">
  <meta name="csrf-name" content="<?= $this->security->get_csrf_token_name(); ?>">
  <meta name="csrf-token" content="<?= $this->security->get_csrf_hash(); ?>">

  <title><?= get_option('site_name'); ?> | <?= $template['title']; ?></title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <link rel="stylesheet" href="<?= BASE_ASSET; ?>/bootstrap5/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= BASE_ASSET; ?>vendor/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?= BASE_ASSET; ?>/admin-lte4/css/adminlte.css">
  <link rel="stylesheet" href="<?= BASE_ASSET; ?>/admin-lte/plugins/iCheck/flat/blue.css">
  <link rel="stylesheet" href="<?= BASE_ASSET; ?>/admin-lte/plugins/morris/morris.css">
  <link rel="stylesheet" href="<?= BASE_ASSET; ?>/admin-lte/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/3.0.2/css/dataTables.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/4.0.2/css/buttons.dataTables.min.css">
  <link rel="stylesheet" href="<?= BASE_ASSET; ?>/admin-lte/plugins/datepicker/datepicker3.css">
  <link rel="stylesheet" href="<?= BASE_ASSET; ?>/admin-lte/plugins/daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="<?= BASE_ASSET; ?>/admin-lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
  <link rel="stylesheet" href="<?= BASE_ASSET; ?>/admin-lte/plugins/iCheck/all.css">
  <link rel="stylesheet" href="<?= BASE_ASSET; ?>/sweet-alert/sweetalert.css">
  <link rel="stylesheet" href="<?= BASE_ASSET; ?>/toastr/build/toastr.css">
  <link rel="stylesheet" href="<?= BASE_ASSET; ?>/fancy-box/source/jquery.fancybox.css?v=2.1.5" media="screen" />
  <link rel="stylesheet" href="<?= BASE_ASSET; ?>/chosen/chosen.css">
  <link rel="stylesheet" href="<?= BASE_ASSET; ?>/css/custom.css?timestamp=201803311526">
  <link rel="stylesheet" href="<?= BASE_ASSET; ?>/css/modern-theme.css?v=<?= @filemtime(FCPATH.'asset/css/modern-theme.css'); ?>">
  <link rel="stylesheet" href="<?= BASE_ASSET; ?>datetimepicker/jquery.datetimepicker.css"/>
  <link rel="stylesheet" href="<?= BASE_ASSET; ?>js-scroll/style/jquery.jscrollpane.css" rel="stylesheet" media="all" />
  <link rel="stylesheet" href="<?= BASE_ASSET; ?>flag-icon/css/flag-icon.css" rel="stylesheet" media="all" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <?= $this->cc_html->getCssFileTop(); ?>

  <!-- Generated administrator views load page plugins inside their content.
       jQuery must exist before those plugins are parsed on a full page load. -->
<script src="<?= BASE_ASSET; ?>/jquery4/jquery.min.js"></script>
  <script src="<?= BASE_ASSET; ?>/jquery4/jquery-compat-shim.js"></script>
</head>
<body class="layout-fixed sidebar-expand-lg admin-silaris">
<div class="app-wrapper">

  <nav class="app-header navbar navbar-expand bg-body">
    <div class="container-fluid">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button" aria-label="Toggle sidebar">
            <i class="fa fa-bars"></i>
          </a>
        </li>
        <li class="nav-item d-none d-sm-flex align-items-center">
          <span class="admin-context">Panel Administrasi</span>
        </li>
      </ul>

      <ul class="navbar-nav ms-auto">
        <li class="nav-item dropdown user-menu">
          <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Buka menu akun">
            <img src="<?= BASE_URL.'uploads/user/'.(!empty(get_user_data('avatar')) ? get_user_data('avatar') :'default.png'); ?>" class="user-image rounded-circle" alt="User Image">
            <span class="d-none d-md-inline ms-2"><?= _ent(format_person_name(clean_snake_case(get_user_data('full_name')))); ?></span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-label="Menu akun">
            <li class="user-header">
              <span class="user-header__avatar">
                <img src="<?= BASE_URL.'uploads/user/'.(!empty(get_user_data('avatar')) ? get_user_data('avatar') :'default.png'); ?>" alt="Foto <?= _ent(get_user_data('full_name')); ?>">
                <i aria-hidden="true"></i>
              </span>
              <span class="user-header__identity">
                <strong><?= _ent(format_person_name(clean_snake_case($this->aauth->get_user()->full_name))); ?></strong>
                <small><?= _ent($sidebar_role_label); ?></small>
              </span>
              <span class="user-header__meta">
                <i class="fa fa-clock-o" aria-hidden="true"></i>
                <span>Login terakhir</span>
                <strong><?= _ent($last_login_label); ?></strong>
              </span>
            </li>

            <li class="user-footer">
              <a href="<?= site_url('administrator/profile'); ?>" class="user-menu-action user-menu-action--profile"><i class="fa fa-user" aria-hidden="true"></i><span>Profil Saya</span></a>
              <a href="<?= site_url('administrator/logout'); ?>" class="user-menu-action user-menu-action--logout"><i class="fa fa-sign-out" aria-hidden="true"></i><span>Keluar</span></a>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>

  <aside class="app-sidebar" data-bs-theme="dark">
    <div class="sidebar-brand">
      <a href="<?= site_url('administrator/dashboard'); ?>" class="brand-link">
        <span class="brand-mark">
          <img src="<?= base_url('assets/assets-guest/img/kemenkumham.png'); ?>" alt="" aria-hidden="true">
        </span>
        <span class="brand-copy">
          <strong><?= get_option('site_name'); ?></strong>
          <small>Kemenkum Sulawesi Tenggara</small>
        </span>
        <span class="brand-mode"><?= _ent(strtoupper($sidebar_role_label)); ?></span>
      </a>
    </div>

    <div class="sidebar-wrapper">
      <nav aria-label="Main navigation">
        <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" data-accordion="false">
          <!-- DASHBOARD MENU -->
          <li class="nav-item">
            <a href="<?= site_url('administrator/dashboard'); ?>" class="nav-link <?= ($this->uri->segment(1) == 'administrator' && ($this->uri->segment(2) == 'dashboard' || $this->uri->segment(2) == '')) ? 'active' : ''; ?>">
              <i class="nav-icon fa fa-th-large"></i>
              <p>DASHBOARD</p>
            </a>
          </li>
          <!-- DYNAMIC MENU -->
          <?= display_menu_admin(0, 1); ?>
        </ul>
      </nav>
    </div>

    <div class="sidebar-account">
      <a href="<?= site_url('administrator/profile'); ?>" class="sidebar-account__profile" title="Buka profil saya">
        <img src="<?= BASE_URL.'uploads/user/'.(!empty(get_user_data('avatar')) ? get_user_data('avatar') :'default.png'); ?>" alt="Foto <?= _ent(get_user_data('full_name')); ?>">
        <span class="sidebar-account__copy">
          <strong><?= _ent(format_person_name(clean_snake_case(get_user_data('full_name')))); ?></strong>
          <small>Akun <?= _ent($sidebar_role_label); ?></small>
        </span>
      </a>
      <a href="<?= site_url('administrator/logout'); ?>" class="sidebar-account__logout" title="Keluar" aria-label="Keluar dari akun">
        <i class="fa fa-sign-out"></i>
      </a>
    </div>
  </aside>

  <main class="app-main">
    <div class="app-content">
      <div class="container-fluid">
        <?php cicool()->eventListen('backend_content_top'); ?>
        <?= $template['partials']['content']; ?>
        <?php cicool()->eventListen('backend_content_bottom'); ?>
      </div>
    </div>
  </main>

  <footer class="app-footer">
    <div class="float-end d-none d-sm-inline">
      <b><?= cclang('version') ?></b> <?= VERSION ?>
    </div>
    <strong>&copy; 2016-<?=date('Y'); ?> <?= get_option('site_name'); ?></strong>
    <span class="footer-divider">Kantor Wilayah Kementerian Hukum Sulawesi Tenggara</span>
  </footer>

</div>

<?= $this->cc_html->getHtmlFileBottom(); ?>

<?= $this->cc_html->getCssFileBottom(); ?>

<script src="<?= BASE_ASSET; ?>/bootstrap5/js/bootstrap.bundle.min.js"></script>
<script src="<?= BASE_ASSET; ?>/admin-lte4/js/adminlte.js"></script>

<script>
  // Bridge legacy Bootstrap 3 markup used by generated administrator views.
  document.querySelectorAll('[data-toggle]').forEach(function (element) {
    element.setAttribute('data-bs-toggle', element.getAttribute('data-toggle'));
  });
  document.querySelectorAll('[data-parent]').forEach(function (element) {
    element.setAttribute('data-bs-parent', element.getAttribute('data-parent'));
  });
  document.querySelectorAll('.nav-tabs a[data-toggle="tab"], .nav-tabs a[data-bs-toggle="tab"]').forEach(function (tab) {
    tab.classList.add('nav-link');
    if (tab.parentElement.classList.contains('active')) {
      tab.classList.add('active');
    }
    tab.addEventListener('shown.bs.tab', function () {
      tab.closest('.nav-tabs').querySelectorAll('li').forEach(function (item) {
        item.classList.toggle('active', item.contains(tab));
      });
    });
  });
</script>

<script>
  var BASE_URL = "<?= base_url(); ?>";
  var HTTP_REFERER = "<?= isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/' ; ?>";
  var csrf = '<?= $this->security->get_csrf_token_name(); ?>';
  var token = '<?= $this->security->get_csrf_hash(); ?>';
</script>

<script>
  (function () {
    function getCancelFallbackUrl() {
      var currentUrl = new URL(window.location.href);
      var referer = document.referrer;

      if (referer) {
        try {
          var refererUrl = new URL(referer, window.location.origin);
          var isSafeReferer = refererUrl.origin === window.location.origin &&
            !/(?:^|\/)delete(?:\/|$)/i.test(refererUrl.pathname);

          if (isSafeReferer && refererUrl.href !== currentUrl.href) {
            return refererUrl.href;
          }
        } catch (error) {
          // Gunakan URL daftar yang diturunkan dari alamat halaman saat ini.
        }
      }

      currentUrl.pathname = currentUrl.pathname.replace(
        /\/(?:add|edit|update)(?:\/.*)?\/?$/i,
        ''
      );
      currentUrl.search = '';
      currentUrl.hash = '';

      return currentUrl.href;
    }

    function cancelForm(event) {
      if (event) {
        event.preventDefault();
        event.stopImmediatePropagation();
      }

      if (window.history.length > 1) {
        window.history.back();
        return false;
      }

      window.location.assign(getCancelFallbackUrl());
      return false;
    }

    // Menangkap klik sebelum handler lama halaman menjalankan dialog penghapusan.
    document.addEventListener('click', function (event) {
      var cancelButton = event.target.closest('#btn_cancel');
      if (cancelButton) {
        cancelForm(event);
      }
    }, true);

    $(document).ready(function () {
      var cancelButton = $('#btn_cancel');

      if (!cancelButton.length) {
        return;
      }

      cancelButton
        .attr('role', 'button')
        .attr('href', getCancelFallbackUrl())
        .off('click')
        .on('click.adminCancel', cancelForm);
    });
  })();
</script>

<script src="<?= BASE_ASSET; ?>/admin-lte/plugins/iCheck/icheck.min.js"></script>
<script src="<?= BASE_ASSET; ?>/sweet-alert/sweetalert-dev.js"></script>
<script src="<?= BASE_ASSET; ?>/admin-lte/plugins/input-mask/jquery.inputmask.js"></script>
<script src="<?= BASE_ASSET; ?>/admin-lte/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="<?= BASE_ASSET; ?>/admin-lte/plugins/input-mask/jquery.inputmask.extensions.js"></script>
<script src="<?= BASE_ASSET; ?>/toastr/toastr.js"></script>
<script src="<?= BASE_ASSET; ?>/fancy-box/source/jquery.fancybox.js?v=2.1.5"></script>
<script src="<?= BASE_ASSET; ?>/datetimepicker/build/jquery.datetimepicker.full.js"></script>
<script src="<?= BASE_ASSET; ?>/editor/dist/js/medium-editor.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.6.2/chosen.jquery.min.js" type="text/javascript"></script>
<script src="<?= BASE_ASSET; ?>jquery-ui/jquery-ui.js"></script>
<script src="<?= BASE_ASSET; ?>jquery-switch-button/jquery.switchButton.js?v=<?= @filemtime(FCPATH.'asset/jquery-switch-button/jquery.switchButton.js'); ?>"></script>
<script src="<?= BASE_ASSET; ?>/js/jquery.ui.touch-punch.js"></script>
<script src="<?= BASE_ASSET; ?>js-scroll/script/jquery.jscrollpane.min.js"></script>
<script src="https://cdn.datatables.net/3.0.2/js/dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/4.0.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/4.0.2/js/buttons.colVis.min.js"></script>
<script src="https://cdn.datatables.net/buttons/4.0.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/4.0.2/js/buttons.print.min.js"></script>

<script src="<?= BASE_ASSET; ?>js/cc-extension.js"></script>
<script src="<?= BASE_ASSET; ?>/js/cc-page-element.js"></script>
<script src="<?= BASE_ASSET; ?>/js/custom.js"></script>
<script src="<?= BASE_ASSET; ?>/js/admin-ui.js?v=<?= @filemtime(FCPATH.'asset/js/admin-ui.js'); ?>"></script>
<script src="<?= BASE_ASSET; ?>/js/admin-datatables.js?v=<?= @filemtime(FCPATH.'asset/js/admin-datatables.js'); ?>"></script>
<script src="<?= BASE_ASSET; ?>/js/admin-navigation.js?v=<?= @filemtime(FCPATH.'asset/js/admin-navigation.js'); ?>"></script>

<?= $this->cc_html->getScriptFileBottom(); ?>

<script>
  $(document).ready(function(){

    toastr.options = {
      "positionClass": "toast-top-right",
      "preventDuplicates": true,
      "newestOnTop": true,
      "timeOut": 3500,
    }

    var f_message = '<?= $this->session->flashdata('f_message'); ?>';
    var f_type = '<?= $this->session->flashdata('f_type'); ?>';

    if (f_message.length > 0) {
      toastr[f_type](f_message);
    }

    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass: 'iradio_minimal-red'
    });
  });
</script>
</body>
</html>
