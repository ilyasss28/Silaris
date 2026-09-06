
<!-- Fine Uploader Gallery CSS file
    ====================================================================== -->
<link href="<?= BASE_ASSET; ?>/fine-upload/fine-uploader-gallery.min.css" rel="stylesheet">
<!-- Fine Uploader jQuery JS file
    ====================================================================== -->
<script src="<?= BASE_ASSET; ?>/fine-upload/jquery.fine-uploader.js"></script>
<?php $this->load->view('core_template/fine_upload'); ?>
<script src="<?= BASE_ASSET; ?>/js/jquery.hotkeys.js"></script>
<script type="text/javascript">
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

<!-- Main content -->
<section class="content">
    <div class="row" >
        <div class="col-md-12">
            <div class="box box-warning">
                <div class="box-body ">
                    <!-- Widget: user widget style 1 -->
                    <div class="box box-widget widget-user-2">
                        <!-- Add the bg color to the header using any of the bg-* classes -->
                        <div class="widget-user-header ">
                            <div class="widget-user-image">
                                <img class="img-circle" src="<?= BASE_ASSET; ?>/img/add2.png" alt="User Avatar">
                            </div>
                            <!-- /.widget-user-image -->
                            <h3 class="widget-user-username"><b>Data Notaris</b></h3>
                            <h5 class="widget-user-desc">Edit Data Notaris</h5>
                            <hr>
                        </div>
                        <?= form_open(base_url('data_notaris/edit_save/'.$this->uri->segment(3)), [
                            'name'    => 'form_data_notaris', 
                            'class'   => 'form-horizontal', 
                            'id'      => 'form_data_notaris', 
                            'method'  => 'POST'
                            ]); ?>
                         
                                                <div class="form-group ">
                            <label for="nama_notaris" class="col-sm-2 control-label">Nama Notaris 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="nama_notaris" id="nama_notaris" maxlength="200" required placeholder="Nama lengkap beserta gelar" value="<?= _ent(set_value('nama_notaris', $data_notaris->nama_notaris)); ?>" <?= !empty($data_notaris->account_user_id) ? 'readonly aria-readonly="true"' : ''; ?>>
                                <small class="info help-block">
                                <?= !empty($data_notaris->account_user_id) ? 'Nama mengikuti nama lengkap akun SILARIS dengan group User.' : 'Belum terhubung ke akun; gunakan nama lengkap beserta gelar.'; ?></small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="tempat_lahir" class="col-sm-2 control-label">Tempat Lahir 
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="tempat_lahir" id="tempat_lahir" maxlength="100" placeholder="Tempat lahir" value="<?= set_value('tempat_lahir', $data_notaris->tempat_lahir); ?>">
                                <small class="info help-block">
                                <b>Input Tempat Lahir</b> Max Length : 100.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="tanggal_lahir" class="col-sm-2 control-label">Tanggal Lahir 
                            </label>
                            <div class="col-sm-6">
                            <div class="input-group date col-sm-8">
