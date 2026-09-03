<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$is_edit = isset($laporan_form_mode) && $laporan_form_mode === 'edit';
$record = $is_edit ? $laporan : null;
$form_action = $is_edit ? site_url('laporan/edit_save/'.$record->id) : site_url('laporan/add_save');
$date_value = set_value('Tanggal_Laporan', $record ? $record->Tanggal_Laporan : '');
$file_value = set_value('laporan_Laporan_name', $record ? $record->Laporan : '');
?>
<link href="<?= BASE_ASSET; ?>/fine-upload/fine-uploader-gallery.min.css" rel="stylesheet">
<script src="<?= BASE_ASSET; ?>/fine-upload/jquery.fine-uploader.js"></script>
<?php $this->load->view('core_template/fine_upload'); ?>

<section class="content fidusia-form-page service-record-form-page monthly-report-form-page">
  <div class="fidusia-form-shell">
    <header class="fidusia-form-header">
      <div class="fidusia-form-header__copy">
        <span class="fidusia-form-header__icon"><i class="fa <?= $is_edit ? 'fa-pencil' : 'fa-plus'; ?>" aria-hidden="true"></i></span>
        <div><span class="fidusia-form-eyebrow">LAPORAN BULANAN</span><h1><?= $is_edit ? 'Edit' : 'Tambah'; ?> Laporan Bulanan</h1><p><?= $is_edit ? 'Perbarui periode atau dokumen laporan Anda.' : 'Lengkapi periode dan unggah dokumen laporan Anda.'; ?></p></div>
      </div>
      <span class="fidusia-form-status"><i class="fa <?= $is_edit ? 'fa-edit' : 'fa-file-o'; ?>" aria-hidden="true"></i><?= $is_edit ? 'Mode edit' : 'Data baru'; ?></span>
    </header>

    <?= form_open($form_action, ['name' => 'form_laporan', 'class' => 'form-horizontal fidusia-form', 'id' => 'form_laporan', 'method' => 'POST']); ?>
      <div class="message fidusia-form-message" aria-live="polite"></div>
      <div class="fidusia-form-grid">
        <section class="fidusia-form-card monthly-report-card">
          <div class="fidusia-form-card__heading">
            <span><i class="fa fa-file-text-o" aria-hidden="true"></i></span>
            <div><h2>Data Laporan Bulanan</h2><p>Lengkapi periode dan dokumen laporan dalam satu formulir.</p></div>
          </div>

          <div class="monthly-report-card__content">
            <div class="monthly-report-card__section">
              <div class="fidusia-form-fields">
                <div class="fidusia-form-field fidusia-form-field--full">
                  <label for="Tanggal_Laporan">Tanggal Laporan <i class="required">*</i></label>
                  <input type="date" class="form-control native-date-input" name="Tanggal_Laporan" id="Tanggal_Laporan" value="<?= _ent($date_value); ?>" required>
                  <small><i class="fa fa-calendar" aria-hidden="true"></i>Pilih tanggal sesuai periode laporan.</small>
                </div>
              </div>
            </div>

            <div class="monthly-report-card__section monthly-report-card__section--document">
              <div class="fidusia-form-fields">
                <div class="fidusia-form-field fidusia-form-field--full monthly-report-upload">
                  <label>File Laporan <?= $is_edit ? '' : '<i class="required">*</i>'; ?></label>
                  <div id="laporan_Laporan_galery"></div>
                  <input class="data_file data_file_uuid" name="laporan_Laporan_uuid" id="laporan_Laporan_uuid" type="hidden" value="<?= _ent(set_value('laporan_Laporan_uuid')); ?>">
                  <input class="data_file" name="laporan_Laporan_name" id="laporan_Laporan_name" type="hidden" value="<?= _ent($file_value); ?>">
                  <small><i class="fa fa-info-circle" aria-hidden="true"></i><?= $is_edit ? 'Biarkan file saat ini jika tidak ingin menggantinya.' : 'PDF, Office, atau gambar; ukuran maksimal 10 MB.'; ?></small>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>

      <footer class="fidusia-form-actions">
        <div class="fidusia-form-actions__hint"><i class="fa fa-info-circle" aria-hidden="true"></i><span>Pastikan tanggal dan dokumen laporan sudah benar.</span></div>
        <div class="fidusia-form-actions__buttons">
          <a class="btn admin-button admin-button--neutral" id="report_cancel" href="<?= site_url('laporan'); ?>"><i class="fa fa-times" aria-hidden="true"></i> Batal</a>
          <button type="button" class="btn admin-button admin-button--save-secondary report-save" data-stype="back"><i class="fa fa-list" aria-hidden="true"></i> Simpan &amp; Kembali</button>
          <button type="button" class="btn admin-button admin-button--save report-save" id="btn_save" data-stype="stay"><i class="fa fa-save" aria-hidden="true"></i> Simpan</button>
        </div>
        <span class="loading loading-hide"><img src="<?= BASE_ASSET; ?>/img/loading-spin-primary.svg" alt=""> <i><?= cclang('loading_saving_data'); ?></i></span>
      </footer>
    <?= form_close(); ?>
  </div>
