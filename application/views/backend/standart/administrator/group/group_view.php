<script src="<?= BASE_ASSET; ?>js/jquery.hotkeys.js"></script>

<section class="content record-detail-page group-detail-page">
  <div class="record-detail-shell">
    <header class="record-detail-header">
      <div class="record-detail-header__copy">
        <span class="record-detail-icon"><i class="fa fa-users"></i></span>
        <div>
          <span class="record-detail-eyebrow">MANAJEMEN PENGGUNA</span>
          <h1>Detail Groups</h1>
          <p>Informasi kelompok akses dan perannya di dalam sistem.</p>
        </div>
      </div>
      <div class="record-detail-header__aside">
        <div class="record-detail-id"><small>ID GROUP</small><strong>#<?= (int) $group->id; ?></strong></div>
        <div class="record-detail-actions admin-action-group admin-action-group--header">
          <a href="<?= site_url('administrator/group'); ?>" class="btn crud-detail-btn crud-detail-btn--secondary" id="btn_back"><i class="fa fa-arrow-left"></i> Kembali</a>
          <a href="<?= site_url('administrator/group/edit/' . (int) $group->id); ?>" class="btn crud-detail-btn crud-detail-btn--primary" id="btn_edit"><i class="fa fa-pencil"></i> Edit Groups</a>
        </div>
      </div>
    </header>

    <div class="record-detail-grid">
      <section class="record-detail-card record-detail-card--wide">
        <div class="record-detail-card__heading">
          <span><i class="fa fa-users"></i></span>
          <div><h2>Informasi Group</h2><p>Nama dan penjelasan kelompok pengguna aplikasi.</p></div>
        </div>
        <dl class="record-detail-list">
          <div><dt>ID Group</dt><dd>#<?= (int) $group->id; ?></dd></div>
          <div><dt>Nama Group</dt><dd><?= _ent($group->name); ?></dd></div>
          <div><dt>Definisi</dt><dd><?= $group->definition !== '' && $group->definition !== null ? _ent($group->definition) : '-'; ?></dd></div>
        </dl>
      </section>
    </div>
  </div>
</section>

<script>
$(function () {
  $(document).off('keydown.groupDetail').on('keydown.groupDetail', function (event) {
    if (!event.ctrlKey) return;
    var key = String(event.key).toLowerCase();
    if (key === 'e') { event.preventDefault(); window.location.href = $('#btn_edit').attr('href'); }
    if (key === 'x') { event.preventDefault(); window.location.href = $('#btn_back').attr('href'); }
  });
});
</script>
