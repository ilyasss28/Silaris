<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <title>Silaris | Login</title>
  <link rel="stylesheet" href="<?= BASE_ASSET; ?>/bootstrap5/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= BASE_ASSET; ?>vendor/font-awesome/css/font-awesome.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root {
      --navy: #07064f;
      --navy-dark: #03032f;
      --navy-soft: #15146d;
      --yellow: #ffcf00;
      --yellow-soft: #fff8d8;
      --ink: #101828;
      --muted: #667085;
      --border: #dce3ed;
    }
    * { box-sizing: border-box; }
    html, body { width: 100%; height: 100%; min-height: 100%; overflow: hidden; }
    body {
      margin: 0;
      height: 100vh;
      height: 100dvh;
      min-height: 0;
      padding: 0;
      display: flex;
      align-items: stretch;
      justify-content: center;
      overflow-x: hidden;
      background: #fff;
      color: var(--ink);
      font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
    }
    .login-shell {
      width: 100%;
      height: 100vh;
      height: 100dvh;
      min-height: 0;
      display: grid;
      grid-template-columns: minmax(380px, 45%) minmax(440px, 55%);
      overflow: hidden;
      border: 0;
      border-radius: 0;
      background: #fff;
      box-shadow: none;
    }
    .welcome-panel {
      position: relative;
      height: 100%;
      min-height: 0;
      padding: clamp(28px, 4vw, 56px);
      display: flex;
      flex-direction: column;
      overflow: hidden;
      background:
        linear-gradient(145deg, rgba(255,255,255,.06), transparent 42%),
        linear-gradient(145deg, var(--navy-soft), var(--navy) 58%, var(--navy-dark));
      color: #fff;
    }
    .welcome-panel::before,
    .welcome-panel::after {
      content: '';
      position: absolute;
      border: 1px solid rgba(255, 207, 0, .38);
      border-radius: 44% 56% 55% 45%;
      transform: rotate(28deg);
    }
    .welcome-panel::before {
      width: 290px;
      height: 290px;
      right: -145px;
      bottom: -70px;
      box-shadow: 0 0 0 16px rgba(255,207,0,.04), 0 0 0 38px rgba(255,207,0,.025);
    }
    .welcome-panel::after {
      width: 180px;
      height: 180px;
      top: -92px;
      left: -82px;
      box-shadow: 0 0 0 18px rgba(255,255,255,.035);
    }
    .brand, .welcome-copy, .welcome-notice { position: relative; z-index: 2; }
    .brand { display: flex; align-items: center; gap: 13px; }
    .brand-logo {
      width: 56px;
      height: 56px;
      padding: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      border: 0;
      border-radius: 0;
      background: transparent;
      box-shadow: none;
    }
    .brand-logo img { max-width: 100%; max-height: 100%; object-fit: contain; }
    .brand-name strong, .brand-name span { display: block; }
    .brand-name strong { font-size: 20px; font-weight: 800; letter-spacing: -.02em; }
    .brand-name span { margin-top: 3px; color: rgba(255,255,255,.7); font-size: 10px; line-height: 1.5; }
    .welcome-copy { margin: auto 0; padding: 38px 0; }
    .welcome-kicker {
      margin: 0 0 15px;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      color: var(--yellow);
      font-size: 11px;
      font-weight: 800;
      letter-spacing: .12em;
      text-transform: uppercase;
    }
    .welcome-kicker::before { content: ''; width: 24px; height: 2px; border-radius: 99px; background: var(--yellow); }
    .welcome-copy h1 {
      max-width: 360px;
      margin: 0;
      color: #fff;
      font-size: clamp(34px, 4vw, 48px);
      font-weight: 800;
      line-height: 1.12;
      letter-spacing: -.04em;
    }
    .welcome-copy h1 span { color: var(--yellow); }
    .welcome-copy p {
      max-width: 350px;
      margin: 18px 0 0;
      color: rgba(255,255,255,.72);
      font-size: 13px;
      line-height: 1.8;
    }
    .welcome-notice {
      padding-top: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
      border-top: 1px solid rgba(255,255,255,.13);
      color: rgba(255,255,255,.65);
      font-size: 10.5px;
      line-height: 1.5;
    }
    .welcome-notice i { color: var(--yellow); font-size: 14px; }
    .dot-pattern {
      position: absolute;
      z-index: 1;
      top: 34px;
      right: 32px;
      width: 58px;
      height: 88px;
      opacity: .62;
      background-image: radial-gradient(var(--yellow) 1.5px, transparent 1.5px);
      background-size: 10px 10px;
    }
    .form-panel { height: 100%; min-height: 0; padding: clamp(20px, 3.5vh, 36px) clamp(38px, 8vw, 112px); display: flex; align-items: center; overflow: hidden; background: #fff; }
    .login-form-wrap { width: 100%; max-width: 440px; margin: 0 auto; }
    .form-heading { margin-bottom: 20px; }
    .form-heading .eyebrow {
      margin: 0 0 7px;
      color: #a17c00;
      font-size: 10.5px;
      font-weight: 800;
      letter-spacing: .1em;
      text-transform: uppercase;
    }
    .form-heading h2 { margin: 0; color: var(--navy); font-size: 30px; font-weight: 800; letter-spacing: -.035em; }
    .form-heading p { margin: 8px 0 0; color: var(--muted); font-size: 12px; line-height: 1.6; }
    .alert-message {
      margin-bottom: 17px;
      padding: 11px 13px;
      display: flex;
      align-items: flex-start;
      gap: 9px;
      border: 1px solid #f0bbc1;
      border-radius: 10px;
      background: #fff4f5;
      color: #a92735;
      font-size: 11.5px;
      line-height: 1.55;
    }
    .alert-message--success { border-color: #b8e0cb; background: #f0faf5; color: #19744a; }
    .alert-message--warning { border-color: #efd371; background: #fffbea; color: #735900; }
    .alert-message p { margin: 0; }
    .login-field { margin-bottom: 12px; }
    .login-field label { margin: 0 0 5px; display: block; color: #344054; font-size: 11.5px; font-weight: 700; }
    .control-wrap { position: relative; }
    .control-icon {
      position: absolute;
      z-index: 2;
      top: 50%;
      left: 15px;
      width: 17px;
      transform: translateY(-50%);
      color: #919bae;
      text-align: center;
      font-size: 13px;
      pointer-events: none;
    }
    .login-control {
      width: 100%;
      height: 44px;
      padding: 0 43px;
      border: 1px solid var(--border);
      border-radius: 11px;
      outline: none;
      background: #fbfcfe;
      color: var(--ink);
      font-family: inherit;
      font-size: 12.5px;
      font-weight: 500;
      line-height: 44px;
      text-align: left;
      transition: border-color .18s ease, box-shadow .18s ease, background .18s ease;
    }
    select.login-control { appearance: none; -webkit-appearance: none; padding-right: 44px; cursor: pointer; }
    .select-arrow {
      position: absolute;
      top: 50%;
      right: 16px;
      transform: translateY(-50%);
      color: #7a8597;
      font-size: 11px;
      pointer-events: none;
    }
    .login-control::placeholder { color: #a5adba; font-weight: 400; }
    .login-control:focus { border-color: #a58817; background: #fff; box-shadow: 0 0 0 4px rgba(255,207,0,.18); }
    .login-field.has-error .login-control { border-color: #df7883; }
    .password-toggle {
      position: absolute;
      z-index: 3;
      top: 50%;
      right: 8px;
      width: 34px;
      height: 34px;
      transform: translateY(-50%);
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border: 0;
      border-radius: 8px;
      background: transparent;
      color: #7a8597;
      cursor: pointer;
    }
    .password-toggle:hover { background: #f1f3f7; color: var(--navy); }
    .captcha-grid { display: grid; grid-template-columns: minmax(120px, 1fr) 142px 46px; gap: 8px; }
    .captcha-grid .login-control { padding-right: 12px; }
    .captcha-image {
      height: 44px;
      padding: 3px 7px;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      border: 1px solid var(--border);
      border-radius: 11px;
      background: #f7f8fb;
    }
    .captcha-image img, .captcha-image svg { max-width: 100%; max-height: 38px; display: block; }
    .captcha-refresh {
      width: 46px;
      height: 44px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border: 1px solid #e4c34c;
      border-radius: 11px;
      background: var(--yellow-soft);
      color: #846500;
      cursor: pointer;
    }
    .captcha-refresh:hover { background: #fff0a6; }
    .captcha-refresh.is-loading i { animation: spin .7s linear infinite; }
    @keyframes spin { to { transform: rotate(360deg); } }
    .form-options { margin: 3px 0 14px; display: flex; align-items: center; justify-content: flex-end; }
    .forgot-link { color: #535d70; font-size: 10.5px; font-weight: 600; text-decoration: none; }
    .forgot-link:hover { color: var(--navy); text-decoration: underline; }
    .login-button {
      width: 100%;
      height: 46px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 9px;
      border: 1px solid var(--navy);
      border-radius: 11px;
      background: var(--navy);
      color: #fff;
      font-family: inherit;
      font-size: 12.5px;
      font-weight: 800;
      cursor: pointer;
      box-shadow: none;
      transition: transform .15s ease, background .15s ease;
    }
    .login-button:hover { background: #11106a; box-shadow: none; }
    .login-button:active { transform: translateY(1px); }
    .login-button i { color: var(--yellow); }
    .form-footer { margin: 14px 0 0; color: #667085; font-size: 10.5px; line-height: 1.5; text-align: center; }
    .home-link { display: inline-flex; align-items: center; gap: 7px; color: var(--navy); font-weight: 700; text-decoration: none; }
    .home-link:hover { color: #a17c00; text-decoration: underline; }

    @media (min-width: 821px) and (max-height: 720px) {
      .welcome-panel { padding: 26px 44px; }
      .welcome-copy { padding: 20px 0; }
      .welcome-copy h1 { font-size: 38px; }
      .welcome-copy p { margin-top: 12px; line-height: 1.65; }
      .welcome-notice { padding-top: 14px; }
      .form-panel { padding-top: 14px; padding-bottom: 14px; }
      .form-heading { margin-bottom: 14px; }
      .form-heading h2 { font-size: 27px; }
      .form-heading p { margin-top: 5px; }
      .login-field { margin-bottom: 9px; }
      .form-options { margin: 1px 0 10px; }
      .form-footer { margin-top: 10px; }
    }
    @media (max-width: 820px) {
      html, body { height: auto; min-height: 100%; overflow-x: hidden; overflow-y: auto; }
      body { align-items: flex-start; }
      .login-shell { height: auto; grid-template-columns: 1fr; min-height: 100dvh; }
      .welcome-panel { height: auto; min-height: 230px; padding: 28px 30px; }
      .welcome-copy { padding: 38px 0 8px; }
      .welcome-copy h1 { font-size: 30px; }
      .welcome-copy p { margin-top: 10px; }
      .welcome-notice { display: none; }
      .dot-pattern { right: 18px; top: 18px; }
      .form-panel { height: auto; min-height: 0; padding: 34px 30px 40px; overflow: visible; }
    }
    @media (max-width: 480px) {
      body { padding: 0; background: #fff; }
      .login-shell { border: 0; border-radius: 0; box-shadow: none; }
      .welcome-panel { min-height: 205px; padding: 24px; }
      .brand-logo { width: 48px; height: 48px; }
      .brand-name strong { font-size: 17px; }
      .welcome-copy { padding-top: 28px; }
      .welcome-copy h1 { font-size: 27px; }
      .welcome-copy p { font-size: 11.5px; }
      .form-panel { padding: 30px 22px 38px; }
      .captcha-grid { grid-template-columns: minmax(105px, 1fr) 115px 46px; }
    }
  </style>
</head>
<body>
  <main class="login-shell">
    <section class="welcome-panel" aria-label="Selamat datang di Silaris">
      <div class="dot-pattern" aria-hidden="true"></div>
      <div class="brand">
        <span class="brand-logo"><img src="<?= base_url('assets/assets-guest/img/kemenkumham.png'); ?>" alt="Logo Kementerian Hukum"></span>
        <span class="brand-name">
          <strong><?= _ent(get_option('site_name') ?: 'Silaris'); ?></strong>
          <span>Kementerian Hukum Sulawesi Tenggara</span>
        </span>
      </div>
      <div class="welcome-copy">
        <div class="welcome-kicker">Portal Administrasi</div>
        <h1>Selamat datang <span>kembali!</span></h1>
        <p>Masuk untuk mengelola layanan, laporan, dan data administrasi melalui satu sistem yang aman dan terintegrasi.</p>
      </div>
      <div class="welcome-notice">
        <i class="fa fa-shield"></i>
        <span>Akses sistem dilindungi dan hanya tersedia untuk pengguna yang telah memiliki kewenangan.</span>
      </div>
    </section>

    <section class="form-panel">
      <div class="login-form-wrap">
        <header class="form-heading">
          <p class="eyebrow">Akses Akun</p>
          <h2>Masuk ke Silaris</h2>
          <p>Pilih role akun lalu masukkan kredensial Anda.</p>
        </header>

        <?php if (isset($error) && !empty($error)): ?>
          <div class="alert-message" role="alert"><i class="fa fa-exclamation-circle"></i><div><?= $error; ?></div></div>
        <?php endif; ?>
        <?php $message = $this->session->flashdata('f_message'); ?>
        <?php $message_type = $this->session->flashdata('f_type'); ?>
        <?php if ($message): ?>
          <div class="alert-message <?= $message_type === 'success' ? 'alert-message--success' : ($message_type === 'warning' ? 'alert-message--warning' : ''); ?>" role="status"><i class="fa <?= $message_type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i><p><?= _ent($message); ?></p></div>
        <?php endif; ?>

        <?= form_open('', ['name' => 'form_login', 'id' => 'form_login', 'method' => 'POST']); ?>
          <div class="login-field <?= form_error('group') ? 'has-error' : ''; ?>">
            <label for="group">Group/Role</label>
            <div class="control-wrap">
              <i class="fa fa-users control-icon"></i>
              <select class="login-control" id="group" name="group" required>
                <option value="">Pilih Group/Role</option>
                <?php foreach ($login_groups as $login_group): ?>
                  <?php $role_label = $login_group->name === 'User' ? 'User / Notaris' : $login_group->name; ?>
                  <option value="<?= _ent($login_group->name); ?>" <?= set_select('group', $login_group->name); ?>><?= _ent($role_label); ?></option>
                <?php endforeach; ?>
              </select>
              <i class="fa fa-chevron-down select-arrow"></i>
            </div>
          </div>
          <div class="login-field <?= form_error('username') ? 'has-error' : ''; ?>">
            <label for="username">Username</label>
            <div class="control-wrap">
              <i class="fa fa-user control-icon"></i>
              <input class="login-control" type="text" id="username" name="username" value="<?= _ent(set_value('username')); ?>" autocomplete="username" autofocus placeholder="Masukkan username" required>
            </div>
          </div>
          <div class="login-field <?= form_error('password') ? 'has-error' : ''; ?>">
            <label for="password">Password</label>
            <div class="control-wrap">
              <i class="fa fa-lock control-icon"></i>
              <input class="login-control" type="password" id="password" name="password" autocomplete="current-password" placeholder="Masukkan password" required>
              <button type="button" class="password-toggle" id="togglePassword" aria-label="Tampilkan password" aria-pressed="false"><i class="fa fa-eye"></i></button>
            </div>
          </div>
          <div class="login-field <?= form_error('captcha') ? 'has-error' : ''; ?>">
            <label for="captcha">Captcha</label>
            <?php $cap = get_captcha(); ?>
            <div class="captcha-grid" id="captchaRow" data-captcha-time="<?= _ent($cap['time']); ?>">
              <div class="control-wrap">
                <i class="fa fa-keyboard-o control-icon"></i>
                <input class="login-control" type="text" id="captcha" name="captcha" autocomplete="off" placeholder="Kode" required>
              </div>
              <div class="captcha-image" id="captchaBox" aria-label="Gambar captcha"><?= $cap['image']; ?></div>
              <button type="button" class="captcha-refresh" id="refreshCaptcha" title="Muat ulang captcha" aria-label="Muat ulang captcha"><i class="fa fa-refresh"></i></button>
            </div>
          </div>
          <div class="form-options"><a class="forgot-link" href="<?= site_url('administrator/forgot-password'); ?>">Lupa password?</a></div>
          <button type="submit" class="login-button"><span>Masuk</span><i class="fa fa-arrow-right"></i></button>
        <?= form_close(); ?>
        <p class="form-footer"><a class="home-link" href="<?= site_url('home'); ?>"><i class="fa fa-arrow-left" aria-hidden="true"></i> Kembali ke Beranda</a></p>
      </div>
    </section>
  </main>

  <script>
    (function () {
      'use strict';
      var toggle = document.getElementById('togglePassword');
      var password = document.getElementById('password');
      toggle.addEventListener('click', function () {
        var showing = password.type === 'text';
        password.type = showing ? 'password' : 'text';
        toggle.setAttribute('aria-pressed', showing ? 'false' : 'true');
        toggle.setAttribute('aria-label', showing ? 'Tampilkan password' : 'Sembunyikan password');
        toggle.querySelector('i').className = showing ? 'fa fa-eye' : 'fa fa-eye-slash';
      });

      var refreshButton = document.getElementById('refreshCaptcha');
      refreshButton.addEventListener('click', function () {
        var row = document.getElementById('captchaRow');
        var oldTime = row.getAttribute('data-captcha-time');
        refreshButton.classList.add('is-loading');
        refreshButton.disabled = true;
        fetch('<?= base_url('captcha/reload/'); ?>' + encodeURIComponent(oldTime), {
          credentials: 'same-origin',
          headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
          .then(function (response) {
            if (!response.ok) throw new Error('HTTP ' + response.status);
            return response.json();
          })
          .then(function (data) {
            if (!data || !data.captcha) throw new Error('Captcha response invalid');
            row.setAttribute('data-captcha-time', data.captcha.time);
            document.getElementById('captchaBox').innerHTML = data.captcha.image;
            document.getElementById('captcha').value = '';
            document.getElementById('captcha').focus();
          })
          .catch(function () { window.location.reload(); })
          .finally(function () {
            refreshButton.classList.remove('is-loading');
            refreshButton.disabled = false;
          });
      });
    }());
  </script>
</body>
</html>
