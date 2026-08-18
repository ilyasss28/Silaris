<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?= get_option('site_name'); ?> | Forgot Password</title>
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
    .login-shell { width: 100%; max-width: 420px; }
    .login-brand { text-align: center; margin-bottom: 22px; color: #fff; }
    .login-brand .site-name { font-weight: 800; font-size: 20px; letter-spacing: .02em; }
    .login-card {
      background: #fff;
      border-radius: 16px;
      border-top: 4px solid var(--accent);
      box-shadow: 0 24px 60px rgba(0,0,0,.28);
      padding: 32px 30px;
    }
    .login-card h2 { margin: 0 0 4px 0; font-size: 19px; font-weight: 800; color: var(--ink-900); }
    .login-card .sub { margin: 0 0 22px 0; font-size: 13.5px; color: var(--ink-500); }
    .field { margin-bottom: 16px; position: relative; }
    .field label {
      display: block;
      font-size: 12.5px;
      font-weight: 700;
      color: var(--ink-900);
      margin-bottom: 6px;
      text-transform: uppercase;
      letter-spacing: .03em;
    }
    .field label .required { color: #C0242F; }
    .field .fa {
      position: absolute;
      left: 13px;
      top: 40px;
      color: #B9BCC9;
    }
    .field input[type="email"],
    .field input[type="text"] {
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
      box-shadow: 0 0 0 3px rgba(5,6,62,.12);
    }
    .field.has-error input { border-color: #C0242F; }
    .captcha-row { display: flex; align-items: center; gap: 10px; }
    .captcha-row input[type="text"] {
      flex: 1 1 auto;
      height: var(--control-h);
      box-sizing: border-box;
      padding: 0 12px;
      border: 1.5px solid var(--border);
      border-radius: var(--radius-sm);
      font-size: 14px;
    }
    .captcha-row .box-image { flex: 0 0 auto; }
    .captcha-row .box-image svg,
    .captcha-row .box-image img { display: block; height: var(--control-h); }
    .refresh-captcha {
      flex: 0 0 auto;
      width: var(--control-h);
      height: var(--control-h);
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border: 1.5px solid var(--border);
      border-radius: var(--radius-sm);
      color: var(--brand);
      cursor: pointer;
      text-decoration: none;
    }
    .refresh-captcha:hover { background: var(--border); }
    .btn-submit {
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
      margin-top: 6px;
      transition: background .15s ease, color .15s ease;
    }
    .btn-submit:hover { background: var(--accent-dark); color: var(--ink-900); }
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
    <div class="site-name"><?= get_option('site_name'); ?></div>
  </div>

  <div class="login-card">
    <h2><?= cclang('login'); ?></h2>
    <p class="sub"><?= cclang('send_me_link_to_reset_password'); ?></p>

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
      'name'    => 'form_forgot_password',
      'id'      => 'form_forgot_password',
      'method'  => 'POST'
    ]); ?>
      <div class="field <?= form_error('email') ? 'has-error' : ''; ?>">
        <label><?= cclang('email') ?> <span class="required">*</span></label>
        <i class="fa fa-envelope"></i>
        <input type="email" placeholder="Email" name="email">
      </div>

      <?php $cap = get_captcha(); ?>
      <div class="field <?= form_error('email') ? 'has-error' : ''; ?>">
        <label><?= cclang('human_challenge'); ?> <span class="required">*</span></label>
        <div class="captcha-row" data-captcha-time="<?= $cap['time']; ?>">
          <input type="text" name="captcha" placeholder="">
          <span class="box-image"><?= $cap['image']; ?></span>
          <a class="refresh-captcha" title="Refresh"><i class="fa fa-refresh"></i></a>
        </div>
      </div>

      <button type="submit" class="btn-submit"><?= cclang('reset'); ?></button>
    <?= form_close(); ?>
  </div>

  <div class="back-link">
    <a href="<?= site_url('administrator/register'); ?>"><?= cclang('register_a_new_membership'); ?></a>
  </div>
</div>

<script src="<?= BASE_ASSET; ?>/jquery4/jquery.min.js"></script>
<script src="<?= BASE_ASSET; ?>/jquery4/jquery-compat-shim.js"></script>
<script>
  $(function() {
     var BASE_URL = "<?= base_url(); ?>";

     $('.refresh-captcha').on('click', function() {
         var capparent = $(this).closest('.captcha-row');

         $.ajax({
                 url: BASE_URL + '/captcha/reload/' + capparent.attr('data-captcha-time'),
                 dataType: 'JSON',
             })
             .done(function(res) {
                 capparent.find('.box-image').html(res.image);
                 capparent.attr('data-captcha-time', res.captcha.time);
             });
     });
 });
</script>
</body>
</html>
