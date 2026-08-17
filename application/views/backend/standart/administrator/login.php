<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Silaris | <?= cclang('login'); ?></title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="<?= BASE_ASSET; ?>/admin-lte/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <style>
    :root {
      --brand: #12294D;
      --brand-dark: #0A1930;
      --accent: #F5B301;
      --accent-dark: #C98E00;
      --ink-900: #0F1B2D;
      --ink-500: #6B7686;
      --border: #E3E7EF;
      --radius-sm: 8px;
      --control-h: 44px;
    }
    html, body {
      height: 100%;
      margin: 0;
      font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
    }
    body {
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, var(--ink-900) 0%, var(--brand-dark) 55%, var(--brand) 100%);
      padding: 24px;
    }
    .login-shell {
      width: 100%;
      max-width: 380px;
    }
    .login-brand {
      text-align: center;
      margin-bottom: 22px;
      color: #fff;
    }
    .login-brand img {
      height: 54px;
      margin-bottom: 12px;
    }
    .login-brand .site-name {
      font-weight: 800;
      font-size: 20px;
      letter-spacing: .02em;
    }
    .login-brand .site-tagline {
      font-size: 13px;
      color: rgba(255,255,255,.75);
      margin-top: 2px;
    }
    .login-card {
      background: #fff;
      border-radius: 16px;
      border-top: 4px solid var(--accent);
      box-shadow: 0 24px 60px rgba(0,0,0,.28);
      padding: 32px 30px;
    }
    .login-card h2 {
      margin: 0 0 4px 0;
      font-size: 19px;
      font-weight: 800;
      color: var(--ink-900);
    }
    .login-card .sub {
      margin: 0 0 22px 0;
      font-size: 13.5px;
      color: var(--ink-500);
    }
    .field {
      margin-bottom: 16px;
      position: relative;
    }
    .field label {
      display: block;
      font-size: 12.5px;
      font-weight: 700;
      color: var(--ink-900);
      margin-bottom: 6px;
      text-transform: uppercase;
      letter-spacing: .03em;
    }
    .field .fa {
      position: absolute;
      left: 13px;
      top: 40px;
      color: #B9BCC9;
    }
    .field input {
      width: 100%;
      height: var(--control-h);
      box-sizing: border-box;
      padding: 0 12px 0 36px;
      border: 1.5px solid var(--border);
      border-radius: var(--radius-sm);
      font-size: 14px;
      font-family: inherit;
      transition: border-color .15s ease, box-shadow .15s ease;
    }
    .field input:focus {
      outline: none;
      border-color: var(--brand);
      box-shadow: 0 0 0 3px rgba(18,41,77,.12);
    }
    .field.has-error input {
      border-color: #C0242F;
    }
    .row-between {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 20px;
    }
    .remember-label {
      display: flex;
      align-items: center;
      gap: 7px;
      font-size: 13px;
      color: var(--ink-500);
      cursor: pointer;
      font-weight: 500;
    }
    .btn-signin {
      width: 100%;
      height: var(--control-h);
      box-sizing: border-box;
      border: none;
      background: var(--accent);
      color: var(--ink-900);
      font-weight: 700;
      font-size: 14.5px;
      border-radius: var(--radius-sm);
      cursor: pointer;
      transition: background .15s ease, color .15s ease;
    }
    .btn-signin:hover { background: var(--accent-dark); color: #fff; }
    .callout-box {
      background: #FCEAEB;
      border: 1px solid #F3C3C6;
      color: #C0242F;
      border-radius: 8px;
      padding: 10px 14px;
      font-size: 13px;
      margin-bottom: 16px;
    }
    .callout-box h4 { margin: 0 0 2px 0; font-size: 13px; }
    .callout-box p { margin: 0; }
    .back-link {
      display: block;
      text-align: center;
      margin-top: 18px;
      font-size: 13px;
      color: rgba(255,255,255,.8);
    }
    .back-link a { color: #fff; font-weight: 600; text-decoration: none; }
    .back-link a:hover { text-decoration: underline; }
  </style>
</head>
<body>
<div class="login-shell">
  <div class="login-brand">
    <img src="<?= base_url('assets/assets-guest/img/kemenkumham.png'); ?>" alt="Kemenkumham">
    <div class="site-name"><?= get_option('site_name'); ?></div>
    <div class="site-tagline">Sistem Pelaporan Notaris</div>
  </div>

  <div class="login-card">
    <h2><?= cclang('login'); ?></h2>
    <p class="sub"><?= cclang('sign_to_start_your_session'); ?></p>

    <?php if (isset($error) AND !empty($error)): ?>
      <div class="callout-box">
        <h4><?= cclang('error'); ?>!</h4>
        <p><?= $error; ?></p>
      </div>
    <?php endif; ?>
    <?php
    $message = $this->session->flashdata('f_message');
    $type = $this->session->flashdata('f_type');
    if ($message):
    ?>
      <div class="callout-box"><p><?= $message; ?></p></div>
    <?php endif; ?>

    <?= form_open('', [
      'name'    => 'form_login',
      'id'      => 'form_login',
      'method'  => 'POST'
    ]); ?>
      <div class="field <?= form_error('username') ? 'has-error' : ''; ?>">
        <label for="login_username">Username</label>
        <i class="fa fa-user"></i>
        <input type="text" id="login_username" name="username" autocomplete="username" autofocus>
      </div>
      <div class="field <?= form_error('password') ? 'has-error' : ''; ?>">
        <label for="login_password">Password</label>
        <i class="fa fa-lock"></i>
        <input type="password" id="login_password" name="password" autocomplete="current-password">
      </div>

      <div class="row-between">
        <label class="remember-label">
          <input type="checkbox" name="remember" value="1"> <?= cclang('remember_me'); ?>
        </label>
      </div>

      <button type="submit" class="btn-signin"><?= cclang('sign_in'); ?></button>
    <?= form_close(); ?>
  </div>

  <div class="back-link">
    <a href="<?= site_url('home'); ?>">&larr; Kembali ke Beranda</a>
  </div>
</div>
</body>
</html>
