<section class="content record-detail-page region-detail-page">
  <div class="record-detail-shell">
    <header class="record-detail-header">
      <div class="record-detail-header__copy"><span class="record-detail-icon"><i class="fa fa-map-marker"></i></span><div><span class="record-detail-eyebrow">MASTER WILAYAH</span><h1>Detail Setup Wilayah</h1><p>Informasi kode dan nama wilayah yang digunakan oleh seluruh modul SILARIS.</p></div></div>
      <div class="record-detail-header__aside">
        <span class="record-detail-id"><small>ID WILAYAH</small><strong>#<?= (int) $wil->id; ?></strong></span>
        <div class="record-detail-actions" role="group" aria-label="Tindakan wilayah">
          <a class="btn admin-button admin-button--neutral" id="btn_back" href="<?= site_url('wil'); ?>"><i class="fa fa-arrow-left"></i> Kembali</a>
          <?php is_allowed('wil_update', function () use ($wil) { ?>
            <a class="btn admin-button admin-button--edit" id="btn_edit" href="<?= site_url('wil/edit/' . $wil->id); ?>"><i class="fa fa-pencil"></i> Edit Data</a>
          <?php }); ?>
        </div>
      </div>
    </header>
    <div class="record-detail-content">
      <div class="record-detail-grid">
        <section class="record-detail-card record-detail-card--wide">
          <div class="record-detail-card__heading"><span><i class="fa fa-map-marker"></i></span><div><h2>Identitas Wilayah</h2><p>Kode referensi dan nama resmi kabupaten/kota.</p></div></div>
          <dl class="record-detail-list region-detail-list">
            <div><dt>Kode Wilayah</dt><dd><span class="record-detail-number"><?= _ent($wil->kd_wilayah); ?></span></dd></div>
            <div><dt>Nama Wilayah</dt><dd><?= _ent(format_title_case($wil->nama_wilayah)); ?></dd></div>
          </dl>
        </section>
      </div>
    </div>
  </div>
</section>

<script>
$(function () {
  $(document).off('keydown.regionDetail').on('keydown.regionDetail', function (event) {
    if (!event.ctrlKey) return;
    var key = String(event.key).toLowerCase();
    if (key === 'e' && document.getElementById('btn_edit')) { event.preventDefault(); document.getElementById('btn_edit').click(); }
    if (key === 'x') { event.preventDefault(); document.getElementById('btn_back').click(); }
  });
});
</script>
