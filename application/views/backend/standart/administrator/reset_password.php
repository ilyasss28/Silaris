<!doctype html>
<html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= _ent(get_option('site_name')); ?> | Atur Ulang Password</title>
<link rel="stylesheet" href="<?= BASE_ASSET; ?>vendor/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
<link rel="stylesheet" href="<?= base_url('asset/css/auth-account.css'); ?>"></head>
<body class="account-auth"><main class="auth-shell">
<aside class="auth-brand-panel">
  <div class="auth-brand"><img src="<?= base_url('assets/assets-guest/img/kemenkumham.png'); ?>" alt="Logo Kementerian Hukum"><div><strong><?= _ent(get_option('site_name') ?: 'Silaris'); ?></strong><span>Kementerian Hukum Sulawesi Tenggara</span></div></div>
  <div class="auth-brand-copy"><small>Keamanan Akun</small><h1>Buat password baru yang kuat.</h1><p>Password baru langsung menggantikan password lama setelah disimpan. Tautan tidak dapat digunakan kembali.</p></div>
  <div class="auth-security"><i class="fa fa-lock"></i><span>Gunakan password unik yang tidak dipakai pada layanan lain.</span></div>
</aside>
<section class="auth-form-panel"><div class="auth-form-wrap">
  <header class="auth-heading"><p class="auth-eyebrow">Atur Ulang Password</p><h2>Password baru</h2><p>Gunakan minimal 8 karakter dengan kombinasi huruf besar, huruf kecil, dan angka.</p></header>
  <?php if (!empty($error)): ?><div class="auth-alert" role="alert"><i class="fa fa-exclamation-circle"></i><div><?= $error; ?></div></div><?php endif; ?>
  <?php if (!empty($token_valid)): ?>
    <?= form_open(current_url(), array('id'=>'form_reset_password','method'=>'POST')); ?>
      <div class="auth-field <?= form_error('password') ? 'has-error' : ''; ?>"><label for="password">Password Baru <span class="required">*</span></label><div class="auth-control-wrap"><i class="fa fa-lock"></i><input class="auth-control" id="password" type="password" name="password" minlength="8" maxlength="72" autocomplete="new-password" required autofocus><button class="password-toggle" type="button" data-toggle-password="password" aria-label="Tampilkan password"><i class="fa fa-eye"></i></button></div></div>
      <div class="auth-field <?= form_error('password_confirmation') ? 'has-error' : ''; ?>"><label for="password_confirmation">Konfirmasi Password <span class="required">*</span></label><div class="auth-control-wrap"><i class="fa fa-lock"></i><input class="auth-control" id="password_confirmation" type="password" name="password_confirmation" minlength="8" maxlength="72" autocomplete="new-password" required><button class="password-toggle" type="button" data-toggle-password="password_confirmation" aria-label="Tampilkan konfirmasi password"><i class="fa fa-eye"></i></button></div></div>
      <button class="auth-submit" type="submit">Simpan Password Baru <i class="fa fa-check"></i></button>
    <?= form_close(); ?>
  <?php else: ?>
    <div class="auth-invalid"><i class="fa fa-clock-o"></i><h3>Tautan tidak valid</h3><p>Tautan sudah digunakan, kedaluwarsa, atau tidak dikenali. Ajukan tautan baru untuk melanjutkan.</p><a class="auth-submit" style="display:grid;place-items:center;text-decoration:none" href="<?= site_url('administrator/forgot-password'); ?>">Kirim Tautan Baru</a></div>
  <?php endif; ?>
  <p class="auth-links"><a href="<?= site_url('administrator/login'); ?>"><i class="fa fa-arrow-left"></i> Kembali ke halaman masuk</a></p>
</div></section></main>
<script>(function(){document.querySelectorAll('[data-toggle-password]').forEach(function(button){button.addEventListener('click',function(){var input=document.getElementById(button.dataset.togglePassword);var show=input.type==='password';input.type=show?'text':'password';button.querySelector('i').className=show?'fa fa-eye-slash':'fa fa-eye';});});})();</script>
</body></html>
