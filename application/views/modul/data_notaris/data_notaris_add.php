
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
<section class="content fidusia-form-page data-notaris-modern-form-page data-notaris-create-page">
    <div class="row" >
        <div class="col-md-12">
            <div class="box box-warning">
                <div class="box-body ">
                    <!-- Widget: user widget style 1 -->
                    <div class="box box-widget widget-user-2 fidusia-form-shell">
                        <!-- Add the bg color to the header using any of the bg-* classes -->
                        <header class="fidusia-form-header">
                            <div class="fidusia-form-header__copy"><span class="fidusia-form-header__icon"><i class="fa fa-user-plus"></i></span><div><span class="fidusia-form-eyebrow">MASTER DATA NOTARIS</span><h1>Tambah Data Notaris</h1><p>Lengkapi identitas, kontak, wilayah kerja, dokumen, dan status Notaris.</p></div></div>
                            <span class="fidusia-form-status"><i class="fa fa-file-o"></i>Data baru</span>
                        </header>
                        <?= form_open('', [
                            'name'    => 'form_data_notaris', 
                            'class'   => 'fidusia-form data-notaris-create-form',
                            'id'      => 'form_data_notaris', 
                            'enctype' => 'multipart/form-data', 
                            'method'  => 'POST'
                            ]); ?>
                        <div class="message fidusia-form-message"></div>
                        <div class="fidusia-form-grid">
                          <section class="fidusia-form-card data-notaris-create-card">
                            <div class="fidusia-form-card__heading"><span><i class="fa fa-user"></i></span><div><h2>Informasi Data Notaris</h2><p>Field wajib ditandai dengan tanda bintang merah.</p></div></div>
                            <div class="fidusia-form-fields fidusia-form-fields--document">
                         
                                                <div class="form-group ">
                            <label for="nama_notaris" class="col-sm-2 control-label">Nama Notaris 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="nama_notaris" id="nama_notaris" maxlength="200" required placeholder="Nama lengkap beserta gelar" value="<?= _ent(set_value('nama_notaris')); ?>">
                                <small class="info help-block">
                                Jika akun group User sudah tersedia, gunakan nama yang sama dengan nama lengkap akun.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="tempat_lahir" class="col-sm-2 control-label">Tempat Lahir 
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="tempat_lahir" id="tempat_lahir" maxlength="100" placeholder="Tempat lahir" value="<?= set_value('tempat_lahir'); ?>">
                                <small class="info help-block">
                                Masukkan kabupaten/kota tempat lahir sesuai dokumen identitas; maksimal 100 karakter.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="tanggal_lahir" class="col-sm-2 control-label">Tanggal Lahir 
                            </label>
                            <div class="col-sm-6">
                            <input type="date" class="form-control native-date-input" name="tanggal_lahir" id="tanggal_lahir" max="<?= date('Y-m-d'); ?>">
                            <small class="info help-block">
                            Pilih tanggal lahir sesuai dokumen identitas; tanggal tidak boleh melebihi hari ini.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="jenis_kelamin" class="col-sm-2 control-label">Jenis Kelamin 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <select class="form-control chosen chosen-select" name="jenis_kelamin" id="jenis_kelamin" data-placeholder="Pilih jenis kelamin" required>
                                    <option value=""></option>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                    </select>
                                <small class="info help-block">
                                Pilih jenis kelamin sesuai dokumen identitas Notaris.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="email" class="col-sm-2 control-label">Email <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="email" class="form-control" name="email" id="email" maxlength="150" required placeholder="nama@contoh.com" value="<?= set_value('email'); ?>">
                                <small class="info help-block">
                                Masukkan alamat email aktif dengan format yang valid; maksimal 150 karakter.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="surat_pindah" class="col-sm-2 control-label">Surat Pindah 
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="surat_pindah" id="surat_pindah" placeholder="Surat Pindah" value="<?= set_value('surat_pindah'); ?>">
                                <small class="info help-block">
                                Isi nomor surat pindah apabila Notaris pernah berpindah wilayah kerja.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="surat_keputusan" class="col-sm-2 control-label">Surat Keputusan 
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="surat_keputusan" id="surat_keputusan" placeholder="Surat Keputusan" value="<?= set_value('surat_keputusan'); ?>">
                                <small class="info help-block">
                                Masukkan nomor surat keputusan pengangkatan Notaris sesuai dokumen resmi.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="alamat_rumah" class="col-sm-2 control-label">Alamat Rumah 
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="alamat_rumah" id="alamat_rumah" placeholder="Alamat Rumah" value="<?= set_value('alamat_rumah'); ?>">
                                <small class="info help-block">
                                Masukkan alamat tempat tinggal secara lengkap dan mudah dikenali.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="alamat_kantor" class="col-sm-2 control-label">Alamat Kantor 
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="alamat_kantor" id="alamat_kantor" placeholder="Alamat Kantor" value="<?= set_value('alamat_kantor'); ?>">
                                <small class="info help-block">
                                Masukkan alamat kantor Notaris secara lengkap sesuai kedudukan.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="foto" class="col-sm-2 control-label">Foto 
                            </label>
                            <div class="col-sm-8">
                                <div id="data_notaris_foto_galery"></div>
                                <input class="data_file" name="data_notaris_foto_uuid" id="data_notaris_foto_uuid" type="hidden" value="<?= set_value('data_notaris_foto_uuid'); ?>">
                                <input class="data_file" name="data_notaris_foto_name" id="data_notaris_foto_name" type="hidden" value="<?= set_value('data_notaris_foto_name'); ?>">
                                <small class="info help-block">
                                Gunakan foto profil resmi yang sama dengan foto pada akun pengguna SILARIS.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="kode_wilayah" class="col-sm-2 control-label">Wilayah Kerja <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <select class="form-control chosen chosen-select-deselect" name="kode_wilayah" id="kode_wilayah" data-placeholder="Pilih kabupaten/kota" required>
                                    <option value=""></option>
                                    <?php foreach (db_get_all_data('wilayah') as $row): ?>
                                    <option value="<?= $row->kd_wilayah ?>"><?= '[ '._ent($row->kd_wilayah).' ] '._ent($row->nama); ?></option>
                                    <?php endforeach; ?>  
                                </select>
                                <small class="info help-block">
                                Pilih satu kabupaten/kota yang menjadi wilayah kerja resmi Notaris.</small>
                            </div>
                        </div>

                                                 
                                                <div class="form-group ">
                            <label for="lat" class="col-sm-2 control-label">Lat 
                            </label>
                            <div class="col-sm-8">
                                <input type="number" step="any" min="-90" max="90" class="form-control" name="lat" id="lat" placeholder="Contoh: -3.998" value="<?= set_value('lat'); ?>">
                                <small class="info help-block">
                                Masukkan koordinat lintang kantor antara -90 hingga 90, misalnya -3.998.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="no_telepon" class="col-sm-2 control-label">Nomor Telepon <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="tel" inputmode="numeric" minlength="10" maxlength="13" pattern="08[0-9]{8,11}" class="form-control" name="no_telepon" id="no_telepon" required placeholder="Contoh: 081234567890" value="<?= set_value('no_telepon'); ?>">
                                <small class="info help-block">
                                Gunakan format lokal diawali 08, terdiri dari 10–13 digit tanpa spasi.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="long" class="col-sm-2 control-label">Long 
                            </label>
                            <div class="col-sm-8">
                                <input type="number" step="any" min="-180" max="180" class="form-control" name="long" id="long" placeholder="Contoh: 122.512" value="<?= set_value('long'); ?>">
                                <small class="info help-block">
                                Masukkan koordinat bujur kantor antara -180 hingga 180, misalnya 122.512.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="npwp" class="col-sm-2 control-label">NPWP 
                            </label>
                            <div class="col-sm-8">
                                <input type="text" inputmode="numeric" maxlength="16" pattern="(?:[0-9]{15}|[0-9]{16})" class="form-control" name="npwp" id="npwp" placeholder="15 atau 16 digit" value="<?= set_value('npwp'); ?>">
                                <small class="info help-block">
                                Masukkan NPWP 15 atau 16 digit tanpa titik, spasi, atau tanda hubung.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="nomor_ktp" class="col-sm-2 control-label">Nomor KTP 
                            </label>
                            <div class="col-sm-8">
                                <input type="text" inputmode="numeric" minlength="16" maxlength="16" pattern="[0-9]{16}" class="form-control" name="nomor_ktp" id="nomor_ktp" placeholder="16 digit NIK" value="<?= set_value('nomor_ktp'); ?>">
                                <small class="info help-block">
                                Masukkan NIK sesuai KTP, terdiri dari tepat 16 digit angka.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="nomor_bap" class="col-sm-2 control-label">Nomor BAP 
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="nomor_bap" id="nomor_bap" placeholder="Masukkan nomor BAP" value="<?= set_value('nomor_bap'); ?>">
                                <small class="info help-block">
                                Masukkan nomor Berita Acara Pengambilan Sumpah sesuai dokumen resmi.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="tanggal_bap" class="col-sm-2 control-label">Tanggal BAP 
                            </label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control" name="tanggal_bap" id="tanggal_bap" max="<?= date('Y-m-d'); ?>" value="<?= set_value('tanggal_bap'); ?>">
                                <small class="info help-block">
                                Pilih tanggal Berita Acara Pengambilan Sumpah; tidak boleh melebihi hari ini.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="pemegang_protokol" class="col-sm-2 control-label">Pemegang Protokol 
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="pemegang_protokol" id="pemegang_protokol" placeholder="Pemegang Protokol" value="<?= set_value('pemegang_protokol'); ?>">
                                <small class="info help-block">
                                Isi nama pemegang protokol apabila protokol Notaris telah dialihkan.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="status_notaris" class="col-sm-2 control-label">Status Notaris 
                            </label>
                            <div class="col-sm-8">
                                <select class="form-control chosen chosen-select" name="status_notaris" id="status_notaris" required data-placeholder="Pilih status">
                                    <?php foreach (array('NOTARIS AKTIF', 'NOTARIS NONAKTIF', 'CUTI', 'PINDAH', 'MENINGGAL DUNIA') as $status): ?>
                                    <option value="<?= $status; ?>" <?= set_select('status_notaris', $status, $status === 'NOTARIS AKTIF'); ?>><?= $status; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="info help-block">
                                Pilih status terakhir Notaris sesuai kondisi dan dokumen administratif.</small>
                            </div>
                        </div>
                                                
                            </div>
                          </section>
                        </div>
                        <footer class="fidusia-form-actions">
                          <div class="fidusia-form-actions__hint"><i class="fa fa-info-circle"></i><span>Pastikan identitas dan kode wilayah sesuai data resmi.</span></div>
                          <div class="fidusia-form-actions__buttons">
                            <a class="btn admin-button admin-button--neutral btn_action" id="btn_cancel"><i class="fa fa-times"></i> Batal</a>
                            <button type="button" class="btn admin-button admin-button--secondary btn_save btn_action btn_save_back" data-stype="back"><i class="fa fa-list"></i> Simpan & kembali</button>
                            <button type="button" class="btn admin-button admin-button--save btn_save btn_action" id="btn_save" data-stype="stay"><i class="fa fa-save"></i> Simpan Data Notaris</button>
                            <span class="loading loading-hide"><img src="<?= BASE_ASSET; ?>/img/loading-spin-primary.svg"><i><?= cclang('loading_saving_data'); ?></i></span>
                          </div>
                        </footer>
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
            title: "<?= cclang('are_you_sure'); ?>",
            text: "<?= cclang('data_to_be_deleted_can_not_be_restored'); ?>",
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
          url: BASE_URL + '/data_notaris/add_save',
          type: 'POST',
          dataType: 'json',
          data: data_post,
        })
        .done(function(res) {
          if(res.success) {
            var id_foto = $('#data_notaris_foto_galery').find('li').attr('qq-file-id');
            
            if (save_type == 'back') {
              window.location.href = res.redirect;
              return;
            }
    
            $('.message').printMessage({message : res.message});
            $('.message').fadeIn();
            resetForm();
            if (typeof id_foto !== 'undefined') {
                    $('#data_notaris_foto_galery').fineUploader('deleteFile', id_foto);
                }
            $('.chosen option').prop('selected', false).trigger('chosen:updated');
                
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
              enabled: true, 
              endpoint: BASE_URL + '/data_notaris/delete_foto_file',
          },
          thumbnails: {
              placeholders: {
                  waitingPath: BASE_URL + '/asset/fine-upload/placeholders/waiting-generic.png',
                  notAvailablePath: BASE_URL + '/asset/fine-upload/placeholders/not_available-generic.png'
              }
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
      }); /*end foto galery*/
              
 
       
    
    
    }); /*end doc ready*/
</script>
