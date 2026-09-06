<?php
$is_own_profile = isset($profile_mode) && $profile_mode === 'account';
$profile_title = $is_own_profile ? 'Profil Saya' : 'Detail Profil Notaris';
$profile_description = $is_own_profile
  ? 'Kelola identitas, wilayah, dan keamanan akun yang sedang digunakan.'
  : 'Informasi akun notaris yang dipilih dari halaman manajemen pengguna.';
$region_name = trim((string) (isset($region_name) ? $region_name : ''));
$region_display = $region_name !== '' ? $region_name : '-';
$mpd_region_names = isset($mpd_region_names) && is_array($mpd_region_names) ? $mpd_region_names : array();
$avatar_name = !empty($user->avatar) ? basename($user->avatar) : 'default.png';
$avatar_path = FCPATH . 'uploads/user/' . $avatar_name;
$avatar_url = BASE_URL . 'uploads/user/' . (is_file($avatar_path) ? $avatar_name : 'default.png');
$phone_display = format_phone_number(isset($user->phone_number) ? $user->phone_number : '');
$display_date = function ($value) {
  if (empty($value) || $value === '0000-00-00 00:00:00') {
    return '-';
  }

  $timestamp = strtotime($value);
  return $timestamp ? format_date_id($value) . ', ' . date('H:i', $timestamp) : $value;
};
$notary_profile = isset($notary_profile) ? $notary_profile : false;
$is_notary_profile = !empty($is_notary_profile);
$is_mpd_profile = !empty($is_mpd_profile);
$mpd_profile = isset($mpd_profile) ? $mpd_profile : false;
$notary_completeness = isset($notary_completeness) ? $notary_completeness : null;
$display_value = function ($value) { return trim((string) $value) !== '' ? _ent($value) : '-'; };
$display_registry_date = function ($value) {
  return !empty($value) && $value !== '0000-00-00' ? _ent(format_date_id($value)) : '-';
};
?>

