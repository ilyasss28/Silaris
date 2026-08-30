
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
                                <input type="text" class="form-control" name="nama_notaris" id="nama_notaris" placeholder="Nama Notaris" value="<?= set_value('nama_notaris', $data_notaris->nama_notaris); ?>">
                                <small class="info help-block">
                                <b>Input Nama Notaris</b> Max Length : 100.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="tempat_lahir" class="col-sm-2 control-label">Tempat Lahir 
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="tempat_lahir" id="tempat_lahir" placeholder="Tempat Lahir" value="<?= set_value('tempat_lahir', $data_notaris->tempat_lahir); ?>">
                                <small class="info help-block">
                                <b>Input Tempat Lahir</b> Max Length : 100.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="tanggal_lahir" class="col-sm-2 control-label">Tanggal Lahir 
                            </label>
                            <div class="col-sm-6">
                            <div class="input-group date col-sm-8">
<input type="date" class="form-control pull-right native-date-input" name="tanggal_lahir" id="tanggal_lahir" value="<?= set_value('data_notaris_tanggal_lahir_name', $data_notaris->tanggal_lahir); ?>">
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
                                <select  class="form-control chosen chosen-select" name="jenis_kelamin" id="jenis_kelamin" data-placeholder="Select Jenis Kelamin" >
                                    <option value=""></option>
                                    <option <?= $data_notaris->jenis_kelamin == "Laki-Laki" ? 'selected' :''; ?> value="Laki-Laki">Laki-laki</option>
                                    <option <?= $data_notaris->jenis_kelamin == "Perempuan" ? 'selected' :''; ?> value="Perempuan">Perempuan</option>
                                    </select>
                                <small class="info help-block">
                                </small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="email" class="col-sm-2 control-label">Email 
                            </label>
                            <div class="col-sm-8">
                                <input type="email" class="form-control" name="email" id="email" placeholder="Email" value="<?= set_value('email', $data_notaris->email); ?>">
                                <small class="info help-block">
                                <b>Input Email</b> Max Length : 100.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group wrapper-options-crud">
                            <label for="wilayah" class="col-sm-2 control-label">Wilayah 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                    <?php foreach (db_get_all_data('wil') as $row): ?>
                                    <div class="col-md-3 padding-left-0">
                                    <label>
                                    <input <?=  $row->nama_wilayah ==  $data_notaris->wilayah ? 'checked' : ''; ?>  type="radio" class="flat-red" name="wilayah" value="<?= $row->nama_wilayah ?>"> <?= $row->nama_wilayah; ?>
                                    </label>
                                    </div>
                                    <?php endforeach; ?>  
                                </select>
                                <div class="row-fluid clear-both">
                                <small class="info help-block">
                                <b>Input Wilayah</b> Max Length : 100.</small>
                                </div>
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
                            <label for="password" class="col-sm-2 control-label">Password 
                            </label>
                            <div class="col-sm-6">
                              <div class="input-group col-md-8 input-password">
                              <input type="password" class="form-control password" name="password" id="password" placeholder="Password" value="">
                                <span class="input-group-btn">
                                  <button type="button" class="btn btn-flat show-password"><i class="fa fa-eye eye"></i></button>
                                </span>
                              </div>
                            <small class="info help-block">
                            <b>Input Password</b> Max Length : 100.</small>
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
                            <label for="kode_wilayah" class="col-sm-2 control-label">Kode Wilayah 
                            </label>
                            <div class="col-sm-8">
                                <select  class="form-control chosen chosen-select-deselect" name="kode_wilayah" id="kode_wilayah" data-placeholder="Select Kode Wilayah" >
                                    <option value=""></option>
                                    <?php foreach (db_get_all_data('wilayah') as $row): ?>
                                    <option <?=  $row->kd_wilayah ==  $data_notaris->kode_wilayah ? 'selected' : ''; ?> value="<?= $row->kd_wilayah ?>"><?= $row->nama; ?></option>
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
                                <input type="text" class="form-control" name="lat" id="lat" placeholder="Lat" value="<?= set_value('lat', $data_notaris->lat); ?>">
                                <small class="info help-block">
                                </small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="no_telepon" class="col-sm-2 control-label">No Telepon 
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="no_telepon" id="no_telepon" placeholder="No Telepon" value="<?= set_value('no_telepon', $data_notaris->no_telepon); ?>">
                                <small class="info help-block">
                                </small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="long" class="col-sm-2 control-label">Long 
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="long" id="long" placeholder="Long" value="<?= set_value('long', $data_notaris->long); ?>">
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
