<script src="<?= BASE_ASSET; ?>/js/jquery.hotkeys.js"></script>

<style>
  .access-page { --access-primary: #08064d; --access-accent: #ffc800; --access-border: #e1e7ef; }
  .access-page .access-shell { max-width: 1180px; margin: 0 auto; }
  .access-page .access-header, .access-page .access-panel {
    border: 1px solid var(--access-border); border-radius: 14px; background: #fff;
    box-shadow: 0 8px 24px rgba(15, 23, 42, .05);
  }
  .access-page .access-header {
    display: flex; align-items: center; justify-content: space-between; gap: 20px;
    margin-bottom: 18px; padding: 22px; border-top: 3px solid var(--access-accent);
  }
  .access-page .access-heading { display: flex; align-items: center; gap: 14px; }
  .access-page .access-heading-icon {
    display: grid; width: 44px; height: 44px; flex: 0 0 44px; place-items: center;
    border-radius: 11px; background: var(--access-primary); color: var(--access-accent); font-size: 18px;
  }
  .access-page .access-heading h1 { margin: 0 0 3px; color: #111827; font-size: 23px; font-weight: 700; }
  .access-page .access-heading p { margin: 0; color: #718096; font-size: 13px; }
  .access-page .btn-add-permission {
    display: inline-flex; min-height: 40px; padding: 9px 15px; align-items: center; gap: 8px;
    border: 0; border-radius: 9px; background: var(--access-accent); color: #17143d;
    font-size: 12px; font-weight: 700;
  }
  .access-page .access-panel { overflow: hidden; }
  .access-page .group-navigation {
    position: relative; z-index: 20; display: flex; min-height: 56px; padding: 0 12px;
    align-items: stretch; border-bottom: 1px solid var(--access-border); background: #fff;
  }
  .access-page .group-tabs-scroll {
    display: flex; min-width: 0; flex: 1 1 auto; align-items: stretch; gap: 3px;
    overflow-x: auto; overflow-y: hidden; scrollbar-width: thin;
  }
  .access-page .access-group-tab, .access-page .group-menu-button {
    position: relative; display: inline-flex; padding: 0 15px; align-items: center; gap: 7px;
    border: 0; background: transparent; color: #64748b; font-size: 12px; font-weight: 700; white-space: nowrap;
  }
  .access-page .access-group-tab::after {
    position: absolute; right: 12px; bottom: 0; left: 12px; height: 3px;
    border-radius: 3px 3px 0 0; background: transparent; content: '';
  }
  .access-page .access-group-tab:hover, .access-page .access-group-tab.active { color: var(--access-primary); }
  .access-page .access-group-tab.active::after { background: var(--access-accent); }
  .access-page .group-management { position: relative; display: flex; flex: 0 0 auto; }
  .access-page .group-menu-button { border-left: 1px solid var(--access-border); }
  .access-page .group-menu-button .fa-angle-down { transition: transform .18s ease; }
  .access-page .group-management.open .group-menu-button { color: var(--access-primary); }
  .access-page .group-management.open .group-menu-button .fa-angle-down { transform: rotate(180deg); }
  .access-page .group-menu {
    position: absolute; top: calc(100% - 4px); right: 0; z-index: 50; display: none;
    min-width: 205px; margin: 0; padding: 7px; border: 1px solid var(--access-border);
    border-radius: 10px; background: #fff; box-shadow: 0 14px 32px rgba(15, 23, 42, .16); list-style: none;
  }
  .access-page .group-management.open .group-menu { display: block; }
  .access-page .group-menu a {
    display: flex; min-height: 38px; padding: 9px 11px; align-items: center; gap: 9px;
    border-radius: 7px; color: #475569; font-size: 12px; font-weight: 600; text-decoration: none;
  }
  .access-page .group-menu a i { width: 15px; color: #7c8da5; text-align: center; }
  .access-page .group-menu a:hover { background: #f3f5fa; color: var(--access-primary); }
  .access-page .access-toolbar {
    display: flex; padding: 18px 22px; align-items: center; justify-content: space-between; gap: 14px;
    border-bottom: 1px solid var(--access-border); background: #fbfcfe;
  }
  .access-page .check-all-label { display: inline-flex; margin: 0; align-items: center; gap: 8px; color: #334155; font-size: 13px; }
  .access-page input[type="checkbox"] { width: 17px; height: 17px; margin: 0; accent-color: var(--access-primary); }
  .access-page .access-search-wrap { position: relative; width: min(100%, 330px); }
  .access-page .access-search-wrap i { position: absolute; top: 50%; left: 13px; color: #94a3b8; transform: translateY(-50%); }
  .access-page #search {
    width: 100%; height: 40px; padding: 0 13px 0 38px; border: 1px solid #d8e0ea;
    border-radius: 9px; outline: none; background: #fff; color: #1e293b; font-size: 12px;
  }
  .access-page #search:focus { border-color: #7b8ca5; box-shadow: 0 0 0 3px rgba(8, 6, 77, .08); }
  .access-page .permission-area { position: relative; min-height: 260px; padding: 20px 22px; }
  .access-page #container_permission {
    display: grid; margin: 0; padding: 0; grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 14px; list-style: none;
  }
  .access-page .permission-group { overflow: hidden; border: 1px solid var(--access-border); border-radius: 11px; background: #fff; }
  .access-page .permission-group-header {
    display: flex; min-height: 44px; padding: 10px 13px; align-items: center; justify-content: space-between; gap: 10px;
    border-bottom: 1px solid var(--access-border); background: #f7f9fc;
  }
  .access-page .permission-group-toggle {
    display: inline-flex; padding: 0; align-items: center; gap: 8px; border: 0; background: transparent;
    color: #1e293b; font-size: 12px; font-weight: 800;
  }
  .access-page .permission-count {
    padding: 3px 8px; border-radius: 999px; background: #e9edf5; color: #64748b; font-size: 10px; font-weight: 700;
  }
  .access-page .permission-items {
    display: grid; margin: 0; padding: 8px 13px; grid-template-columns: repeat(2, minmax(0, 1fr)); list-style: none;
  }
  .access-page .permission-item { min-width: 0; }
  .access-page .permission-item label {
    display: flex; min-height: 38px; margin: 0; padding: 8px 4px; align-items: center; gap: 8px;
    color: #475569; font-size: 12px; font-weight: 500; cursor: pointer;
  }
  .access-page .permission-item label:hover { color: var(--access-primary); }
  .access-page .access-empty { grid-column: 1 / -1; padding: 55px 20px; color: #94a3b8; text-align: center; }
  .access-page .access-loading {
    position: absolute; inset: 0; z-index: 5; display: none; align-items: center; justify-content: center; gap: 9px;
    background: rgba(255, 255, 255, .82); color: #64748b; font-size: 12px;
  }
  .access-page .access-loading.visible { display: flex; }
  .access-page .access-actions {
    display: flex; min-height: 70px; padding: 14px 22px; align-items: center; justify-content: flex-end; gap: 9px;
    border-top: 1px solid var(--access-border); background: #fbfcfe;
  }
  .access-page .access-actions .btn {
    display: inline-flex; min-height: 40px; padding: 9px 16px; align-items: center; gap: 7px;
    border-radius: 9px; font-size: 12px; font-weight: 700;
  }
  .access-page .btn-save-access { border-color: var(--access-primary); background: var(--access-primary); color: #fff; }
  .access-page .btn-save-access:hover { background: #151178; color: #fff; }
  .access-page .message:empty { display: none; }
  .access-page .message { margin: 16px 22px 0; }
  @media (max-width: 767.98px) {
    .access-page .access-header, .access-page .access-toolbar { align-items: stretch; flex-direction: column; }
    .access-page .btn-add-permission { justify-content: center; }
    .access-page .access-search-wrap { width: 100%; }
    .access-page #container_permission, .access-page .permission-items { grid-template-columns: 1fr; }
  }
</style>

<section class="content access-page">
  <div class="access-shell">
    <header class="access-header">
      <div class="access-heading">
        <span class="access-heading-icon"><i class="fa fa-shield"></i></span>
        <div><h1>Pengaturan Access</h1><p>Atur permission yang dapat digunakan oleh setiap grup pengguna.</p></div>
      </div>
      <?php is_allowed('permission_add', function(){ ?>
        <a class="btn btn-add-permission" id="btn_add_new" title="Tambah permission baru (Ctrl+a)" href="<?= site_url('administrator/permission/add'); ?>">
          <i class="fa fa-plus-square-o"></i> Tambahkan Permission Baru
        </a>
      <?php }) ?>
    </header>

    <div class="access-panel">
      <?php if (empty($groups)): ?>
        <div class="access-empty"><i class="fa fa-users fa-2x"></i><p>Belum ada grup pengguna. Tambahkan grup terlebih dahulu.</p></div>
      <?php else: ?>
        <?= form_open('administrator/access/save', ['name' => 'form_access', 'id' => 'form_access', 'method' => 'POST']); ?>
          <input type="hidden" name="group_id" id="group_id" value="<?= (int) $groups[0]->id; ?>">
          <nav class="group-navigation" aria-label="Grup pengguna">
            <div class="group-tabs-scroll">
              <?php foreach ($groups as $index => $group): ?>
                <button type="button" class="access-group-tab<?= $index === 0 ? ' active' : ''; ?>" data-id="<?= (int) $group->id; ?>" aria-pressed="<?= $index === 0 ? 'true' : 'false'; ?>">
                  <?= _ent($group->name); ?>
                </button>
              <?php endforeach; ?>
            </div>
            <div class="group-management" id="group_management">
              <button type="button" class="group-menu-button" id="group_menu_toggle" aria-expanded="false" aria-controls="group_menu">
                Kelola Grup <i class="fa fa-angle-down"></i>
              </button>
              <ul class="group-menu" id="group_menu">
                <?php if ($this->aauth->is_allowed('group_add')): ?>
                  <li><a href="<?= site_url('administrator/group/add'); ?>"><i class="fa fa-plus"></i> Tambah Grup</a></li>
                <?php endif; ?>
                <?php if ($this->aauth->is_allowed('group_list')): ?>
                  <li><a href="<?= site_url('administrator/group'); ?>"><i class="fa fa-list"></i> Daftar Grup</a></li>
                <?php endif; ?>
                <li><a href="<?= site_url('administrator/permission'); ?>"><i class="fa fa-cog"></i> Kelola Permission</a></li>
              </ul>
            </div>
          </nav>

          <div class="access-toolbar">
            <label class="check-all-label" for="check_all"><input type="checkbox" id="check_all"> Tandai Semua Permission</label>
            <div class="access-search-wrap"><i class="fa fa-search"></i><input type="search" id="search" autocomplete="off" placeholder="Cari permission..." aria-label="Cari permission"></div>
          </div>
          <div class="permission-area">
            <div class="access-loading" id="access_loading" aria-live="polite"><i class="fa fa-circle-o-notch fa-spin"></i> Memuat permission...</div>
            <ul id="container_permission"></ul>
          </div>
          <div class="message"></div>
          <footer class="access-actions">
            <button type="button" class="btn btn-default" id="btn_undo" disabled><i class="fa fa-undo"></i> Kembalikan Perubahan</button>
            <?php is_allowed('access_update', function(){ ?>
              <button type="submit" class="btn btn-save-access" id="btn_save"><i class="fa fa-save"></i> Simpan Access</button>
            <?php }) ?>
          </footer>
        <?= form_close(); ?>
      <?php endif; ?>
    </div>
  </div>
</section>

<script>
  $(function () {
    var request = null;
    var $form = $('#form_access');
    var $container = $('#container_permission');
    var $loading = $('#access_loading');
    var $checkAll = $('#check_all');
    var $undo = $('#btn_undo');
    var $save = $('#btn_save');
    if (!$form.length) return;

    function setLoading(state) {
      $loading.toggleClass('visible', state);
      $save.prop('disabled', state);
      $('.access-group-tab').prop('disabled', state);
    }
    function syncCheckAll() {
      var $checks = $container.find('input.check');
      var checkedCount = $checks.filter(':checked').length;
      $checkAll.prop('checked', $checks.length > 0 && checkedCount === $checks.length);
      $checkAll.prop('indeterminate', checkedCount > 0 && checkedCount < $checks.length);
    }
    function setDirty(state) { $undo.prop('disabled', !state); }
    function showMessage(message, type) {
      if ($.fn.printMessage) {
        $('.message').printMessage({ message: message, type: type || 'success' }).fadeIn();
      } else {
        $('.message').html('<div class="alert alert-' + (type === 'warning' ? 'warning' : 'success') + '">' + message + '</div>').show();
      }
    }
    function refreshAccess(groupId) {
      if (request) request.abort();
      setLoading(true);
      setDirty(false);
      $checkAll.prop({ checked: false, indeterminate: false });
      $('#search').val('');
      request = $.ajax({
        url: BASE_URL + 'administrator/access/get_access_group/' + encodeURIComponent(groupId),
        type: 'GET', dataType: 'html', cache: false
      }).done(function (html) {
        $container.html(html);
        syncCheckAll();
      }).fail(function (xhr, status) {
        if (status !== 'abort') {
          $container.html('<li class="access-empty"><i class="fa fa-exclamation-circle"></i><p>Permission gagal dimuat. Silakan coba kembali.</p></li>');
        }
      }).always(function () {
        request = null;
        setLoading(false);
      });
    }

    $('.access-group-tab').on('click', function () {
      var $tab = $(this);
      $('.access-group-tab').removeClass('active').attr('aria-pressed', 'false');
      $tab.addClass('active').attr('aria-pressed', 'true');
      $('#group_id').val($tab.data('id'));
      refreshAccess($tab.data('id'));
    });
    $('#group_menu_toggle').on('click', function (event) {
      event.stopPropagation();
      var $management = $('#group_management');
      var isOpen = !$management.hasClass('open');
      $management.toggleClass('open', isOpen);
      $(this).attr('aria-expanded', isOpen ? 'true' : 'false');
    });
    $('#group_menu').on('click', function (event) { event.stopPropagation(); });
    $(document).on('click.accessGroupMenu', function () {
      $('#group_management').removeClass('open');
      $('#group_menu_toggle').attr('aria-expanded', 'false');
    });
    $(document).on('keydown.accessGroupMenu', function (event) {
      if (event.key === 'Escape') {
        $('#group_management').removeClass('open');
        $('#group_menu_toggle').attr('aria-expanded', 'false').trigger('focus');
      }
    });
    $checkAll.on('change', function () {
      $container.find('input.check').prop('checked', this.checked);
      syncCheckAll(); setDirty(true);
    });
    $container.on('change', 'input.check', function () { syncCheckAll(); setDirty(true); });
    $container.on('click', '.permission-group-toggle', function () {
      var target = $(this).data('target');
      var $checks = $container.find('input[data-group="' + target + '"]');
      var shouldCheck = $checks.filter(':checked').length !== $checks.length;
      $checks.prop('checked', shouldCheck);
      syncCheckAll(); setDirty(true);
    });
    $('#search').on('input', function () {
      var query = $.trim($(this).val()).toLowerCase();
      $container.find('.permission-group').each(function () {
        var hasMatch = false;
        $(this).find('.permission-item').each(function () {
          var matches = !query || $(this).text().toLowerCase().indexOf(query) !== -1;
          $(this).toggle(matches);
          hasMatch = hasMatch || matches;
        });
        $(this).toggle(hasMatch);
      });
    });
    $undo.on('click', function () { refreshAccess($('#group_id').val()); });
    $form.on('submit', function (event) {
      event.preventDefault(); setLoading(true); $('.message').hide();
      $.ajax({ url: $form.attr('action'), type: 'POST', dataType: 'json', data: $form.serialize() })
        .done(function (response) {
          if (response.success) { setDirty(false); showMessage(response.message, 'success'); }
          else { showMessage(response.message || 'Access gagal disimpan.', 'warning'); }
        })
        .fail(function () { showMessage('Access gagal disimpan. Periksa koneksi lalu coba kembali.', 'warning'); })
        .always(function () { setLoading(false); });
    });

    $('*').bind('keydown', 'Ctrl+a', function () { window.location.href = BASE_URL + 'administrator/permission/add'; return false; });
    $('*').bind('keydown', 'Ctrl+s', function () { $form.trigger('submit'); return false; });
    $('*').bind('keydown', 'Ctrl+x', function () { if (!$undo.prop('disabled')) $undo.trigger('click'); return false; });
    refreshAccess($('#group_id').val());
  });
</script>
