<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$module = $akta_page['module'];
$title = $akta_page['title'];
$record = isset($akta_page['record']) ? $akta_page['record'] : null;
$is_edit = $record !== null;
$primary_key = $akta_page['primary_key'];
$record_id = $is_edit ? $record->{$primary_key} : null;
$form_action = $is_edit ? site_url($module.'/edit_save/'.$record_id) : site_url($module.'/add_save');
$form_id = 'form_'.$module;
$number_value = set_value('nomor_akta', $is_edit ? $record->nomor_akta : '');
$date_value = set_value('tanggal_akta', $is_edit ? $record->tanggal_akta : '');
$nature_value = set_value('sifat_akta', $is_edit ? $record->sifat_akta : '');
$appearer_value = set_value('penghadap', $is_edit ? $record->penghadap : '');
?>

<section class="content fidusia-form-page service-record-form-page">
  <div class="fidusia-form-shell">
    <header class="fidusia-form-header">
      <div class="fidusia-form-header__copy">
        <span class="fidusia-form-header__icon"><i class="fa <?= $is_edit ? 'fa-pencil' : 'fa-plus'; ?>" aria-hidden="true"></i></span>
        <div>
          <span class="fidusia-form-eyebrow"><?= _ent(strtoupper('DATA '.$title)); ?></span>
          <h1><?= $is_edit ? 'Edit ' : 'Tambah '; ?><?= _ent($title); ?></h1>
          <p><?= $is_edit ? 'Perbarui informasi akta dan pihak terkait.' : 'Lengkapi informasi akta dan pihak terkait.'; ?></p>
        </div>
      </div>
      <span class="fidusia-form-status"><i class="fa <?= $is_edit ? 'fa-edit' : 'fa-file-o'; ?>" aria-hidden="true"></i><?= $is_edit ? 'Mode edit' : 'Data baru'; ?></span>
    </header>

    <?= form_open($form_action, [
        'name' => $form_id,
        'class' => 'form-horizontal fidusia-form',
        'id' => $form_id,
        'method' => 'POST',
        'data-reset-after-save' => $is_edit ? 'false' : 'true',
    ]); ?>
      <div class="message fidusia-form-message" aria-live="polite"></div>
      <div class="fidusia-form-grid">
        <section class="fidusia-form-card">
          <div class="fidusia-form-card__heading">
            <span><i class="fa fa-file-text-o" aria-hidden="true"></i></span>
            <div><h2>Informasi Akta</h2><p>Nomor dan tanggal akta yang dicatat.</p></div>
          </div>
          <div class="fidusia-form-fields">
            <div class="fidusia-form-field">
              <label for="nomor_akta">Nomor Akta <i class="required">*</i></label>
              <input type="number" class="form-control" name="nomor_akta" id="nomor_akta" placeholder="Contoh: 25 atau 0 jika nihil" value="<?= _ent($number_value); ?>" min="0" max="9999999999" step="1" required>
              <small><i class="fa fa-info-circle" aria-hidden="true"></i>Isi 0 jika nihil; maksimal 10 digit angka.</small>
            </div>
            <div class="fidusia-form-field">
              <label for="tanggal_akta">Tanggal Akta <i class="required">*</i></label>
              <input type="date" class="form-control native-date-input" name="tanggal_akta" id="tanggal_akta" value="<?= _ent($date_value); ?>" required>
              <small><i class="fa fa-calendar" aria-hidden="true"></i>Pilih tanggal yang tercantum pada akta.</small>
            </div>
          </div>
        </section>

        <section class="fidusia-form-card">
          <div class="fidusia-form-card__heading">
            <span><i class="fa fa-users" aria-hidden="true"></i></span>
            <div><h2>Keterangan Akta</h2><p>Sifat akta dan pihak yang menghadap.</p></div>
          </div>
          <div class="fidusia-form-fields">
            <div class="fidusia-form-field fidusia-form-field--full">
              <label for="sifat_akta">Sifat Akta <i class="required">*</i></label>
              <input type="text" class="form-control" name="sifat_akta" id="sifat_akta" placeholder="Masukkan sifat akta atau Nihil" value="<?= _ent($nature_value); ?>" maxlength="100" required>
              <small><i class="fa fa-tag" aria-hidden="true"></i>Maksimal 100 karakter; isi Nihil jika tidak ada.</small>
            </div>
            <div class="fidusia-form-field fidusia-form-field--full">
              <label for="penghadap">Penghadap <i class="required">*</i></label>
              <input type="text" class="form-control" name="penghadap" id="penghadap" placeholder="Masukkan nama penghadap atau Nihil" value="<?= _ent($appearer_value); ?>" maxlength="100" required>
              <small><i class="fa fa-user" aria-hidden="true"></i>Maksimal 100 karakter; isi Nihil jika tidak ada.</small>
            </div>
          </div>
        </section>
      </div>

      <footer class="fidusia-form-actions">
        <div class="fidusia-form-actions__hint"><i class="fa fa-info-circle" aria-hidden="true"></i><span>Pastikan seluruh informasi sesuai dengan dokumen asli.</span></div>
        <div class="fidusia-form-actions__buttons">
          <a class="btn admin-button admin-button--neutral" id="service_record_cancel" href="<?= site_url($module); ?>"><i class="fa fa-times" aria-hidden="true"></i> Batal</a>
          <button type="button" class="btn admin-button admin-button--save-secondary service-record-save" data-stype="back"><i class="fa fa-list" aria-hidden="true"></i> Simpan &amp; Kembali</button>
          <button type="button" class="btn admin-button admin-button--save service-record-save" id="btn_save" data-stype="stay"><i class="fa fa-save" aria-hidden="true"></i> Simpan</button>
        </div>
        <span class="loading loading-hide"><img src="<?= BASE_ASSET; ?>/img/loading-spin-primary.svg" alt=""> <i><?= cclang('loading_saving_data'); ?></i></span>
      </footer>
    <?= form_close(); ?>
  </div>
