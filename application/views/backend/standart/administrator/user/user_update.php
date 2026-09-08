<link href="<?= BASE_ASSET; ?>/fine-upload/fine-uploader-gallery.min.css" rel="stylesheet">
<script src="<?= BASE_ASSET; ?>/fine-upload/jquery.fine-uploader.js"></script>
<script src="<?= BASE_ASSET; ?>/js/jquery.hotkeys.js"></script>
<?php $this->load->view('core_template/fine_upload'); ?>

<section class="content fidusia-form-page user-registry-form-page user-registry-edit-page">
  <div class="fidusia-form-shell">
    <header class="fidusia-form-header">
      <div class="fidusia-form-header__copy"><span class="fidusia-form-header__icon"><i class="fa fa-pencil"></i></span><div><span class="fidusia-form-eyebrow">MANAJEMEN PENGGUNA</span><h1>Edit Pengguna</h1><p>Perbarui identitas akun, wilayah kerja, kelompok akses, foto, dan kata sandi.</p></div></div>
      <span class="fidusia-form-status"><i class="fa fa-edit"></i>Mode edit</span>
    </header>
    <?= form_open(base_url('administrator/user/edit_save/' . $this->uri->segment(4)), ['name' => 'form_user', 'class' => 'fidusia-form user-registry-form', 'id' => 'form_user', 'enctype' => 'multipart/form-data', 'method' => 'POST']); ?>
      <div class="message fidusia-form-message"></div>
      <div class="fidusia-form-grid">
        <section class="fidusia-form-card">
          <div class="fidusia-form-card__heading"><span><i class="fa fa-user"></i></span><div><h2>Identitas Akun</h2><p>Informasi utama yang digunakan pengguna untuk mengakses SILARIS.</p></div></div>
          <div class="fidusia-form-fields fidusia-form-fields--document">
            <div class="fidusia-form-field"><label for="username">Username <i class="required">*</i></label><input type="text" class="form-control" name="username" id="username" minlength="3" maxlength="100" pattern="[A-Za-z0-9._-]+" autocomplete="username" required value="<?= _ent(set_value('username', $user->username)); ?>"><small><i class="fa fa-info-circle"></i>Nama unik yang digunakan untuk masuk ke sistem.</small></div>
            <div class="fidusia-form-field"><label for="email">Email <i class="required">*</i></label><input type="email" class="form-control" name="email" id="email" maxlength="100" autocomplete="email" required value="<?= _ent(set_value('email', $user->email)); ?>"><small><i class="fa fa-envelope-o"></i>Gunakan alamat email aktif.</small></div>
            <div class="fidusia-form-field"><label for="full_name">Nama Lengkap <i class="required">*</i></label><input type="text" class="form-control" name="full_name" id="full_name" maxlength="200" required value="<?= _ent(set_value('full_name', $user->full_name)); ?>"><small><i class="fa fa-user"></i>Nama lengkap beserta gelar yang tampil pada aplikasi.</small></div>
            <div class="fidusia-form-field"><label for="phone_number">Nomor Telepon <i class="required">*</i></label><input type="tel" class="form-control" name="phone_number" id="phone_number" inputmode="numeric" minlength="10" maxlength="13" pattern="08[0-9]{8,11}" autocomplete="tel" required value="<?= _ent(set_value('phone_number', format_phone_number($user->phone_number ?? ''))); ?>"><small><i class="fa fa-phone"></i>Gunakan 10–13 digit dalam format 08xxxxxxxxxx.</small></div>
            <?php is_allowed('user_update_password', function () { ?>
              <div class="fidusia-form-field fidusia-form-field--full"><label for="password">Kata Sandi Baru</label><div class="input-group input-password"><input type="password" class="form-control password" name="password" id="password" minlength="8" maxlength="72" autocomplete="new-password" placeholder="Kosongkan jika tidak diubah"><span class="input-group-btn"><button type="button" class="btn btn-flat show-password" aria-label="Tampilkan kata sandi"><i class="fa fa-eye eye"></i></button></span></div><small><i class="fa fa-lock"></i>Kosongkan jika tidak ingin mengganti kata sandi.</small></div>
            <?php }); ?>
          </div>
        </section>

        <section class="fidusia-form-card">
          <div class="fidusia-form-card__heading"><span><i class="fa fa-shield"></i></span><div><h2>Wilayah dan Hak Akses</h2><p>Atur cakupan kerja, kewenangan, serta foto profil akun.</p></div></div>
          <div class="fidusia-form-fields fidusia-form-fields--document">
            <div class="fidusia-form-field"><label for="kd_wilayah">Wilayah Kerja <i class="required">*</i></label><select class="form-control chosen chosen-select-deselect" name="kd_wilayah" id="kd_wilayah" required data-placeholder="Pilih wilayah kerja"><option value=""></option><?php foreach (db_get_all_data('wilayah') as $row): ?><option value="<?= _ent($row->kd_wilayah); ?>" <?= set_select('kd_wilayah', $row->kd_wilayah, (string) $row->kd_wilayah === (string) $user->kd_wilayah); ?>>[ <?= _ent($row->kd_wilayah); ?> ] <?= _ent($row->nama); ?></option><?php endforeach; ?></select><small><i class="fa fa-map-marker"></i>Pilih kabupaten/kota tempat pengguna bertugas.</small></div>
            <div class="fidusia-form-field"><label for="group">Kelompok Akses <i class="required">*</i></label><select class="form-control chosen chosen-select" name="group[]" id="group" multiple required data-placeholder="Pilih kelompok akses"><?php foreach (get_application_groups() as $row): ?><option value="<?= (int) $row->id; ?>" <?= in_array($row->id, (array) $group_user) ? 'selected' : ''; ?>><?= _ent(ucwords($row->name)); ?></option><?php endforeach; ?></select><small><i class="fa fa-users"></i>Pilih satu atau beberapa kelompok akses.</small></div>
            <div class="fidusia-form-field fidusia-form-field--full user-registry-guidance-list" id="registry-group-guidance" hidden>
              <div class="alert alert-info user-registry-guidance" data-group-guidance="notaris" hidden><strong>Data Notaris:</strong> lengkapi identitas, dokumen, wilayah kerja, dan statusnya melalui menu Setup → Data Notaris.</div>
              <div class="alert alert-info user-registry-guidance" data-group-guidance="mpd" hidden><strong>Data MPD:</strong> identitas, verifikasi, dan wilayah pengawasan dikelola melalui menu Setup → Data MPD.</div>
            </div>
            <div class="fidusia-form-field fidusia-form-field--full"><label for="user_avatar_galery">Foto Profil</label><div id="user_avatar_galery" src="<?= BASE_URL . 'uploads/user/' . _ent($user->avatar); ?>"></div><input name="user_avatar_uuid" id="user_avatar_uuid" type="hidden" value=""><input name="user_avatar_name" id="user_avatar_name" type="hidden" value="<?= _ent(set_value('user_avatar_name', $user->avatar)); ?>"><small><i class="fa fa-image"></i>Format PNG, JPG, JPEG, atau GIF; maksimal 5 MB.</small></div>
          </div>
        </section>
      </div>
      <footer class="fidusia-form-actions"><div class="fidusia-form-actions__hint"><i class="fa fa-info-circle"></i><span>Pastikan wilayah dan kelompok akses sesuai kewenangan pengguna.</span></div><div class="fidusia-form-actions__buttons"><a class="btn admin-button admin-button--neutral btn_action" id="btn_cancel"><i class="fa fa-times"></i> Batal</a><button class="btn admin-button admin-button--secondary btn_save btn_action btn_save_back" type="button" data-stype="back"><i class="fa fa-list"></i> Simpan & kembali</button><button class="btn admin-button admin-button--save btn_save btn_action" id="btn_save" type="button" data-stype="stay"><i class="fa fa-save"></i> Simpan Perubahan</button><span class="loading loading-hide"><img src="<?= BASE_ASSET; ?>/img/loading-spin-primary.svg"><i><?= cclang('loading_saving_data'); ?></i></span></div></footer>
    <?= form_close(); ?>
  </div>
