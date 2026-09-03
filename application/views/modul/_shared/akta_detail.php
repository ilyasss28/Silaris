<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$module = $akta_page['module'];
$title = $akta_page['title'];
$record = $akta_page['record'];
$primary_key = $akta_page['primary_key'];
$permission = $module.'_update';
$display_value = static function ($value) {
    $value = trim((string) $value);
    return $value !== '' ? $value : '—';
};
$format_date = static function ($value) use ($display_value) {
    return $display_value(format_date_id($value));
};
$record_id = $record->{$primary_key};
?>

<section class="content record-detail-page service-record-detail-page">
  <div class="record-detail-shell">
    <header class="record-detail-header">
      <div class="record-detail-header__copy">
        <span class="record-detail-icon"><i class="fa fa-file-text-o" aria-hidden="true"></i></span>
        <div><span class="record-detail-eyebrow"><?= _ent(strtoupper('REKAP '.$title)); ?></span><h1>Detail <?= _ent($title); ?></h1><p>Informasi pencatatan akta dalam satu tampilan.</p></div>
      </div>
      <div class="record-detail-header__aside">
        <span class="record-detail-id"><small>ID <?= _ent(strtoupper($title)); ?></small><strong>#<?= _ent($record_id); ?></strong></span>
        <div class="record-detail-actions">
          <a class="btn admin-button admin-button--neutral record-detail-btn" id="btn_back" href="<?= site_url($module); ?>"><i class="fa fa-arrow-left" aria-hidden="true"></i> Kembali</a>
          <?php is_allowed($permission, function() use ($module, $title, $record_id){ ?>
            <a class="btn admin-button admin-button--edit record-detail-btn" id="btn_edit" href="<?= site_url($module.'/edit/'.$record_id); ?>"><i class="fa fa-pencil" aria-hidden="true"></i> Edit <?= _ent($title); ?></a>
          <?php }); ?>
        </div>
      </div>
    </header>

    <div class="record-detail-content">
      <div class="record-detail-grid">
        <section class="record-detail-card">
          <div class="record-detail-card__heading"><span><i class="fa fa-user" aria-hidden="true"></i></span><div><h2>Informasi Pencatatan</h2><p>Identitas Notaris pemilik data.</p></div></div>
          <dl class="record-detail-list">
            <div><dt>Nama Notaris</dt><dd><?= _ent(format_person_name($display_value($record->nama_notaris))); ?></dd></div>
            <div><dt>Username</dt><dd><span class="record-detail-username"><i class="fa fa-at" aria-hidden="true"></i><?= _ent($display_value($record->username)); ?></span></dd></div>
          </dl>
        </section>
        <section class="record-detail-card">
          <div class="record-detail-card__heading"><span><i class="fa fa-file-text-o" aria-hidden="true"></i></span><div><h2>Informasi Akta</h2><p>Nomor dan tanggal akta.</p></div></div>
          <dl class="record-detail-list">
            <div><dt>Nomor Akta</dt><dd><span class="record-detail-number"><?= _ent($display_value($record->nomor_akta)); ?></span></dd></div>
            <div><dt>Tanggal Akta</dt><dd><time datetime="<?= _ent($record->tanggal_akta); ?>"><?= _ent($format_date($record->tanggal_akta)); ?></time></dd></div>
          </dl>
        </section>
        <section class="record-detail-card record-detail-card--wide">
          <div class="record-detail-card__heading"><span><i class="fa fa-users" aria-hidden="true"></i></span><div><h2>Keterangan Akta</h2><p>Sifat akta dan pihak yang menghadap.</p></div></div>
          <dl class="record-detail-list">
            <div><dt>Sifat Akta</dt><dd><?= _ent($display_value($record->sifat_akta)); ?></dd></div>
            <div><dt>Penghadap</dt><dd><?= _ent($display_value($record->penghadap)); ?></dd></div>
          </dl>
        </section>
      </div>
    </div>
  </div>
</section>

<script>
$(function () {
  $(document).off('keydown.serviceRecordDetail').on('keydown.serviceRecordDetail', function (event) {
    if (!event.ctrlKey) return;
    var key = String(event.key).toLowerCase();
    if (key === 'e' && $('#btn_edit').length) { event.preventDefault(); document.getElementById('btn_edit').click(); }
    if (key === 'x') { event.preventDefault(); document.getElementById('btn_back').click(); }
  });
});
</script>
