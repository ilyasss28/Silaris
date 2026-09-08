<?php
$permission_is_add = isset($permission_form_mode) && $permission_form_mode === 'add';
$permission_record = isset($permission) ? $permission : (object) ['name' => '', 'definition' => ''];
$permission_action = $permission_is_add
  ? site_url('administrator/permission/add_save')
  : site_url('administrator/permission/edit_save/'.$this->uri->segment(4));
?>
<script src="<?= BASE_ASSET; ?>js/jquery.hotkeys.js"></script>

<section class="content fidusia-form-page permission-form-page">
  <div class="fidusia-form-shell">
    <header class="fidusia-form-header">
      <div class="fidusia-form-header__copy">
        <span class="fidusia-form-header__icon"><i class="fa <?= $permission_is_add ? 'fa-plus' : 'fa-pencil'; ?>"></i></span>
        <div>
          <span class="fidusia-form-eyebrow">MANAJEMEN AKSES</span>
          <h1><?= $permission_is_add ? 'Tambah Permission' : 'Edit Permission'; ?></h1>
          <p><?= $permission_is_add ? 'Buat hak akses baru untuk kebutuhan aplikasi.' : 'Perbarui nama dan definisi hak akses aplikasi.'; ?></p>
        </div>
      </div>
      <span class="fidusia-form-status"><i class="fa <?= $permission_is_add ? 'fa-file-o' : 'fa-edit'; ?>"></i> <?= $permission_is_add ? 'Data baru' : 'Mode edit'; ?></span>
    </header>

    <?= form_open($permission_action, [
      'name' => 'form_permission',
      'class' => 'form-horizontal fidusia-form',
      'id' => 'form_permission',
      'method' => 'POST',
    ]); ?>
      <div class="message fidusia-form-message" aria-live="polite"></div>

      <div class="fidusia-form-grid">
        <section class="fidusia-form-card record-detail-card--wide">
          <div class="fidusia-form-card__heading">
            <span><i class="fa fa-shield"></i></span>
            <div><h2>Informasi Permission</h2><p>Tentukan identitas hak akses secara jelas dan konsisten.</p></div>
          </div>
          <div class="fidusia-form-fields fidusia-form-fields--document">
            <div class="fidusia-form-field">
              <label for="name">Nama Permission <i class="required">*</i></label>
              <input type="text" class="form-control" name="name" id="name" placeholder="Contoh: laporan_view" value="<?= _ent(set_value('name', $permission_record->name)); ?>" maxlength="100" autocomplete="off" required>
              <small><i class="fa fa-info-circle"></i>Gunakan nama unik yang menggambarkan hak akses.</small>
            </div>
            <div class="fidusia-form-field">
              <label for="definition">Definisi</label>
              <input type="text" class="form-control" name="definition" id="definition" placeholder="Jelaskan fungsi permission" value="<?= _ent(set_value('definition', $permission_record->definition)); ?>">
              <small><i class="fa fa-align-left"></i>Penjelasan singkat mengenai fungsi hak akses ini.</small>
            </div>
          </div>
        </section>
      </div>

      <footer class="fidusia-form-actions">
        <div class="fidusia-form-actions__hint"><i class="fa fa-info-circle"></i><span>Pastikan nama permission sesuai dengan pemeriksaan akses di aplikasi.</span></div>
        <div class="fidusia-form-actions__buttons">
          <a class="btn admin-button admin-button--neutral" id="btn_cancel" href="<?= site_url('administrator/permission'); ?>"><i class="fa fa-times"></i> Batal</a>
          <button type="button" class="btn admin-button admin-button--save-secondary permission-save" id="btn_save_back" data-stype="back"><i class="fa fa-list"></i> Simpan &amp; Kembali</button>
          <button type="button" class="btn admin-button admin-button--save permission-save" id="btn_save" data-stype="stay"><i class="fa fa-save"></i> Simpan</button>
        </div>
        <span class="loading loading-hide"><img src="<?= BASE_ASSET; ?>img/loading-spin-primary.svg" alt=""> <i><?= cclang('loading_saving_data'); ?></i></span>
      </footer>
    <?= form_close(); ?>
  </div>
</section>

<script>
$(function () {
  var form = $('#form_permission');

  $(document).off('click.permissionForm', '.permission-save').on('click.permissionForm', '.permission-save', function () {
    if (!form.length || !form[0].checkValidity()) {
      if (form.length) form[0].reportValidity();
      return false;
    }

    var saveType = $(this).data('stype');
    var dataPost = form.serializeArray();
    dataPost.push({name: 'save_type', value: saveType});
    $('.fidusia-form-message').stop(true, true).hide().empty();
    form.find('.permission-save').prop('disabled', true);
    form.find('.loading').removeClass('loading-hide').show();

    $.ajax({url: form.attr('action'), type: 'POST', dataType: 'json', data: dataPost})
      .done(function (response) {
        if (!response.success) {
          $('.fidusia-form-message').printMessage({message: response.message, type: 'warning'});
          $('.fidusia-form-message').fadeIn();
          return;
        }
        if (saveType === 'back') { window.location.href = response.redirect; return; }
        $('.fidusia-form-message').printMessage({message: response.message});
        $('.fidusia-form-message').fadeIn();
        <?php if ($permission_is_add): ?>form[0].reset();<?php endif; ?>
        window.scrollTo({top: 0, behavior: 'smooth'});
      })
      .fail(function () {
        $('.fidusia-form-message').printMessage({message: 'Permission gagal disimpan. Silakan coba kembali.', type: 'warning'});
        $('.fidusia-form-message').fadeIn();
      })
      .always(function () {
        form.find('.permission-save').prop('disabled', false);
        form.find('.loading').hide().addClass('loading-hide');
      });
    return false;
  });

  $(document).off('keydown.permissionForm').on('keydown.permissionForm', function (event) {
    if (!event.ctrlKey) return;
    var key = String(event.key).toLowerCase();
    if (key === 's') { event.preventDefault(); $('#btn_save').trigger('click'); }
    if (key === 'd') { event.preventDefault(); $('#btn_save_back').trigger('click'); }
    if (key === 'x') { event.preventDefault(); window.location.href = $('#btn_cancel').attr('href'); }
  });
});
</script>
