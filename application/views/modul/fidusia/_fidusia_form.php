<?php
$is_edit = isset($fidusia_form_mode) && $fidusia_form_mode === 'edit';
$record = $is_edit && isset($fidusia) ? $fidusia : null;
$date_value = set_value('tanggal_akta', $record ? $record->tanggal_akta : '');
$number_value = set_value('nomor_akta', $record ? $record->nomor_akta : '');
$grantor_value = set_value('nama_pemberi_fidusia', $record ? $record->nama_pemberi_fidusia : '');
$recipient_value = set_value('nama_penerima_fidusia', $record ? $record->nama_penerima_fidusia : '');
$certificate_value = set_value('no_sertifikat_jaminan_fidusia', $record ? $record->no_sertifikat_jaminan_fidusia : '');
?>

<section class="content fidusia-form-page">
  <div class="fidusia-form-shell">
    <header class="fidusia-form-header">
      <div class="fidusia-form-header__copy">
        <span class="fidusia-form-header__icon"><i class="fa <?= $is_edit ? 'fa-pencil' : 'fa-plus'; ?>" aria-hidden="true"></i></span>
        <div>
          <span class="fidusia-form-eyebrow">DATA FIDUSIA</span>
          <h1><?= $is_edit ? 'Edit Fidusia' : 'Tambah Fidusia'; ?></h1>
          <p><?= $is_edit ? 'Perbarui informasi akta, sertifikat, dan para pihak.' : 'Lengkapi informasi akta, sertifikat, dan para pihak.'; ?></p>
        </div>
      </div>
      <span class="fidusia-form-status"><i class="fa <?= $is_edit ? 'fa-edit' : 'fa-file-o'; ?>" aria-hidden="true"></i><?= $is_edit ? 'Mode edit' : 'Data baru'; ?></span>
    </header>

    <?= form_open($fidusia_form_action, [
        'name' => 'form_fidusia',
        'class' => 'form-horizontal fidusia-form',
        'id' => 'form_fidusia',
        'method' => 'POST',
        'data-reset-after-save' => $is_edit ? 'false' : 'true',
    ]); ?>
      <div class="message fidusia-form-message" aria-live="polite"></div>

      <div class="fidusia-form-grid">
        <section class="fidusia-form-card">
          <div class="fidusia-form-card__heading">
            <span><i class="fa fa-file-text-o" aria-hidden="true"></i></span>
            <div><h2>Informasi Dokumen</h2><p>Data utama akta dan sertifikat jaminan Fidusia.</p></div>
          </div>

          <div class="fidusia-form-fields fidusia-form-fields--document">
            <div class="fidusia-form-field">
              <label for="tanggal_akta">Tanggal Akta <i class="required">*</i></label>
              <input type="date" class="form-control native-date-input" name="tanggal_akta" id="tanggal_akta" value="<?= _ent($date_value); ?>" required>
              <small><i class="fa fa-calendar" aria-hidden="true"></i>Pilih tanggal yang tercantum pada akta.</small>
            </div>

            <div class="fidusia-form-field">
              <label for="nomor_akta">Nomor Akta <i class="required">*</i></label>
              <input type="number" class="form-control" name="nomor_akta" id="nomor_akta" placeholder="Contoh: 25" value="<?= _ent($number_value); ?>" min="1" max="9999999999" required>
              <small><i class="fa fa-info-circle" aria-hidden="true"></i>Maksimal 10 digit angka.</small>
            </div>

            <div class="fidusia-form-field fidusia-form-field--full">
              <label for="no_sertifikat_jaminan_fidusia">Nomor Sertifikat Jaminan Fidusia <i class="required">*</i></label>
              <input type="text" class="form-control" name="no_sertifikat_jaminan_fidusia" id="no_sertifikat_jaminan_fidusia" placeholder="Masukkan nomor sertifikat" value="<?= _ent($certificate_value); ?>" maxlength="255" required>
              <small><i class="fa fa-certificate" aria-hidden="true"></i>Gunakan nomor lengkap sesuai dokumen sertifikat.</small>
            </div>
          </div>
        </section>

        <section class="fidusia-form-card">
          <div class="fidusia-form-card__heading">
            <span><i class="fa fa-exchange" aria-hidden="true"></i></span>
            <div><h2>Para Pihak</h2><p>Identitas pihak pemberi dan penerima jaminan Fidusia.</p></div>
          </div>

          <div class="fidusia-form-fields">
            <div class="fidusia-form-field fidusia-form-field--full">
              <label for="nama_pemberi_fidusia">Nama Pemberi Fidusia <i class="required">*</i></label>
              <input type="text" class="form-control" name="nama_pemberi_fidusia" id="nama_pemberi_fidusia" placeholder="Masukkan nama pemberi Fidusia" value="<?= _ent($grantor_value); ?>" maxlength="255" required>
              <small><i class="fa fa-user" aria-hidden="true"></i>Nama pihak yang menyerahkan objek sebagai jaminan.</small>
            </div>

            <div class="fidusia-form-field fidusia-form-field--full">
              <label for="nama_penerima_fidusia">Nama Penerima Fidusia <i class="required">*</i></label>
              <input type="text" class="form-control" name="nama_penerima_fidusia" id="nama_penerima_fidusia" placeholder="Masukkan nama penerima Fidusia" value="<?= _ent($recipient_value); ?>" maxlength="255" required>
              <small><i class="fa fa-building-o" aria-hidden="true"></i>Nama pihak yang menerima hak jaminan Fidusia.</small>
            </div>
          </div>
        </section>
      </div>

      <footer class="fidusia-form-actions">
        <div class="fidusia-form-actions__hint"><i class="fa fa-info-circle" aria-hidden="true"></i><span>Pastikan seluruh informasi sesuai dengan dokumen asli.</span></div>
        <div class="fidusia-form-actions__buttons">
          <a class="btn admin-button admin-button--neutral" id="fidusia_cancel" href="<?= site_url('fidusia'); ?>" title="Kembali ke daftar (Ctrl+X)"><i class="fa fa-times" aria-hidden="true"></i> Batal</a>
          <button type="button" class="btn admin-button admin-button--save-secondary fidusia-save" id="btn_save_back" data-stype="back" title="Simpan dan kembali ke daftar (Ctrl+D)"><i class="fa fa-list" aria-hidden="true"></i> Simpan &amp; Kembali</button>
          <button type="button" class="btn admin-button admin-button--save fidusia-save" id="btn_save" data-stype="stay" title="Simpan data (Ctrl+S)"><i class="fa fa-save" aria-hidden="true"></i> Simpan</button>
        </div>
        <span class="loading loading-hide"><img src="<?= BASE_ASSET; ?>/img/loading-spin-primary.svg" alt=""> <i><?= cclang('loading_saving_data'); ?></i></span>
      </footer>
    <?= form_close(); ?>
  </div>
