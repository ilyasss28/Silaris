(function () {
  'use strict';

  function textOf(element) {
    return element ? element.textContent.replace(/\s+/g, ' ').trim() : '';
  }

  function normalizeFunctionalButton(button) {
    if (!button || button.dataset.adminButtonReady) return;
    if (button.matches('.table-action, .btn-box-tool, .btn-mode, .dt-button, .dt-paging-button, .qq-upload-button, .rest-tool-param-toggle, .rest-tool-send, .rest-tool-format') ||
        button.closest('.pagination, .dt-paging, .note-toolbar, .cke, .medium-editor-toolbar, .rest-tool-method, .rest-tool-view-switch')) return;

    var icon = button.querySelector('i');
    var signature = [
      textOf(button),
      button.id || '',
      button.className || '',
      button.getAttribute('title') || '',
      button.getAttribute('href') || '',
      icon ? icon.className : ''
    ].join(' ').toLowerCase();
    var type = 'neutral';

    if (button.classList.contains('btn_save_back') || /simpan.{0,12}(kembali|daftar)|save.{0,12}(back|list)/.test(signature)) {
      type = 'save-secondary';
    } else if (button.classList.contains('btn_save') || /\bsimpan\b|\bsave\b|fa-save/.test(signature)) {
      type = 'save';
    } else if (button.classList.contains('btn_add_new') || button.classList.contains('btn-add') || /\btambah\b|\badd\b|\bcreate\b|fa-plus/.test(signature)) {
      type = 'create';
    } else if (button.classList.contains('btn_edit') || /\bedit\b|\bubah\b|\bupdate\b|fa-pencil|fa-edit/.test(signature)) {
      type = 'edit';
    } else if (button.classList.contains('remove-data') || /\bhapus\b|\bdelete\b|\bremove\b|fa-trash|fa-close/.test(signature)) {
      type = 'delete';
    } else if (/\blihat\b|\bview\b|\bdetail\b|fa-eye|fa-newspaper/.test(signature)) {
      type = 'view';
    } else if (/\bexcel\b|\bpdf\b|\bekspor\b|\bexport\b|\bcetak\b|\bprint\b|\bsalin\b|\bcopy\b|\bkolom\b|\bcolumn\b|\btool\b|\balat\b|kunci api|dokumentasi|fa-file/.test(signature)) {
      type = 'utility';
    } else if (/\bfilter\b|\bterapkan\b|\bkirim\b|\bsend\b|\bproses\b|\buji\b|\btest\b|\brefresh\b|\brequest\b|\binstall\b|\bmuat ulang\b|fa-search|fa-refresh|fa-paper-plane/.test(signature)) {
      type = 'action';
    } else if (/\bbatal\b|\bkembali\b|\bback\b|\breset\b|\bundo\b|\bkembalikan\b|fa-undo|fa-arrow-left/.test(signature)) {
      type = 'neutral';
    } else if (button.classList.contains('btn-danger')) {
      type = 'delete';
    } else if (button.classList.contains('btn-primary')) {
      type = 'action';
    } else if (button.classList.contains('btn-success')) {
      type = 'create';
    } else if (button.classList.contains('btn-info')) {
      type = 'view';
    } else if (button.classList.contains('btn-warning')) {
      type = 'edit';
    }

    button.dataset.adminButtonReady = 'true';
    button.classList.add('admin-button', 'admin-button--' + type);
  }

  function normalizeButtons(root) {
    if (!root || root.nodeType !== 1) return;
    if (root.matches && root.matches('.btn')) normalizeFunctionalButton(root);
    root.querySelectorAll('.btn').forEach(normalizeFunctionalButton);
  }

  function normalizeTableAction(action) {
    if (action.dataset.actionReady) return;

    var label = textOf(action);
    var href = (action.getAttribute('href') || '') + ' ' + (action.dataset.href || '');
    var icon = action.querySelector('i');
    var signature = (label + ' ' + href + ' ' + (icon ? icon.className : '')).toLowerCase();
    var type = '';
    var iconClass = '';
    var accessibleLabel = label;

    if (action.classList.contains('remove-data') || /hapus|delete|remove/.test(signature)) {
      type = 'delete';
      iconClass = 'fa fa-trash';
      accessibleLabel = label || 'Hapus data';
    } else if (/ubah|edit|update|fa-pencil|fa-edit/.test(signature)) {
      type = 'edit';
      iconClass = 'fa fa-pencil';
      accessibleLabel = label || 'Ubah data';
    } else if (/lihat|view|detail|fa-newspaper|fa-eye/.test(signature)) {
      type = 'view';
      iconClass = 'fa fa-eye';
      accessibleLabel = label || 'Lihat data';
    }

    if (!type) return;

    action.dataset.actionReady = 'true';
    action.classList.add('table-action', 'table-action--' + type);
    action.setAttribute('title', accessibleLabel);
    action.setAttribute('aria-label', accessibleLabel);
    action.innerHTML = '<i class="' + iconClass + '" aria-hidden="true"></i>';
  }

  function normalizeTableToolbar(header) {
    if (header.dataset.toolbarReady) return;

    var title = header.querySelector('.widget-user-username');
    var description = header.querySelector('.widget-user-desc');
    var actions = header.querySelector('.pull-right');
    if (!title && !description) return;

    var copy = document.createElement('div');
    copy.className = 'table-toolbar__copy';
    if (title) copy.appendChild(title);
    if (description) copy.appendChild(description);
    header.insertBefore(copy, header.firstChild);

    header.classList.add('table-toolbar');
    header.dataset.toolbarReady = 'true';

    if (!actions) return;
    actions.classList.add('table-toolbar__actions');
    actions.querySelectorAll('.btn').forEach(function (button) {
      var signature = (textOf(button) + ' ' + (button.getAttribute('href') || '')).toLowerCase();
      button.classList.add('table-toolbar__button');
      button.classList.add(/export|excel|pdf|ekspor/.test(signature) ? 'table-toolbar__button--secondary' : 'table-toolbar__button--primary');
    });
  }

  function normalizeListFooter(content) {
    var rows = Array.from(content.querySelectorAll('.box-body > .row, .box-widget > .row'));

    rows.forEach(function (row) {
      if (!row.querySelector('#bulk, #filter, #field, .pagination, .dataTables_paginate')) return;
      if (row.dataset.listFooterReady) return;

      row.dataset.listFooterReady = 'true';
      row.classList.add('table-list-footer');

      var columns = Array.from(row.children).filter(function (child) {
        return child.matches('[class*="col-"]');
      });
      var controls = columns.find(function (column) {
        return column.querySelector('#bulk, #filter, #field, #sbtn, #apply, #reset');
      });
      var pagination = columns.find(function (column) {
        return column.querySelector('.pagination, .dataTables_paginate');
      });

      if (controls) {
        controls.classList.add('table-list-footer__controls');
        Array.from(controls.children).forEach(function (group) {
          if (group.matches('[class*="col-"]')) group.classList.add('table-list-footer__group');
        });
      }

      if (pagination) pagination.classList.add('table-list-footer__pagination');

      row.querySelectorAll('#apply, #sbtn, #reset').forEach(function (button) {
        button.classList.add('table-list-footer__button');
      });
      row.querySelectorAll('#bulk, #filter, #field').forEach(function (control) {
        control.classList.add('table-list-footer__control');
      });
    });
  }

  function enhanceFormPage(content) {
    if (content.dataset.formUiReady) return;

    var form = content.querySelector('form.form-horizontal:not([method="get"]), form[id^="form_"]:not([method="get"])');
    if (!form) return;

    content.dataset.formUiReady = 'true';
    form.classList.add('admin-modern-form');

    var card = form.closest('.box-widget, .box');
    if (card) card.classList.add('admin-form-card');

    var header = content.querySelector('.widget-user-header, .box-header');
    if (header) header.classList.add('admin-form-header');

    form.querySelectorAll('.form-group').forEach(function (group) {
      group.classList.add('admin-form-field');

      var label = group.querySelector(':scope > .control-label, :scope > label.control-label');
      var help = group.querySelector('.help-block');
      if (help && label && label.getAttribute('for')) {
        var target = label.getAttribute('for');
        var targetControl = document.getElementById(target);
        help.id = target + '-help';
        if (targetControl) targetControl.setAttribute('aria-describedby', help.id);
      }
    });

    form.querySelectorAll('.btn_save, #btn_cancel, .btn_action').forEach(function (button) {
      button.classList.add('admin-form-action');
      if (button.classList.contains('btn_save_back')) button.classList.add('admin-form-action--back');
      if (button.id === 'btn_cancel') button.classList.add('admin-form-action--cancel');
    });

    var isEditRoute = /\/(edit|update)(\/|$)/i.test(window.location.pathname) ||
      /\/edit_profile(\/|$)/i.test(window.location.pathname);
    if (isEditRoute) {
      form.querySelectorAll('#btn_cancel, .btn_cancel').forEach(function (button) {
        if (button.dataset.cancelNavigationReady) return;
        button.dataset.cancelNavigationReady = 'true';
        button.setAttribute('title', 'Kembali ke halaman sebelumnya (Ctrl+X)');
        button.setAttribute('aria-label', 'Kembali ke halaman sebelumnya');

        button.addEventListener('click', function (event) {
          event.preventDefault();
          event.stopImmediatePropagation();

          if (window.history.length > 1) {
            window.history.back();
            return;
          }

          var pathParts = window.location.pathname.split('/').filter(Boolean);
          var editIndex = pathParts.findIndex(function (part) {
            return /^(edit|update|edit_profile)$/i.test(part);
          });
          var fallbackPath = editIndex > 0 ? '/' + pathParts.slice(0, editIndex).join('/') : '/administrator';
          window.location.href = window.location.origin + fallbackPath;
        }, true);
      });
    }

    var primaryAction = form.querySelector('.btn_save, .btn_action');
    if (primaryAction) {
      var actionBar = primaryAction.closest('.row-fluid, .view-nav, .form-actions, .box-footer');
      if (!actionBar) actionBar = primaryAction.parentElement;
      if (actionBar && actionBar !== form && !actionBar.classList.contains('form-group')) {
        actionBar.classList.add('admin-form-actions');
      }
    }

    form.querySelectorAll('input, select, textarea').forEach(function (control) {
      if (control.type === 'hidden') return;
      if (!control.getAttribute('aria-label') && !control.id) {
        control.setAttribute('aria-label', control.name || 'Isian formulir');
      }
    });
  }

  function classifyPage(root) {
    if (!root) return;

    var content = root.querySelector('.content');
    if (!content) return;

    content.classList.remove('admin-page--list', 'admin-page--form', 'admin-page--detail');

    var isListPage = Boolean(content.querySelector('.table-responsive > table, table.dataTable'));

    if (isListPage) {
      content.classList.add('admin-page--list');
    }
    if (!isListPage && content.querySelector('form.form-horizontal:not([method="get"]), form[id^="form_"]:not([method="get"]), .view-nav')) {
      content.classList.add('admin-page--form');
    }
    if (!content.querySelector('form') && content.querySelector('.form-horizontal, .nav-stacked')) {
      content.classList.add('admin-page--detail');
    }

    content.querySelectorAll('.widget-user-header').forEach(function (header) {
      if (header.dataset.uiReady) return;
      header.dataset.uiReady = 'true';

      var title = textOf(header.querySelector('.widget-user-username'));
      if (title) header.setAttribute('aria-label', title);
      if (content.classList.contains('admin-page--list')) normalizeTableToolbar(header);
    });

    content.querySelectorAll('.table-responsive > table').forEach(function (table) {
      table.classList.add('table-hover');
      if (!table.getAttribute('aria-label')) {
        var heading = content.querySelector('.widget-user-username, .content-header h1');
        table.setAttribute('aria-label', textOf(heading) || 'Data administrasi');
      }
    });

    content.querySelectorAll('.table td:last-child').forEach(function (cell) {
      if (!cell.querySelector('a, button')) return;
      cell.classList.add('table-actions');
      cell.querySelectorAll('a, button').forEach(normalizeTableAction);
    });

    if (content.classList.contains('admin-page--list')) normalizeListFooter(content);

    content.querySelectorAll('.form-group').forEach(function (group) {
      if (group.querySelector('.help-block')) group.classList.add('has-help');
    });

    content.querySelectorAll('.view-nav').forEach(function (nav) {
      nav.setAttribute('role', 'group');
      nav.setAttribute('aria-label', 'Tindakan halaman');
    });

    normalizeButtons(content);

    var editorRoute = /\/(add|edit|update)(\/|$)/i.test(window.location.pathname) ||
      /\/(edit_profile|setting)(\/|$)/i.test(window.location.pathname);
    if (content.classList.contains('admin-page--form') && editorRoute) enhanceFormPage(content);
  }

  function enhance() {
    if (typeof window.initializeNativeDateInputs === 'function') {
      window.initializeNativeDateInputs(document.querySelector('.app-main'));
    }
    classifyPage(document.querySelector('.app-main'));
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', enhance);
  } else {
    enhance();
  }

  var dynamicButtonObserver = new MutationObserver(function (mutations) {
    mutations.forEach(function (mutation) {
      mutation.addedNodes.forEach(function (node) {
        normalizeButtons(node);
      });
    });
  });
  var appMain = document.querySelector('.app-main');
  if (appMain) dynamicButtonObserver.observe(appMain, { childList: true, subtree: true });

  document.addEventListener('silaris:page-loaded', enhance);
})();
