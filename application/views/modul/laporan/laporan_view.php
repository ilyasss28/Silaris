<?php
$display_value = static function ($value) {
    $value = trim((string) $value);
    return $value !== '' ? $value : '—';
};
$format_date = static function ($value) use ($display_value) {
    return $display_value(format_date_id($value));
};
$document_name = trim((string) $laporan->Laporan);
$document_url = $document_name !== '' ? BASE_URL.'uploads/laporan/'.rawurlencode($document_name) : '';
$viewer_url = $document_url !== '' ? document_preview_url($document_url) : '';
?>

<section class="content record-detail-page service-record-detail-page report-detail-page">
  <div class="record-detail-shell">
    <header class="record-detail-header">
      <div class="record-detail-header__copy">
        <span class="record-detail-icon"><i class="fa fa-file-text-o" aria-hidden="true"></i></span>
        <div><span class="record-detail-eyebrow">LAPORAN BULANAN</span><h1>Detail Laporan Bulanan</h1><p>Informasi pelapor, periode, dan dokumen dalam satu tampilan.</p></div>
      </div>
      <div class="record-detail-header__aside">
        <span class="record-detail-id"><small>ID LAPORAN</small><strong>#<?= _ent($laporan->id); ?></strong></span>
        <div class="record-detail-actions" role="group" aria-label="Tindakan detail laporan bulanan">
          <a class="btn admin-button admin-button--neutral record-detail-btn" id="btn_back" title="Kembali ke daftar (Ctrl+X)" href="<?= site_url('laporan'); ?>"><i class="fa fa-arrow-left" aria-hidden="true"></i> Kembali</a>
          <?php is_allowed('laporan_update', function() use ($laporan){ ?>
            <a class="btn btn_edit admin-button admin-button--edit record-detail-btn" id="btn_edit" title="Edit laporan (Ctrl+E)" href="<?= site_url('laporan/edit/'.$laporan->id); ?>"><i class="fa fa-pencil" aria-hidden="true"></i> Edit Laporan</a>
          <?php }); ?>
        </div>
      </div>
    </header>

    <div class="form-horizontal record-detail-content" name="form_laporan" id="form_laporan">
      <div class="record-detail-grid">
        <section class="record-detail-card">
          <div class="record-detail-card__heading"><span><i class="fa fa-user" aria-hidden="true"></i></span><div><h2>Informasi Pelapor</h2><p>Identitas Notaris pemilik laporan.</p></div></div>
          <dl class="record-detail-list">
            <div><dt>Nama Notaris</dt><dd><?= _ent(format_person_name($display_value($laporan->nama_notaris))); ?></dd></div>
            <div><dt>Username</dt><dd><span class="record-detail-username"><i class="fa fa-at" aria-hidden="true"></i><?= _ent($display_value($laporan->username)); ?></span></dd></div>
          </dl>
        </section>
        <section class="record-detail-card">
          <div class="record-detail-card__heading"><span><i class="fa fa-calendar" aria-hidden="true"></i></span><div><h2>Periode Laporan</h2><p>Tanggal laporan yang tercatat.</p></div></div>
          <dl class="record-detail-list"><div><dt>Tanggal Laporan</dt><dd><time datetime="<?= _ent($laporan->Tanggal_Laporan); ?>"><?= _ent($format_date($laporan->Tanggal_Laporan)); ?></time></dd></div></dl>
        </section>
        <section class="record-detail-card record-detail-card--wide">
          <div class="record-detail-card__heading"><span><i class="fa fa-file-pdf-o" aria-hidden="true"></i></span><div><h2>Dokumen Laporan</h2><p>Buka dokumen di tab baru atau unduh salinannya.</p></div></div>
          <?php if ($document_name !== ''): ?>
            <div class="report-document-links">
              <a href="<?= _ent($viewer_url); ?>" target="_blank" rel="noopener noreferrer" class="report-drive-link" title="Buka dokumen di tab baru"><img src="<?= _ent(get_icon_file($document_name)); ?>" alt=""><span><strong><?= _ent($document_name); ?></strong><small>Buka di tab baru <i class="fa fa-external-link"></i></small></span></a>
              <a href="<?= _ent($document_url); ?>" download="<?= _ent($document_name); ?>" class="report-download-link"><i class="fa fa-download"></i> Unduh</a>
            </div>
          <?php else: ?>
            <p class="record-detail-empty">Dokumen belum tersedia.</p>
          <?php endif; ?>
        </section>
      </div>
    </div>
  </div>
</section>

<script>
$(function () {
  $(document).off('keydown.reportDetail').on('keydown.reportDetail', function (event) {
    if (!event.ctrlKey) return;
    var key = String(event.key).toLowerCase();
    if (key === 'e' && $('#btn_edit').length) { event.preventDefault(); document.getElementById('btn_edit').click(); }
    if (key === 'x') { event.preventDefault(); document.getElementById('btn_back').click(); }
  });
});
</script>
