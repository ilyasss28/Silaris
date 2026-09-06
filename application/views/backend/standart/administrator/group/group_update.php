<script src="<?= BASE_ASSET; ?>js/jquery.hotkeys.js"></script>

<section class="content fidusia-form-page group-form-page">
  <div class="fidusia-form-shell">
    <header class="fidusia-form-header">
      <div class="fidusia-form-header__copy">
        <span class="fidusia-form-header__icon"><i class="fa fa-pencil"></i></span>
        <div>
          <span class="fidusia-form-eyebrow">MANAJEMEN PENGGUNA</span>
          <h1>Edit Groups</h1>
          <p>Perbarui nama dan definisi kelompok pengguna.</p>
        </div>
      </div>
      <span class="fidusia-form-status"><i class="fa fa-edit"></i> Mode edit</span>
    </header>

    <?= form_open(base_url('administrator/group/edit_save/' . $this->uri->segment(4)), [
      'name' => 'form_group',
      'class' => 'form-horizontal fidusia-form',
      'id' => 'form_group',
      'method' => 'POST',
    ]); ?>
      <div class="message fidusia-form-message" aria-live="polite"></div>

      <div class="fidusia-form-grid">
        <section class="fidusia-form-card record-detail-card--wide">
          <div class="fidusia-form-card__heading">
            <span><i class="fa fa-users"></i></span>
            <div><h2>Informasi Group</h2><p>Kelola identitas kelompok pengguna aplikasi.</p></div>
          </div>
          <div class="fidusia-form-fields fidusia-form-fields--document">
            <div class="fidusia-form-field">
              <label for="name">Nama Group <i class="required">*</i></label>
              <select class="form-control" name="name" id="name" required>
                <?php foreach (['Admin', 'User', 'Kanwil', 'MPD'] as $group_name): ?>
                  <option value="<?= $group_name; ?>" <?= set_select('name', $group_name, strcasecmp((string) $group->name, $group_name) === 0); ?>><?= $group_name; ?></option>
                <?php endforeach; ?>
              </select>
              <small><i class="fa fa-info-circle"></i>Pilih salah satu kelompok pengguna yang didukung aplikasi.</small>
            </div>
            <div class="fidusia-form-field">
              <label for="definition">Definisi</label>
              <input type="text" class="form-control" name="definition" id="definition" placeholder="Jelaskan fungsi group" value="<?= _ent(set_value('definition', $group->definition)); ?>">
              <small><i class="fa fa-align-left"></i>Penjelasan singkat mengenai tanggung jawab kelompok ini.</small>
            </div>
          </div>
        </section>
      </div>

      <footer class="fidusia-form-actions">
        <div class="fidusia-form-actions__hint"><i class="fa fa-info-circle"></i><span>Perubahan group dapat memengaruhi pengelompokan akses pengguna.</span></div>
        <div class="fidusia-form-actions__buttons">
          <a class="btn admin-button admin-button--neutral" id="btn_cancel" href="<?= site_url('administrator/group'); ?>"><i class="fa fa-times"></i> Batal</a>
          <button type="button" class="btn admin-button admin-button--save-secondary group-save" id="btn_save_back" data-stype="back"><i class="fa fa-list"></i> Simpan &amp; Kembali</button>
          <button type="button" class="btn admin-button admin-button--save group-save" id="btn_save" data-stype="stay"><i class="fa fa-save"></i> Simpan</button>
        </div>
        <span class="loading loading-hide"><img src="<?= BASE_ASSET; ?>img/loading-spin-primary.svg" alt=""> <i><?= cclang('loading_saving_data'); ?></i></span>
      </footer>
    <?= form_close(); ?>
  </div>
</section>

<script>
$(function () {
  var form = $('#form_group');

  $(document).off('click.groupForm', '.group-save').on('click.groupForm', '.group-save', function () {
    if (!form.length || !form[0].checkValidity()) {
      if (form.length) form[0].reportValidity();
      return false;
    }

    var saveType = $(this).data('stype');
    var dataPost = form.serializeArray();
    dataPost.push({name: 'save_type', value: saveType});
    $('.fidusia-form-message').stop(true, true).hide().empty();
    form.find('.group-save').prop('disabled', true);
    form.find('.loading').removeClass('loading-hide').show();

    $.ajax({url: form.attr('action'), type: 'POST', dataType: 'json', data: dataPost})
      .done(function (response) {
        if (!response.success) {
          $('.fidusia-form-message').printMessage({message: response.message, type: 'warning'});
          $('.fidusia-form-message').fadeIn();
          return;
        }
        if (saveType === 'back') { window.location.href = response.redirect; return; }
        $('.fidusia-form-message').printMessage({message: response.message});
        $('.fidusia-form-message').fadeIn();
        window.scrollTo({top: 0, behavior: 'smooth'});
      })
      .fail(function () {
        $('.fidusia-form-message').printMessage({message: 'Group gagal disimpan. Silakan coba kembali.', type: 'warning'});
        $('.fidusia-form-message').fadeIn();
      })
      .always(function () {
        form.find('.group-save').prop('disabled', false);
        form.find('.loading').hide().addClass('loading-hide');
      });
    return false;
  });

  $(document).off('keydown.groupForm').on('keydown.groupForm', function (event) {
    if (!event.ctrlKey) return;
    var key = String(event.key).toLowerCase();
    if (key === 's') { event.preventDefault(); $('#btn_save').trigger('click'); }
    if (key === 'd') { event.preventDefault(); $('#btn_save_back').trigger('click'); }
    if (key === 'x') { event.preventDefault(); window.location.href = $('#btn_cancel').attr('href'); }
  });
});
</script>
