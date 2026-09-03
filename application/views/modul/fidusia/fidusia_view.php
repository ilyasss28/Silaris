<?php
$display_value = static function ($value) {
    $value = trim((string) $value);
    return $value !== '' ? $value : '—';
};

$format_date = static function ($value) use ($display_value) {
    return $display_value(format_date_id($value));
};

$notary_name = format_person_name($display_value($fidusia->nama_notaris));
$grantor_name = format_person_name($display_value($fidusia->nama_pemberi_fidusia));
$recipient_name = format_person_name($display_value($fidusia->nama_penerima_fidusia));
?>

<section class="content record-detail-page fidusia-detail-page">
  <div class="record-detail-shell">
    <header class="record-detail-header">
      <div class="record-detail-header__copy">
        <span class="record-detail-icon"><i class="fa fa-file-text-o" aria-hidden="true"></i></span>
        <div>
          <span class="record-detail-eyebrow">REKAP FIDUSIA</span>
          <h1>Detail Fidusia</h1>
          <p>Informasi akta, sertifikat, dan para pihak dalam satu tampilan.</p>
        </div>
      </div>

      <div class="record-detail-header__aside">
        <span class="record-detail-id"><small>ID FIDUSIA</small><strong>#<?= _ent($fidusia->id_fidusia); ?></strong></span>
        <div class="record-detail-actions" role="group" aria-label="Tindakan detail Fidusia">
          <a class="btn admin-button admin-button--neutral record-detail-btn" id="btn_back" title="Kembali ke daftar (Ctrl+X)" href="<?= site_url('fidusia/'); ?>"><i class="fa fa-arrow-left" aria-hidden="true"></i> Kembali</a>
          <?php is_allowed('fidusia_update', function() use ($fidusia){ ?>
            <a class="btn btn_edit admin-button admin-button--edit record-detail-btn" id="btn_edit" title="Edit Fidusia (Ctrl+E)" href="<?= site_url('fidusia/edit/'.$fidusia->id_fidusia); ?>"><i class="fa fa-pencil" aria-hidden="true"></i> Edit Data</a>
          <?php }); ?>
        </div>
      </div>
    </header>

    <div class="form-horizontal record-detail-content" name="form_fidusia" id="form_fidusia">
      <div class="record-detail-grid">
        <section class="record-detail-card">
          <div class="record-detail-card__heading">
            <span><i class="fa fa-user" aria-hidden="true"></i></span>
            <div><h2>Informasi Pencatatan</h2><p>Identitas pemilik dan waktu pencatatan data.</p></div>
          </div>
          <dl class="record-detail-list">
            <div><dt>Nama Notaris</dt><dd><?= _ent($notary_name); ?></dd></div>
            <div><dt>Username</dt><dd><span class="record-detail-username"><i class="fa fa-at" aria-hidden="true"></i><?= _ent($display_value($fidusia->username)); ?></span></dd></div>
            <div><dt>Tanggal Pencatatan</dt><dd><time datetime="<?= _ent($fidusia->tanggal); ?>"><?= _ent($format_date($fidusia->tanggal)); ?></time></dd></div>
          </dl>
        </section>

        <section class="record-detail-card">
          <div class="record-detail-card__heading">
            <span><i class="fa fa-certificate" aria-hidden="true"></i></span>
            <div><h2>Dokumen Fidusia</h2><p>Referensi akta dan sertifikat jaminan Fidusia.</p></div>
          </div>
          <dl class="record-detail-list">
            <div><dt>Nomor Akta</dt><dd><span class="record-detail-number"><?= _ent($display_value($fidusia->nomor_akta)); ?></span></dd></div>
            <div><dt>Tanggal Akta</dt><dd><time datetime="<?= _ent($fidusia->tanggal_akta); ?>"><?= _ent($format_date($fidusia->tanggal_akta)); ?></time></dd></div>
            <div><dt>Nomor Sertifikat</dt><dd><?= _ent($display_value($fidusia->no_sertifikat_jaminan_fidusia)); ?></dd></div>
          </dl>
        </section>

        <section class="record-detail-card record-detail-card--wide">
          <div class="record-detail-card__heading">
            <span><i class="fa fa-exchange" aria-hidden="true"></i></span>
            <div><h2>Para Pihak</h2><p>Pihak pemberi dan penerima jaminan Fidusia.</p></div>
          </div>
          <div class="fidusia-parties">
            <article class="fidusia-party">
              <span class="fidusia-party__icon"><i class="fa fa-user" aria-hidden="true"></i></span>
              <div><small>PEMBERI FIDUSIA</small><strong><?= _ent($grantor_name); ?></strong></div>
            </article>
            <span class="fidusia-parties__connector" aria-hidden="true"><i class="fa fa-long-arrow-right"></i></span>
            <article class="fidusia-party">
              <span class="fidusia-party__icon"><i class="fa fa-building-o" aria-hidden="true"></i></span>
              <div><small>PENERIMA FIDUSIA</small><strong><?= _ent($recipient_name); ?></strong></div>
            </article>
          </div>
        </section>
      </div>
    </div>
  </div>
</section>

<script>
$(function () {
  $(document).off('keydown.fidusiaDetail').on('keydown.fidusiaDetail', function (event) {
    if (!event.ctrlKey) return;

    var key = String(event.key).toLowerCase();
    if (key === 'e' && $('#btn_edit').length) {
      event.preventDefault();
      document.getElementById('btn_edit').click();
    }
    if (key === 'x') {
      event.preventDefault();
      document.getElementById('btn_back').click();
    }
  });
});
</script>
