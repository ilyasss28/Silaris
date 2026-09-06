<link rel="stylesheet" type="text/css" href="<?= BASE_ASSET; ?>css/crud.css">
<script src="<?= BASE_ASSET; ?>/js/jquery.hotkeys.js"></script>
<section class="content crud-builder-page crud-builder-page--edit">
   <div class="crud-builder-shell">
      <header class="crud-builder-header">
         <div><span class="crud-builder-eyebrow">CRUD BUILDER</span><h1>Edit <?= _ent($crud->subject); ?></h1><p>Perbarui struktur, fitur halaman, dan aturan field modul.</p></div>
         <div class="crud-builder-header-actions">
            <a href="<?= site_url('administrator/crud/view/' . (int) $crud->id); ?>" class="btn btn-light crud-builder-back"><i class="fa fa-eye"></i><span>Lihat Detail</span></a>
            <a href="<?= site_url('administrator/crud'); ?>" class="btn btn-light crud-builder-back"><i class="fa fa-arrow-left"></i><span>Kembali</span></a>
         </div>
      </header>
      <?= form_open(site_url('administrator/crud/edit_save/' . (int) $crud->id), [
                     'name'    => 'form_crud',
                     'class'   => 'crud-builder-form',
                     'id'      => 'form_crud',
                     'method'  => 'POST'
                     ]); ?>

         <?php $custom_module_protected = is_file(FCPATH . 'application/views/modul/' . $crud->table_name . '/.tmc-preserve'); ?>
         <?php if ($custom_module_protected): ?>
         <div class="alert alert-warning" role="status" style="margin:0 0 18px">
            <i class="fa fa-shield"></i>
            <strong>Modul dengan tampilan khusus.</strong>
            Konfigurasi field tetap disimpan, tetapi TMC CRUD tidak akan menimpa view, controller, atau model. Penambahan field ke halaman modul ini perlu diselaraskan pada kode khususnya.
         </div>
         <?php endif; ?>

         <input type="hidden" name="table_name" id="table_name" value="<?= _ent($crud->table_name); ?>">
         <input type="hidden" class="primary_key" name="primary_key" id="primary_key" value="<?= _ent($crud->primary_key); ?>">

         <div class="crud-builder-section">
            <div class="crud-builder-section-title"><span class="crud-builder-step">1</span><div><h2>Informasi modul</h2><p>Nama tabel dikunci untuk mencegah konfigurasi berpindah ke modul lain.</p></div></div>
            <div class="row g-3">
               <div class="col-12 col-lg-6"><label class="form-label">Nama tabel</label><div class="crud-builder-readonly"><i class="fa fa-database"></i><code><?= _ent($crud->table_name); ?></code></div></div>
               <div class="col-12 col-lg-6"><label class="form-label">Primary key</label><div class="crud-builder-readonly"><i class="fa fa-key"></i><code><?= _ent($crud->primary_key); ?></code></div></div>
               <div class="col-12 col-lg-6"><label for="subject" class="form-label">Nama modul <span class="required">*</span></label><input type="text" class="form-control" name="subject" id="subject" placeholder="Contoh: Data Pegawai" value="<?= set_value('subject', $crud->subject); ?>" required><div class="form-text">Nama yang digunakan pada menu dan pesan aplikasi.</div></div>
               <div class="col-12 col-lg-6"><label for="title" class="form-label">Judul halaman</label><input type="text" class="form-control" name="title" id="title" placeholder="Kosongkan untuk menggunakan nama modul" value="<?= set_value('title', $crud->title); ?>"></div>
            </div>
         </div>

         <div class="crud-builder-section">
            <div class="crud-builder-section-title"><span class="crud-builder-step">2</span><div><h2>Fitur halaman</h2><p>Pilih halaman yang akan tersedia pada modul hasil generator.</p></div></div>
            <div class="crud-builder-options">
               <label class="crud-builder-option" for="create"><input class="form-check-input check page_create" type="checkbox" id="create" value="yes" name="create" <?= $crud->page_create == 'yes' ? 'checked' : ''; ?>><span><strong>Tambah data</strong><small>Form untuk membuat data baru.</small></span></label>
               <label class="crud-builder-option" for="read"><input class="form-check-input check page_read" type="checkbox" id="read" value="yes" name="read" <?= $crud->page_read == 'yes' ? 'checked' : ''; ?>><span><strong>Detail data</strong><small>Halaman untuk membaca detail data.</small></span></label>
               <label class="crud-builder-option" for="update"><input class="form-check-input check page_update" type="checkbox" id="update" value="yes" name="update" <?= $crud->page_update == 'yes' ? 'checked' : ''; ?>><span><strong>Ubah data</strong><small>Form untuk memperbarui data.</small></span></label>
            </div>
         </div>

         <div class="crud-builder-section crud-builder-fields">
            <div class="crud-builder-section-title"><span class="crud-builder-step">3</span><div><h2>Konfigurasi field</h2><p>Geser urutan field dan atur label, visibilitas, tipe input, relasi, serta validasinya.</p></div></div>
            <div class="crud-builder-field-note"><i class="fa fa-arrows-v"></i><span><strong>Atur field modul</strong> Geser ikon pada kolom Urut, ubah label field, lalu tentukan halaman tempat field ditampilkan.</span></div>
            <div class="wrapper-crud">
                   <table class="table table-bordered table-striped" id="diagnosis_list">
                     <colgroup>
                        <col class="crud-col-sort">
                        <col class="crud-col-field">
                        <col class="crud-col-visibility">
                        <col class="crud-col-visibility">
                        <col class="crud-col-visibility">
                        <col class="crud-col-visibility">
                        <col class="crud-col-input">
                        <col class="crud-col-validation">
                     </colgroup>
                     <thead>
                        <tr>
                           <th rowspan="2" class="crud-heading-sort">Urut</th>
                           <th rowspan="2">Nama Field</th>
                           <th colspan="4" class="crud-heading-visibility">Ditampilkan pada</th>
                           <th rowspan="2">Jenis Masukan</th>
                           <th rowspan="2">Aturan Validasi</th>
                        </tr>
                        <tr>
                           <th class="module-page-list column" title="Tampilkan pada tabel daftar">Daftar</th>
                           <th class="module-page-add add_form" title="Tampilkan pada form tambah">Tambah</th>
                           <th class="module-page-update update_form" title="Tampilkan pada form edit">Edit</th>
                           <th class="detail_page" title="Tampilkan pada halaman detail">Detail</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php $i=0; foreach ($crud_field as $row):  $i++; ?>
                        <tr <?= isset($row->new_field) ? 'class="new-field"' : ''; ?>>
                           <td  class="dragable">
                              <i class="fa fa-bars fa-lg text-muted"></i>
                              <input type="hidden" name="crud[<?=$i; ?>][<?=$row->field_name; ?>][sort]" class="priority" value="<?= $i; ?>" >
                              <input type="hidden" class="crud-id" value="<?= $i; ?>">
                              <input type="hidden" class="crud-name" value="<?= _ent($row->field_name); ?>">
                           </td>
                           <td class="crud-field-cell">
                              <div class="crud-field-toolbar">
                                 <?php if ($row->field_name === $crud->primary_key): ?>
                                 <span class="crud-primary-field" title="Primary key wajib dipertahankan"><i class="fa fa-key"></i> Primary key</span>
                                 <?php elseif (isset($row->new_field)): ?>
                                 <span class="crud-new-field"><i class="fa fa-info-circle"></i> Field baru</span>
                                 <?php else: ?>
                                 <span class="crud-field-label">Label tampilan</span>
                                 <?php endif; ?>

                                 <?php if ($row->field_name !== $crud->primary_key): ?>
                                 <button type="button" class="fa fa-trash text-danger btn-remove-field" title="Keluarkan field dari konfigurasi" aria-label="Keluarkan field <?= _ent($row->field_name); ?>"></button>
                                 <?php endif; ?>
                              </div>
                              <input type="text" class="crud-input-initial" name="crud[<?=$i; ?>][<?=$row->field_name; ?>][label]" placeholder="<?= _ent($row->field_name); ?>" value="<?= _ent($row->field_label); ?>">
                              <small class="crud-database-field"><i class="fa fa-database"></i><code><?= _ent($row->field_name); ?></code></small>
                           </td>
                           <td class="column">
                              <input class="flat-red check" type="checkbox" <?= $row->show_column == 'yes' ? 'checked' : ''; ?> name="crud[<?=$i; ?>][<?=$row->field_name; ?>][show_in_column]" value="yes" aria-label="Tampilkan <?= _ent($row->field_name); ?> pada daftar">
                           </td>
                           <td class="add_form">
                              <input class="flat-red check" type="checkbox" <?= $row->show_add_form == 'yes' ? 'checked' : ''; ?> name="crud[<?=$i; ?>][<?=$row->field_name; ?>][show_in_add_form]" value="yes" aria-label="Tampilkan <?= _ent($row->field_name); ?> pada form tambah">
                           </td>
                           <td class="update_form">
                              <input class="flat-red check" type="checkbox" <?= $row->show_update_form == 'yes' ? 'checked' : ''; ?> name="crud[<?=$i; ?>][<?=$row->field_name; ?>][show_in_update_form]" value="yes" aria-label="Tampilkan <?= _ent($row->field_name); ?> pada form edit">
                           </td>
                           <td class="detail_page">
                              <input class="flat-red check" type="checkbox" <?= $row->show_detail_page == 'yes' ? 'checked' : ''; ?> name="crud[<?=$i; ?>][<?=$row->field_name; ?>][show_in_detail_page]" value="yes" aria-label="Tampilkan <?= _ent($row->field_name); ?> pada halaman detail">
                           </td>
                           <td>
                              <div class="col-md-12">
                                 <div class="form-group ">
                                    <select class="form-control chosen chosen-select input_type" name="crud[<?=$i; ?>][<?=$row->field_name; ?>][input_type]" data-placeholder="Pilih tipe input">
                                       <option value="" class="<?= $this->model_crud->get_input_type(); ?>"></option>
                                       <?php foreach (db_get_all_data('crud_input_type') as $input):  ?>
                                       <option  value="<?= $input->type; ?>" class="<?= $input->type; ?>" title="<?= $input->validation_group; ?>" relation="<?= $input->relation; ?>" custom-value="<?= $input->custom_value; ?>" <?= $input->type == $row->input_type ? 'selected="selected"' : ''; ?> ><?= ucwords(clean_snake_case($input->type)); ?></option>
                                       <?php endforeach; ?>
                                    </select>
                                 </div>
                              </div>


                              <?php if (isset($crud_field_option[$row->id])): ?>
                              <div class="custom-option-container ">
                                 <div class="custom-option-contain">
                              <?php 
                              $j = 0; 
                              foreach ($crud_field_option[$row->id] as $option) {
                                 $j++;
                              ?>
                                    <div class="custom-option-item">
                                       <div class="box-custom-option padding-left-0 box-top"> 
                                          <div class="col-md-3"><?= cclang('value'); ?></div>  <input type="text" name="crud[<?=$i; ?>][<?= $row->field_name; ?>][custom_option][<?= $j; ?>][value]" value="<?= _ent($option->option_value); ?>">
                                       </div>
                                       <div class="box-custom-option padding-left-0 box-bottom"> 
                                          <div class="col-md-3"><?= cclang('label'); ?></div>  <input type="text" name="crud[<?=$i; ?>][<?= $row->field_name; ?>][custom_option][<?= $j; ?>][label]" value="<?= _ent($option->option_label); ?>">
                                       </div>
                                       <a class="text-red delete-option fa fa-trash" data-original-title="" title=""></a> 
                                    </div>
                              <?php 
                              }
                               ?>
                                 </div>
                               <a class="box-custom-option input btn btn-flat btn-block bg-black  add-option"> 
                               <i class="fa fa-plus-circle"></i> <?= cclang('add_new_option'); ?>
                              </a>
                              </div>
                              <?php else: ?>
                              <div class="custom-option-container display-none">
                                 <div class="custom-option-contain">
                                    <div class="custom-option-item">
                                       <div class="box-custom-option padding-left-0 box-top"> 
                                          <div class="col-md-3"><?= cclang('value'); ?></div>  <input type="text" name="crud[<?=$i; ?>][<?= $row->field_name; ?>][custom_option][0][value]">
                                       </div>
                                       <div class="box-custom-option padding-left-0 box-bottom"> 
                                          <div class="col-md-3"><?= cclang('label'); ?></div>  <input type="text" name="crud[<?=$i; ?>][<?= $row->field_name; ?>][custom_option][0][label]">
                                       </div>
                                       <a class="text-red delete-option fa fa-trash" data-original-title="" title=""></a> 
                                    </div>
                                 </div>
                                  <a class="box-custom-option input btn btn-flat btn-block bg-black  add-option"> 
                                  <i class="fa fa-plus-circle"></i> <?= cclang('add_new_option'); ?>
                                 </a>
                              </div>
                              <?php endif; ?>

                              <?php if (!empty($row->relation_table)): ?>
                              <div class="col-md-12" style="margin:0px !important">
                                 <div class="form-group" >
                                    <label><small class="text-muted"><?= cclang('table_reff'); ?></small></label>
                                    <select class="form-control chosen chosen-select relation_table relation_field" name="crud[<?=$i; ?>][<?=$row->field_name; ?>][relation_table]" data-placeholder="Pilih tabel relasi">
                                       <option value=""></option>
                                        <?php foreach ($this->db->list_tables() as $table): ?>
                                       <option <?= $row->relation_table == $table ? 'selected' : ''; ?> value="<?= _ent($table); ?>"><?= _ent($table); ?></option>
                                       <?php endforeach; ?>  
                                    </select>
                                 </div>
                              </div>
                              <div class="col-md-12" style="margin:0px !important">
                                 <div class="form-group ">
                                    <label><small class="text-muted"><?= cclang('value_field_reff'); ?></small></label>
                                    <select class="form-control chosen chosen-select relation_value relation_field" name="crud[<?=$i; ?>][<?=$row->field_name; ?>][relation_value]" data-placeholder="Pilih field nilai">
                                       <option value=""></option>
                                       <?php foreach ($this->db->list_fields($row->relation_table) as $field){ ?>
                                       <option <?= $row->relation_value == $field ? 'selected' : ''; ?> value="<?= _ent($field); ?>"><?= _ent(ucwords($field)); ?></option>
                                       <?php } ?>
                                    </select>
                                 </div>
                              </div>
                              <div class="col-md-12" style="margin:0px !important">
                                 <div class="form-group ">
                                    <label><small class="text-muted"><?= cclang('label_field_reff'); ?></small></label>
                                    <select class="form-control chosen chosen-select relation_label relation_field" name="crud[<?=$i; ?>][<?=$row->field_name; ?>][relation_label]" data-placeholder="Pilih field label">
                                       <option value=""></option>
                                       <?php foreach ($this->db->list_fields($row->relation_table) as $field){ ?>
                                       <option <?= $row->relation_label == $field ? 'selected' : ''; ?> value="<?= _ent($field); ?>"><?= _ent(ucwords($field)); ?></option>
                                       <?php } ?>
                                    </select>
                                 </div>
                              </div>
                              <?php else : ?>
                                 <div class="col-md-12" style="margin:0px !important">
                                 <div class="form-group display-none ">
                                    <label><small class="text-muted"><?= cclang('table_reff'); ?></small></label>
                                    <select class="form-control chosen chosen-select relation_table relation_field" name="crud[<?=$i; ?>][<?=$row->field_name; ?>][relation_table]" data-placeholder="Pilih tabel relasi">
                                       <option value=""></option>
                                        <?php foreach ($this->db->list_tables() as $table): ?>
                                       <option value="<?= _ent($table); ?>"><?= _ent($table); ?></option>
                                       <?php endforeach; ?>  
                                    </select>
                                 </div>
                              </div>
                              <div class="col-md-12" style="margin:0px !important">
                                 <div class="form-group display-none ">
                                    <label><small class="text-muted"><?= cclang('value_field_reff'); ?></small></label>
                                    <select class="form-control chosen chosen-select relation_value relation_field" name="crud[<?=$i; ?>][<?=$row->field_name; ?>][relation_value]" data-placeholder="Pilih field nilai">
                                       <option value=""></option>
                                    </select>
                                 </div>
                              </div>
                              <div class="col-md-12" style="margin:0px !important">
                                 <div class="form-group display-none ">
                                    <label><small class="text-muted"><?= cclang('label_field_reff'); ?></small></label>
                                    <select class="form-control chosen chosen-select relation_label relation_field" name="crud[<?=$i; ?>][<?=$row->field_name; ?>][relation_label]" data-placeholder="Pilih field label">
                                       <option value=""></option>
                                    </select>
                                 </div>
                              </div>
                              <?php endif; ?>
                           </td>
                           <td>
                              <div class="col-md-12">
                                 <div class="form-group ">
                                    <select class="form-control chosen chosen-select validation" name="crud[<?=$i; ?>][<?=$row->field_name; ?>][validation]" data-placeholder="+ Tambah aturan validasi">
                                        <option value="" class="input file number text datetime select"></option>
                                        <?php 
                                        foreach (db_get_all_data('crud_input_validation') as $input): 
                                        ?>
                                          <option value="<?= $input->validation; ?>" class="<?= str_replace(',', ' ', $input->group_input ?? ''); ?>" data-group-validation="<?= str_replace(',', ' ', $input->group_input ?? ''); ?>" data-placeholder="<?= $input->input_placeholder; ?>" title="<?= $input->input_able; ?>"><?= ucwords(clean_snake_case($input->validation)); ?></option>
                                         <?php endforeach; ?> 
                                    </select>
                                 </div>
                              </div>
                              <?php if (isset($crud_field_validation[$row->id])): 
                              foreach ($crud_field_validation[$row->id] as $rule) {
                              ?>
                              <div class="box-validation <?= str_replace(',', ' ', $rule->group_input ?? '').' '.$rule->validation_name; ?>"> 
                                <label><div class="validation-name<?= $rule->input_able == 'yes' ? '' : '-max'; ?>"><?= clean_snake_case($rule->validation); ?></div> 
                                <input class="input_validation" name="crud[<?=$i; ?>][<?= $row->field_name; ?>][validation][rules][<?= $rule->validation; ?>]" type="<?= $rule->input_able == 'yes' ? 'text' : 'hidden'; ?>" value="<?= $rule->validation_value; ?>"></label> <a class="delete fa fa-trash"></a> 
                              </div>
                              <?php 
                               }
                              endif; ?>
                           </td>
                        </tr>
                        <?php endforeach; ?>
                     </tbody>
                  </table>
                    
            </div>
         </div>
         <div class="validation_rules" hidden>
                     <option value="" class="<?= $this->model_crud->get_input_type(); ?>"></option>
                     <?php foreach (db_get_all_data('crud_input_validation') as $input): ?>
                       <option value="<?= $input->validation; ?>" class="<?= str_replace(',', ' ', $input->group_input ?? ''); ?>" title="<?= $input->input_able; ?>" data-placeholder="<?= $input->input_placeholder; ?>" ><?= ucwords(clean_snake_case($input->validation)); ?></option>
                      <?php endforeach; ?> 
         </div>
         <div class="message no-message-padding" role="alert"></div>
         <footer class="crud-builder-actions view-nav">
            <span class="loading loading-hide"><img src="<?= BASE_ASSET; ?>/img/loading-spin-primary.svg" alt=""> Menyimpan perubahan...</span>
            <a class="btn btn-light btn_action" id="btn_cancel" href="<?= site_url('administrator/crud/view/' . (int) $crud->id); ?>"><i class="fa fa-times"></i> Batal</a>
            <button type="button" class="btn btn-outline-primary btn_save btn_action btn_save_back" id="btn_save_back" data-stype="back" title="Simpan dan kembali ke daftar (Ctrl+d)"><i class="fa fa-list"></i> Simpan &amp; kembali</button>
            <button type="button" class="btn btn-primary btn_save btn_action" id="btn_save" data-stype="stay" title="Simpan perubahan (Ctrl+s)"><i class="fa fa-save"></i> Simpan perubahan</button>
         </footer>
      <?= form_close(); ?>
   </div>
