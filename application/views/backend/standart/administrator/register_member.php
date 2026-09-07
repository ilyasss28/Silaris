<!doctype html>
<html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= _ent(get_option('site_name')); ?> | Tambah Anggota Baru</title>
<link rel="stylesheet" href="<?= BASE_ASSET; ?>vendor/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
<link rel="stylesheet" href="<?= base_url('asset/css/auth-account.css'); ?>"></head>
<body class="account-auth"><main class="auth-shell">
<aside class="auth-brand-panel">
  <div class="auth-brand"><img src="<?= base_url('assets/assets-guest/img/kemenkumham.png'); ?>" alt="Logo Kementerian Hukum"><div><strong><?= _ent(get_option('site_name') ?: 'Silaris'); ?></strong><span>Kementerian Hukum Sulawesi Tenggara</span></div></div>
  <div class="auth-brand-copy"><small>Keanggotaan SILARIS</small><h1>Daftarkan akun Notaris Anda.</h1><p>Gunakan identitas dan kontak yang sama dengan data resmi. Admin akan memeriksa pendaftaran sebelum akun dapat digunakan.</p></div>
  <div class="auth-security"><i class="fa fa-check-circle"></i><span>Akun baru otomatis berstatus menunggu verifikasi dan tidak dapat masuk sebelum diaktifkan admin.</span></div>
</aside>
<section class="auth-form-panel"><div class="auth-form-wrap">
  <header class="auth-heading"><p class="auth-eyebrow">Tambah Anggota Baru</p><h2>Buat akun SILARIS</h2><p>Lengkapi data berikut dengan benar. Seluruh field wajib diisi.</p></header>
  <div class="auth-notice"><i class="fa fa-info-circle"></i><p>Pendaftaran ini khusus akun Notaris/User dan memerlukan persetujuan admin.</p></div>
  <?php if (!empty($error)): ?><div class="auth-alert" role="alert"><i class="fa fa-exclamation-circle"></i><div><?= $error; ?></div></div><?php endif; ?>
  <?= form_open('', array('id'=>'form_register','method'=>'POST')); ?>
    <div class="auth-field <?= form_error('full_name') ? 'has-error' : ''; ?>"><label for="full_name">Nama Lengkap <span class="required">*</span></label><div class="auth-control-wrap"><i class="fa fa-user"></i><input class="auth-control" id="full_name" name="full_name" value="<?= _ent(set_value('full_name')); ?>" maxlength="200" autocomplete="name" placeholder="Nama lengkap beserta gelar" required autofocus></div></div>
    <div class="auth-grid">
      <div class="auth-field <?= form_error('username') ? 'has-error' : ''; ?>"><label for="username">Username <span class="required">*</span></label><div class="auth-control-wrap"><i class="fa fa-id-badge"></i><input class="auth-control" id="username" name="username" value="<?= _ent(set_value('username')); ?>" minlength="3" maxlength="50" autocomplete="username" placeholder="username" pattern="[A-Za-z0-9._-]+" required></div></div>
      <div class="auth-field <?= form_error('phone_number') ? 'has-error' : ''; ?>"><label for="phone_number">Nomor Telepon <span class="required">*</span></label><div class="auth-control-wrap"><i class="fa fa-phone"></i><input class="auth-control" id="phone_number" type="tel" name="phone_number" value="<?= _ent(set_value('phone_number')); ?>" maxlength="16" inputmode="numeric" autocomplete="tel" placeholder="08xxxxxxxxxx" required></div></div>
    </div>
    <div class="auth-field <?= form_error('email') ? 'has-error' : ''; ?>"><label for="email">Email <span class="required">*</span></label><div class="auth-control-wrap"><i class="fa fa-envelope"></i><input class="auth-control" id="email" type="email" name="email" value="<?= _ent(set_value('email')); ?>" maxlength="100" autocomplete="email" placeholder="nama@domain.go.id" required></div></div>
    <div class="auth-grid">
      <div class="auth-field <?= form_error('password') ? 'has-error' : ''; ?>"><label for="password">Password <span class="required">*</span></label><div class="auth-control-wrap"><i class="fa fa-lock"></i><input class="auth-control" id="password" type="password" name="password" minlength="8" maxlength="72" autocomplete="new-password" required><button class="password-toggle" type="button" data-toggle-password="password" aria-label="Tampilkan password"><i class="fa fa-eye"></i></button></div></div>
      <div class="auth-field <?= form_error('password_confirmation') ? 'has-error' : ''; ?>"><label for="password_confirmation">Konfirmasi <span class="required">*</span></label><div class="auth-control-wrap"><i class="fa fa-lock"></i><input class="auth-control" id="password_confirmation" type="password" name="password_confirmation" minlength="8" maxlength="72" autocomplete="new-password" required><button class="password-toggle" type="button" data-toggle-password="password_confirmation" aria-label="Tampilkan konfirmasi password"><i class="fa fa-eye"></i></button></div></div>
    </div>
    <small class="auth-hint" style="margin:-8px 0 14px">Minimal 8 karakter serta memuat huruf besar, huruf kecil, dan angka.</small>
    <?php $cap = get_captcha(); ?>
    <div class="auth-field <?= form_error('captcha') ? 'has-error' : ''; ?>"><label for="captcha">Captcha <span class="required">*</span></label><div class="captcha-grid" id="captchaRow" data-captcha-time="<?= _ent($cap['time']); ?>"><div class="auth-control-wrap"><i class="fa fa-keyboard-o"></i><input class="auth-control" id="captcha" name="captcha" maxlength="20" autocomplete="off" placeholder="Kode" required></div><div class="captcha-image" id="captchaBox"><?= $cap['image']; ?></div><button class="captcha-refresh" id="refreshCaptcha" type="button" aria-label="Muat ulang captcha"><i class="fa fa-refresh"></i></button></div></div>
    <label class="auth-agree"><input type="checkbox" name="agree" value="1" <?= set_checkbox('agree','1'); ?> required><span>Saya menyatakan data yang dimasukkan benar dan menyetujui proses verifikasi oleh administrator.</span></label>
    <button class="auth-submit" type="submit">Kirim Pendaftaran <i class="fa fa-arrow-right"></i></button>
  <?= form_close(); ?>
  <p class="auth-links">Sudah memiliki akun? <a href="<?= site_url('administrator/login'); ?>">Kembali ke halaman masuk</a></p>
</div></section></main>
<script>(function(){document.querySelectorAll('[data-toggle-password]').forEach(function(button){button.addEventListener('click',function(){var input=document.getElementById(button.dataset.togglePassword);var show=input.type==='password';input.type=show?'text':'password';button.querySelector('i').className=show?'fa fa-eye-slash':'fa fa-eye';});});var phone=document.getElementById('phone_number');phone.addEventListener('input',function(){phone.value=phone.value.replace(/[^0-9+]/g,'');});var refresh=document.getElementById('refreshCaptcha');refresh.addEventListener('click',function(){var row=document.getElementById('captchaRow');refresh.disabled=true;fetch('<?= base_url('captcha/reload/'); ?>'+encodeURIComponent(row.dataset.captchaTime),{credentials:'same-origin',headers:{'X-Requested-With':'XMLHttpRequest'}}).then(function(r){if(!r.ok)throw new Error();return r.json();}).then(function(data){document.getElementById('captchaBox').innerHTML=data.image;row.dataset.captchaTime=data.captcha.time;}).finally(function(){refresh.disabled=false;});});})();</script>
</body></html>
