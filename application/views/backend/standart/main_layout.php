<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="<?= get_option('site_description'); ?>">
  <meta name="keywords" content="<?= get_option('keywords'); ?>">
  <meta name="author" content="<?= get_option('author'); ?>">

  <title><?= get_option('site_name'); ?> | <?= $template['title']; ?></title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <link rel="stylesheet" href="<?= BASE_ASSET; ?>/bootstrap5/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="<?= BASE_ASSET; ?>/admin-lte4/css/adminlte.css">
  <link rel="stylesheet" href="<?= BASE_ASSET; ?>/admin-lte/plugins/iCheck/flat/blue.css">
  <link rel="stylesheet" href="<?= BASE_ASSET; ?>/admin-lte/plugins/morris/morris.css">
  <link rel="stylesheet" href="<?= BASE_ASSET; ?>/admin-lte/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
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
</head>
<body class="layout-fixed sidebar-expand-lg">
<div class="app-wrapper">

  <nav class="app-header navbar navbar-expand bg-body">
    <div class="container-fluid">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button" aria-label="Toggle sidebar">
            <i class="fa fa-bars"></i>
          </a>
        </li>
      </ul>

      <ul class="navbar-nav ms-auto">
        <li class="nav-item dropdown user-menu">
          <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
            <img src="<?= BASE_URL.'uploads/user/'.(!empty(get_user_data('avatar')) ? get_user_data('avatar') :'default.png'); ?>" class="user-image rounded-circle" alt="User Image">
            <span class="d-none d-md-inline ms-2"><?= _ent(ucwords(clean_snake_case(get_user_data('full_name')))); ?></span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li class="user-header">
              <img src="<?= BASE_URL.'uploads/user/'.(!empty(get_user_data('avatar')) ? get_user_data('avatar') :'default.png'); ?>" class="rounded-circle" alt="User Image">

              <p>
                <?= _ent(ucwords(clean_snake_case($this->aauth->get_user()->full_name))); ?>
                <small>Login Terakhir, <?= date('Y-M-D', strtotime(get_user_data('last_login'))); ?></small>
              </p>
            </li>

            <li class="user-footer">
              <div class="float-start">
                <a href="<?= site_url('administrator/user/profile'); ?>" class="btn btn-default btn-flat"><?= cclang('profile'); ?></a>
              </div>
              <div class="float-end">
                <a href="<?= site_url('administrator/auth/logout'); ?>" class="btn btn-default btn-flat"><?= cclang('sign_out'); ?></a>
              </div>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>

  <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
      <a href="<?= site_url('/'); ?>" class="brand-link">
        <span class="brand-text fw-light"><?= get_option('site_name'); ?></span>
      </a>
    </div>

    <div class="sidebar-wrapper">
      <nav class="mt-2" aria-label="Main navigation">
        <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" data-accordion="false">
          <?= display_menu_admin(0, 1); ?>
        </ul>
      </nav>
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
    <strong>Copyright &copy; 2016-<?=date('Y'); ?> <a href="#"><?= get_option('site_name'); ?></a>.</strong> All rights
    reserved.
  </footer>

</div>

<?= $this->cc_html->getHtmlFileBottom(); ?>

<?= $this->cc_html->getCssFileBottom(); ?>

<script src="<?= BASE_ASSET; ?>/jquery4/jquery.min.js"></script>
<script src="<?= BASE_ASSET; ?>/jquery4/jquery-compat-shim.js"></script>
<script src="<?= BASE_ASSET; ?>/bootstrap5/js/bootstrap.bundle.min.js"></script>
<script src="<?= BASE_ASSET; ?>/admin-lte4/js/adminlte.js"></script>

<script>
  var BASE_URL = "<?= base_url(); ?>";
  var HTTP_REFERER = "<?= isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/' ; ?>";
  var csrf = '<?= $this->security->get_csrf_token_name(); ?>';
  var token = '<?= $this->security->get_csrf_hash(); ?>';
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
<script src="<?= BASE_ASSET; ?>jquery-switch-button/jquery.switchButton.js"></script>
<script src="<?= BASE_ASSET; ?>/js/jquery.ui.touch-punch.js"></script>
<script src="<?= BASE_ASSET; ?>js-scroll/script/jquery.jscrollpane.min.js"></script>

<script src="<?= BASE_ASSET; ?>js/cc-extension.js"></script>
<script src="<?= BASE_ASSET; ?>/js/cc-page-element.js"></script>
<script src="<?= BASE_ASSET; ?>/js/custom.js"></script>

<?= $this->cc_html->getScriptFileBottom(); ?>

<script>
  $(document).ready(function(){

    toastr.options = {
      "positionClass": "toast-top-right",
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
