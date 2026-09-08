
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
<section class="content fidusia-form-page region-form-page">
  <div class="fidusia-form-shell">
    <header class="fidusia-form-header">
      <div class="fidusia-form-header__copy"><span class="fidusia-form-header__icon"><i class="fa fa-map-marker"></i></span><div><span class="fidusia-form-eyebrow">MASTER WILAYAH</span><h1>Tambah Setup Wilayah</h1><p>Daftarkan kode dan nama kabupaten/kota yang digunakan di seluruh modul SILARIS.</p></div></div>
      <span class="fidusia-form-status"><i class="fa fa-file-o"></i>Data baru</span>
    </header>
    <?= form_open('', ['name' => 'form_wil', 'class' => 'fidusia-form region-form', 'id' => 'form_wil', 'method' => 'POST']); ?>
      <div class="message fidusia-form-message"></div>
      <div class="fidusia-form-grid">
        <section class="fidusia-form-card">
          <div class="fidusia-form-card__heading"><span><i class="fa fa-map-o"></i></span><div><h2>Identitas Wilayah</h2><p>Gunakan kode resmi yang unik dan nama wilayah dengan penulisan yang konsisten.</p></div></div>
          <div class="fidusia-form-fields fidusia-form-fields--document">
            <div class="fidusia-form-field"><label for="kd_wilayah">Kode Wilayah <i class="required">*</i></label><input type="text" class="form-control" name="kd_wilayah" id="kd_wilayah" maxlength="30" required placeholder="Contoh: 7471" value="<?= _ent(set_value('kd_wilayah')); ?>"><small><i class="fa fa-key"></i>Maksimal 30 karakter dan tidak boleh sama dengan wilayah lain.</small></div>
            <div class="fidusia-form-field"><label for="nama_wilayah">Nama Wilayah <i class="required">*</i></label><input type="text" class="form-control" name="nama_wilayah" id="nama_wilayah" maxlength="100" required placeholder="Contoh: Kota Kendari" value="<?= _ent(set_value('nama_wilayah')); ?>"><small><i class="fa fa-font"></i>Maksimal 100 karakter; gunakan nama kabupaten/kota resmi.</small></div>
          </div>
        </section>
      </div>
      <footer class="fidusia-form-actions"><div class="fidusia-form-actions__hint"><i class="fa fa-info-circle"></i><span>Kode wilayah dipakai untuk menyinkronkan notaris, MPD, dashboard, dan laporan.</span></div><div class="fidusia-form-actions__buttons"><a class="btn admin-button admin-button--neutral btn_action" id="btn_cancel"><i class="fa fa-times"></i> Batal</a><button class="btn admin-button admin-button--secondary btn_save btn_action btn_save_back" type="button" data-stype="back"><i class="fa fa-list"></i> Simpan & kembali</button><button class="btn admin-button admin-button--save btn_save btn_action" id="btn_save" type="button" data-stype="stay"><i class="fa fa-save"></i> Simpan Wilayah</button><span class="loading loading-hide"><img src="<?= BASE_ASSET; ?>/img/loading-spin-primary.svg"> <i><?= cclang('loading_saving_data'); ?></i></span></div></footer>
    <?= form_close(); ?>
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
              window.location.href = BASE_URL + 'wil';
            }
          });
    
        return false;
      }); /*end btn cancel*/
    
      $('.btn_save').click(function(){
        $('.message').fadeOut();
            
        var form_wil = $('#form_wil');
        var data_post = form_wil.serializeArray();
        var save_type = $(this).attr('data-stype');

        data_post.push({name: 'save_type', value: save_type});
    
        $('.loading').show();
    
        $.ajax({
          url: BASE_URL + '/wil/add_save',
          type: 'POST',
          dataType: 'json',
          data: data_post,
        })
        .done(function(res) {
          if(res.success) {
            
            if (save_type == 'back') {
              window.location.href = res.redirect;
              return;
            }
    
            $('.message').printMessage({message : res.message});
            $('.message').fadeIn();
            resetForm();
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
      
       
 
       
    
    
    }); /*end doc ready*/
</script>