</section>

<script>
$(function () {
  var form = $('#<?= _ent($form_id); ?>');
  var resetAfterSave = <?= $is_edit ? 'false' : 'true'; ?>;

  $(document).off('click.serviceRecord', '.service-record-save').on('click.serviceRecord', '.service-record-save', function () {
    if (!form.length || !form[0].checkValidity()) {
      if (form.length) form[0].reportValidity();
      return false;
    }
    var saveType = $(this).data('stype');
    var dataPost = form.serializeArray();
    dataPost.push({name: 'save_type', value: saveType});
    form.find('.service-record-save').prop('disabled', true);
    form.find('.loading').removeClass('loading-hide').show();
    $('.fidusia-form-message').hide().empty();

    $.ajax({url: form.attr('action'), type: 'POST', dataType: 'json', data: dataPost})
      .done(function (response) {
        if (!response.success) {
          $('.fidusia-form-message').printMessage({message: response.message, type: 'warning'}).fadeIn();
          return;
        }
        if (saveType === 'back') {
          window.location.href = response.redirect;
          return;
        }
        $('.fidusia-form-message').printMessage({message: response.message}).fadeIn();
        if (resetAfterSave) form[0].reset();
        window.scrollTo({top: 0, behavior: 'smooth'});
      })
      .fail(function () {
        $('.fidusia-form-message').printMessage({message: 'Data gagal disimpan. Silakan coba kembali.', type: 'warning'}).fadeIn();
      })
      .always(function () {
        form.find('.service-record-save').prop('disabled', false);
        form.find('.loading').hide().addClass('loading-hide');
      });
    return false;
  });

  $(document).off('keydown.serviceRecord').on('keydown.serviceRecord', function (event) {
    if (!event.ctrlKey) return;
    var key = String(event.key).toLowerCase();
    if (key === 's') { event.preventDefault(); $('#btn_save').trigger('click'); }
    if (key === 'd') { event.preventDefault(); $('.service-record-save[data-stype="back"]').trigger('click'); }
    if (key === 'x') { event.preventDefault(); document.getElementById('service_record_cancel').click(); }
  });
});
</script>