</section>
<script src="<?= BASE_ASSET; ?>js/crud.js"></script>
<!-- Page script -->
<script>
$(document).ready(function() {
	var saving = false;
	var $form = $('#form_crud');
	var $saveButtons = $('.btn_save');

   $('.btn-remove-field').click(function(event) {
      var btn = $(this);
        swal({
            title: "<?= cclang('are_you_sure'); ?>",
            text: "Field akan dikeluarkan dari konfigurasi setelah perubahan disimpan.",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Ya, keluarkan",
            cancelButtonText: "Tidak",
            closeOnConfirm: true,
            closeOnCancel: true
          },
          function(isConfirm){
            if (isConfirm) {
               btn.parents('tr').fadeOut(function(){
                  $(this).remove();
				  renumber_table('#diagnosis_list');
               });
            }
          });
    
        return false;
   });
    $('.input_type').trigger('chosen:updated');

    //update validation
    $(document).find('table tr .input_type').each(function() {
        updateValidation($(this));
    });

    // Keep every column at its original width while a field is reordered.
    // The former floating-header clone caused the header and body to separate
    // after a long vertical scroll, so the real table header remains in-flow.
    var fixHelperModified = function(e, tr) {
        var $originals = tr.children();
        var $helper = tr.clone();
        $helper.children().each(function(index) {
            $(this).width($originals.eq(index).outerWidth());
        });
        return $helper;
    };

    var $fieldRows = $('#diagnosis_list tbody');
    if ($.fn.sortable) $fieldRows.sortable({
        items: '> tr',
        axis: 'y',
        handle: 'td.dragable',
        helper: fixHelperModified,
        forcePlaceholderSize: true,
        tolerance: 'pointer',
        start: function(event, ui) {
            $fieldRows.addClass('target-area');
            ui.placeholder.height(ui.item.outerHeight());
        },
        stop: function() {
            $fieldRows.removeClass('target-area');
            renumber_table('#diagnosis_list');
        }
    });

   $('.btn_save').click(function() {
		if (saving) return false;
        $('.message').hide();

		if (!$form[0].checkValidity()) {
			$form[0].reportValidity();
			return false;
		}
		if (!$('#diagnosis_list tbody tr').length) {
			$('.message').printMessage({message: 'Minimal satu field harus tersedia pada konfigurasi CRUD.', type: 'warning'}).fadeIn();
			return false;
		}

        var data_post = $form.serializeArray();
        var save_type = $(this).attr('data-stype');

        data_post.push({
            name: 'save_type',
            value: save_type
        });
		saving = true;
		$saveButtons.prop('disabled', true);
		$('.loading').show();

        $.ajax({
                url: $form.attr('action'),
                type: 'POST',
                dataType: 'json',
                data: data_post,
            })
            .done(function(res) {
                if (res && res.success) {

                    if (save_type == 'back') {
                        window.location.href = res.redirect || '<?= site_url('administrator/crud'); ?>';
                        return;
                    }

                    $('.message').printMessage({
                        message: res.message || 'Perubahan CRUD berhasil disimpan.'
                    });
                    $('.message').fadeIn();

                } else {
                    $('.message').printMessage({
                        message: (res && res.message) || 'Perubahan CRUD tidak dapat disimpan.',
                        type: 'warning'
                    });
                    $('.message').fadeIn();
                }

            })
            .fail(function(xhr) {
                $('.message').printMessage({
					message: xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan saat menyimpan perubahan CRUD.',
                    type: 'warning'
                });
            })
            .always(function() {
				saving = false;
				$saveButtons.prop('disabled', false);
                $('.loading').hide();
            });

        return false;
    }); /*end btn save*/

    //Renumber table rows
    function renumber_table(tableID) {
		$(tableID + " tbody tr").each(function(index) {
			$(this).find('.priority').val(index + 1);
        });
    }

	$(document).off('keydown.crudBuilderEdit').on('keydown.crudBuilderEdit', function(event) {
		if (!event.ctrlKey) return;
		var key = String(event.key).toLowerCase();
		if (key === 's') { event.preventDefault(); $('#btn_save').trigger('click'); }
		if (key === 'd') { event.preventDefault(); $('#btn_save_back').trigger('click'); }
		if (key === 'x') { event.preventDefault(); window.location.href = $('#btn_cancel').attr('href'); }
	});
}); /*end doc ready*/
</script>