</section>

<script>
$(function () {
  var form = $('#form_fidusia');

  $(document).off('click.fidusiaForm', '.fidusia-save').on('click.fidusiaForm', '.fidusia-save', function () {
    if (!form.length || !form[0].checkValidity()) {
      if (form.length) form[0].reportValidity();
      return false;
    }

    var button = $(this);
    var saveType = button.data('stype');
    var dataPost = form.serializeArray();
    dataPost.push({name: 'save_type', value: saveType});

    $('.fidusia-form-message').stop(true, true).hide().empty();
    form.find('.fidusia-save').prop('disabled', true);
    form.find('.loading').removeClass('loading-hide').show();

    $.ajax({
      url: form.attr('action'),
      type: 'POST',
      dataType: 'json',
      data: dataPost
    }).done(function (response) {
      if (!response.success) {
        $('.fidusia-form-message').printMessage({message: response.message, type: 'warning'});
        $('.fidusia-form-message').fadeIn();
        return;
      }

      if (saveType === 'back') {
        window.location.href = response.redirect;
        return;
      }

      $('.fidusia-form-message').printMessage({message: response.message});
      $('.fidusia-form-message').fadeIn();
      if (form.data('reset-after-save') === true) {
        form[0].reset();
        if (typeof window.initializeNativeDateInputs === 'function') window.initializeNativeDateInputs(form[0]);
      }
      window.scrollTo({top: 0, behavior: 'smooth'});
    }).fail(function () {
      $('.fidusia-form-message').printMessage({message: 'Data Fidusia gagal disimpan. Silakan coba kembali.', type: 'warning'});
      $('.fidusia-form-message').fadeIn();
    }).always(function () {
      form.find('.fidusia-save').prop('disabled', false);
      form.find('.loading').hide().addClass('loading-hide');
    });

    return false;
  });

  $(document).off('keydown.fidusiaForm').on('keydown.fidusiaForm', function (event) {
    if (!event.ctrlKey) return;
    var key = String(event.key).toLowerCase();
    if (key === 's') { event.preventDefault(); $('#btn_save').trigger('click'); }
    if (key === 'd') { event.preventDefault(); $('#btn_save_back').trigger('click'); }
    if (key === 'x') { event.preventDefault(); document.getElementById('fidusia_cancel').click(); }
  });
});
</script>
