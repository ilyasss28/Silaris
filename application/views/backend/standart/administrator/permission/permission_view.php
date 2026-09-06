<script src="<?= BASE_ASSET; ?>js/jquery.hotkeys.js"></script>

<section class="content record-detail-page permission-detail-page">
  <div class="record-detail-shell">
    <header class="record-detail-header">
      <div class="record-detail-header__copy">
        <span class="record-detail-icon"><i class="fa fa-key"></i></span>
        <div>
          <span class="record-detail-eyebrow">MANAJEMEN AKSES</span>
          <h1>Detail Permission</h1>
          <p>Informasi hak akses yang tersedia di dalam sistem.</p>
        </div>
      </div>
      <div class="record-detail-header__aside">
        <div class="record-detail-id"><small>ID PERMISSION</small><strong>#<?= (int) $permission->id; ?></strong></div>
        <div class="record-detail-actions admin-action-group admin-action-group--header">
          <a href="<?= site_url('administrator/permission'); ?>" class="btn crud-detail-btn crud-detail-btn--secondary" id="btn_back"><i class="fa fa-arrow-left"></i> Kembali</a>
          <?php is_allowed('permission_update', function () use ($permission) { ?>
            <a href="<?= site_url('administrator/permission/edit/' . (int) $permission->id); ?>" class="btn crud-detail-btn crud-detail-btn--primary" id="btn_edit"><i class="fa fa-pencil"></i> Edit Permission</a>
          <?php }); ?>
        </div>
      </div>
    </header>

    <div class="record-detail-grid">
      <section class="record-detail-card record-detail-card--wide">
        <div class="record-detail-card__heading">
          <span><i class="fa fa-shield"></i></span>
          <div><h2>Informasi Permission</h2><p>Nama dan penjelasan hak akses aplikasi.</p></div>
        </div>
        <dl class="record-detail-list">
          <div><dt>ID Permission</dt><dd>#<?= (int) $permission->id; ?></dd></div>
          <div><dt>Nama Permission</dt><dd><?= _ent($permission->name); ?></dd></div>
          <div><dt>Definisi</dt><dd><?= $permission->definition !== '' && $permission->definition !== null ? _ent($permission->definition) : '-'; ?></dd></div>
        </dl>
      </section>
    </div>
  </div>
</section>

<script>
$(function () {
  $(document).off('keydown.permissionDetail').on('keydown.permissionDetail', function (event) {
    if (!event.ctrlKey) return;
    var key = String(event.key).toLowerCase();
    if (key === 'e' && $('#btn_edit').length) { event.preventDefault(); window.location.href = $('#btn_edit').attr('href'); }
    if (key === 'x') { event.preventDefault(); window.location.href = $('#btn_back').attr('href'); }
  });
});
</script>
