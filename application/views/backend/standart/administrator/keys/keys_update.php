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
                            <h3 class="widget-user-username">API Keys</h3>
                            <h5 class="widget-user-desc"><?= cclang('edit', 'API Keys'); ?></h5>
                            <hr>
                        </div>
                        <?= form_open(base_url('administrator/keys/edit_save/'.$this->uri->segment(4)), [
                            'name'    => 'form_keys', 
                            'class'   => 'form-horizontal', 
                            'id'      => 'form_keys', 
                            'method'  => 'POST'
                            ]); ?>
                         
                        <div class="form-group ">
                            <label for="key" class="col-sm-2 control-label">Key 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="key" id="key" placeholder="API key" autocomplete="off" value="<?= set_value('key', $keys->key); ?>">
                                    <button type="button" class="btn btn-default" id="copy_key"><i class="fa fa-copy"></i> Salin</button>
                                </div>
                                <small class="info help-block">
                                Mengubah key akan membuat key lama tidak dapat digunakan lagi.</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="level" class="col-sm-2 control-label">Level <i class="required">*</i></label>
                            <div class="col-sm-8">
                                <input type="number" min="0" max="99" class="form-control" name="level" id="level" value="<?= set_value('level', $keys->level); ?>">
                                <small class="info help-block">Level akses API, antara 0 sampai 99.</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Pengaturan</label>
                            <div class="col-sm-8">
                                <label class="checkbox-inline"><input type="checkbox" name="ignore_limits" value="1" <?= set_checkbox('ignore_limits', '1', (bool) $keys->ignore_limits); ?>> Abaikan batas request</label>
                                <label class="checkbox-inline"><input type="checkbox" name="is_private_key" value="1" <?= set_checkbox('is_private_key', '1', (bool) $keys->is_private_key); ?>> Batasi berdasarkan alamat IP</label>
                                <small class="info help-block">Jika pembatasan IP diaktifkan, isi daftar IP yang diizinkan di bawah.</small>
                            </div>
                        </div>
                                            
                        <div class="form-group ">
                            <label for="ip_addresses" class="col-sm-2 control-label">Ip Addresses 
                            </label>
                            <div class="col-sm-8">
                                <textarea id="ip_addresses" name="ip_addresses" rows="5" class="form-control textarea" placeholder="Contoh: 127.0.0.1, 192.168.1.10"><?= set_value('ip_addresses', $keys->ip_addresses); ?></textarea>
                                <small class="info help-block">
                                IP address can access this API.
                                </small>
                            </div>
                        </div>
                                                
                        <div class="message"></div>
                        <div class="row-fluid col-md-7">
                            <button class="btn btn-flat btn-primary btn_save btn_action" id="btn_save" data-stype='stay' title="save (Ctrl+s)"><i class="fa fa-save" ></i> <?= cclang('save_button'); ?></button>
                     <a class="btn btn-flat btn-info btn_save btn_action btn_save_back" id="btn_save_back" data-stype='back' title="<?= cclang('save_and_go_the_list_button'); ?> (Ctrl+d)"><i class="fa fa-list"></i> <?= cclang('save_and_go_the_list_button'); ?></a>
                     <a class="btn btn-flat btn-default btn_action" id="btn_cancel" title="<?= cclang('cancel_button'); ?> (Ctrl+x)"><i class="fa fa-undo" ></i> <?= cclang('cancel_button'); ?></a>
                     <span class="loading loading-hide"><img src="<?= BASE_ASSET; ?>/img/loading-spin-primary.svg"> <i><?= cclang('loading_saving_data'); ?></i></span>
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
  $(document).ready(function() {

    $('#copy_key').on('click', function() {
        var keyInput = document.getElementById('key');
        keyInput.select();
        keyInput.setSelectionRange(0, keyInput.value.length);
        var copied = document.execCommand('copy');
        if (copied) toastr.success('API key berhasil disalin.');
    });

    $('#btn_cancel').click(function() {
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
            function(isConfirm) {
                if (isConfirm) {
                    window.location.href = BASE_URL + 'administrator/keys';
                }
            });

        return false;
    }); /*end btn cancel*/

    $('.btn_save').click(function() {
        $('.message').fadeOut();

        var form_keys = $('#form_keys');
        var data_post = form_keys.serializeArray();
        var save_type = $(this).attr('data-stype');
        data_post.push({
            name: 'save_type',
            value: save_type
        });

        $('.loading').show();

        $.ajax({
                url: form_keys.attr('action'),
                type: 'POST',
                dataType: 'json',
                data: data_post,
            })
            .done(function(res) {
                if (res.success) {
                    var id = $('#keys_image_galery').find('li').attr('qq-file-id');
                    if (save_type == 'back') {
                        window.location.href = res.redirect;
                        return;
                    }

                    $('.message').printMessage({
                        message: res.message
                    });
                    $('.message').fadeIn();
                    $('.data_file_uuid').val('');

                } else {
                    $('.message').printMessage({
                        message: res.message,
                        type: 'warning'
                    });
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
                }, 2000);
            });

        return false;
    }); /*end btn save*/
}); /*end doc ready*/
</script>
