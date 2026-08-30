<script>
if (!document.body || !document.body.classList.contains('admin-silaris')) {
  window.location.replace(window.location.href);
}
</script>
<script src="<?= BASE_ASSET; ?>js/jquery.hotkeys.js"></script>

<section class="content crud-builder-page">
  <div class="crud-builder-shell">
    <header class="crud-builder-header">
      <div><span class="crud-builder-eyebrow">CRUD BUILDER</span><h1>Tambah CRUD</h1><p>Buat modul administrasi dari tabel database yang tersedia.</p></div>
      <a href="<?= site_url('administrator/crud'); ?>" class="btn btn-light crud-builder-back"><i class="fa fa-arrow-left"></i><span>Kembali</span></a>
    </header>
    <?= form_open('', ['name' => 'form_crud', 'class' => 'crud-builder-form', 'id' => 'form_crud', 'method' => 'POST']); ?>
      <div class="crud-builder-section">
        <div class="crud-builder-section-title"><span class="crud-builder-step">1</span><div><h2>Informasi modul</h2><p>Tentukan sumber data dan nama modul.</p></div></div>
        <div class="row g-3">
          <div class="col-12 col-lg-6">
            <label for="table_name" class="form-label">Nama tabel <span class="required">*</span></label>
            <select class="form-select chosen chosen-select chosen-select-with-deselect" name="table_name" id="table_name" data-placeholder="Pilih tabel" required>
              <option value=""></option>
              <?php foreach ($tables as $row): ?><option value="<?= _ent($row); ?>"><?= _ent($row); ?></option><?php endforeach; ?>
            </select>
            <div class="form-text">Tabel ini digunakan sebagai sumber data modul.</div>
            <span class="loading2 loading-hide crud-builder-inline-loading"><img src="<?= BASE_ASSET; ?>img/loading-spin-primary.svg" alt=""> Memuat struktur tabel...</span>
          </div>
          <div class="col-12 col-lg-6">
            <label for="subject" class="form-label">Nama modul <span class="required">*</span></label>
            <input type="text" class="form-control" name="subject" id="subject" placeholder="Contoh: Data Pegawai" value="<?= set_value('subject'); ?>" required>
            <div class="form-text">Nama yang digunakan pada menu dan pesan aplikasi.</div>
          </div>
          <div class="col-12">
            <label for="title" class="form-label">Judul halaman</label>
            <input type="text" class="form-control" name="title" id="title" placeholder="Kosongkan untuk menggunakan nama modul" value="<?= set_value('title'); ?>">
          </div>
        </div>
      </div>
      <div class="crud-builder-section">
        <div class="crud-builder-section-title"><span class="crud-builder-step">2</span><div><h2>Fitur halaman</h2><p>Pilih halaman yang akan dibuat.</p></div></div>
        <div class="crud-builder-options">
          <label class="crud-builder-option" for="create"><input class="form-check-input check page_create" type="checkbox" id="create" value="yes" name="create" checked><span><strong>Tambah data</strong><small>Form untuk membuat data baru.</small></span></label>
          <label class="crud-builder-option" for="read"><input class="form-check-input check page_read" type="checkbox" id="read" value="yes" name="read" checked><span><strong>Detail data</strong><small>Halaman untuk membaca detail data.</small></span></label>
          <label class="crud-builder-option" for="update"><input class="form-check-input check page_update" type="checkbox" id="update" value="yes" name="update" checked><span><strong>Ubah data</strong><small>Form untuk memperbarui data.</small></span></label>
        </div>
      </div>
      <div class="crud-builder-section crud-builder-fields">
        <div class="crud-builder-section-title"><span class="crud-builder-step">3</span><div><h2>Konfigurasi field</h2><p>Atur label, visibilitas, tipe input, dan validasi setiap field.</p></div></div>
        <div id="crud_builder_empty" class="crud-builder-empty"><i class="fa fa-table"></i><strong>Pilih tabel terlebih dahulu</strong><span>Struktur field akan tampil otomatis.</span></div>
        <div class="wrapper-crud" aria-live="polite"></div>
      </div>
      <div class="validation_rules" hidden>
        <option value="" class="<?= $this->model_crud->get_input_type(); ?>"></option>
        <?php foreach (db_get_all_data('crud_input_validation') as $input): ?><option value="<?= $input->validation; ?>" class="<?= str_replace(',', ' ', $input->group_input ?? ''); ?>" title="<?= $input->input_able; ?>" data-placeholder="<?= $input->input_placeholder; ?>"><?= ucwords(clean_snake_case($input->validation)); ?></option><?php endforeach; ?>
      </div>
      <div class="message no-message-padding" role="alert"></div>
      <footer class="crud-builder-actions">
        <span class="loading loading-hide"><img src="<?= BASE_ASSET; ?>img/loading-spin-primary.svg" alt=""> Menyimpan...</span>
        <a class="btn btn-light btn_action" id="btn_cancel" href="<?= site_url('administrator/crud'); ?>"><i class="fa fa-times"></i> Batal</a>
        <button type="button" class="btn btn-outline-primary btn_save btn_action btn_save_back" id="btn_save_back" data-stype="back" disabled><i class="fa fa-list"></i> Simpan &amp; kembali</button>
        <button type="button" class="btn btn-primary btn_save btn_action" id="btn_save" data-stype="stay" disabled><i class="fa fa-save"></i> Simpan</button>
      </footer>
    <?= form_close(); ?>
  </div>
