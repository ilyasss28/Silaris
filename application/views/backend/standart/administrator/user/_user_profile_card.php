<?php
$is_own_profile = isset($profile_mode) && $profile_mode === 'account';
$profile_title = $is_own_profile ? 'Profil Saya' : 'Detail Profil Notaris';
$profile_description = $is_own_profile
  ? 'Kelola identitas, wilayah, dan keamanan akun yang sedang digunakan.'
  : 'Informasi akun notaris yang dipilih dari halaman manajemen pengguna.';
$avatar_name = !empty($user->avatar) ? basename($user->avatar) : 'default.png';
$avatar_path = FCPATH . 'uploads/user/' . $avatar_name;
$avatar_url = BASE_URL . 'uploads/user/' . (is_file($avatar_path) ? $avatar_name : 'default.png');
$display_date = function ($value) {
  if (empty($value) || $value === '0000-00-00 00:00:00') {
    return '-';
  }

  $timestamp = strtotime($value);
  return $timestamp ? date('d M Y, H:i', $timestamp) : $value;
};
?>

<section class="content profile-page <?= $is_own_profile ? 'profile-page--account' : 'profile-page--notary'; ?>">
  <div class="profile-shell">
    <header class="profile-page-header">
      <div class="profile-page-header__copy">
        <span class="profile-page-header__icon"><i class="fa <?= $is_own_profile ? 'fa-user-circle' : 'fa-address-card'; ?>"></i></span>
        <div>
          <span class="profile-page-header__eyebrow"><?= $is_own_profile ? 'AKUN SAYA' : 'MANAJEMEN NOTARIS'; ?></span>
          <h1><?= $profile_title; ?></h1>
          <p><?= $profile_description; ?></p>
        </div>
      </div>

      <nav class="profile-page-actions" aria-label="Tindakan profil">
        <?php if ($is_own_profile): ?>
          <?php if ($this->aauth->is_allowed('user_update_profile')): ?>
            <a href="<?= site_url('administrator/profile/edit'); ?>" class="btn profile-btn profile-btn--primary"><i class="fa fa-pencil"></i> Edit Profil Saya</a>
          <?php endif; ?>
        <?php else: ?>
          <a href="<?= site_url('administrator/user'); ?>" class="btn profile-btn profile-btn--secondary"><i class="fa fa-arrow-left"></i> Kembali ke Daftar</a>
          <?php if ($this->aauth->is_allowed('user_update')): ?>
            <a href="<?= site_url('administrator/user/edit/' . (int) $user->id); ?>" class="btn profile-btn profile-btn--primary"><i class="fa fa-pencil"></i> Edit Notaris</a>
          <?php endif; ?>
        <?php endif; ?>
      </nav>
    </header>

    <div class="profile-layout">
      <aside class="profile-identity-card">
        <div class="profile-avatar-wrap">
          <img src="<?= $avatar_url; ?>" alt="Foto <?= _ent(format_person_name($user->full_name)); ?>" class="profile-avatar">
          <span class="profile-presence <?= $user->banned ? 'is-inactive' : 'is-active'; ?>" title="<?= $user->banned ? 'Nonaktif' : 'Aktif'; ?>"></span>
        </div>
        <h2><?= _ent(format_person_name($user->full_name)); ?></h2>
        <p class="profile-username">@<?= _ent($user->username); ?></p>
        <span class="profile-status <?= $user->banned ? 'is-inactive' : 'is-active'; ?>"><i class="fa fa-circle"></i> <?= $user->banned ? 'Nonaktif' : 'Aktif'; ?></span>

        <div class="profile-identity-meta">
          <span><i class="fa fa-envelope-o"></i> <?= _ent($user->email); ?></span>
          <?php if (!empty($user->kd_wilayah)): ?>
            <span><i class="fa fa-map-marker"></i> Wilayah <?= _ent($user->kd_wilayah); ?></span>
          <?php endif; ?>
        </div>
      </aside>

      <div class="profile-content">
        <section class="profile-panel">
          <div class="profile-panel__heading">
            <span class="profile-panel__icon"><i class="fa fa-id-card-o"></i></span>
            <div><h2>Informasi Akun</h2><p>Identitas utama yang digunakan pada sistem.</p></div>
          </div>
          <dl class="profile-detail-grid">
            <div><dt>Nama Lengkap</dt><dd><?= _ent(format_person_name($user->full_name)); ?></dd></div>
            <div><dt>Username</dt><dd><?= _ent($user->username); ?></dd></div>
            <div><dt>Email</dt><dd><?= _ent($user->email); ?></dd></div>
            <div><dt>Kode Wilayah</dt><dd><?= !empty($user->kd_wilayah) ? _ent($user->kd_wilayah) : '-'; ?></dd></div>
          </dl>
        </section>

        <div class="profile-panel-row">
          <section class="profile-panel">
            <div class="profile-panel__heading">
              <span class="profile-panel__icon"><i class="fa fa-users"></i></span>
              <div><h2>Kelompok Akses</h2><p>Peran yang terhubung dengan akun ini.</p></div>
            </div>
            <div class="profile-group-list">
              <?php if (!empty($groups)): ?>
                <?php foreach ($groups as $group): ?>
                  <span><i class="fa fa-check-circle"></i> <?= _ent($group->name); ?></span>
                <?php endforeach; ?>
              <?php else: ?>
                <p class="profile-empty">Belum memiliki kelompok akses.</p>
              <?php endif; ?>
            </div>
          </section>

          <section class="profile-panel">
            <div class="profile-panel__heading">
              <span class="profile-panel__icon"><i class="fa fa-shield"></i></span>
              <div><h2>Aktivitas Akun</h2><p>Riwayat akses terakhir pada sistem.</p></div>
            </div>
            <dl class="profile-activity-list">
              <div><dt><i class="fa fa-sign-in"></i> Login terakhir</dt><dd><?= _ent($display_date($user->last_login)); ?></dd></div>
              <div><dt><i class="fa fa-history"></i> Aktivitas terakhir</dt><dd><?= _ent($display_date($user->last_activity)); ?></dd></div>
              <div><dt><i class="fa fa-calendar"></i> Akun dibuat</dt><dd><?= _ent($display_date($user->date_created)); ?></dd></div>
              <div><dt><i class="fa fa-globe"></i> Alamat IP</dt><dd><?= !empty($user->ip_address) ? _ent($user->ip_address) : '-'; ?></dd></div>
            </dl>
          </section>
        </div>
      </div>
    </div>
  </div>
</section>
