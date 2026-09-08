<script src="<?= BASE_ASSET; ?>/js/jquery.hotkeys.js"></script>

<section class="content fidusia-form-page region-form-page">
  <div class="fidusia-form-shell">
    <header class="fidusia-form-header">
      <div class="fidusia-form-header__copy"><span class="fidusia-form-header__icon"><i class="fa fa-pencil"></i></span><div><span class="fidusia-form-eyebrow">MASTER WILAYAH</span><h1>Edit Setup Wilayah</h1><p>Perbarui kode dan nama kabupaten/kota yang digunakan oleh SILARIS.</p></div></div>
      <span class="fidusia-form-status"><i class="fa fa-edit"></i>Mode edit</span>
    </header>
    <?= form_open(base_url('wil/edit_save/' . $this->uri->segment(3)), ['name' => 'form_wil', 'class' => 'fidusia-form region-form', 'id' => 'form_wil', 'method' => 'POST']); ?>
      <div class="message fidusia-form-message"></div>
      <div class="fidusia-form-grid">
        <section class="fidusia-form-card">
          <div class="fidusia-form-card__heading"><span><i class="fa fa-map-marker"></i></span><div><h2>Identitas Wilayah</h2><p>Gunakan kode resmi yang unik dan nama wilayah yang konsisten.</p></div></div>
          <div class="fidusia-form-fields fidusia-form-fields--document">
            <div class="fidusia-form-field"><label for="kd_wilayah">Kode Wilayah <i class="required">*</i></label><input type="text" class="form-control" name="kd_wilayah" id="kd_wilayah" maxlength="30" required placeholder="Contoh: 7471" value="<?= _ent(set_value('kd_wilayah', $wil->kd_wilayah)); ?>"><small><i class="fa fa-key"></i>Maksimal 30 karakter dan tidak boleh sama dengan wilayah lain.</small></div>
            <div class="fidusia-form-field"><label for="nama_wilayah">Nama Wilayah <i class="required">*</i></label><input type="text" class="form-control" name="nama_wilayah" id="nama_wilayah" maxlength="100" required placeholder="Contoh: Kota Kendari" value="<?= _ent(set_value('nama_wilayah', $wil->nama_wilayah)); ?>"><small><i class="fa fa-font"></i>Maksimal 100 karakter; gunakan nama kabupaten/kota resmi.</small></div>
          </div>
        </section>
      </div>
      <footer class="fidusia-form-actions"><div class="fidusia-form-actions__hint"><i class="fa fa-info-circle"></i><span>Perubahan kode wilayah dapat memengaruhi relasi Notaris dan MPD.</span></div><div class="fidusia-form-actions__buttons"><a class="btn admin-button admin-button--neutral btn_action" id="btn_cancel"><i class="fa fa-times"></i> Batal</a><button class="btn admin-button admin-button--secondary btn_save btn_action btn_save_back" type="button" data-stype="back"><i class="fa fa-list"></i> Simpan & kembali</button><button class="btn admin-button admin-button--save btn_save btn_action" id="btn_save" type="button" data-stype="stay"><i class="fa fa-save"></i> Simpan Perubahan</button><span class="loading loading-hide"><img src="<?= BASE_ASSET; ?>/img/loading-spin-primary.svg"><i><?= cclang('loading_saving_data'); ?></i></span></div></footer>
    <?= form_close(); ?>
  </div>
</section>

<script>
$(function () {
  $('#btn_cancel').on('click', function () {
    swal({title: 'Batalkan perubahan?', text: 'Perubahan yang belum disimpan akan hilang.', type: 'warning', showCancelButton: true, confirmButtonColor: '#DD6B55', confirmButtonText: 'Ya, kembali', cancelButtonText: 'Lanjut mengedit', closeOnConfirm: true}, function (confirmed) { if (confirmed) window.location.href = BASE_URL + 'wil'; });
    return false;
  });

  $('.btn_save').on('click', function () {
    var form = $('#form_wil');
    var data = form.serializeArray();
    var saveType = $(this).attr('data-stype');
    data.push({name: 'save_type', value: saveType});
    $('.message').hide(); $('.loading').show();
    $.ajax({url: form.attr('action'), type: 'POST', dataType: 'json', data: data})
      .done(function (res) { if (res.success) { if (saveType === 'back') { window.location.href = res.redirect; return; } $('.message').printMessage({message: res.message}).fadeIn(); } else { $('.message').printMessage({message: res.message, type: 'warning'}).fadeIn(); } })
      .fail(function () { $('.message').printMessage({message: 'Data wilayah gagal disimpan.', type: 'warning'}).fadeIn(); })
      .always(function () { $('.loading').hide(); });
    return false;
  });

  $(document).off('keydown.regionEdit').on('keydown.regionEdit', function (event) { if (!event.ctrlKey) return; var key = String(event.key).toLowerCase(); if (key === 's') { event.preventDefault(); $('#btn_save').trigger('click'); } if (key === 'd') { event.preventDefault(); $('.btn_save_back').trigger('click'); } if (key === 'x') { event.preventDefault(); $('#btn_cancel').trigger('click'); } });
});
</script>