</section>

<script>
$(function () {
  var form = $('#form_laporan');
  var isEdit = <?= $is_edit ? 'true' : 'false'; ?>;
  var params = {};
  params[csrf] = token;
  var uploaderOptions = {
    template: 'qq-template-gallery',
    request: {endpoint: BASE_URL + '/laporan/upload_Laporan_file', params: params},
    deleteFile: {enabled: true, endpoint: BASE_URL + '/laporan/delete_Laporan_file'},
    thumbnails: {placeholders: {waitingPath: BASE_URL + '/asset/fine-upload/placeholders/waiting-generic.png', notAvailablePath: BASE_URL + '/asset/fine-upload/placeholders/not_available-generic.png'}},
    multiple: false,
    validation: {allowedExtensions: ['pdf','doc','docx','xls','xlsx','ppt','pptx','jpg','jpeg','png'], sizeLimit: 10240000},
    showMessage: function (message) { toastr.error(message); },
    callbacks: {
      onComplete: function (id, name, response) {
        if (!response.success) { toastr.error(response.error); return; }
        $('#laporan_Laporan_uuid').val($('#laporan_Laporan_galery').fineUploader('getUuid', id));
        $('#laporan_Laporan_name').val(response.uploadName);
      },
      onSubmit: function () {
        var uuid = $('#laporan_Laporan_uuid').val();
        if (uuid) $.get(BASE_URL + '/laporan/delete_Laporan_file/' + encodeURIComponent(uuid));
      },
      onDeleteComplete: function (id, xhr, isError) {
        if (!isError) $('#laporan_Laporan_uuid, #laporan_Laporan_name').val('');
      }
    }
  };
  <?php if ($is_edit): ?>
  uploaderOptions.session = {endpoint: BASE_URL + 'laporan/get_Laporan_file/<?= (int) $record->id; ?>', refreshOnRequest: true};
  <?php endif; ?>
  $('#laporan_Laporan_galery').fineUploader(uploaderOptions);

  $(document).off('click.reportForm', '.report-save').on('click.reportForm', '.report-save', function () {
    if (!form[0].checkValidity()) { form[0].reportValidity(); return false; }
    if (!$('#laporan_Laporan_name').val()) { toastr.error('Dokumen laporan wajib dipilih.'); return false; }
    var saveType = $(this).data('stype');
    var dataPost = form.serializeArray();
    dataPost.push({name: 'save_type', value: saveType});
    form.find('.report-save').prop('disabled', true);
    form.find('.loading').removeClass('loading-hide').show();
    $('.fidusia-form-message').hide().empty();
    $.ajax({url: form.attr('action'), type: 'POST', dataType: 'json', data: dataPost})
      .done(function (response) {
        if (!response.success) { $('.fidusia-form-message').printMessage({message: response.message, type: 'warning'}).fadeIn(); return; }
        if (saveType === 'back') { window.location.href = response.redirect; return; }
        $('.fidusia-form-message').printMessage({message: response.message}).fadeIn();
        if (!isEdit) { form[0].reset(); $('#laporan_Laporan_galery').fineUploader('reset'); }
        else $('#laporan_Laporan_uuid').val('');
        window.scrollTo({top: 0, behavior: 'smooth'});
      })
      .fail(function () { $('.fidusia-form-message').printMessage({message: 'Laporan gagal disimpan. Silakan coba kembali.', type: 'warning'}).fadeIn(); })
      .always(function () { form.find('.report-save').prop('disabled', false); form.find('.loading').hide().addClass('loading-hide'); });
    return false;
  });

  $(document).off('keydown.reportForm').on('keydown.reportForm', function (event) {
    if (!event.ctrlKey) return;
    var key = String(event.key).toLowerCase();
    if (key === 's') { event.preventDefault(); $('#btn_save').trigger('click'); }
    if (key === 'd') { event.preventDefault(); $('.report-save[data-stype="back"]').trigger('click'); }
    if (key === 'x') { event.preventDefault(); document.getElementById('report_cancel').click(); }
  });
});
</script>