<input type="date" class="form-control pull-right native-date-input" name="tanggal_lahir" id="tanggal_lahir" max="<?= date('Y-m-d'); ?>" value="<?= set_value('tanggal_lahir', $data_notaris->tanggal_lahir); ?>">
                            </div>
                            <small class="info help-block">
                            </small>
                            </div>
                        </div>
                       
                                                 
                                                <div class="form-group ">
                            <label for="jenis_kelamin" class="col-sm-2 control-label">Jenis Kelamin 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <select class="form-control chosen chosen-select" name="jenis_kelamin" id="jenis_kelamin" data-placeholder="Pilih jenis kelamin" required>
                                    <option value=""></option>
                                    <option <?= $data_notaris->jenis_kelamin == "Laki-laki" ? 'selected' :''; ?> value="Laki-laki">Laki-laki</option>
                                    <option <?= $data_notaris->jenis_kelamin == "Perempuan" ? 'selected' :''; ?> value="Perempuan">Perempuan</option>
                                    </select>
                                <small class="info help-block">
                                </small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="email" class="col-sm-2 control-label">Email <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="email" class="form-control" name="email" id="email" maxlength="150" required placeholder="nama@contoh.com" value="<?= set_value('email', $data_notaris->email); ?>">
                                <small class="info help-block">
                                <b>Input Email</b> Max Length : 100.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="surat_pindah" class="col-sm-2 control-label">Surat Pindah 
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="surat_pindah" id="surat_pindah" placeholder="Surat Pindah" value="<?= set_value('surat_pindah', $data_notaris->surat_pindah); ?>">
                                <small class="info help-block">
                                <b>Input Surat Pindah</b> Max Length : 100.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="surat_keputusan" class="col-sm-2 control-label">Surat Keputusan 
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="surat_keputusan" id="surat_keputusan" placeholder="Surat Keputusan" value="<?= set_value('surat_keputusan', $data_notaris->surat_keputusan); ?>">
                                <small class="info help-block">
                                <b>Input Surat Keputusan</b> Max Length : 100.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="alamat_rumah" class="col-sm-2 control-label">Alamat Rumah 
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="alamat_rumah" id="alamat_rumah" placeholder="Alamat Rumah" value="<?= set_value('alamat_rumah', $data_notaris->alamat_rumah); ?>">
                                <small class="info help-block">
                                <b>Input Alamat Rumah</b> Max Length : 100.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="alamat_kantor" class="col-sm-2 control-label">Alamat Kantor 
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="alamat_kantor" id="alamat_kantor" placeholder="Alamat Kantor" value="<?= set_value('alamat_kantor', $data_notaris->alamat_kantor); ?>">
                                <small class="info help-block">
                                <b>Input Alamat Kantor</b> Max Length : 100.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="foto" class="col-sm-2 control-label">Foto 
                            </label>
                            <div class="col-sm-8">
                                <div id="data_notaris_foto_galery"></div>
                                <input class="data_file data_file_uuid" name="data_notaris_foto_uuid" id="data_notaris_foto_uuid" type="hidden" value="<?= set_value('data_notaris_foto_uuid'); ?>">
                                <input class="data_file" name="data_notaris_foto_name" id="data_notaris_foto_name" type="hidden" value="<?= set_value('data_notaris_foto_name', $data_notaris->foto); ?>">
                                <small class="info help-block">
                                </small>
                            </div>
                        </div>
                                                  
                                                <div class="form-group ">
                            <label for="kode_wilayah" class="col-sm-2 control-label">Wilayah Kerja <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <select class="form-control chosen chosen-select-deselect" name="kode_wilayah" id="kode_wilayah" data-placeholder="Pilih kabupaten/kota" required>
                                    <option value=""></option>
                                    <?php foreach (db_get_all_data('wilayah') as $row): ?>
                                    <option <?=  $row->kd_wilayah ==  $data_notaris->kode_wilayah ? 'selected' : ''; ?> value="<?= $row->kd_wilayah ?>"><?= '[ '._ent($row->kd_wilayah).' ] '._ent($row->nama); ?></option>
                                    <?php endforeach; ?>  
                                </select>
                                <small class="info help-block">
                                </small>
                            </div>
                        </div>

                                                 
                                                <div class="form-group ">
                            <label for="lat" class="col-sm-2 control-label">Lat 
                            </label>
                            <div class="col-sm-8">
                                <input type="number" step="any" min="-90" max="90" class="form-control" name="lat" id="lat" placeholder="Contoh: -3.998" value="<?= set_value('lat', $data_notaris->lat); ?>">
                                <small class="info help-block">
                                </small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="no_telepon" class="col-sm-2 control-label">Nomor Telepon <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="tel" inputmode="numeric" minlength="10" maxlength="13" pattern="08[0-9]{8,11}" class="form-control" name="no_telepon" id="no_telepon" required placeholder="Contoh: 081234567890" value="<?= set_value('no_telepon', format_phone_number($data_notaris->no_telepon)); ?>">
                                <small class="info help-block">
                                </small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="long" class="col-sm-2 control-label">Long 
                            </label>
                            <div class="col-sm-8">
                                <input type="number" step="any" min="-180" max="180" class="form-control" name="long" id="long" placeholder="Contoh: 122.512" value="<?= set_value('long', $data_notaris->long); ?>">
                                <small class="info help-block">
                                </small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="npwp" class="col-sm-2 control-label">NPWP 
                            </label>
                            <div class="col-sm-8">
                                <input type="text" inputmode="numeric" maxlength="16" pattern="(?:[0-9]{15}|[0-9]{16})" class="form-control" name="npwp" id="npwp" placeholder="15 atau 16 digit" value="<?= set_value('npwp', preg_replace('/\D+/', '', (string) $data_notaris->npwp)); ?>">
                                <small class="info help-block">
                                </small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="nomor_ktp" class="col-sm-2 control-label">Nomor KTP 
                            </label>
                            <div class="col-sm-8">
                                <input type="text" inputmode="numeric" minlength="16" maxlength="16" pattern="[0-9]{16}" class="form-control" name="nomor_ktp" id="nomor_ktp" placeholder="16 digit NIK" value="<?= set_value('nomor_ktp', preg_replace('/\D+/', '', (string) $data_notaris->nomor_ktp)); ?>">
                                <small class="info help-block">
                                </small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="nomor_bap" class="col-sm-2 control-label">Nomor BAP 
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="nomor_bap" id="nomor_bap" placeholder="Masukkan nomor BAP" value="<?= set_value('nomor_bap', $data_notaris->nomor_bap); ?>">
                                <small class="info help-block">
                                </small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="tanggal_bap" class="col-sm-2 control-label">Tanggal BAP 
                            </label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control" name="tanggal_bap" id="tanggal_bap" max="<?= date('Y-m-d'); ?>" value="<?= set_value('tanggal_bap', $data_notaris->tanggal_bap); ?>">
                                <small class="info help-block">
                                </small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="pemegang_protokol" class="col-sm-2 control-label">Pemegang Protokol 
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="pemegang_protokol" id="pemegang_protokol" placeholder="Pemegang Protokol" value="<?= set_value('pemegang_protokol', $data_notaris->pemegang_protokol); ?>">
                                <small class="info help-block">
                                </small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="status_notaris" class="col-sm-2 control-label">Status Notaris 
                            </label>
                            <div class="col-sm-8">
                                <select class="form-control chosen chosen-select" name="status_notaris" id="status_notaris" required data-placeholder="Pilih status">
                                    <?php foreach (array('NOTARIS AKTIF', 'NOTARIS NONAKTIF', 'CUTI', 'PINDAH', 'MENINGGAL DUNIA') as $status): ?>
                                    <option value="<?= $status; ?>" <?= set_select('status_notaris', $status, $data_notaris->status_notaris === $status); ?>><?= $status; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="info help-block">
                                </small>
                            </div>
                        </div>
                                                
                        <div class="message"></div>
                        <div class="row-fluid col-md-7">
                            <button class="btn btn-flat btn-primary btn_save btn_action" id="btn_save" data-stype='stay' title="<?= cclang('save_button'); ?> (Ctrl+s)">
                            <i class="fa fa-save" ></i> <?= cclang('save_button'); ?>
                            </button>
                            <a class="btn btn-flat btn-info btn_save btn_action btn_save_back" id="btn_save" data-stype='back' title="<?= cclang('save_and_go_the_list_button'); ?> (Ctrl+d)">
                            <i class="fa fa-list"></i> <?= cclang('save_and_go_the_list_button'); ?>
                            </a>
                            <a class="btn btn-flat btn-default btn_action" id="btn_cancel" title="<?= cclang('cancel_button'); ?> (Ctrl+x)">
                            <i class="fa fa-undo" ></i> <?= cclang('cancel_button'); ?>
                            </a>
                            <span class="loading loading-hide">
                            <img src="<?= BASE_ASSET; ?>/img/loading-spin-primary.svg"> 
                            <i><?= cclang('loading_saving_data'); ?></i>
                            </span>
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
<!-- Page script -->
<script>
    $(document).ready(function(){
      
             
      $('#btn_cancel').click(function(){
        swal({
            title: "Are you sure?",
            text: "the data that you have created will be in the exhaust!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes!",
            cancelButtonText: "No!",
            closeOnConfirm: true,
            closeOnCancel: true
          },
          function(isConfirm){
            if (isConfirm) {
              window.location.href = BASE_URL + 'data_notaris';
            }
          });
    
        return false;
      }); /*end btn cancel*/
    
      $('.btn_save').click(function(){
        $('.message').fadeOut();
            
        var form_data_notaris = $('#form_data_notaris');
        var data_post = form_data_notaris.serializeArray();
        var save_type = $(this).attr('data-stype');
        data_post.push({name: 'save_type', value: save_type});
    
        $('.loading').show();
    
        $.ajax({
          url: form_data_notaris.attr('action'),
          type: 'POST',
          dataType: 'json',
          data: data_post,
        })
        .done(function(res) {
          if(res.success) {
            var id = $('#data_notaris_image_galery').find('li').attr('qq-file-id');
            if (save_type == 'back') {
              window.location.href = res.redirect;
              return;
            }
    
            $('.message').printMessage({message : res.message});
            $('.message').fadeIn();
            $('.data_file_uuid').val('');
    
          } else {
            $('.message').printMessage({message : res.message, type : 'warning'});
          }
    
        })
        .fail(function() {
          $('.message').printMessage({message : 'Error save data', type : 'warning'});
        })
        .always(function() {
          $('.loading').hide();
          $('html, body').animate({ scrollTop: $(document).height() }, 2000);
        });
    
        return false;
      }); /*end btn save*/
      
                     var params = {};
       params[csrf] = token;

       $('#data_notaris_foto_galery').fineUploader({
          template: 'qq-template-gallery',
          request: {
              endpoint: BASE_URL + '/data_notaris/upload_foto_file',
              params : params
          },
          deleteFile: {
              enabled: true, // defaults to false
              endpoint: BASE_URL + '/data_notaris/delete_foto_file'
          },
          thumbnails: {
              placeholders: {
                  waitingPath: BASE_URL + '/asset/fine-upload/placeholders/waiting-generic.png',
                  notAvailablePath: BASE_URL + '/asset/fine-upload/placeholders/not_available-generic.png'
              }
          },
           session : {
             endpoint: BASE_URL + 'data_notaris/get_foto_file/<?= $data_notaris->id_notaris; ?>',
             refreshOnRequest:true
           },
          multiple : false,
          validation: {
              allowedExtensions: ["*"],
              sizeLimit : 0,
                        },
          showMessage: function(msg) {
              toastr['error'](msg);
          },
          callbacks: {
              onComplete : function(id, name, xhr) {
                if (xhr.success) {
                   var uuid = $('#data_notaris_foto_galery').fineUploader('getUuid', id);
                   $('#data_notaris_foto_uuid').val(uuid);
                   $('#data_notaris_foto_name').val(xhr.uploadName);
                } else {
                   toastr['error'](xhr.error);
                }
              },
              onSubmit : function(id, name) {
                  var uuid = $('#data_notaris_foto_uuid').val();
                  $.get(BASE_URL + '/data_notaris/delete_foto_file/' + uuid);
              },
              onDeleteComplete : function(id, xhr, isError) {
                if (isError == false) {
                  $('#data_notaris_foto_uuid').val('');
                  $('#data_notaris_foto_name').val('');
                }
              }
          }
      }); /*end foto galey*/
              
       
           
    
    }); /*end doc ready*/
</script>
