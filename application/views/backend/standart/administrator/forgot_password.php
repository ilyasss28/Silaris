<!doctype html>
<html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= _ent(get_option('site_name')); ?> | Lupa Password</title>
<link rel="stylesheet" href="<?= BASE_ASSET; ?>vendor/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
<link rel="stylesheet" href="<?= base_url('asset/css/auth-account.css'); ?>"></head>
<body class="account-auth"><main class="auth-shell">
<aside class="auth-brand-panel">
  <div class="auth-brand"><img src="<?= base_url('assets/assets-guest/img/kemenkumham.png'); ?>" alt="Logo Kementerian Hukum"><div><strong><?= _ent(get_option('site_name') ?: 'Silaris'); ?></strong><span>Kementerian Hukum Sulawesi Tenggara</span></div></div>
  <div class="auth-brand-copy"><small>Pemulihan Akun</small><h1>Akses akun Anda dengan aman.</h1><p>Kami akan mengirim tautan satu kali ke alamat email yang terdaftar. Tautan hanya berlaku selama satu jam.</p></div>
  <div class="auth-security"><i class="fa fa-shield"></i><span>SILARIS tidak pernah meminta password lama melalui email maupun telepon.</span></div>
</aside>
<section class="auth-form-panel"><div class="auth-form-wrap">
  <header class="auth-heading"><p class="auth-eyebrow">Lupa Password</p><h2>Atur ulang password</h2><p>Masukkan email akun Anda untuk menerima tautan pemulihan.</p></header>
  <?php if (!empty($error)): ?><div class="auth-alert" role="alert"><i class="fa fa-exclamation-circle"></i><div><?= $error; ?></div></div><?php endif; ?>
  <?= form_open('', array('id'=>'form_forgot_password','method'=>'POST')); ?>
    <div class="auth-field <?= form_error('email') ? 'has-error' : ''; ?>"><label for="email">Email <span class="required">*</span></label><div class="auth-control-wrap"><i class="fa fa-envelope"></i><input class="auth-control" id="email" type="email" name="email" value="<?= _ent(set_value('email')); ?>" maxlength="100" autocomplete="email" placeholder="nama@domain.go.id" required autofocus></div></div>
    <?php $cap = get_captcha(); ?>
    <div class="auth-field <?= form_error('captcha') ? 'has-error' : ''; ?>"><label for="captcha">Captcha <span class="required">*</span></label><div class="captcha-grid" id="captchaRow" data-captcha-time="<?= _ent($cap['time']); ?>"><div class="auth-control-wrap"><i class="fa fa-keyboard-o"></i><input class="auth-control" id="captcha" type="text" name="captcha" maxlength="20" autocomplete="off" placeholder="Kode" required></div><div class="captcha-image" id="captchaBox"><?= $cap['image']; ?></div><button class="captcha-refresh" id="refreshCaptcha" type="button" aria-label="Muat ulang captcha"><i class="fa fa-refresh"></i></button></div></div>
    <button class="auth-submit" type="submit">Kirim Tautan Reset <i class="fa fa-arrow-right"></i></button>
  <?= form_close(); ?>
  <p class="auth-links"><a href="<?= site_url('administrator/register'); ?>"><i class="fa fa-user-plus"></i> Tambah Anggota Baru</a> &nbsp;&middot;&nbsp; <a href="<?= site_url('administrator/login'); ?>"><i class="fa fa-arrow-left"></i> Kembali ke halaman masuk</a></p>
</div></section></main>
<script>(function(){var button=document.getElementById('refreshCaptcha');button.addEventListener('click',function(){var row=document.getElementById('captchaRow');button.disabled=true;fetch('<?= base_url('captcha/reload/'); ?>'+encodeURIComponent(row.dataset.captchaTime),{credentials:'same-origin',headers:{'X-Requested-With':'XMLHttpRequest'}}).then(function(r){if(!r.ok)throw new Error();return r.json();}).then(function(data){document.getElementById('captchaBox').innerHTML=data.image;row.dataset.captchaTime=data.captcha.time;}).finally(function(){button.disabled=false;});});})();</script>
</body></html>
