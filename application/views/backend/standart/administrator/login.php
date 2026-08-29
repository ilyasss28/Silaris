<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Silaris | <?= cclang('login'); ?></title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="<?= BASE_ASSET; ?>/bootstrap5/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  
  <style>
    :root {
      --brand: #05063E;
      --brand-dark: #030228;
      --accent: #FECD08;
      --accent-dark: #CBA406;
      --control-h: 46px;
    }
    body, html {
      height: 100%;
      margin: 0;
      font-family: 'Plus Jakarta Sans', sans-serif;
      background: linear-gradient(135deg, var(--brand-dark) 0%, var(--brand) 100%);
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    
    /* Abstract background shapes */
    .shape {
      position: absolute;
      z-index: 0;
    }
    .shape-1 {
      width: 120px;
      height: 120px;
      background: linear-gradient(135deg, #17207a, #0b1049);
      border-radius: 30px;
      top: 15%;
      left: 15%;
      transform: rotate(45deg);
      box-shadow: 10px 10px 30px rgba(0,0,0,0.4);
    }
    .shape-1::before {
      content: '';
      position: absolute;
      width: 60px;
      height: 60px;
      background: var(--accent);
      border-radius: 15px;
      bottom: -30px;
      right: -30px;
      box-shadow: 5px 5px 20px rgba(0,0,0,0.3);
    }
    .shape-2 {
      width: 250px;
      height: 250px;
      border: 45px solid rgba(254, 205, 8, 0.15);
      border-radius: 50%;
      bottom: -50px;
      right: 10%;
      box-shadow: 15px 15px 50px rgba(0,0,0,0.3);
    }
    .shape-3 {
      width: 80px;
      height: 25px;
      background: #17207a;
      border-radius: 20px;
      bottom: 25%;
      left: 20%;
      transform: rotate(-15deg);
      box-shadow: 5px 5px 20px rgba(0,0,0,0.4);
    }
    .shape-3::after {
      content: '';
      position: absolute;
      width: 60px;
      height: 25px;
      background: rgba(254, 205, 8, 0.8);
      border-radius: 20px;
      bottom: -35px;
      left: 15px;
    }
    .shape-4 {
      width: 200px;
      height: 200px;
      border: 30px solid #0f1559;
      border-radius: 50%;
      top: -50px;
      right: 20%;
    }
    
    .login-container {
      position: relative;
      z-index: 1;
      width: 100%;
      max-width: 440px;
      padding: 0 20px;
    }
    
    .glass-card {
      background: rgba(255, 255, 255, 0.08);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.15);
      border-radius: 24px;
      padding: 30px 35px;
      box-shadow: 0 25px 45px rgba(0, 0, 0, 0.4);
      text-align: center;
    }
    
    .login-logo {
      height: 55px;
      margin-bottom: 10px;
    }
    
    .glass-card h2 {
      color: #fff;
      font-weight: 800;
      font-size: 26px;
      margin-bottom: 20px;
    }
    .glass-card .logo-title {
      font-size: 13px;
      color: rgba(255,255,255,0.7);
      font-weight: 600;
      margin-bottom: -2px;
      letter-spacing: 0.05em;
    }
    
    .field {
      margin-bottom: 15px;
      text-align: left;
      position: relative;
    }
    .field label {
      display: block;
      color: #fff;
      font-size: 13.5px;
      font-weight: 500;
      margin-bottom: 6px;
    }
    .field input {
      width: 100%;
      height: var(--control-h);
      background: rgba(255, 255, 255, 0.95);
      border: none;
      border-radius: 8px;
      padding: 0 16px;
      font-size: 14.5px;
      font-family: inherit;
      color: var(--brand);
      transition: box-shadow 0.2s, background 0.2s;
    }
    .field input:focus {
      outline: none;
      box-shadow: 0 0 0 4px rgba(254, 205, 8, 0.4);
    }
    .field.has-error input {
      border: 2px solid #ff4b4b;
    }
    .toggle-password {
      position: absolute;
      right: 16px;
      bottom: 14px;
      color: var(--brand);
      cursor: pointer;
      font-size: 16px;
      opacity: 0.5;
      transition: opacity 0.2s;
    }
    .toggle-password:hover {
      opacity: 1;
    }
    
    .captcha-row {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .captcha-row input {
      flex: 1;
      width: auto;
    }
    .captcha-box {
      height: var(--control-h);
      border-radius: 8px;
      overflow: hidden;
      background: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 0 5px;
    }
    .captcha-box img {
      height: 90%;
      display: block;
    }
    .btn-refresh {
      background: rgba(255, 255, 255, 0.15);
      border: 1px solid rgba(255,255,255,0.25);
      color: #fff;
      height: var(--control-h);
      width: var(--control-h);
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: background 0.2s;
    }
    .btn-refresh:hover {
      background: rgba(255, 255, 255, 0.25);
    }
    
    .btn-login {
      width: 100%;
      height: 48px;
      background: var(--accent); 
      color: var(--brand-dark);
      border: none;
      border-radius: 8px;
      font-weight: 800;
      font-size: 16px;
      margin-top: 15px;
      cursor: pointer;
      transition: background 0.2s, transform 0.1s;
    }
    .btn-login:hover {
      background: var(--accent-dark);
    }
    .btn-login:active {
      transform: scale(0.98);
    }
    
    .callout-box {
      background: rgba(255, 75, 75, 0.15);
      border: 1px solid rgba(255, 75, 75, 0.4);
      color: #fff;
      border-radius: 8px;
      padding: 12px;
      font-size: 13px;
      margin-bottom: 20px;
      text-align: left;
    }
    .callout-box p { margin: 0; }
  </style>
</head>
<body>

<!-- Background Shapes -->
<div class="shape shape-1"></div>
<div class="shape shape-2"></div>
<div class="shape shape-3"></div>
<div class="shape shape-4"></div>

<div class="login-container">
  <div class="glass-card">
    <img src="<?= base_url('assets/assets-guest/img/kemenkumham.png'); ?>" alt="Logo Kemenkumham" class="login-logo">
    <div class="logo-title"><?= get_option('site_name'); ?></div>
    <h2>Login</h2>
    
    <?php if (isset($error) AND !empty($error)): ?>
      <div class="callout-box">
        <p><?= $error; ?></p>
      </div>
    <?php endif; ?>
    <?php
    $message = $this->session->flashdata('f_message');
    if ($message):
    ?>
      <div class="callout-box" style="background: rgba(34,197,94,0.2); border-color: rgba(34,197,94,0.4);"><p><?= $message; ?></p></div>
    <?php endif; ?>

    <?= form_open('', ['name' => 'form_login', 'id' => 'form_login', 'method' => 'POST']); ?>
      
      <div class="field <?= form_error('username') ? 'has-error' : ''; ?>">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" autocomplete="username" autofocus placeholder="Masukkan Username">
      </div>
      
      <div class="field <?= form_error('password') ? 'has-error' : ''; ?>">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" autocomplete="current-password" placeholder="Masukkan Password">
        <i class="fa fa-eye toggle-password" id="togglePassword" title="Show password"></i>
      </div>
      
      <div class="field <?= form_error('captcha') ? 'has-error' : ''; ?>">
        <label>Captcha</label>
        <?php 
        $cap = get_captcha(); 
        ?>
        <div class="captcha-row" id="captchaRow" data-captcha-time="<?= $cap['time']; ?>">
          <input type="text" name="captcha" autocomplete="off" placeholder="Code">
          <div class="captcha-box" id="captchaBox">
            <?= $cap['image']; ?>
          </div>
          <button type="button" class="btn-refresh" id="refreshCaptcha" title="Refresh Captcha">
            <i class="fa fa-refresh"></i>
          </button>
        </div>
      </div>
      
      <button type="submit" class="btn-login">Sign in</button>
      
    <?= form_close(); ?>
  </div>
</div>

<script>
  // Password toggle
  const togglePassword = document.getElementById('togglePassword');
  const password = document.getElementById('password');
  
  togglePassword.addEventListener('click', function () {
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);
    this.classList.toggle('fa-eye-slash');
  });

  // Refresh captcha
  document.getElementById('refreshCaptcha').addEventListener('click', function() {
    var row = document.getElementById('captchaRow');
    var time = row.getAttribute('data-captcha-time');
    var btn = this;
    var icon = btn.querySelector('.fa-refresh');
    
    icon.style.transform = 'rotate(180deg)';
    icon.style.transition = 'transform 0.3s';
    
    fetch('<?= base_url("captcha/reload/"); ?>' + time)
      .then(response => response.json())
      .then(data => {
        if(data && data.captcha) {
          row.setAttribute('data-captcha-time', data.captcha.time);
          document.getElementById('captchaBox').innerHTML = data.captcha.image;
        }
        setTimeout(() => { icon.style.transform = 'rotate(0deg)'; }, 300);
      })
      .catch(err => {
        console.error('Error refreshing captcha', err);
        icon.style.transform = 'rotate(0deg)';
      });
  });
</script>
</body>
</html>
