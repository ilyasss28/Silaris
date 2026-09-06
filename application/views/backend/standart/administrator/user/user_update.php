<!-- Fine Uploader Gallery CSS file
   ====================================================================== -->
<link href="<?= BASE_ASSET; ?>/fine-upload/fine-uploader-gallery.min.css" rel="stylesheet">
<style>
   .admin-silaris .user-edit-page { padding: 0 !important; }
   .admin-silaris .user-edit-shell {
      max-width: 1120px;
      margin: 0 auto;
      overflow: hidden;
      border: 1px solid var(--border);
      border-top: 3px solid var(--accent);
      border-radius: var(--radius);
      background: var(--card);
      box-shadow: 0 12px 34px rgba(15, 27, 45, .08);
   }
   .admin-silaris .user-edit-shell > .box-body { padding: 0 !important; }
   .admin-silaris .user-edit-shell .box-widget { margin: 0; border: 0; box-shadow: none; }
   .admin-silaris .user-edit-header {
      display: flex;
      align-items: center;
      gap: 18px;
      min-height: 112px;
      padding: 21px 24px;
      border-bottom: 1px solid var(--border);
      background: #fff;
   }
   .admin-silaris .user-edit-header .widget-user-image { position: static; margin: 0; }
   .admin-silaris .user-edit-header .widget-user-image img {
      width: 70px;
      height: 70px;
      padding: 3px;
      border: 2px solid var(--accent);
      border-radius: 18px;
      background: #fff;
      object-fit: cover;
      box-shadow: 0 7px 18px rgba(15, 27, 45, .12);
   }
   .admin-silaris .user-edit-heading { flex: 1; min-width: 0; }
   .admin-silaris .user-edit-eyebrow {
      display: block;
      margin-bottom: 5px;
      color: #8a6b00;
      font-size: 11px;
      font-weight: 800;
      letter-spacing: .09em;
      text-transform: uppercase;
   }
   .admin-silaris .user-edit-header .widget-user-username {
      margin: 0 0 5px !important;
      color: var(--ink-900);
      font-size: 24px;
      font-weight: 800;
      letter-spacing: -.03em;
   }
   .admin-silaris .user-edit-header .widget-user-desc {
      margin: 0 !important;
      color: var(--ink-500);
      font-size: 13px;
      font-weight: 500;
   }
   .admin-silaris .user-edit-back {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      min-height: 38px;
      padding: 0 14px;
      border: 1px solid var(--border);
      border-radius: 9px;
      background: #fff;
      color: var(--ink-700);
      font-size: 12px;
      font-weight: 700;
      text-decoration: none;
   }
   .admin-silaris .user-edit-back:hover { border-color: var(--accent-dark); background: var(--accent-tint); }
   .admin-silaris .user-edit-form { padding: 28px 30px 0; }
   .admin-silaris .user-edit-grid {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 20px 22px;
   }
   .admin-silaris .user-edit-form .form-group {
      display: block !important;
      margin: 0 !important;
      padding: 0 !important;
      border: 0 !important;
   }
   .admin-silaris .user-edit-form .form-group.user-edit-wide { grid-column: 1 / -1; }
   .admin-silaris .user-edit-form .control-label {
      display: block;
      width: auto !important;
      margin: 0 0 8px;
      padding: 0 !important;
      float: none !important;
      color: var(--ink-800);
      font-size: 12px;
      font-weight: 750;
      text-align: left !important;
   }
   .admin-silaris .user-edit-form .form-group > [class*="col-"] {
      width: 100% !important;
      padding: 0 !important;
      float: none !important;
   }
   .admin-silaris .user-edit-form .form-control,
   .admin-silaris .user-edit-form .chosen-container,
   .admin-silaris .user-edit-form .input-password { width: 100% !important; max-width: none !important; }
   .admin-silaris .user-edit-form .form-control,
   .admin-silaris .user-edit-form .chosen-container-single .chosen-single,
   .admin-silaris .user-edit-form .chosen-container-multi .chosen-choices {
      min-height: 44px;
      border: 1px solid #d8dee8 !important;
      border-radius: 9px !important;
      background: #fbfcfe !important;
      box-shadow: none !important;
   }
   .admin-silaris .user-edit-form .form-control { padding: 10px 13px; }
   .admin-silaris .user-edit-form .form-control:focus {
      border-color: #d5aa00 !important;
      background: #fff !important;
      box-shadow: 0 0 0 3px rgba(254, 205, 8, .18) !important;
   }
   .admin-silaris .user-edit-form .chosen-container-single .chosen-single { padding: 9px 12px; }
   .admin-silaris .user-edit-form .chosen-container-single .chosen-single div { top: 9px; }
   .admin-silaris .user-edit-form .chosen-container-multi .chosen-choices { padding: 5px 8px; }
   .admin-silaris .user-edit-form .help-block {
      display: block;
      margin: 7px 0 0;
      color: var(--ink-500);
      font-size: 11px;
      line-height: 1.5;
   }
   .admin-silaris .user-edit-form .required { color: var(--danger); }
   .admin-silaris .user-avatar-field {
      padding: 18px !important;
      border: 1px dashed #cbd3df !important;
      border-radius: 11px !important;
      background: #f8f9fc;
   }
   .admin-silaris .user-avatar-field > .control-label { margin-bottom: 12px; }
   .admin-silaris .user-avatar-field .qq-uploader { min-height: 180px; border-radius: 9px; background: #fff; }
   .admin-silaris .user-edit-form .input-password { display: flex; }
   .admin-silaris .user-edit-form .input-password .form-control { border-radius: 9px 0 0 9px !important; }
   .admin-silaris .user-edit-form .show-password {
      height: 44px;
      min-width: 46px;
      border: 1px solid #d8dee8;
      border-left: 0;
      border-radius: 0 9px 9px 0;
      background: #f8f9fc;
      color: var(--ink-500);
   }
   .admin-silaris .user-edit-message { grid-column: 1 / -1; }
   .admin-silaris .user-edit-actions {
      display: flex;
      align-items: center;
      justify-content: flex-end;
      gap: 9px;
      margin: 28px -30px 0;
      padding: 18px 30px;
      border-top: 1px solid var(--border);
      background: #f8f9fc;
   }
   .admin-silaris .user-edit-actions .btn {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      min-height: 40px;
      padding: 0 16px;
      border-radius: 9px !important;
      font-size: 12px;
      font-weight: 750;
   }
   .admin-silaris .user-edit-actions .btn-primary { background: var(--brand) !important; border-color: var(--brand) !important; color: #fff; }
   .admin-silaris .user-edit-actions .btn-info { background: var(--accent) !important; border-color: var(--accent) !important; color: var(--ink-900) !important; }
   .admin-silaris .user-edit-actions .loading { margin-right: auto; color: var(--ink-500); font-size: 12px; }
   .admin-silaris .user-edit-actions .loading img { width: 18px; }
   @media (max-width: 767.98px) {
      .admin-silaris .user-edit-page { padding: 0 !important; }
      .admin-silaris .user-edit-header { align-items: flex-start; padding: 20px; }
      .admin-silaris .user-edit-header .widget-user-image img { width: 58px; height: 58px; }
      .admin-silaris .user-edit-header .widget-user-username { font-size: 20px; }
      .admin-silaris .user-edit-back { display: none; }
      .admin-silaris .user-edit-form { padding: 22px 20px 0; }
      .admin-silaris .user-edit-grid { grid-template-columns: minmax(0, 1fr); gap: 18px; }
      .admin-silaris .user-edit-form .form-group.user-edit-wide { grid-column: auto; }
      .admin-silaris .user-edit-actions { margin: 24px -20px 0; padding: 15px 20px; flex-wrap: wrap; }
      .admin-silaris .user-edit-actions .loading { width: 100%; order: 4; }
      .admin-silaris .user-edit-actions .btn { flex: 1 1 auto; justify-content: center; }
   }
</style>
<!-- Fine Uploader jQuery JS file
   ====================================================================== -->
<script src="<?= BASE_ASSET; ?>/fine-upload/jquery.fine-uploader.js"></script>
<script src="<?= BASE_ASSET; ?>/js/jquery.hotkeys.js"></script>
<script type="text/javascript">
   //This page is a result of an autogenerated content made by running test.html with firefox.
   function domo(){
    
      // Binding keys
      $('*').bind('keydown', 'Ctrl+s', function assets() {
         $('#btn_save').trigger('click');
          return false;
      });
   
      $('*').bind('keydown', 'Ctrl+x', function assets() {
         $('#btn_cancel').trigger('click');
          return false;
      });
   
     $('*').bind('keydown', 'Ctrl+d', function assets() {
         $('.btn_save_back').trigger('click');
          return false;
      });
       
   }
   
   jQuery(document).ready(domo);
</script>

<?php $this->load->view('core_template/fine_upload'); ?>

<!-- Main content -->
<section class="content user-edit-page">
   <div class="row" >
      <div class="col-md-12">
         <div class="box box-warning user-edit-shell">
            <div class="box-body ">
               <!-- Widget: user widget style 1 -->
               <div class="box box-widget widget-user-2">
                  <!-- Add the bg color to the header using any of the bg-* classes -->
                  <div class="widget-user-header user-edit-header">
                     <div class="widget-user-image">
                        <img src="<?= BASE_URL . 'uploads/user/' . (!empty($user->avatar) ? $user->avatar : 'default.png'); ?>" alt="Avatar <?= _ent($user->full_name); ?>">
                     </div>
                     <div class="user-edit-heading">
                        <span class="user-edit-eyebrow">Manajemen Pengguna</span>
                        <h3 class="widget-user-username">Edit <?= _ent($user->full_name); ?></h3>
                        <h5 class="widget-user-desc">Perbarui informasi akun, hak akses, avatar, dan kata sandi pengguna.</h5>
                     </div>
                     <a class="user-edit-back" href="<?= base_url('administrator/user'); ?>"><i class="fa fa-arrow-left"></i> Daftar pengguna</a>
                  </div>
                  <?= form_open(base_url('administrator/user/edit_save/'.$this->uri->segment(4)), [
                    'name'    => 'form_user', 
                    'class'   => 'form-horizontal user-edit-form',
                    'id'      => 'form_user', 
                    'enctype' => 'multipart/form-data', 
                    'method'  => 'POST'
                  ]); ?>

                   <div class="user-edit-grid">
                   <div class="form-group ">
                        <label for="username" class="col-sm-2 control-label"><?= cclang('username'); ?> <i class="required">*</i></label>

                        <div class="col-sm-8">
                          <input type="text" class="form-control" name="username" id="username" minlength="3" maxlength="100" pattern="[A-Za-z0-9._-]+" autocomplete="username" required placeholder="Contoh: nama.pengguna" value="<?= set_value('username', $user->username); ?>">
                          <small class="info help-block">Nama unik yang digunakan untuk masuk ke sistem.</small>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="email" class="col-sm-2 control-label"><?= cclang('email'); ?> <i class="required">*</i></label>

                        <div class="col-sm-8">
                          <input type="email" class="form-control" name="email" id="email" maxlength="100" autocomplete="email" required placeholder="nama@contoh.go.id" value="<?= set_value('email', $user->email); ?>">
                          <small class="info help-block">Alamat email aktif pengguna.</small>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="full_name" class="col-sm-2 control-label"><?= cclang('full_name'); ?> <i class="required">*</i></label>

                        <div class="col-sm-8">
                          <input type="text" class="form-control" name="full_name" id="full_name" maxlength="200" required placeholder="Nama lengkap beserta gelar" value="<?= set_value('full_name', $user->full_name); ?>">
                          <small class="info help-block">Nama lengkap yang tampil pada aplikasi.</small>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label for="phone_number" class="col-sm-2 control-label">Nomor Telepon <i class="required">*</i></label>

                        <div class="col-sm-8">
                          <input type="tel" class="form-control" name="phone_number" id="phone_number" inputmode="numeric" minlength="10" maxlength="13" pattern="08[0-9]{8,11}" autocomplete="tel" required placeholder="Contoh: 081234567890" value="<?= _ent(set_value('phone_number', format_phone_number(isset($user->phone_number) ? $user->phone_number : ''))); ?>">
                          <small class="info help-block">Nomor telepon aktif pengguna.</small>
                        </div>
                    </div>
                    <div class="form-group ">
                            <label for="kd_wilayah" class="col-sm-2 control-label">SKPD 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <?php  $a = db_get_all_data('wilayah');
                                
                              ?>
                                <select class="form-control chosen chosen-select-deselect" name="kd_wilayah" id="kd_wilayah" data-placeholder="Pilih wilayah kerja" required>
                                    <option value=""></option>
                                    <?php foreach ($a as $row): ?>
                                    <option <?=  $row->kd_wilayah ==  $user->kd_wilayah ? 'selected' : ''; ?> value="<?= $row->kd_wilayah ?>"><?= $row->nama; ?></option>
                                    <?php endforeach; ?>  
                                </select>
                                <small class="info help-block">Pilih satuan kerja pengguna.</small>
                            </div>
                        </div>
                    <div class="form-group ">
                        <label for="content" class="col-sm-2 control-label"><?= cclang('groups'); ?> <i class="required">*</i></label>

                        <div class="col-sm-8">
                           <select class="form-control chosen-select" name="group[]" id="group" multiple required data-placeholder="Pilih kelompok akses">
                            <?php foreach (get_application_groups() as $row): ?>
                            <option <?= array_search($row->id, $group_user) !== false? 'selected="selected"' : ''; ?> value="<?= $row->id; ?>"  ><?= ucwords($row->name); ?></option>
                            <?php endforeach; ?>  
                           </select>
                            <small class="info help-block">
                             Pilih satu atau beberapa grup hak akses.
                          </small>
                        </div>
                    </div>

                    <div class="form-group user-edit-wide" id="mpd-region-field">
                        <label class="col-sm-2 control-label">Data MPD</label>
                        <div class="col-sm-8">
                            <div class="alert alert-info" style="margin-bottom:0">
                                Identitas, status verifikasi, dan wilayah pengawasan MPD dikelola terpusat melalui menu <strong>SETUP &rarr; Data MPD</strong>. Satu MPD dapat ditugaskan ke beberapa kabupaten/kota.
                            </div>
                        </div>
                    </div>

                    <div class="form-group user-edit-wide user-avatar-field">
                        <label for="username" class="col-sm-2 control-label"><?= cclang('avatar'); ?> </label>

                        <div class="col-sm-8">
                            <div id="user_avatar_galery" src="<?= BASE_URL . 'uploads/user/' . $user->avatar; ?>" ></div>
                            <input name="user_avatar_uuid" id="user_avatar_uuid" type="hidden" value="<?= set_value('user_avatar_uuid'); ?>">
                            <input name="user_avatar_name" id="user_avatar_name" type="hidden" value="<?= set_value('user_avatar_name', $user->avatar); ?>">
                            <small class="info help-block">
                              Format PNG, JPG, JPEG, atau GIF dengan ukuran maksimal 5 MB.
                            </small>
                        </div>
                    </div>
                    <?php is_allowed('user_update_password', function(){?>
                    <div class="form-group user-edit-wide">
                        <label for="password" class="col-sm-2 control-label"><?= cclang('password'); ?> </label>

                        <div class="col-sm-6">
                          <div class="input-group col-md-8 input-password">
                          <input type="password" class="form-control password" name="password" id="password" minlength="8" maxlength="72" autocomplete="new-password" placeholder="Minimal 8 karakter" value="<?= set_value('password'); ?>">
                            <span class="input-group-btn">
                              <button type="button" class="btn btn-flat show-password"><i class="fa fa-eye eye"></i></button>
                            </span>
                          </div>
                           <small class="info help-block">
                            Kosongkan jika tidak ingin mengganti kata sandi. Gunakan 8-72 karakter.
                          </small>
                        </div>
                    </div>
                    <?php }) ?>
                    
                    <div class="message user-edit-message"></div>
                    </div>
                     <div class="user-edit-actions">
                     <span class="loading loading-hide"><img src="<?= BASE_ASSET; ?>/img/loading-spin-primary.svg"> <i><?= cclang('loading_saving_data'); ?></i></span>
                        <button class="btn btn-flat btn-primary btn_save btn_action" id="btn_save" data-stype='stay' title="save (Ctrl+s)"><i class="fa fa-save" ></i> <?= cclang('save_button'); ?></button>
                     <a class="btn btn-flat btn-info btn_save btn_action btn_save_back" id="btn_save_back" data-stype='back' title="<?= cclang('save_and_go_the_list_button'); ?> (Ctrl+d)"><i class="fa fa-list"></i> <?= cclang('save_and_go_the_list_button'); ?></a>
                     <a class="btn btn-flat btn-default btn_action" id="btn_cancel" title="<?= cclang('cancel_button'); ?> (Ctrl+x)"><i class="fa fa-undo" ></i> <?= cclang('cancel_button'); ?></a>
                     </div>
                  <?= form_close(); ?>
               </div>
            </div>
            <!--/box body -->
         </div>
         <!--/box -->
      </div>
   </div>
</section>
<!-- /.content -->
<script src="<?= BASE_ASSET; ?>ckeditor/ckeditor.js"></script>
<!-- Page script -->

<script>
   $(document).ready(function() {
     $('#btn_cancel').click(function() {
         swal({
                 title: "<?= cclang('are_you_sure'); ?>",
                 text: "<?= cclang('data_to_be_deleted_can_not_be_restored'); ?>",
                 type: "warning",
                 showCancelButton: true,
                 confirmButtonColor: "#DD6B55",
                 confirmButtonText: "<?= cclang('yes_delete_it'); ?>",
                 cancelButtonText: "<?= cclang('no_cancel_plx'); ?>",
                 closeOnConfirm: true,
                 closeOnCancel: true
             },
             function(isConfirm) {
                 if (isConfirm) {
                     window.location.href = BASE_URL + 'administrator/user';
                 }
             });

         return false;
     }); /*end btn cancel*/

     $('.btn_save').click(function() {
         $('.message').fadeOut();

         var form_user = $('#form_user');
         var data_post = form_user.serializeArray();
         var save_type = $(this).attr('data-stype');

         data_post.push({
             name: 'save_type',
             value: save_type
         });

         $('.loading').show();

         $.ajax({
                 url: form_user.attr('action'),
                 type: 'POST',
                 dataType: 'json',
                 data: data_post,
             })
             .done(function(res) {
                 if (res.success) {
                     var id = $('#user_avatar_galery').find('li').attr('qq-file-id');
                     $('#user_avatar_uuid').val('');
                     $('#user_avatar_name').val('');

                     if (save_type == 'back') {
                         window.location.href = res.redirect;
                         return;
                     }

                     $('.message').printMessage({
                         message: res.message
                     });
                     $('.message').fadeIn();

                 } else {
                     $('.message').printMessage({
                         message: res.message,
                         type: 'warning'
                     });
                     $('.message').fadeIn();
                 }

             })
             .fail(function() {
                 $('.message').printMessage({
                     message: 'Error save data',
                     type: 'warning'
                 });
             })
             .always(function() {
                 $('.loading').hide();
                 $('html, body').animate({
                     scrollTop: $(document).height()
                 }, 1000);
             });

         return false;
     }); /*end btn save*/

     $('#user_avatar_galery').fineUploader({
         template: 'qq-template-gallery',
         request: {
             endpoint: BASE_URL + 'administrator/user/upload_avatar_file',
             params: {
                 '<?= $this->security->get_csrf_token_name(); ?>': '<?=   $this->security->get_csrf_hash(); ?>'
             }
         },
         deleteFile: {
             enabled: true,
             endpoint: BASE_URL + 'administrator/user/delete_avatar_file',
         },
         thumbnails: {
             placeholders: {
                 waitingPath: BASE_URL + '/asset/fine-upload/placeholders/waiting-generic.png',
                 notAvailablePath: BASE_URL + '/asset/fine-upload/placeholders/not_available-generic.png'
             }
         },
         session: {
             endpoint: BASE_URL + 'administrator/user/get_avatar_file/<?= $user->id; ?>',
             refreshOnRequest: true
         },
         multiple: false,
         validation: {
             allowedExtensions: ['jpeg', 'jpg', 'gif', 'png'],
             sizeLimit: 5 * 1024 * 1024
         },
         showMessage: function(msg) {
             toastr['error'](msg);
         },
         callbacks: {
             onComplete: function(id, name, responseJSON) {
                 if (!responseJSON.success) {
                     $('#user_avatar_uuid').val('');
                     $('#user_avatar_name').val('');
                     return;
                 }
                 var uuid = $('#user_avatar_galery').fineUploader('getUuid', id);
                 $('#user_avatar_uuid').val(uuid);
                 $('#user_avatar_name').val(responseJSON.uploadName || name);
             },
             onSubmit: function(id, name) {
                 var uuid = $('#user_avatar_uuid').val();
                 $.get(BASE_URL + '/administrator/user/delete_image_file/' + uuid);
             }
         }
     }); /*end image galey*/

 }); /*end doc ready*/
</script>
