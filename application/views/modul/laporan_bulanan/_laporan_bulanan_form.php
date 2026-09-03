<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$is_edit = isset($laporan_bulanan_form_mode) && $laporan_bulanan_form_mode === 'edit';
$record = $is_edit ? $laporan_bulanan : null;
$form_action = $is_edit ? site_url('laporan_bulanan/edit_save/'.$record->id_laporan_bulanan) : site_url('laporan_bulanan/add_save');
$date_value = set_value('tanggal_laporan', $record ? $record->tanggal_laporan : '');
$region_value = set_value('kd_wilayah', $record ? $record->kd_wilayah : '');
$file_value = set_value('laporan_bulanan_file_laporan_name', $record ? $record->file_laporan : '');
?>
<link href="<?= BASE_ASSET; ?>/fine-upload/fine-uploader-gallery.min.css" rel="stylesheet">
<script src="<?= BASE_ASSET; ?>/fine-upload/jquery.fine-uploader.js"></script>
<?php $this->load->view('core_template/fine_upload'); ?>

<section class="content fidusia-form-page service-record-form-page monthly-report-form-page">
  <div class="fidusia-form-shell">
    <header class="fidusia-form-header">
      <div class="fidusia-form-header__copy">
        <span class="fidusia-form-header__icon"><i class="fa <?= $is_edit ? 'fa-pencil' : 'fa-plus'; ?>" aria-hidden="true"></i></span>
        <div><span class="fidusia-form-eyebrow">LAPORAN BULANAN</span><h1><?= $is_edit ? 'Edit' : 'Tambah'; ?> Laporan Bulanan</h1><p><?= $is_edit ? 'Perbarui periode, wilayah, atau dokumen laporan.' : 'Lengkapi periode, wilayah, dan dokumen laporan.'; ?></p></div>
      </div>
      <span class="fidusia-form-status"><i class="fa <?= $is_edit ? 'fa-edit' : 'fa-file-o'; ?>" aria-hidden="true"></i><?= $is_edit ? 'Mode edit' : 'Data baru'; ?></span>
    </header>

    <?= form_open($form_action, ['name' => 'form_laporan_bulanan', 'class' => 'form-horizontal fidusia-form', 'id' => 'form_laporan_bulanan', 'method' => 'POST']); ?>
      <div class="message fidusia-form-message" aria-live="polite"></div>
      <div class="fidusia-form-grid">
        <section class="fidusia-form-card">
          <div class="fidusia-form-card__heading"><span><i class="fa fa-calendar" aria-hidden="true"></i></span><div><h2>Periode dan Wilayah</h2><p>Waktu pelaporan dan wilayah kerja Notaris.</p></div></div>
          <div class="fidusia-form-fields monthly-report-fields">
            <div class="fidusia-form-field fidusia-form-field--full">
              <label for="tanggal_laporan">Tanggal Laporan <i class="required">*</i></label>
              <input type="date" class="form-control native-date-input" name="tanggal_laporan" id="tanggal_laporan" value="<?= _ent($date_value); ?>" required>
              <small><i class="fa fa-calendar" aria-hidden="true"></i>Pilih tanggal periode laporan.</small>
            </div>
            <div class="fidusia-form-field fidusia-form-field--full">
              <label for="kd_wilayah">Wilayah <i class="required">*</i></label>
              <select class="form-control chosen chosen-select-deselect" name="kd_wilayah" id="kd_wilayah" data-placeholder="Pilih wilayah" required>
                <option value=""></option>
                <?php foreach (db_get_all_data('wil') as $row): ?>
                  <option value="<?= _ent($row->id); ?>" <?= (string) $row->id === (string) $region_value ? 'selected' : ''; ?>><?= _ent($row->nama_wilayah); ?></option>
                <?php endforeach; ?>
              </select>
              <small><i class="fa fa-map-marker" aria-hidden="true"></i>Pilih kabupaten atau kota sesuai wilayah kerja.</small>
            </div>
          </div>
        </section>

        <section class="fidusia-form-card">
          <div class="fidusia-form-card__heading"><span><i class="fa fa-file-pdf-o" aria-hidden="true"></i></span><div><h2>Dokumen Laporan</h2><p>Unggah satu dokumen laporan untuk periode tersebut.</p></div></div>
          <div class="fidusia-form-fields">
            <div class="fidusia-form-field fidusia-form-field--full monthly-report-upload">
              <label>File Laporan <?= $is_edit ? '' : '<i class="required">*</i>'; ?></label>
              <div id="laporan_bulanan_file_laporan_galery"></div>
              <input class="data_file data_file_uuid" name="laporan_bulanan_file_laporan_uuid" id="laporan_bulanan_file_laporan_uuid" type="hidden" value="<?= _ent(set_value('laporan_bulanan_file_laporan_uuid')); ?>">
              <input class="data_file" name="laporan_bulanan_file_laporan_name" id="laporan_bulanan_file_laporan_name" type="hidden" value="<?= _ent($file_value); ?>">
              <small><i class="fa fa-info-circle" aria-hidden="true"></i><?= $is_edit ? 'Biarkan dokumen saat ini jika tidak ingin menggantinya.' : 'Pilih dokumen laporan sebelum menyimpan data.'; ?></small>
            </div>
          </div>
        </section>
      </div>

      <footer class="fidusia-form-actions">
        <div class="fidusia-form-actions__hint"><i class="fa fa-info-circle" aria-hidden="true"></i><span>Pastikan periode, wilayah, dan dokumen sudah benar.</span></div>
        <div class="fidusia-form-actions__buttons">
          <a class="btn admin-button admin-button--neutral" id="monthly_report_cancel" href="<?= site_url('laporan_bulanan'); ?>"><i class="fa fa-times" aria-hidden="true"></i> Batal</a>
          <button type="button" class="btn admin-button admin-button--save-secondary monthly-report-save" data-stype="back"><i class="fa fa-list" aria-hidden="true"></i> Simpan &amp; Kembali</button>
          <button type="button" class="btn admin-button admin-button--save monthly-report-save" id="btn_save" data-stype="stay"><i class="fa fa-save" aria-hidden="true"></i> Simpan</button>
        </div>
        <span class="loading loading-hide"><img src="<?= BASE_ASSET; ?>/img/loading-spin-primary.svg" alt=""> <i><?= cclang('loading_saving_data'); ?></i></span>
      </footer>
    <?= form_close(); ?>
  </div>
