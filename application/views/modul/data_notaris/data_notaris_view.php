<?php
$display = static function ($value) {
    $value = trim((string) $value);
    return $value !== '' ? _ent($value) : '-';
};
$display_date = static function ($value) {
    return !empty($value) && $value !== '0000-00-00' ? _ent(format_date_id($value)) : '-';
};
$notary_photo = $data_notaris->photo_url ?? notary_photo_url($data_notaris->account_avatar ?? '', $data_notaris->foto ?? '');
$region_name = format_title_case($data_notaris->region_name ?? $data_notaris->wilayah ?? '');
$region_code = $data_notaris->kode_wilayah ?? $data_notaris->region_code ?? '';
?>

<section class="content record-detail-page data-notaris-detail-page">
  <div class="record-detail-shell">
    <header class="record-detail-header">
      <div class="record-detail-header__copy">
        <span class="record-detail-icon"><i class="fa fa-user" aria-hidden="true"></i></span>
        <div><span class="record-detail-eyebrow">MASTER DATA NOTARIS</span><h1>Detail Data Notaris</h1><p>Informasi identitas, kedudukan, dokumen, dan status Notaris.</p></div>
      </div>
      <div class="record-detail-header__aside">
        <span class="record-detail-id"><small>ID NOTARIS</small><strong>#<?= (int) $data_notaris->id_notaris; ?></strong></span>
        <div class="record-detail-actions" role="group" aria-label="Tindakan Data Notaris">
          <a class="btn admin-button admin-button--neutral" id="btn_back" href="<?= site_url('data_notaris'); ?>"><i class="fa fa-arrow-left"></i> Kembali</a>
          <?php is_allowed('data_notaris_update', function () use ($data_notaris) { ?>
            <a class="btn admin-button admin-button--edit" id="btn_edit" href="<?= site_url('data_notaris/edit/' . $data_notaris->id_notaris); ?>"><i class="fa fa-pencil"></i> Edit Data</a>
          <?php }); ?>
        </div>
      </div>
    </header>

    <div class="record-detail-content">
      <div class="record-detail-grid">
        <section class="record-detail-card">
          <div class="record-detail-card__heading"><span><i class="fa fa-user"></i></span><div><h2>Identitas Pribadi</h2><p>Identitas resmi dan data kelahiran Notaris.</p></div></div>
          <div class="notary-detail-identity">
            <img src="<?= _ent($notary_photo); ?>" alt="Foto <?= _ent($data_notaris->nama_notaris); ?>">
            <div><small>NAMA NOTARIS</small><strong><?= $display(format_person_name($data_notaris->nama_notaris)); ?></strong></div>
          </div>
          <dl class="record-detail-list">
            <div><dt>Tempat Lahir</dt><dd><?= $display($data_notaris->tempat_lahir); ?></dd></div>
            <div><dt>Tanggal Lahir</dt><dd><?= $display_date($data_notaris->tanggal_lahir); ?></dd></div>
            <div><dt>Jenis Kelamin</dt><dd><?= $display($data_notaris->jenis_kelamin); ?></dd></div>
            <div><dt>NIK</dt><dd><?= $display($data_notaris->nomor_ktp); ?></dd></div>
            <div><dt>NPWP</dt><dd><?= $display($data_notaris->npwp); ?></dd></div>
          </dl>
        </section>

        <section class="record-detail-card">
          <div class="record-detail-card__heading"><span><i class="fa fa-map-marker"></i></span><div><h2>Kontak dan Kedudukan</h2><p>Kontak, wilayah kerja, dan alamat Notaris.</p></div></div>
          <dl class="record-detail-list">
            <div><dt>Email</dt><dd><?= $display($data_notaris->email); ?></dd></div>
            <div><dt>Nomor Telepon</dt><dd><?= $display(format_phone_number($data_notaris->no_telepon)); ?></dd></div>
            <div><dt>Kode Wilayah</dt><dd><?= $display($region_code); ?></dd></div>
            <div><dt>Wilayah Kerja</dt><dd><?= $display($region_name); ?></dd></div>
            <div><dt>Alamat Rumah</dt><dd><?= $display($data_notaris->alamat_rumah); ?></dd></div>
            <div><dt>Alamat Kantor</dt><dd><?= $display($data_notaris->alamat_kantor); ?></dd></div>
            <div><dt>Koordinat Kantor</dt><dd><?= $display(trim((string) $data_notaris->lat) !== '' || trim((string) $data_notaris->long) !== '' ? $data_notaris->lat . ', ' . $data_notaris->long : ''); ?></dd></div>
          </dl>
        </section>

        <section class="record-detail-card record-detail-card--wide">
          <div class="record-detail-card__heading"><span><i class="fa fa-file-text-o"></i></span><div><h2>Dokumen dan Status</h2><p>Dasar pengangkatan, berita acara, serta status jabatan.</p></div></div>
          <dl class="record-detail-list notary-detail-documents">
            <div><dt>Surat Keputusan</dt><dd><?= $display($data_notaris->surat_keputusan); ?></dd></div>
            <div><dt>Surat Pindah</dt><dd><?= $display($data_notaris->surat_pindah); ?></dd></div>
            <div><dt>Nomor BAP</dt><dd><?= $display($data_notaris->nomor_bap); ?></dd></div>
            <div><dt>Tanggal BAP</dt><dd><?= $display_date($data_notaris->tanggal_bap); ?></dd></div>
            <div><dt>Status Notaris</dt><dd><?= $display($data_notaris->status_notaris); ?></dd></div>
            <div><dt>Pemegang Protokol</dt><dd><?= $display($data_notaris->pemegang_protokol); ?></dd></div>
          </dl>
        </section>
      </div>
    </div>
  </div>
</section>

<script>
$(function () {
  $(document).off('keydown.dataNotarisDetail').on('keydown.dataNotarisDetail', function (event) {
    if (!event.ctrlKey) return;
    var key = String(event.key).toLowerCase();
    if (key === 'e' && document.getElementById('btn_edit')) { event.preventDefault(); document.getElementById('btn_edit').click(); }
    if (key === 'x') { event.preventDefault(); document.getElementById('btn_back').click(); }
  });
});
</script>
