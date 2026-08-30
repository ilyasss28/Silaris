
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
                            <h3 class="widget-user-username"><b>Laporan Bulanan</b></h3>
                            <h5 class="widget-user-desc">Edit Laporan Bulanan</h5>
                            <hr>
                        </div>
                        <?= form_open(base_url('laporan_bulanan/edit_save/'.$this->uri->segment(3)), [
                            'name'    => 'form_laporan_bulanan', 
                            'class'   => 'form-horizontal', 
                            'id'      => 'form_laporan_bulanan', 
                            'method'  => 'POST'
                            ]); ?>
                         
                         
                                                <div class="form-group ">
                            <label for="tanggal_laporan" class="col-sm-2 control-label">Tanggal Laporan 
                            </label>
                            <div class="col-sm-6">
                            <div class="input-group date col-sm-8">
<input type="date" class="form-control pull-right native-date-input" name="tanggal_laporan" id="tanggal_laporan" value="<?= set_value('laporan_bulanan_tanggal_laporan_name', $laporan_bulanan->tanggal_laporan); ?>">
                            </div>
                            <small class="info help-block">
                            </small>
                            </div>
                        </div>
                       
                                                 
                                                <div class="form-group ">
                            <label for="file_laporan" class="col-sm-2 control-label">File Laporan 
                            </label>
                            <div class="col-sm-8">
                                <div id="laporan_bulanan_file_laporan_galery"></div>
                                <input class="data_file data_file_uuid" name="laporan_bulanan_file_laporan_uuid" id="laporan_bulanan_file_laporan_uuid" type="hidden" value="<?= set_value('laporan_bulanan_file_laporan_uuid'); ?>">
                                <input class="data_file" name="laporan_bulanan_file_laporan_name" id="laporan_bulanan_file_laporan_name" type="hidden" value="<?= set_value('laporan_bulanan_file_laporan_name', $laporan_bulanan->file_laporan); ?>">
                                <small class="info help-block">
                                </small>
                            </div>
                        </div>
                                                  
                                                <div class="form-group ">
                            <label for="kd_wilayah" class="col-sm-2 control-label">Kd Wilayah 
                            </label>
                            <div class="col-sm-8">
                                <select  class="form-control chosen chosen-select-deselect" name="kd_wilayah" id="kd_wilayah" data-placeholder="Select Kd Wilayah" >
                                    <option value=""></option>
                                    <?php foreach (db_get_all_data('wil') as $row): ?>
                                    <option <?=  $row->id ==  $laporan_bulanan->kd_wilayah ? 'selected' : ''; ?> value="<?= $row->id ?>"><?= $row->nama_wilayah; ?></option>
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
              window.location.href = BASE_URL + 'laporan_bulanan';
            }
          });
    
        return false;
      }); /*end btn cancel*/
    
      $('.btn_save').click(function(){
        $('.message').fadeOut();
            
        var form_laporan_bulanan = $('#form_laporan_bulanan');
        var data_post = form_laporan_bulanan.serializeArray();
        var save_type = $(this).attr('data-stype');
        data_post.push({name: 'save_type', value: save_type});
    
        $('.loading').show();
    
        $.ajax({
          url: form_laporan_bulanan.attr('action'),
          type: 'POST',
          dataType: 'json',
          data: data_post,
        })
        .done(function(res) {
          if(res.success) {
            var id = $('#laporan_bulanan_image_galery').find('li').attr('qq-file-id');
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

       $('#laporan_bulanan_file_laporan_galery').fineUploader({
          template: 'qq-template-gallery',
          request: {
              endpoint: BASE_URL + '/laporan_bulanan/upload_file_laporan_file',
              params : params
          },
          deleteFile: {
              enabled: true, // defaults to false
              endpoint: BASE_URL + '/laporan_bulanan/delete_file_laporan_file'
          },
          thumbnails: {
              placeholders: {
                  waitingPath: BASE_URL + '/asset/fine-upload/placeholders/waiting-generic.png',
                  notAvailablePath: BASE_URL + '/asset/fine-upload/placeholders/not_available-generic.png'
              }
          },
           session : {
             endpoint: BASE_URL + 'laporan_bulanan/get_file_laporan_file/<?= $laporan_bulanan->id_laporan_bulanan; ?>',
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
                   var uuid = $('#laporan_bulanan_file_laporan_galery').fineUploader('getUuid', id);
                   $('#laporan_bulanan_file_laporan_uuid').val(uuid);
                   $('#laporan_bulanan_file_laporan_name').val(xhr.uploadName);
                } else {
                   toastr['error'](xhr.error);
                }
              },
              onSubmit : function(id, name) {
                  var uuid = $('#laporan_bulanan_file_laporan_uuid').val();
                  $.get(BASE_URL + '/laporan_bulanan/delete_file_laporan_file/' + uuid);
              },
              onDeleteComplete : function(id, xhr, isError) {
                if (isError == false) {
                  $('#laporan_bulanan_file_laporan_uuid').val('');
                  $('#laporan_bulanan_file_laporan_name').val('');
                }
              }
          }
      }); /*end file_laporan galey*/
              
       
           
    
    }); /*end doc ready*/
</script>