</section>

<script>
$(function () {
  var form = $('#form_laporan_bulanan');
  var isEdit = <?= $is_edit ? 'true' : 'false'; ?>;
  var params = {};
  params[csrf] = token;
  var uploaderOptions = {
    template: 'qq-template-gallery',
    request: {endpoint: BASE_URL + '/laporan_bulanan/upload_file_laporan_file', params: params},
    deleteFile: {enabled: true, endpoint: BASE_URL + '/laporan_bulanan/delete_file_laporan_file'},
    thumbnails: {placeholders: {waitingPath: BASE_URL + '/asset/fine-upload/placeholders/waiting-generic.png', notAvailablePath: BASE_URL + '/asset/fine-upload/placeholders/not_available-generic.png'}},
    multiple: false,
    validation: {allowedExtensions: ['*'], sizeLimit: 0},
    showMessage: function (message) { toastr.error(message); },
    callbacks: {
      onComplete: function (id, name, response) {
        if (!response.success) { toastr.error(response.error); return; }
        $('#laporan_bulanan_file_laporan_uuid').val($('#laporan_bulanan_file_laporan_galery').fineUploader('getUuid', id));
        $('#laporan_bulanan_file_laporan_name').val(response.uploadName);
      },
      onSubmit: function () {
        var uuid = $('#laporan_bulanan_file_laporan_uuid').val();
        if (uuid) $.get(BASE_URL + '/laporan_bulanan/delete_file_laporan_file/' + encodeURIComponent(uuid));
      },
      onDeleteComplete: function (id, xhr, isError) {
        if (!isError) $('#laporan_bulanan_file_laporan_uuid, #laporan_bulanan_file_laporan_name').val('');
      }
    }
  };
  <?php if ($is_edit): ?>
  uploaderOptions.session = {endpoint: BASE_URL + 'laporan_bulanan/get_file_laporan_file/<?= (int) $record->id_laporan_bulanan; ?>', refreshOnRequest: true};
  <?php endif; ?>
  $('#laporan_bulanan_file_laporan_galery').fineUploader(uploaderOptions);

  $(document).off('click.monthlyReport', '.monthly-report-save').on('click.monthlyReport', '.monthly-report-save', function () {
    if (!form[0].checkValidity()) { form[0].reportValidity(); return false; }
    if (!isEdit && !$('#laporan_bulanan_file_laporan_name').val()) { toastr.error('Dokumen laporan wajib dipilih.'); return false; }
    var saveType = $(this).data('stype');
    var dataPost = form.serializeArray();
    dataPost.push({name: 'save_type', value: saveType});
    form.find('.monthly-report-save').prop('disabled', true);
    form.find('.loading').removeClass('loading-hide').show();
    $('.fidusia-form-message').hide().empty();
    $.ajax({url: form.attr('action'), type: 'POST', dataType: 'json', data: dataPost})
      .done(function (response) {
        if (!response.success) { $('.fidusia-form-message').printMessage({message: response.message, type: 'warning'}).fadeIn(); return; }
        if (saveType === 'back') { window.location.href = response.redirect; return; }
        $('.fidusia-form-message').printMessage({message: response.message}).fadeIn();
        if (!isEdit) { form[0].reset(); $('#laporan_bulanan_file_laporan_galery').fineUploader('reset'); }
        else $('#laporan_bulanan_file_laporan_uuid').val('');
        window.scrollTo({top: 0, behavior: 'smooth'});
      })
      .fail(function () { $('.fidusia-form-message').printMessage({message: 'Laporan gagal disimpan. Silakan coba kembali.', type: 'warning'}).fadeIn(); })
      .always(function () { form.find('.monthly-report-save').prop('disabled', false); form.find('.loading').hide().addClass('loading-hide'); });
    return false;
  });

  $(document).off('keydown.monthlyReport').on('keydown.monthlyReport', function (event) {
    if (!event.ctrlKey) return;
    var key = String(event.key).toLowerCase();
    if (key === 's') { event.preventDefault(); $('#btn_save').trigger('click'); }
    if (key === 'd') { event.preventDefault(); $('.monthly-report-save[data-stype="back"]').trigger('click'); }
    if (key === 'x') { event.preventDefault(); document.getElementById('monthly_report_cancel').click(); }
  });
});
</script>