</section>

<script src="<?= BASE_ASSET; ?>js/crud.js"></script>
<script>
$(function () {
  var request = null, saving = false;
  var $form = $('#form_crud'), $table = $('#table_name'), $buttons = $('.btn_save');
  function message(text, type) {
    var $box = $('.message');
    if ($.fn.printMessage) $box.printMessage({message: text, type: type || 'warning'});
    else $box.attr('class', 'message alert alert-' + (type === 'success' ? 'success' : 'warning')).text(text);
    $box.stop(true, true).fadeIn();
  }
  function busy(state) {
    $('.loading2').toggle(state); $('.crud-builder-fields').attr('aria-busy', state);
    $table.prop('disabled', state).trigger('chosen:updated');
    if (state) $buttons.prop('disabled', true);
  }
  function initFields() {
    if ($.fn.chosen) $('.wrapper-crud .chosen-select').each(function () { if (!$(this).data('chosen')) $(this).chosen({width: '100%'}); });
    if ($.fn.tooltip) $('.wrapper-crud .tip').tooltip();
    if ($.fn.sortable) $('#diagnosis_list tbody').sortable({handle: 'td:first', helper: function (e, row) { var cells=row.children(), clone=row.clone(); clone.children().each(function(i){ $(this).width(cells.eq(i).width()); }); return clone; }, stop: renumber});
    if ($.fn.iCheck) $('#diagnosis_list input.check').iCheck({checkboxClass: 'icheckbox_minimal-red', radioClass: 'iradio_minimal-red'});
    $('#diagnosis_list .input_type').each(function () { updateValidation($(this)); });
    $('#diagnosis_list .validation').each(function () {
      var row=$(this).closest('tr'), id=row.find('.crud-id').val(), name=row.find('.crud-name').val(), type=row.find('.crud-data-type').val(), pk=row.find('.crud-primarykey').val(), max=parseInt(row.find('.crud-max-length').val(),10)||0;
      if (pk != 1) { addValidation($(this),id,name,'required','no'); if(max>0) addValidation($(this),id,name,'max_length','yes',max); }
      if(type==='number') addValidation($(this),id,name,'number','no');
    });
  }
  function renumber() { $('#diagnosis_list tbody tr').each(function(i){ $(this).find('.priority').val(i+1); }); }
  $table.on('change.crudBuilder', function () {
    var name=$.trim($(this).val()); $('.message').hide(); if(request) request.abort();
    $('.wrapper-crud').empty(); $('#crud_builder_empty').toggle(!name); $buttons.prop('disabled',true); if(!name) return;
    busy(true);
    request=$.ajax({url:BASE_URL+'/administrator/crud/get_field_data/'+encodeURIComponent(name), type:'GET', dataType:'json'})
      .done(function(res){ if(!res || !res.success || !res.html){ message((res&&res.message)||'Struktur tabel tidak dapat dimuat.'); $('#crud_builder_empty').show(); return; } $('#subject, #title').val(res.subject||''); $('.wrapper-crud').html(res.html); $('#crud_builder_empty').hide(); initFields(); $buttons.prop('disabled',false); })
      .fail(function(xhr,status){ if(status!=='abort'){ message('Struktur tabel gagal dimuat. Periksa tabel dan coba kembali.'); $('#crud_builder_empty').show(); } })
      .always(function(){ request=null; busy(false); });
  });
  $buttons.on('click.crudBuilder', function () {
    if(saving) return false; $('.message').hide();
    if(!$table.val() || !$form[0].checkValidity() || !$form.find('input[name="primary_key"]').length){ $form[0].reportValidity(); if(!$form.find('input[name="primary_key"]').length) message('Pilih tabel yang memiliki primary key sebelum menyimpan.'); return false; }
    var saveType=$(this).data('stype'), data=$form.serializeArray(); data.push({name:'save_type',value:saveType}); saving=true; $buttons.prop('disabled',true); $('.loading').show();
    $.ajax({url:BASE_URL+'/administrator/crud/add_save',type:'POST',dataType:'json',data:data})
      .done(function(res){ if(res&&res.success){ if(saveType==='back'&&res.redirect){ window.location.href=res.redirect; return; } message(res.message||'CRUD berhasil dibuat.','success'); } else message((res&&res.message)||'CRUD tidak dapat disimpan.'); })
      .fail(function(xhr){ message(xhr.responseJSON&&xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan saat menyimpan CRUD.'); })
      .always(function(){ saving=false; $buttons.prop('disabled',false); $('.loading').hide(); }); return false;
  });
  $(document).off('keydown.crudBuilder').on('keydown.crudBuilder',function(e){ if(!e.ctrlKey)return; var key=String(e.key).toLowerCase(); if(key==='s'){e.preventDefault();$('#btn_save').click();} if(key==='d'){e.preventDefault();$('#btn_save_back').click();} if(key==='x'){e.preventDefault();window.location.href=$('#btn_cancel').attr('href');} });
  if($.fn.chosen && !$table.data('chosen')) $table.chosen({allow_single_deselect:true,width:'100%'});
});
</script>
