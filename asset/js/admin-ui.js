(function () {
  'use strict';

  function textOf(element) {
    return element ? element.textContent.replace(/\s+/g, ' ').trim() : '';
  }

  function removeLegacyPageHeaders(root) {
    if (!root || root.nodeType !== 1) return;

    if (root.matches && root.matches('.content-header')) {
      root.remove();
      return;
    }

    root.querySelectorAll('.content-header').forEach(function (header) {
      header.remove();
    });
  }

  function isEditRoutePath(pathname) {
    return /\/(?:edit|update|edit_profile)(?:\/|$)/i.test(pathname || '');
  }

  function setDetailActionContent(button, iconClass, label) {
    var icon = document.createElement('i');
    icon.className = iconClass;
    icon.setAttribute('aria-hidden', 'true');
    button.replaceChildren(icon, document.createTextNode(label));
    button.setAttribute('title', label);
    button.setAttribute('aria-label', label);
  }

  function normalizeDetailActionButtons(content) {
    if (!content || content.dataset.detailActionsReady) return;

    var actionSelector = [
      '.report-record-header__actions',
      '.record-detail-actions',
      '.crud-detail-actions',
      '.profile-page-actions',
      '.profile-page-header__actions'
    ].join(',');
    var actionGroups = Array.from(content.querySelectorAll(actionSelector));
    if (!actionGroups.length) return;

    content.dataset.detailActionsReady = 'true';
    var heading = content.querySelector('.report-record-header h1, .report-record-header .widget-user-username, .record-detail-header h1, .crud-detail-header h1, .profile-page-header h1, .widget-user-header .widget-user-username');
    var subject = textOf(heading).replace(/^detail\s+/i, '').trim() || 'Data';
    if (content.classList.contains('crud-detail-page')) subject = 'CRUD';

    actionGroups.forEach(function (group) {
      group.classList.add('detail-header-actions');

      group.querySelectorAll('a.btn, button.btn').forEach(function (button) {
        var href = button.getAttribute('href') || '';
        var signature = (textOf(button) + ' ' + button.id + ' ' + button.className + ' ' + href).toLowerCase();

        if (/btn_edit|\bedit\b|\bubah\b|\bupdate\b|\/edit(?:\/|$)/.test(signature)) {
          var editLabel = 'Edit ' + subject;
          button.classList.add('detail-header-button', 'detail-header-button--edit');
          setDetailActionContent(button, 'fa fa-pencil', editLabel);
        } else if (/btn_back|\bkembali\b|\bback\b|\bdaftar\b|fa-arrow-left|fa-undo/.test(signature)) {
          button.classList.add('detail-header-button', 'detail-header-button--back');
          setDetailActionContent(button, 'fa fa-arrow-left', 'Kembali');
        }
      });
    });
  }

  function normalizeFunctionalButton(button) {
    if (!button || button.dataset.adminButtonReady) return;
    if (button.matches('.table-action, .btn-box-tool, .btn-mode, .dt-button, .dt-paging-button, .qq-upload-button, .rest-tool-param-toggle, .rest-tool-send, .rest-tool-format') ||
        button.closest('.pagination, .dt-paging, .note-toolbar, .cke, .medium-editor-toolbar, .qq-uploader, .rest-tool-method, .rest-tool-view-switch')) return;

    var icon = button.querySelector('i');
    var signature = [
      textOf(button),
      button.value || '',
      button.id || '',
      button.className || '',
      button.getAttribute('title') || '',
      button.getAttribute('href') || '',
      icon ? icon.className : ''
    ].join(' ').toLowerCase();
    var type = 'neutral';

    if (button.classList.contains('btn_save_back') || /simpan.{0,18}(kembali|daftar)|save.{0,18}(back|list)/.test(signature)) {
      type = 'save-secondary';
    } else if (button.classList.contains('btn_save') || /\bsimpan\b|\bsave\b|fa-save/.test(signature)) {
      type = 'save';
    } else if (button.classList.contains('btn_add_new') || button.classList.contains('btn-add') || /\btambah\b|\badd\b|\bcreate\b|fa-plus/.test(signature)) {
      type = 'create';
    } else if (button.classList.contains('btn_edit') || /\bedit\b|\bubah\b|\bupdate\b|fa-pencil|fa-edit/.test(signature)) {
      type = 'edit';
    } else if (button.id === 'btn_cancel' || button.classList.contains('btn_cancel') || button.classList.contains('close') ||
        button.getAttribute('data-dismiss') === 'modal' || /\bbatal\b|\bkembali\b|\bback\b|\breset\b|\bundo\b|\bkembalikan\b|\btutup\b|fa-undo|fa-arrow-left/.test(signature)) {
      type = 'neutral';
    } else if (button.classList.contains('remove-data') || /\bhapus\b|\bdelete\b|\bremove\b|fa-trash/.test(signature)) {
      type = 'delete';
    } else if (/\blihat\b|\bview\b|\bdetail\b|fa-eye|fa-newspaper/.test(signature)) {
      type = 'view';
    } else if (/\bexcel\b|\bpdf\b|\bekspor\b|\bexport\b|\bcetak\b|\bprint\b|\bsalin\b|\bcopy\b|\bkolom\b|\bcolumn\b|\btool\b|\balat\b|kunci api|dokumentasi|fa-file/.test(signature)) {
      type = 'utility';
    } else if (/\bfilter\b|\bterapkan\b|\bkirim\b|\bsend\b|\bproses\b|\buji\b|\btest\b|\brefresh\b|\brequest\b|\binstall\b|\bmuat ulang\b|\bbackup\b|\brestore\b|\bimport\b|fa-search|fa-refresh|fa-paper-plane|fa-database/.test(signature)) {
      type = 'action';
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
    button.dataset.adminButtonKind = type;
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
    } else if (/deactivation|deaktivasi|nonaktifkan|disable|fa-minus-square|fa-ban/.test(signature)) {
      type = 'disable';
      iconClass = 'fa fa-ban';
      accessibleLabel = label || 'Nonaktifkan data';
    } else if (/activation|aktivasi|aktifkan|enable|fa-plus-square|fa-check-circle/.test(signature)) {
      type = 'enable';
      iconClass = 'fa fa-check';
      accessibleLabel = label || 'Aktifkan data';
    } else if (/ubah|edit|update|fa-pencil|fa-edit/.test(signature)) {
      type = 'edit';
      iconClass = 'fa fa-pencil';
      accessibleLabel = label || 'Ubah data';
    } else if (/lihat|view|detail|fa-newspaper|fa-eye/.test(signature)) {
      type = 'view';
      iconClass = 'fa fa-eye';
      accessibleLabel = label || 'Lihat data';
    } else if (action.matches('.label-default, .btn, [data-href]')) {
      type = 'utility';
      iconClass = icon ? icon.className : 'fa fa-ellipsis-h';
      accessibleLabel = label || action.getAttribute('title') || 'Tindakan data';
    }

    if (!type) return;

    action.dataset.actionReady = 'true';
    action.dataset.adminButtonKind = type;
    action.classList.add('table-action', 'table-action--' + type);
    action.setAttribute('title', accessibleLabel);
    action.setAttribute('aria-label', accessibleLabel);
    action.innerHTML = '<i class="' + iconClass + '" aria-hidden="true"></i>';
  }

  function normalizeTableActions(root) {
    if (!root || root.nodeType !== 1) return;

    var cells = [];
    if (root.matches && root.matches('.table td:last-child')) cells.push(root);
    root.querySelectorAll('.table td:last-child').forEach(function (cell) { cells.push(cell); });

    cells.forEach(function (cell) {
      var actions = Array.from(cell.querySelectorAll('a, button'));
      actions.forEach(normalizeTableAction);
      actions = actions.filter(function (action) { return action.classList.contains('table-action'); });
      if (!actions.length) return;

      cell.classList.add('table-actions');
      var group = cell.querySelector(':scope > .table-action-group');
      if (!group) {
        group = document.createElement('span');
        group.className = 'table-action-group';
        group.setAttribute('role', 'group');
        group.setAttribute('aria-label', 'Aksi data');
        cell.appendChild(group);
      }
      actions.forEach(function (action) {
        if (action.parentElement !== group) group.appendChild(action);
      });
    });
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

  function actionOrder(kind, position) {
    var headerOrder = {
      neutral: 10, utility: 20, view: 30, edit: 40,
      'save-secondary': 45, create: 50, action: 50, save: 50, delete: 60
    };
    var footerOrder = {
      neutral: 10, utility: 20, view: 25, edit: 30,
      'save-secondary': 40, create: 50, action: 50, save: 50, delete: 60
    };
    var orders = position === 'header' ? headerOrder : footerOrder;
    return orders[kind] || 20;
  }

  function normalizeActionGroup(group, position) {
    if (!group || group.closest('.note-toolbar, .cke, .medium-editor-toolbar, .dt-paging, .pagination')) return;

    var buttons = Array.from(group.querySelectorAll('.btn, .admin-button')).filter(function (button) {
      return !button.matches('.table-action, .btn-box-tool, .show-password') &&
        !button.closest('.dt-buttons, .table-actions, td');
    });
    if (!buttons.length) return;

    group.classList.add('admin-action-group', 'admin-action-group--' + position);
    group.setAttribute('role', group.getAttribute('role') || 'group');
    group.setAttribute('aria-label', group.getAttribute('aria-label') || (position === 'header' ? 'Tindakan halaman' : 'Tindakan formulir'));

    buttons.forEach(function (button) {
      normalizeFunctionalButton(button);
      button.style.order = String(actionOrder(button.dataset.adminButtonKind || 'neutral', position));
    });
  }

  function normalizeActionGroups(content) {
    if (!content) return;

    content.querySelectorAll([
      '.table-toolbar__actions',
      '.widget-user-header > .pull-right',
      '.box-header > .pull-right',
      '.crud-detail-actions',
      '.crud-builder-header-actions',
      '.record-detail-actions',
      '.report-record-header__actions',
      '.profile-page-actions',
      '.profile-page-header__actions',
      '.rest-tool-hero__actions',
      '.fidusia-form-header__actions'
    ].join(',')).forEach(function (group) {
      normalizeActionGroup(group, 'header');
    });

    content.querySelectorAll([
      '.admin-form-actions',
      '.view-nav',
      '.form-actions',
      '.crud-builder-actions',
      '.user-edit-actions',
      '.access-actions',
      '.settings-actionbar__buttons',
      '.fidusia-form-actions__buttons',
      'form > .box-footer',
      'form .box-footer > .pull-right',
      '.modal-footer',
      '.panel-footer',
      '.card-footer',
      'footer:not(.main-footer)'
    ].join(',')).forEach(function (group) {
      normalizeActionGroup(group, 'footer');
    });
  }

  function enhanceFormPage(content) {
    if (content.dataset.formUiReady) return;

    var form = content.querySelector('form.form-horizontal:not([method="get"]), form[id^="form_"]:not([method="get"])');
    if (!form) {
      form = Array.from(document.querySelectorAll('form.form-horizontal:not([method="get"]), form[id^="form_"]:not([method="get"])')).find(function (candidate) {
        return candidate.contains(content);
      });
    }
    if (!form) return;

    content.dataset.formUiReady = 'true';
    var formWrapsContent = form.contains(content);
    var formSurface = formWrapsContent
      ? (content.querySelector('.box-widget') || content.querySelector('.box') || content)
      : form;
    var controlsRoot = formWrapsContent ? content : form;
    formSurface.classList.add('admin-modern-form');

    var card = formSurface.matches('.box-widget, .box') ? formSurface : formSurface.closest('.box-widget, .box');
    if (card) card.classList.add('admin-form-card');

    var header = content.querySelector('.widget-user-header, .box-header');
    if (header) header.classList.add('admin-form-header');

    controlsRoot.querySelectorAll('.form-group').forEach(function (group) {
      group.classList.add('admin-form-field');
      if (isEditRoutePath(window.location.pathname) && !group.closest('table, .wrapper-crud, .wrapper-rest, .crud-builder-form')) {
        group.classList.add('admin-standard-edit-field');
      }

      var label = group.querySelector(':scope > .control-label, :scope > label.control-label');
      var help = group.querySelector('.help-block');
      if (help && label && label.getAttribute('for')) {
        var target = label.getAttribute('for');
        var targetControl = document.getElementById(target);
        help.id = target + '-help';
        if (targetControl) targetControl.setAttribute('aria-describedby', help.id);
      }
    });

    controlsRoot.querySelectorAll('.btn_save, #btn_cancel, .btn_action').forEach(function (button) {
      button.classList.add('admin-form-action');
      if (button.classList.contains('btn_save_back')) button.classList.add('admin-form-action--back');
      if (button.id === 'btn_cancel') button.classList.add('admin-form-action--cancel');
    });

    var isEditRoute = isEditRoutePath(window.location.pathname);
    if (isEditRoute) {
      controlsRoot.querySelectorAll('#btn_cancel, .btn_cancel').forEach(function (button) {
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

    var primaryAction = controlsRoot.querySelector('.btn_save, .btn_action');
    if (primaryAction) {
      var actionBar = primaryAction.closest('.row-fluid, .view-nav, .form-actions, .box-footer');
      if (!actionBar) actionBar = primaryAction.parentElement;
      if (actionBar && actionBar !== form && !actionBar.classList.contains('form-group')) {
        actionBar.classList.add('admin-form-actions');
      }
    }

    controlsRoot.querySelectorAll('input, select, textarea').forEach(function (control) {
      if (control.type === 'hidden') return;
      if (!control.getAttribute('aria-label') && !control.id) {
        control.setAttribute('aria-label', control.name || 'Isian formulir');
      }
    });
  }

  function isReportRecordPath(pathname) {
    return /\/(?:rekap[-_])?(?:laporan(?:[-_]bulanan)?|reportorium|daftar[-_]proses|legalisasi|waarmerking|fidusia)(?:\/|$)/i.test(pathname || '');
  }

  function reportRecordMode(pathname) {
    if (/\/view(?:\/|$)/i.test(pathname || '')) return 'detail';
    if (!isReportRecordPath(pathname)) return null;
    if (/\/(?:edit|update)(?:\/|$)/i.test(pathname || '')) return 'edit';
    return null;
  }

  function enhanceReportRecordPage(content) {
    var recordMode = reportRecordMode(window.location.pathname);
    if (!content || content.dataset.reportRecordUiReady || !recordMode) return;

    var editForm = recordMode === 'edit'
      ? content.querySelector('form.form-horizontal:not([method="get"]), form[id^="form_"]:not([method="get"])')
      : null;
    var detailBody = !editForm ? content.querySelector('.form-horizontal[id^="form_"], .form-horizontal[name^="form_"]') : null;
    var customRecordPage = content.classList.contains('record-detail-page') ||
      content.classList.contains('crud-detail-page') ||
      content.classList.contains('profile-page') ||
      content.querySelector('.fidusia-form-shell');

    if (!editForm && !detailBody && !customRecordPage) return;

    content.dataset.reportRecordUiReady = 'true';
    content.classList.add('report-record-page');
    content.classList.add('report-record-page--' + recordMode);

    if (customRecordPage) return;

    var widget = content.querySelector('.box-widget.widget-user-2');
    var header = widget && widget.querySelector(':scope > .widget-user-header');
    if (widget) widget.classList.add('report-record-card');

    if (header) {
      header.classList.add('report-record-header');

      var title = header.querySelector('.widget-user-username');
      var subtitle = header.querySelector('.widget-user-desc');
      if (title && !header.querySelector('.report-record-header__copy')) {
        var copy = document.createElement('div');
        copy.className = 'report-record-header__copy';

        title.parentNode.insertBefore(copy, title);
        copy.appendChild(title);
        if (subtitle) copy.appendChild(subtitle);
      }
    }

    if (detailBody) {
      detailBody.classList.add('report-detail-form');
      detailBody.querySelectorAll('br').forEach(function (lineBreak) {
        lineBreak.remove();
      });

      var detailFields = Array.from(detailBody.querySelectorAll('.form-group'));
      detailFields.forEach(function (group) {
        group.classList.add('report-detail-field');
        var label = group.querySelector(':scope > .control-label');
        var value = Array.from(group.children).find(function (child) {
          return child !== label && child.nodeType === 1;
        });

        if (label) label.classList.add('report-detail-label');
        if (value) value.classList.add('report-detail-value');
        if (group.querySelector('img, a[href*="download"], a[href*="uploads/"]')) {
          group.classList.add('report-detail-field--file');
        } else if (group.querySelector('table, pre, blockquote, .table-responsive, .well') || textOf(value).length > 180) {
          group.classList.add('report-detail-field--wide');
        }
      });

      // Pair compact cards in document order. A wide/file card closes the
      // current row; an unmatched card then expands so no empty grid cell is
      // left behind on any generated detail page.
      var pendingField = null;
      detailFields.forEach(function (group) {
        var isWide = group.classList.contains('report-detail-field--file') ||
          group.classList.contains('report-detail-field--wide');

        if (isWide) {
          if (pendingField) pendingField.classList.add('report-detail-field--unpaired');
          pendingField = null;
          return;
        }

        if (pendingField) {
          pendingField = null;
        } else {
          pendingField = group;
        }
      });
      if (pendingField) pendingField.classList.add('report-detail-field--unpaired');

      detailBody.querySelectorAll(':scope > .form-horizontal').forEach(function (nestedForm) {
        nestedForm.classList.add('report-detail-nested-form');
      });

      var detailActions = detailBody.querySelector('.view-nav');
      if (detailActions && header) {
        detailActions.classList.remove('view-nav');
        detailActions.classList.add('report-record-header__actions');
        header.appendChild(detailActions);
      }
    }

    if (editForm) {
      editForm.classList.add('report-edit-form');

      if (!editForm.querySelector(':scope > .report-record-section-heading')) {
        var sectionHeading = document.createElement('div');
        sectionHeading.className = 'report-record-section-heading';
        sectionHeading.innerHTML = '<strong>Informasi laporan</strong><span>Lengkapi data berikut dengan benar sebelum menyimpan perubahan.</span>';
        editForm.insertBefore(sectionHeading, editForm.firstChild);
      }

      editForm.querySelectorAll(':scope > .form-group').forEach(function (group) {
        group.classList.add('report-edit-field');
        if (group.querySelector('[id$="_galery"], .qq-uploader, textarea')) group.classList.add('report-edit-field--wide');
      });

      var saveBack = editForm.querySelector('.btn_save_back');
      if (saveBack && saveBack.id === 'btn_save') saveBack.id = 'btn_save_back';
    }
  }

  function classifyPage(root) {
    if (!root) return;

    var content = root.querySelector('.content');
    if (!content) return;

    content.classList.remove('admin-page--list', 'admin-page--form', 'admin-page--detail');
    content.classList.toggle('admin-edit-page', isEditRoutePath(window.location.pathname));

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

    normalizeTableActions(content);

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

    enhanceReportRecordPage(content);
    normalizeDetailActionButtons(content);
    normalizeActionGroups(content);
  }

  function enhance() {
    removeLegacyPageHeaders(document.querySelector('.app-main'));
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
        removeLegacyPageHeaders(node);
        normalizeButtons(node);
        if (node.nodeType === 1) {
          normalizeTableActions(node);
          var content = node.matches && node.matches('.content') ? node : node.closest && node.closest('.content');
          normalizeActionGroups(content || document.querySelector('.app-main .content'));
        }
      });
    });
  });
  var appMain = document.querySelector('.app-main');
  if (appMain) dynamicButtonObserver.observe(appMain, { childList: true, subtree: true });

  window.SilarisAdminUI = window.SilarisAdminUI || {};
  window.SilarisAdminUI.normalizeTableActions = normalizeTableActions;

  if (window.jQuery) {
    window.jQuery(document).off('draw.dt.silarisTableActions').on('draw.dt.silarisTableActions', 'table', function () {
      normalizeTableActions(this);
    });
  }

  document.addEventListener('silaris:page-loaded', enhance);
})();