</section>

<script>
$(function () {
  $('#btn_cancel').on('click', function () { swal({title: 'Batalkan perubahan?', text: 'Perubahan yang belum disimpan akan hilang.', type: 'warning', showCancelButton: true, confirmButtonColor: '#DD6B55', confirmButtonText: 'Ya, kembali', cancelButtonText: 'Lanjut mengedit', closeOnConfirm: true}, function (confirmed) { if (confirmed) window.location.href = BASE_URL + 'administrator/user'; }); return false; });
  $('.btn_save').on('click', function () {
    var form = $('#form_user'); var data = form.serializeArray(); var saveType = $(this).attr('data-stype'); data.push({name: 'save_type', value: saveType}); $('.message').hide(); $('.loading').show();
    $.ajax({url: form.attr('action'), type: 'POST', dataType: 'json', data: data}).done(function (res) { if (res.success) { $('#user_avatar_uuid').val(''); $('#user_avatar_name').val(''); if (saveType === 'back') { window.location.href = res.redirect; return; } $('.message').printMessage({message: res.message}).fadeIn(); } else { $('.message').printMessage({message: res.message, type: 'warning'}).fadeIn(); } }).fail(function () { $('.message').printMessage({message: 'Data pengguna gagal disimpan.', type: 'warning'}).fadeIn(); }).always(function () { $('.loading').hide(); }); return false;
  });
  $('#user_avatar_galery').fineUploader({template: 'qq-template-gallery', request: {endpoint: BASE_URL + 'administrator/user/upload_avatar_file', params: {'<?= $this->security->get_csrf_token_name(); ?>': '<?= $this->security->get_csrf_hash(); ?>'}}, deleteFile: {enabled: true, endpoint: BASE_URL + 'administrator/user/delete_avatar_file'}, thumbnails: {placeholders: {waitingPath: BASE_URL + '/asset/fine-upload/placeholders/waiting-generic.png', notAvailablePath: BASE_URL + '/asset/fine-upload/placeholders/not_available-generic.png'}}, session: {endpoint: BASE_URL + 'administrator/user/get_avatar_file/<?= (int) $user->id; ?>', refreshOnRequest: true}, multiple: false, validation: {allowedExtensions: ['jpeg', 'jpg', 'gif', 'png'], sizeLimit: 5 * 1024 * 1024}, showMessage: function (msg) { toastr.error(msg); }, callbacks: {onComplete: function (id, name, response) { if (!response.success) return; $('#user_avatar_uuid').val($('#user_avatar_galery').fineUploader('getUuid', id)); $('#user_avatar_name').val(response.uploadName || name); }}});
  $(document).off('keydown.userEdit').on('keydown.userEdit', function (event) { if (!event.ctrlKey) return; var key = String(event.key).toLowerCase(); if (key === 's') { event.preventDefault(); $('#btn_save').trigger('click'); } if (key === 'd') { event.preventDefault(); $('.btn_save_back').trigger('click'); } if (key === 'x') { event.preventDefault(); $('#btn_cancel').trigger('click'); } });
});
</script>
