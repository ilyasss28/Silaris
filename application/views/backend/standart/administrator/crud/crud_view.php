<?php $field_count = isset($crud_fields) ? count($crud_fields) : 0; ?>
<script src="<?= BASE_ASSET; ?>js/jquery.hotkeys.js"></script>

<section class="content crud-detail-page">
  <div class="crud-detail-shell">
    <header class="crud-detail-header">
      <div class="crud-detail-header__copy">
        <span class="crud-detail-icon"><i class="fa fa-cubes"></i></span>
        <div>
          <span class="crud-builder-eyebrow">CRUD BUILDER</span>
          <h1><?= _ent($crud->subject); ?></h1>
          <p>Ringkasan konfigurasi modul dan halaman yang dihasilkan.</p>
        </div>
      </div>
      <div class="crud-detail-actions">
        <a href="<?= site_url('administrator/crud'); ?>" class="btn crud-detail-btn crud-detail-btn--secondary" id="btn_back"><i class="fa fa-arrow-left"></i> Kembali</a>
        <?php if ($this->aauth->is_allowed('crud_update')): ?>
          <a href="<?= site_url('administrator/crud/edit/' . (int) $crud->id); ?>" class="btn crud-detail-btn crud-detail-btn--primary" id="btn_edit"><i class="fa fa-pencil"></i> Edit CRUD</a>
        <?php endif; ?>
      </div>
    </header>

    <?php if (!$table_exists): ?>
      <div class="crud-detail-alert"><i class="fa fa-exclamation-triangle"></i><div><strong>Tabel sumber tidak ditemukan</strong><span>Konfigurasi masih tersimpan, tetapi modul tidak dapat dibuat ulang sebelum tabel tersedia.</span></div></div>
    <?php endif; ?>

    <div class="crud-detail-grid">
      <section class="crud-detail-card crud-detail-card--summary">
        <div class="crud-detail-card__heading"><span><i class="fa fa-info-circle"></i></span><div><h2>Informasi Modul</h2><p>Identitas konfigurasi CRUD.</p></div></div>
        <dl class="crud-detail-list">
          <div><dt>ID konfigurasi</dt><dd>#<?= (int) $crud->id; ?></dd></div>
          <div><dt>Nama tabel</dt><dd><code><?= _ent($crud->table_name); ?></code></dd></div>
          <div><dt>Primary key</dt><dd><code><?= _ent($crud->primary_key); ?></code></dd></div>
          <div><dt>Judul halaman</dt><dd><?= !empty($crud->title) ? _ent($crud->title) : _ent($crud->subject); ?></dd></div>
          <div><dt>Jumlah field</dt><dd><?= $field_count; ?> field</dd></div>
        </dl>
      </section>

      <section class="crud-detail-card">
        <div class="crud-detail-card__heading"><span><i class="fa fa-window-restore"></i></span><div><h2>Halaman Aktif</h2><p>Fitur yang tersedia pada modul hasil generator.</p></div></div>
        <div class="crud-feature-list">
          <div class="<?= $crud->page_create === 'yes' ? 'is-enabled' : 'is-disabled'; ?>"><i class="fa <?= $crud->page_create === 'yes' ? 'fa-check' : 'fa-minus'; ?>"></i><span><strong>Tambah Data</strong><small>Form pembuatan data baru</small></span></div>
          <div class="<?= $crud->page_read === 'yes' ? 'is-enabled' : 'is-disabled'; ?>"><i class="fa <?= $crud->page_read === 'yes' ? 'fa-check' : 'fa-minus'; ?>"></i><span><strong>Detail Data</strong><small>Halaman baca informasi lengkap</small></span></div>
          <div class="<?= $crud->page_update === 'yes' ? 'is-enabled' : 'is-disabled'; ?>"><i class="fa <?= $crud->page_update === 'yes' ? 'fa-check' : 'fa-minus'; ?>"></i><span><strong>Edit Data</strong><small>Form pembaruan data</small></span></div>
        </div>
        <?php if ($table_exists && $this->aauth->is_allowed($crud->table_name . '_list')): ?>
          <a href="<?= site_url($crud->table_name); ?>" class="crud-module-link"><i class="fa fa-external-link"></i> Buka modul hasil CRUD</a>
        <?php endif; ?>
      </section>
    </div>

    <section class="crud-detail-card crud-detail-card--fields">
      <div class="crud-detail-card__heading"><span><i class="fa fa-list-alt"></i></span><div><h2>Konfigurasi Field</h2><p>Field yang tersimpan dan lokasi tampilnya.</p></div></div>
      <div class="table-responsive">
        <table class="table crud-detail-table" aria-label="Konfigurasi field CRUD">
          <thead><tr><th>Field</th><th>Label</th><th>Tipe Input</th><th>Kolom</th><th>Tambah</th><th>Edit</th><th>Detail</th></tr></thead>
          <tbody>
          <?php if (!empty($crud_fields)): ?>
            <?php foreach ($crud_fields as $field): ?>
              <tr>
                <td><code><?= _ent($field->field_name); ?></code></td>
                <td><?= _ent($field->field_label); ?></td>
                <td><span class="crud-input-badge"><?= _ent(ucwords(clean_snake_case($field->input_type))); ?></span></td>
                <?php foreach (array('show_column', 'show_add_form', 'show_update_form', 'show_detail_page') as $visibility): ?>
                  <td><span class="crud-visibility <?= $field->{$visibility} === 'yes' ? 'is-enabled' : 'is-disabled'; ?>"><i class="fa <?= $field->{$visibility} === 'yes' ? 'fa-check' : 'fa-minus'; ?>"></i></span></td>
                <?php endforeach; ?>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="7" class="crud-detail-empty">Belum ada konfigurasi field.</td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>
  </div>
</section>

<script>
$(function () {
  $(document).off('keydown.crudDetail').on('keydown.crudDetail', function (event) {
    if (!event.ctrlKey) return;
    var key = String(event.key).toLowerCase();
    if (key === 'e' && $('#btn_edit').length) { event.preventDefault(); window.location.href = $('#btn_edit').attr('href'); }
    if (key === 'x') { event.preventDefault(); window.location.href = $('#btn_back').attr('href'); }
  });
});
</script>
