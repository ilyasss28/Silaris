<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$record = $rekap_edit_record;
$form_id = 'form_' . $rekap_edit_slug;
?>
<section class="content">
   <div class="row">
      <div class="col-md-12">
         <div class="box box-warning">
            <div class="box-body">
               <div class="box box-widget widget-user-2">
                  <div class="widget-user-header">
                     <h3 class="widget-user-username"><b><?= html_escape($rekap_edit_title); ?></b></h3>
                     <h5 class="widget-user-desc">Edit <?= html_escape($rekap_edit_title); ?></h5>
                     <hr>
                  </div>

                  <?= form_open(site_url($rekap_edit_slug . '/edit_save/' . $rekap_edit_id), [
                     'name' => $form_id,
                     'class' => 'form-horizontal',
                     'id' => $form_id,
                     'method' => 'POST',
                  ]); ?>
                     <div class="form-group">
                        <label for="nomor_akta" class="col-sm-2 control-label">Nomor Akta <i class="required">*</i></label>
                        <div class="col-sm-10">
                           <input type="number" class="form-control" name="nomor_akta" id="nomor_akta" maxlength="10" required value="<?= html_escape(set_value('nomor_akta', $record->nomor_akta)); ?>">
                           <small class="info help-block">Maksimal 10 digit.</small>
                        </div>
                     </div>

                     <div class="form-group">
                        <label for="tanggal_akta" class="col-sm-2 control-label">Tanggal Akta <i class="required">*</i></label>
                        <div class="col-sm-10">
                           <input type="date" class="form-control native-date-input" name="tanggal_akta" id="tanggal_akta" required value="<?= html_escape(set_value('tanggal_akta', $record->tanggal_akta)); ?>">
                        </div>
                     </div>

                     <div class="form-group">
                        <label for="sifat_akta" class="col-sm-2 control-label">Sifat Akta <i class="required">*</i></label>
                        <div class="col-sm-10">
                           <input type="text" class="form-control" name="sifat_akta" id="sifat_akta" maxlength="100" required value="<?= html_escape(set_value('sifat_akta', $record->sifat_akta)); ?>">
                           <small class="info help-block">Maksimal 100 karakter.</small>
                        </div>
                     </div>

                     <div class="form-group">
                        <label for="penghadap" class="col-sm-2 control-label">Penghadap <i class="required">*</i></label>
                        <div class="col-sm-10">
                           <input type="text" class="form-control" name="penghadap" id="penghadap" maxlength="100" required value="<?= html_escape(set_value('penghadap', $record->penghadap)); ?>">
                           <small class="info help-block">Maksimal 100 karakter.</small>
                        </div>
                     </div>

                     <div class="message"></div>
                     <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                           <a href="<?= site_url($rekap_edit_slug); ?>" class="btn btn-flat btn-default btn_action">
                              <i class="fa fa-undo"></i> Batal
                           </a>
                           <button type="submit" class="btn btn-flat btn-info btn_save btn_action" data-stype="back">
                              <i class="fa fa-list"></i> Simpan dan kembali ke daftar
                           </button>
                           <button type="submit" class="btn btn-flat btn-primary btn_save btn_action" data-stype="stay">
                              <i class="fa fa-save"></i> Simpan
                           </button>
                           <span class="loading loading-hide"><img src="<?= BASE_ASSET; ?>/img/loading-spin-primary.svg" alt=""> Menyimpan...</span>
                        </div>
                     </div>
                  <?= form_close(); ?>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>

<script>
$(function () {
   var form = $('#<?= addslashes($form_id); ?>');

   form.on('click', '.btn_save', function () {
      form.data('save-type', $(this).data('stype'));
   });

   form.on('submit', function (event) {
      event.preventDefault();
      var saveType = form.data('save-type') || 'stay';
      var data = form.serializeArray();
      data.push({name: 'save_type', value: saveType});
      form.find('.loading').show();
      form.find('.message').hide();

      $.ajax({
         url: form.attr('action'),
         type: 'POST',
         dataType: 'json',
         data: data
      }).done(function (response) {
         if (response.success && saveType === 'back') {
            window.location.href = response.redirect;
            return;
         }
         form.find('.message').printMessage({
            message: response.message,
            type: response.success ? 'success' : 'warning'
         }).fadeIn();
      }).fail(function () {
         form.find('.message').printMessage({message: 'Data gagal disimpan. Silakan coba lagi.', type: 'warning'}).fadeIn();
      }).always(function () {
         form.find('.loading').hide();
      });
   });
});
</script>
