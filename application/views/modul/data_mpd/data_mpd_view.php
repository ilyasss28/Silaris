<?php
$avatar = !empty($profile->avatar) ? basename($profile->avatar) : 'default.png';
$avatar_url = is_file(FCPATH . 'uploads/user/' . $avatar) ? base_url('uploads/user/' . $avatar) : base_url('uploads/user/default.png');
$start_date = format_date_id($profile->tanggal_mulai);
$end_date = format_date_id($profile->tanggal_selesai);
?>
<section class="content record-detail-page mpd-registry-page mpd-registry-detail">
  <div class="record-detail-shell">
    <header class="record-detail-header">
      <div class="record-detail-header__copy">
        <span class="record-detail-icon"><i class="fa fa-users" aria-hidden="true"></i></span>
        <div><span class="record-detail-eyebrow">DATA INDUK MPD</span><h1>Detail Data MPD</h1><p>Identitas, akun, dasar penugasan, dan cakupan wilayah pengawasan.</p></div>
      </div>
      <div class="record-detail-header__aside">
        <span class="record-detail-id"><small>ID MPD</small><strong>#<?= (int) $profile->id_mpd; ?></strong></span>
        <div class="record-detail-actions" role="group" aria-label="Tindakan detail Data MPD">
          <a class="btn admin-button admin-button--neutral record-detail-btn" id="btn_back" href="<?= site_url('data_mpd'); ?>"><i class="fa fa-arrow-left" aria-hidden="true"></i> Kembali</a>
          <?php is_allowed('data_mpd_update', function () use ($profile) { ?><a class="btn admin-button admin-button--edit record-detail-btn" id="btn_edit" href="<?= site_url('data_mpd/edit/' . $profile->id_mpd); ?>"><i class="fa fa-pencil" aria-hidden="true"></i> Edit Data</a><?php }); ?>
        </div>
      </div>
    </header>
    <div class="record-detail-content">
      <div class="record-detail-grid">
        <section class="record-detail-card">
          <div class="record-detail-card__heading"><span><i class="fa fa-id-card-o" aria-hidden="true"></i></span><div><h2>Identitas MPD</h2><p>Profil dan kedudukan anggota Majelis Pengawas Daerah.</p></div></div>
          <div class="mpd-detail-profile">
            <img src="<?= _ent($avatar_url); ?>" alt="Foto <?= _ent($profile->nama_mpd); ?>">
            <div><h3><?= _ent(format_person_name($profile->nama_mpd)); ?></h3><p><?= _ent($profile->jabatan ?: 'Anggota MPD'); ?></p><span class="mpd-badge <?= $profile->is_verified ? 'mpd-badge--verified' : 'mpd-badge--pending'; ?>"><i class="fa <?= $profile->is_verified ? 'fa-check-circle' : 'fa-clock-o'; ?>" aria-hidden="true"></i><?= $profile->is_verified ? 'Terverifikasi' : 'Belum diverifikasi'; ?></span></div>
          </div>
        </section>
        <section class="record-detail-card">
          <div class="record-detail-card__heading"><span><i class="fa fa-user-circle-o" aria-hidden="true"></i></span><div><h2>Informasi Akun</h2><p>Akun SILARIS dan informasi kontak utama.</p></div></div>
          <dl class="record-detail-list">
            <div><dt>Username</dt><dd><span class="record-detail-username"><i class="fa fa-at" aria-hidden="true"></i><?= _ent($profile->username ?: '-'); ?></span></dd></div>
            <div><dt>Status Akun</dt><dd><span class="mpd-badge <?= $profile->banned ? 'mpd-badge--inactive' : 'mpd-badge--active'; ?>"><i class="fa fa-circle" aria-hidden="true"></i><?= $profile->banned ? 'Nonaktif' : 'Aktif'; ?></span></dd></div>
            <div><dt>Email</dt><dd><?= _ent($profile->email ?: '-'); ?></dd></div>
            <div><dt>Nomor Telepon</dt><dd><?= _ent(format_phone_number($profile->no_telepon) ?: '-'); ?></dd></div>
          </dl>
        </section>
        <section class="record-detail-card record-detail-card--wide">
          <div class="record-detail-card__heading"><span><i class="fa fa-map-marker" aria-hidden="true"></i></span><div><h2>Penugasan dan Wilayah</h2><p>Dasar penugasan, masa jabatan, dan kabupaten/kota yang diawasi.</p></div></div>
          <dl class="record-detail-list mpd-assignment-list">
            <div><dt>Nomor Surat Keputusan</dt><dd><?= _ent($profile->nomor_sk ?: '-'); ?></dd></div>
            <div><dt>Masa Jabatan</dt><dd><span class="table-date"><?= _ent($start_date ?: '-'); ?> sampai <?= _ent($end_date ?: '-'); ?></span></dd></div>
            <div class="mpd-detail-list-wide"><dt>Wilayah Pengawasan</dt><dd><?= _ent($profile->wilayah_nama ?: 'Belum ditentukan'); ?><small class="mpd-detail-code">Kode wilayah: <?= _ent($profile->wilayah_kode ?: '-'); ?></small></dd></div>
            <div class="mpd-detail-list-wide"><dt>Alamat</dt><dd><?= nl2br(_ent($profile->alamat ?: '-')); ?></dd></div>
          </dl>
        </section>
      </div>
    </div>
  </div>
</section>
<script>
$(function () {
  $(document).off('keydown.dataMpdDetail').on('keydown.dataMpdDetail', function (event) {
    if (!event.ctrlKey) return;
    var key = String(event.key).toLowerCase();
    if (key === 'e' && $('#btn_edit').length) { event.preventDefault(); document.getElementById('btn_edit').click(); }
    if (key === 'x') { event.preventDefault(); document.getElementById('btn_back').click(); }
  });
});
</script>