<section class="content profile-page <?= $is_own_profile ? 'profile-page--account' : 'profile-page--notary'; ?>">
  <div class="profile-shell">
    <header class="profile-page-header">
      <div class="profile-page-header__copy">
        <span class="profile-page-header__icon"><i class="fa <?= $is_own_profile ? 'fa-user' : 'fa-user-secret'; ?>"></i></span>
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
          <?php if ($phone_display !== ''): ?>
            <span><i class="fa fa-phone"></i> <?= _ent($phone_display); ?></span>
          <?php endif; ?>
          <?php if ($region_name !== ''): ?>
            <span><i class="fa fa-map-marker"></i> <?= _ent($region_name); ?></span>
          <?php endif; ?>
        </div>

        <div class="profile-identity-groups">
          <div class="profile-identity-groups__title"><i class="fa fa-users"></i><span>Kelompok Akses</span></div>
          <div class="profile-group-list">
            <?php if (!empty($groups)): ?>
              <?php foreach ($groups as $group): ?>
                <span><i class="fa fa-check-circle"></i> <?= _ent($group->name); ?></span>
              <?php endforeach; ?>
            <?php else: ?>
              <p class="profile-empty">Belum memiliki kelompok akses.</p>
            <?php endif; ?>
          </div>
        </div>
      </aside>

      <div class="profile-content">
        <?php if ($is_notary_profile && $notary_completeness): ?>
        <section class="profile-completion <?= $notary_completeness['complete'] ? 'is-complete' : 'is-incomplete'; ?>">
          <div class="profile-completion__summary">
            <span class="profile-completion__score"><?= (int) $notary_completeness['percent']; ?>%</span>
            <div><h2>Kelengkapan Data Notaris</h2><p><?= $notary_completeness['complete'] ? 'Profil lengkap. Anda dapat menambahkan laporan.' : 'Lengkapi data wajib sebelum menambahkan laporan.'; ?></p></div>
          </div>
          <div class="profile-completion__track"><span style="width: <?= (int) $notary_completeness['percent']; ?>%"></span></div>
          <?php if (!$notary_completeness['complete']): ?>
            <p class="profile-completion__missing"><strong>Belum lengkap:</strong> <?= _ent(implode(', ', $notary_completeness['missing'])); ?></p>
          <?php endif; ?>
        </section>
        <?php endif; ?>

        <?php if ($is_notary_profile): ?>
        <nav class="profile-section-nav" aria-label="Navigasi data profil" role="tablist">
          <button type="button" class="profile-section-nav__item is-active" role="tab" aria-selected="true" data-profile-tab="summary"><i class="fa fa-th-large"></i><span>Ringkasan</span></button>
          <button type="button" class="profile-section-nav__item" role="tab" aria-selected="false" data-profile-tab="identity" <?= !$notary_profile ? 'disabled aria-disabled="true"' : ''; ?>><i class="fa fa-user"></i><span>Identitas</span></button>
          <button type="button" class="profile-section-nav__item" role="tab" aria-selected="false" data-profile-tab="contact" <?= !$notary_profile ? 'disabled aria-disabled="true"' : ''; ?>><i class="fa fa-map-marker"></i><span>Kontak &amp; Kedudukan</span></button>
          <button type="button" class="profile-section-nav__item" role="tab" aria-selected="false" data-profile-tab="documents" <?= !$notary_profile ? 'disabled aria-disabled="true"' : ''; ?>><i class="fa fa-file-text-o"></i><span>Dokumen</span></button>
          <button type="button" class="profile-section-nav__item" role="tab" aria-selected="false" data-profile-tab="status" <?= !$notary_profile ? 'disabled aria-disabled="true"' : ''; ?>><i class="fa fa-briefcase"></i><span>Status</span></button>
        </nav>
        <?php endif; ?>

        <section class="profile-panel" <?= $is_notary_profile ? 'data-profile-panel="summary"' : ''; ?>>
          <div class="profile-panel__heading">
            <span class="profile-panel__icon"><i class="fa fa-info-circle"></i></span>
            <div><h2>Informasi Akun</h2><p>Identitas utama yang digunakan pada sistem.</p></div>
          </div>
          <dl class="profile-detail-grid">
            <div><dt>Nama Lengkap</dt><dd><?= _ent(format_person_name($user->full_name)); ?></dd></div>
            <div><dt>Username</dt><dd><?= _ent($user->username); ?></dd></div>
            <div><dt>Email</dt><dd><?= _ent($user->email); ?></dd></div>
            <div><dt>Nomor Telepon</dt><dd><?= $phone_display !== '' ? _ent($phone_display) : '-'; ?></dd></div>
            <div class="profile-detail-grid__full"><dt>Wilayah Kerja</dt><dd><?= _ent(!empty($mpd_region_names) ? implode(', ', $mpd_region_names) : $region_display); ?></dd></div>
          </dl>
        </section>

        <?php if ($is_notary_profile): ?>
          <?php if ($notary_profile): ?>
          <section class="profile-panel" data-profile-panel="identity" hidden>
            <div class="profile-panel__heading"><span class="profile-panel__icon"><i class="fa fa-user"></i></span><div><h2>Identitas Pribadi</h2><p>Data kependudukan dan identitas resmi Notaris.</p></div></div>
            <dl class="profile-detail-grid">
              <div><dt>Nama Notaris</dt><dd><?= $display_value(format_person_name($notary_profile->nama_notaris)); ?></dd></div>
              <div><dt>Jenis Kelamin</dt><dd><?= $display_value($notary_profile->jenis_kelamin); ?></dd></div>
              <div><dt>Tempat Lahir</dt><dd><?= $display_value($notary_profile->tempat_lahir); ?></dd></div>
              <div><dt>Tanggal Lahir</dt><dd><?= $display_registry_date($notary_profile->tanggal_lahir); ?></dd></div>
              <div><dt>NIK</dt><dd><?= $display_value($notary_profile->nomor_ktp); ?></dd></div>
              <div><dt>NPWP</dt><dd><?= $display_value($notary_profile->npwp); ?></dd></div>
            </dl>
          </section>

          <section class="profile-panel" data-profile-panel="contact" hidden>
            <div class="profile-panel__heading"><span class="profile-panel__icon"><i class="fa fa-map-marker"></i></span><div><h2>Kontak dan Kedudukan</h2><p>Alamat, wilayah kerja, dan titik lokasi kantor.</p></div></div>
            <dl class="profile-detail-grid">
              <div><dt>Email</dt><dd><?= $display_value($notary_profile->email); ?></dd></div>
              <div><dt>Nomor Telepon</dt><dd><?= $display_value(format_phone_number($notary_profile->no_telepon)); ?></dd></div>
              <div><dt>Kode Wilayah</dt><dd><?= $display_value($notary_profile->kode_wilayah); ?></dd></div>
              <div><dt>Wilayah Kerja</dt><dd><?= $display_value($notary_profile->wilayah); ?></dd></div>
              <div class="profile-detail-grid__full"><dt>Alamat Rumah</dt><dd><?= $display_value($notary_profile->alamat_rumah); ?></dd></div>
              <div class="profile-detail-grid__full"><dt>Alamat Kantor</dt><dd><?= $display_value($notary_profile->alamat_kantor); ?></dd></div>
              <div><dt>Latitude Kantor</dt><dd><?= $display_value($notary_profile->lat); ?></dd></div>
              <div><dt>Longitude Kantor</dt><dd><?= $display_value($notary_profile->long); ?></dd></div>
            </dl>
          </section>

          <section class="profile-panel" data-profile-panel="documents" hidden>
            <div class="profile-panel__heading"><span class="profile-panel__icon"><i class="fa fa-file-text-o"></i></span><div><h2>Pengangkatan dan Berita Acara</h2><p>Dokumen dasar pengangkatan dan pelaksanaan jabatan.</p></div></div>
            <dl class="profile-detail-grid">
              <div><dt>Surat Keputusan</dt><dd><?= $display_value($notary_profile->surat_keputusan); ?></dd></div>
              <div><dt>Surat Pindah (Opsional)</dt><dd><?= $display_value($notary_profile->surat_pindah); ?></dd></div>
              <div><dt>Nomor BAP</dt><dd><?= $display_value($notary_profile->nomor_bap); ?></dd></div>
              <div><dt>Tanggal BAP</dt><dd><?= $display_registry_date($notary_profile->tanggal_bap); ?></dd></div>
            </dl>
          </section>

          <section class="profile-panel" data-profile-panel="status" hidden>
            <div class="profile-panel__heading"><span class="profile-panel__icon"><i class="fa fa-briefcase"></i></span><div><h2>Status</h2><p>Status jabatan serta informasi pemegang protokol.</p></div></div>
            <dl class="profile-detail-grid">
              <div><dt>ID Data Notaris</dt><dd>#<?= (int) $notary_profile->id_notaris; ?></dd></div>
              <div><dt>Status Notaris</dt><dd><?= $display_value($notary_profile->status_notaris); ?></dd></div>
              <div class="profile-detail-grid__full"><dt>Pemegang Protokol (Opsional)</dt><dd><?= $display_value($notary_profile->pemegang_protokol); ?></dd></div>
            </dl>
          </section>
          <?php else: ?>
          <section class="profile-registry-warning" data-profile-panel="summary"><i class="fa fa-exclamation-triangle"></i><div><strong>Data Notaris belum terhubung</strong><p>Hubungi administrator agar akun ini dihubungkan dengan Data Notaris.</p></div></section>
          <?php endif; ?>
        <?php endif; ?>

        <?php if ($is_mpd_profile): ?>
        <section class="profile-panel">
          <div class="profile-panel__heading">
            <span class="profile-panel__icon"><i class="fa fa-users"></i></span>
            <div><h2>Informasi MPD</h2><p>Data penugasan resmi dan wilayah pengawasan akun.</p></div>
          </div>
          <?php if ($mpd_profile): ?>
          <dl class="profile-detail-grid">
            <div><dt>Nama MPD</dt><dd><?= $display_value(format_person_name($mpd_profile->nama_mpd)); ?></dd></div>
            <div><dt>Jabatan</dt><dd><?= $display_value($mpd_profile->jabatan); ?></dd></div>
            <div><dt>Nomor SK</dt><dd><?= $display_value($mpd_profile->nomor_sk); ?></dd></div>
            <div><dt>Status Verifikasi</dt><dd><?= !empty($mpd_profile->is_verified) ? 'Terverifikasi' : 'Belum Diverifikasi'; ?></dd></div>
            <div><dt>Mulai Masa Jabatan</dt><dd><?= $display_registry_date($mpd_profile->tanggal_mulai); ?></dd></div>
            <div><dt>Selesai Masa Jabatan</dt><dd><?= $display_registry_date($mpd_profile->tanggal_selesai); ?></dd></div>
            <div class="profile-detail-grid__full"><dt>Wilayah Pengawasan</dt><dd><?= $display_value(!empty($mpd_region_names) ? implode(', ', $mpd_region_names) : ''); ?></dd></div>
            <div class="profile-detail-grid__full"><dt>Alamat</dt><dd><?= $display_value($mpd_profile->alamat); ?></dd></div>
          </dl>
          <?php else: ?>
          <div class="profile-registry-warning"><i class="fa fa-exclamation-triangle"></i><div><strong>Data MPD belum terhubung</strong><p>Hubungi administrator untuk menghubungkan akun dengan registri Data MPD.</p></div></div>
          <?php endif; ?>
        </section>
        <?php endif; ?>

        <section class="profile-panel profile-panel--activity" <?= $is_notary_profile ? 'data-profile-panel="summary"' : ''; ?>>
          <div class="profile-panel__heading">
            <span class="profile-panel__icon"><i class="fa fa-shield"></i></span>
            <div><h2>Aktivitas Akun</h2><p>Riwayat akses dan informasi keamanan akun.</p></div>
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
</section>
<?php if ($is_notary_profile): ?>
<script>
(function () {
  var root = document.querySelector('.profile-page');
  if (!root) return;
  var tabs = Array.prototype.slice.call(root.querySelectorAll('[data-profile-tab]:not([disabled])'));
  var panels = Array.prototype.slice.call(root.querySelectorAll('[data-profile-panel]'));
  var allowed = tabs.map(function (tab) { return tab.getAttribute('data-profile-tab'); });

  function activate(name, updateHash) {
    if (allowed.indexOf(name) === -1) name = 'summary';
    tabs.forEach(function (tab) {
      var active = tab.getAttribute('data-profile-tab') === name;
      tab.classList.toggle('is-active', active);
      tab.setAttribute('aria-selected', active ? 'true' : 'false');
      tab.setAttribute('tabindex', active ? '0' : '-1');
    });
    panels.forEach(function (panel) {
      panel.hidden = panel.getAttribute('data-profile-panel') !== name;
    });
    if (updateHash && window.history && history.replaceState) history.replaceState(null, '', '#profil-' + name);
  }

  tabs.forEach(function (tab, index) {
    tab.addEventListener('click', function () { activate(tab.getAttribute('data-profile-tab'), true); });
    tab.addEventListener('keydown', function (event) {
      if (event.key !== 'ArrowRight' && event.key !== 'ArrowLeft') return;
      event.preventDefault();
      var next = (index + (event.key === 'ArrowRight' ? 1 : -1) + tabs.length) % tabs.length;
      tabs[next].focus();
      activate(tabs[next].getAttribute('data-profile-tab'), true);
    });
  });
  activate((location.hash || '').replace('#profil-', ''), false);
})();
</script>
<?php endif; ?>
