(function ($) {
  'use strict';

  var sequence = 0;

  function textOf(element) {
    return element ? element.textContent.replace(/\s+/g, ' ').trim() : '';
  }

  function tableTitle(table) {
    var content = table.closest('.content');
    var heading = content && content.querySelector('.widget-user-username, .content-header h1');
    return textOf(heading) || table.getAttribute('aria-label') || 'Data Administrasi';
  }

  function isExportableColumn(index, data, node) {
    if (!node) return true;
    var label = textOf(node).toLowerCase();
    return !node.querySelector('input[type="checkbox"]') &&
      !node.classList.contains('no-export') &&
      !/^(action|aksi|tindakan)$/.test(label);
  }

  function exportDate() {
    return new Intl.DateTimeFormat('id-ID', {
      day: '2-digit', month: 'long', year: 'numeric',
      hour: '2-digit', minute: '2-digit'
    }).format(new Date());
  }

  function exportFilename(title) {
    var cleanTitle = title.replace(/[\\/:*?"<>|]+/g, ' ').replace(/\s+/g, ' ').trim();
    var date = new Date();
    var stamp = [date.getFullYear(), String(date.getMonth() + 1).padStart(2, '0'), String(date.getDate()).padStart(2, '0')].join('-');
    return (cleanTitle || 'Data Administrasi') + ' - ' + stamp;
  }

  function exportOptions() {
    return {
      columns: isExportableColumn,
      modifier: { search: 'applied', order: 'applied', page: 'all' },
      format: {
        header: function (data, column, node) {
          return textOf(node) || $('<div>').html(data).text().trim();
        },
        body: function (data, row, column, node) {
          return cleanExportValue(node ? node.innerHTML : data);
        }
      }
    };
  }

  function removeLegacyDuplicates(content) {
    content.querySelectorAll('.table-toolbar__actions a[href*="/export"], .table-toolbar__actions a[href*="export_pdf"]').forEach(function (button) {
      button.remove();
    });

    content.querySelectorAll('.table-list-footer').forEach(function (footer) {
      footer.remove();
    });
  }

  function clearDetachedInstances() {
    if (!window.DataTable || !DataTable.settings) return;
    for (var index = DataTable.settings.length - 1; index >= 0; index -= 1) {
      var table = DataTable.settings[index].nTable;
      if (!table || !document.documentElement.contains(table)) DataTable.settings.splice(index, 1);
    }
  }

  function prepareEmptyTable(table) {
    var rows = table.querySelectorAll('tbody tr');
    if (rows.length === 1 && rows[0].querySelector('td[colspan]')) rows[0].remove();
  }

  function countFromContent(content) {
    var badge = content && content.querySelector('.widget-user-desc .label.bg-yellow, .widget-user-desc .label');
    var match = badge && textOf(badge).replace(/[.,\s]/g, '').match(/\d+/);
    return match ? Number(match[0]) : 0;
  }

  function legacyServerAdapter(table) {
    if (table.dataset.ajaxUrl || table.dataset.serverSide === 'false') return null;

    var form = table.closest('form[action]');
    if (!form || !form.querySelector('.pagination, .dataTables_paginate') && !document.querySelector('.widget-user-desc .label')) return null;

    var action = new URL(form.action, window.location.href);
    var initialTotal = countFromContent(table.closest('.content') || document);

    table.dataset.serverSide = 'true';
    table.dataset.totalRows = String(initialTotal);

    return function (request, callback) {
      var offset = Math.max(0, Number(request.start) || 0);
      var length = Math.max(1, Math.min(100, Number(request.length) || 25));
      var url = new URL(action.href);
      url.pathname = action.pathname.replace(/\/+$/, '') + '/' + offset;
      url.searchParams.set('q', request.search && request.search.value ? request.search.value : '');
      url.searchParams.set('length', String(length));

      fetch(url.href, {
        credentials: 'same-origin',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-SILARIS-DataTable': 'legacy'
        }
      }).then(function (response) {
        if (!response.ok) throw new Error('HTTP ' + response.status);
        return response.text();
      }).then(function (html) {
        var page = new DOMParser().parseFromString(html, 'text/html');
        var sourceTable = page.querySelector('table.dataTable');
        var rows = [];

        if (sourceTable) {
          sourceTable.querySelectorAll('tbody tr').forEach(function (row) {
            var cells = Array.from(row.children).filter(function (cell) { return cell.tagName === 'TD'; });
            if (!cells.length || cells.some(function (cell) { return cell.hasAttribute('colspan'); })) return;
            rows.push(cells.map(function (cell) { return cell.innerHTML; }));
          });
        }

        var filtered = countFromContent(page);
        callback({
          draw: request.draw,
          recordsTotal: Number(table.dataset.totalRows) || filtered,
          recordsFiltered: filtered,
          data: rows
        });
      }).catch(function () {
        callback({ draw: request.draw, recordsTotal: 0, recordsFiltered: 0, data: [] });
      });
    };
  }

  function initializeServerInteractions(table) {
    if (table.dataset.serverInteractionsReady) return;
    table.dataset.serverInteractionsReady = 'true';

    var $table = $(table);
    var $checkAll = $table.find('#check_all');
    $checkAll.off('.serverTable').on('ifChecked.serverTable ifUnchecked.serverTable change.serverTable', function (event) {
      var checked = event.type === 'ifChecked' || (event.type === 'change' && this.checked);
      var $checks = $table.find('tbody input.check');
      if ($.fn.iCheck) $checks.iCheck(checked ? 'check' : 'uncheck');
      else $checks.prop('checked', checked);
    });

    function enhanceDrawnRows() {
      if ($.fn.iCheck) $table.find('tbody input.flat-red').iCheck({ checkboxClass: 'icheckbox_flat-red' });
      if (table.dataset.tableKind === 'users' && $.fn.switchButton) {
        $table.find('.user-status-control').each(function () {
          var $control = $(this);
          var $switch = $control.find('input.switch-button');
          if (!$switch.length || $control.find('.switch-button-background').length) return;
          $switch.switchButton({
            show_labels: false,
            width: 42,
            height: 22,
            button_width: 22,
            clear: false
          });
        });
      }
      if (window.SilarisAdminUI && typeof window.SilarisAdminUI.normalizeTableActions === 'function') {
        window.SilarisAdminUI.normalizeTableActions(table);
      }
    }

    $table.on('draw.dt.serverTable', enhanceDrawnRows);
    enhanceDrawnRows();
  }

  function exportColumnIndexes(table) {
    return Array.from(table.querySelectorAll('thead th')).reduce(function (indexes, heading, index) {
      if (isExportableColumn(index, null, heading)) indexes.push(index);
      return indexes;
    }, []);
  }

  function cleanExportValue(value) {
    var holder = document.createElement('div');
    holder.innerHTML = value === null || typeof value === 'undefined' ? '' : String(value);

    holder.querySelectorAll('select').forEach(function (select) {
      var selected = select.querySelector('option[selected]') || select.options[select.selectedIndex];
      select.replaceWith(document.createTextNode(selected ? textOf(selected) : ''));
    });
    holder.querySelectorAll('textarea').forEach(function (textarea) {
      textarea.replaceWith(document.createTextNode(textarea.value || textarea.textContent || ''));
    });
    holder.querySelectorAll('input:not([type="checkbox"]):not([type="radio"]):not([type="button"]):not([type="submit"])').forEach(function (input) {
      input.replaceWith(document.createTextNode(input.value || input.getAttribute('value') || ''));
    });
    var hasVisibleText = textOf(holder) !== '';
    holder.querySelectorAll('a').forEach(function (link) {
      if (hasVisibleText) return;
      if (textOf(link)) return;
      try {
        var path = new URL(link.href, window.location.href).pathname.split('/');
        var fileName = decodeURIComponent(path[path.length - 1] || '');
        if (link.hasAttribute('download') || /\.[a-z0-9]{2,8}$/i.test(fileName)) link.textContent = fileName;
      } catch (error) {
        link.textContent = link.getAttribute('download') || '';
      }
    });
    holder.querySelectorAll('script, style, input, button, img, .btn, .table-action, a.label-default').forEach(function (element) {
      element.remove();
    });
    return textOf(holder);
  }

  function exportSearch(table) {
    try {
      return $(table).DataTable().search() || '';
    } catch (error) {
      return '';
    }
  }

  function normalizeExportRows(rows, indexes) {
    return rows.map(function (row) {
      var values = Array.isArray(row) ? row : Object.keys(row || {}).map(function (key) { return row[key]; });
      return indexes.map(function (index) { return cleanExportValue(values[index]); });
    });
  }

  function collectAjaxExportRows(table, indexes, search) {
    var batchSize = 2000;
    var rows = [];
    var start = 0;
    var draw = 1;
    var order = [];

    try {
      order = $(table).DataTable().order() || [];
    } catch (error) {
      order = [];
    }

    function requestBatch() {
      var url = new URL(table.dataset.ajaxUrl, window.location.href);
      url.searchParams.set('draw', String(draw++));
      url.searchParams.set('start', String(start));
      url.searchParams.set('length', String(batchSize));
      url.searchParams.set('search[value]', search);
      url.searchParams.set('search[regex]', 'false');
      url.searchParams.set('export', '1');
      if (order.length) {
        url.searchParams.set('order[0][column]', String(order[0][0]));
        url.searchParams.set('order[0][dir]', String(order[0][1] || 'asc'));
      }

      return fetch(url.href, {
        credentials: 'same-origin',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      }).then(function (response) {
        if (!response.ok) throw new Error('HTTP ' + response.status);
        return response.json();
      }).then(function (payload) {
        var batch = Array.isArray(payload.data) ? payload.data : [];
        rows = rows.concat(normalizeExportRows(batch, indexes));
        start += batch.length;
        var total = Number(payload.recordsFiltered);
        if (batch.length && start < total) return requestBatch();
        return rows;
      });
    }

    return requestBatch();
  }

  function collectLegacyExportRows(table, indexes, search) {
    var form = table.closest('form[action]');
    if (!form) return Promise.reject(new Error('Sumber data tabel tidak ditemukan.'));

    var action = new URL(form.action, window.location.href);
    var batchSize = 2000;
    var rows = [];
    var offset = 0;

    function requestBatch() {
      var url = new URL(action.href);
      url.pathname = action.pathname.replace(/\/+$/, '') + '/' + offset;
      url.searchParams.set('q', search);
      url.searchParams.set('length', String(batchSize));

      return fetch(url.href, {
        credentials: 'same-origin',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-SILARIS-DataTable': 'legacy',
          'X-SILARIS-DataTable-Export': 'legacy'
        }
      }).then(function (response) {
        if (!response.ok) throw new Error('HTTP ' + response.status);
        return response.text();
      }).then(function (html) {
        var page = new DOMParser().parseFromString(html, 'text/html');
        var sourceTable = page.querySelector('table.dataTable');
        var batch = [];
        if (sourceTable) {
          sourceTable.querySelectorAll('tbody tr').forEach(function (row) {
            var cells = Array.from(row.children).filter(function (cell) { return cell.tagName === 'TD'; });
            if (!cells.length || cells.some(function (cell) { return cell.hasAttribute('colspan'); })) return;
            batch.push(cells.map(function (cell) { return cell.innerHTML; }));
          });
        }

        rows = rows.concat(normalizeExportRows(batch, indexes));
        offset += batch.length;
        var total = countFromContent(page);
        // Some legacy views do not expose the total badge consistently. A
        // full batch therefore also means that another page may still exist.
        if (batch.length && (offset < total || batch.length === batchSize)) return requestBatch();
        return rows;
      });
    }

    return requestBatch();
  }

  function collectServerExport(table) {
    // DataTables can detach a hidden checkbox header from the live DOM. Keep
    // using the schema captured before initialization so raw server rows and
    // exported headers always share the same original column indexes.
    var schema = table._silarisExportSchema;
    var indexes = schema ? schema.indexes.slice() : exportColumnIndexes(table);
    var headings = Array.from(table.querySelectorAll('thead th'));
    var headers = schema ? schema.headers.slice() : indexes.map(function (index) { return textOf(headings[index]); });
    var search = exportSearch(table);
    var rows = table.dataset.ajaxUrl
      ? collectAjaxExportRows(table, indexes, search)
      : collectLegacyExportRows(table, indexes, search);

    return rows.then(function (data) {
      return { headers: headers, rows: data, search: search };
    });
  }

  function escapeHtml(value) {
    return String(value === null || typeof value === 'undefined' ? '' : value)
      .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;').replace(/'/g, '&#039;');
  }

  function renderServerPrint(printWindow, dataset, title) {
    var searchNote = dataset.search ? 'Hasil pencarian: ' + dataset.search : 'Seluruh data';
    var head = dataset.headers.map(function (header) { return '<th>' + escapeHtml(header) + '</th>'; }).join('');
    var body = dataset.rows.map(function (row) {
      return '<tr>' + row.map(function (value) { return '<td>' + escapeHtml(value) + '</td>'; }).join('') + '</tr>';
    }).join('');
    var html = '<!doctype html><html><head><meta charset="utf-8"><title>' + escapeHtml(title) + '</title>' +
      '<style>@page{size:A4 portrait;margin:12mm}*{box-sizing:border-box}body{margin:0;color:#24324a;font-family:Arial,sans-serif;font-size:9px}' +
      '.brand{display:flex;justify-content:space-between;align-items:flex-end;padding-bottom:10px;margin-bottom:16px;border-bottom:3px solid #08064d}' +
      '.brand strong{color:#08064d;font-size:18px}.brand span{color:#667085;font-size:9px}h1{margin:0 0 5px;text-align:center;font-size:17px;color:#101828}' +
      '.meta{margin:0 0 15px;text-align:center;color:#667085;font-size:8px}table{width:100%;border-collapse:collapse;table-layout:auto}' +
      'th{padding:7px 5px;border:1px solid #08064d;background:#08064d!important;color:#fff!important;text-align:left;font-size:8px}' +
      'td{padding:6px 5px;border:1px solid #d8e0ea;vertical-align:top;overflow-wrap:anywhere;word-break:break-word}' +
      'tbody tr:nth-child(even) td{background:#f5f7fb!important}thead{display:table-header-group}tr{page-break-inside:avoid}</style></head><body>' +
      '<div class="brand"><strong>SILARIS</strong><span>Kantor Wilayah Kementerian Hukum Sulawesi Tenggara</span></div>' +
      '<h1>' + escapeHtml(title) + '</h1><p class="meta">' + escapeHtml(searchNote + ' | ' + exportDate()) + '</p>' +
      '<table><thead><tr>' + head + '</tr></thead><tbody>' + body + '</tbody></table></body></html>';
    printWindow.document.open();
    printWindow.document.write(html);
    printWindow.document.close();
    printWindow.focus();
    window.setTimeout(function () { printWindow.print(); }, 350);
  }

  function downloadServerDataset(type, dataset, title) {
    var holder = document.createElement('div');
    holder.style.cssText = 'position:fixed;left:-10000px;top:0;width:1200px;visibility:hidden;';
    var exportTable = document.createElement('table');
    exportTable.dataset.officialDatatableReady = 'true';
    holder.appendChild(exportTable);
    document.body.appendChild(holder);

    var definitions = buttonDefinitions(title, null);
    var buttonIndex = type === 'excel' ? 1 : 2;
    var configuration = definitions[buttonIndex];
    if (dataset.search) configuration.messageTop = 'Hasil pencarian: ' + dataset.search + ' | ' + exportDate();

    var instance = new DataTable(exportTable, {
      data: dataset.rows,
      columns: dataset.headers.map(function (header) { return { title: header }; }),
      deferRender: true,
      pageLength: 25,
      searching: false,
      ordering: false,
      info: false,
      layout: { topStart: { buttons: [configuration] } }
    });
    instance.button(0).trigger();
    window.setTimeout(function () {
      instance.destroy();
      holder.remove();
    }, 1500);
  }

  function showExportError(error) {
    var message = error && error.message ? error.message : 'Data gagal disiapkan untuk diekspor.';
    if (window.swal) swal('Ekspor gagal', message, 'error');
    else window.alert('Ekspor gagal: ' + message);
  }

  function serverExportAction(type, table, title) {
    return function (event, dataTable, node) {
      var button = node && node[0] ? node[0] : node;
      var printWindow = null;
      if (type === 'print') {
        printWindow = window.open('', '_blank');
        if (!printWindow) {
          showExportError(new Error('Izinkan pop-up browser untuk menggunakan fitur cetak.'));
          return;
        }
        printWindow.document.write('<p style="font-family:Arial;padding:24px">Menyiapkan data hasil pencarian...</p>');
      }
      if (button) {
        button.disabled = true;
        button.setAttribute('aria-busy', 'true');
      }

      collectServerExport(table).then(function (dataset) {
        if (!dataset.rows.length) throw new Error('Tidak ada data hasil pencarian untuk diekspor.');
        if (type === 'print') renderServerPrint(printWindow, dataset, title);
        else downloadServerDataset(type, dataset, title);
      }).catch(function (error) {
        if (printWindow && !printWindow.closed) printWindow.close();
        showExportError(error);
      }).then(function () {
        if (button) {
          button.disabled = false;
          button.removeAttribute('aria-busy');
        }
      });
    };
  }

  function buttonDefinitions(title, table) {
    var filename = exportFilename(title);
    var metadata = function () { return 'Dicetak dari SILARIS pada ' + exportDate(); };
    var serverSide = table && table.dataset.serverSide === 'true';

    if (serverSide) {
      return [
        { extend: 'copy', text: '<i class="fa fa-copy"></i> Salin', exportOptions: exportOptions() },
        { text: '<i class="fa fa-file-excel-o"></i> Excel', action: serverExportAction('excel', table, title) },
        { text: '<i class="fa fa-file-pdf-o"></i> PDF', action: serverExportAction('pdf', table, title) },
        { text: '<i class="fa fa-print"></i> Cetak', action: serverExportAction('print', table, title) },
        { extend: 'colvis', text: '<i class="fa fa-columns"></i> Kolom', columns: isExportableColumn }
      ];
    }

    return [
      {
        extend: 'copy',
        text: '<i class="fa fa-copy"></i> Salin',
        title: title,
        exportOptions: exportOptions()
      },
      {
        extend: 'excelHtml5',
        text: '<i class="fa fa-file-excel-o"></i> Excel',
        title: title,
        filename: filename,
        sheetName: title.substring(0, 31),
        messageTop: metadata,
        autoFilter: true,
        createEmptyCells: true,
        footer: false,
        exportOptions: exportOptions(),
        customize: function (xlsx) {
          var sheet = xlsx.xl.worksheets['sheet1.xml'];
          var rows = $('sheetData row', sheet);
          rows.eq(0).attr('ht', '28').attr('customHeight', '1').find('c').attr('s', '2');
          rows.eq(1).attr('ht', '20').attr('customHeight', '1').find('c').attr('s', '3');
          rows.eq(2).attr('ht', '22').attr('customHeight', '1').find('c').attr('s', '22');
          rows.slice(3).attr('ht', '19').attr('customHeight', '1');
          $('cols col', sheet).each(function () {
            var width = parseFloat($(this).attr('width')) || 12;
            $(this).attr('width', Math.min(Math.max(width, 12), 42));
          });
        }
      },
      {
        extend: 'pdfHtml5',
        text: '<i class="fa fa-file-pdf-o"></i> PDF',
        title: title,
        filename: filename,
        messageTop: metadata,
        orientation: 'portrait',
        pageSize: 'A4',
        footer: false,
        exportOptions: exportOptions(),
        customize: function (document) {
          document.pageMargins = [24, 50, 24, 42];
          document.defaultStyle.fontSize = 7;
          document.defaultStyle.color = '#24324a';
          document.styles.title = {
            alignment: 'center', bold: true, color: '#08064d', fontSize: 16,
            margin: [0, 0, 0, 8]
          };
          document.styles.message = {
            alignment: 'center', color: '#667085', fontSize: 8,
            margin: [0, 0, 0, 14]
          };
          document.styles.tableHeader = {
            bold: true, color: '#ffffff', fillColor: '#08064d',
            alignment: 'left', fontSize: 8, margin: [3, 5, 3, 5]
          };

          var table = document.content.find(function (item) { return item.table; });
          if (table) {
            var body = table.table.body || [];
            var columnCount = body[0] ? body[0].length : 0;
            var weights = [];
            // A4 portrait = 595pt. Sisakan ruang untuk margin, padding sel,
            // dan garis tabel agar tidak terpotong di sisi kanan kertas.
            var usableWidth = Math.max(120, 547 - (columnCount * 11));

            for (var column = 0; column < columnCount; column += 1) {
              var longestValue = 0;
              body.forEach(function (row) {
                var cell = row[column];
                var value = cell && typeof cell === 'object' ? cell.text : cell;
                longestValue = Math.max(longestValue, String(value || '').length);
              });
              weights.push(Math.min(Math.max(Math.sqrt(longestValue || 1), 2.4), 6.5));
            }

            var totalWeight = weights.reduce(function (total, weight) { return total + weight; }, 0) || 1;
            table.table.widths = weights.map(function (weight) {
              return Math.floor((weight / totalWeight) * usableWidth);
            });
            table.table.dontBreakRows = true;
            table.table.keepWithHeaderRows = 1;
            table.layout = {
              fillColor: function (row) { return row > 0 && row % 2 === 0 ? '#f5f7fb' : null; },
              hLineColor: function () { return '#d8e0ea'; },
              vLineColor: function () { return '#d8e0ea'; },
              hLineWidth: function () { return 0.5; },
              vLineWidth: function () { return 0.5; },
              paddingLeft: function () { return 5; },
              paddingRight: function () { return 5; },
              paddingTop: function () { return 4; },
              paddingBottom: function () { return 4; }
            };
          }

          document.header = {
            columns: [
              { text: 'SILARIS', bold: true, color: '#08064d', fontSize: 10 },
              { text: 'Kantor Wilayah Kementerian Hukum Sulawesi Tenggara', alignment: 'right', color: '#667085', fontSize: 7 }
            ],
            margin: [28, 18, 28, 0]
          };
          document.footer = function (page, pages) {
            return {
              columns: [
                { text: 'Dokumen administrasi SILARIS', color: '#667085', fontSize: 7 },
                { text: 'Halaman ' + page + ' dari ' + pages, alignment: 'right', color: '#667085', fontSize: 7 }
              ],
              margin: [28, 10, 28, 0]
            };
          };
        }
      },
      {
        extend: 'print',
        text: '<i class="fa fa-print"></i> Cetak',
        title: title,
        messageTop: metadata,
        footer: false,
        exportOptions: exportOptions(),
        customize: function (printWindow) {
          var printDocument = printWindow.document;
          printDocument.title = title;
          $(printDocument.body).addClass('silaris-datatable-print');

          var style = printDocument.createElement('style');
          style.textContent = [
            '@page { size: A4 portrait; margin: 12mm; }',
            'body { color:#24324a; font-family:Arial,sans-serif; font-size:10px; }',
            '.silaris-print-brand { display:flex; justify-content:space-between; align-items:flex-end; padding-bottom:12px; margin-bottom:18px; border-bottom:3px solid #08064d; }',
            '.silaris-print-brand strong { color:#08064d; font-size:18px; }',
            '.silaris-print-brand span { color:#667085; font-size:10px; }',
            'h1 { margin:0 0 5px; color:#101828; font-size:17px; text-align:center; }',
            'h1 + div, h1 + p { margin:0 0 16px; color:#667085; font-size:9px; text-align:center; }',
            'table { width:100% !important; table-layout:auto !important; border-collapse:collapse !important; font-size:8px !important; }',
            'thead th { padding:7px 6px !important; border:1px solid #08064d !important; background:#08064d !important; color:#fff !important; font-size:8px; text-align:left; overflow-wrap:anywhere; word-break:break-word; }',
            'tbody td { padding:6px !important; border:1px solid #d8e0ea !important; vertical-align:top; overflow-wrap:anywhere; word-break:break-word; }',
            'tbody tr:nth-child(even) td { background:#f5f7fb !important; }',
            'img, button, input, .btn { display:none !important; }'
          ].join('');
          printDocument.head.appendChild(style);

          var brand = printDocument.createElement('div');
          brand.className = 'silaris-print-brand';
          var brandName = printDocument.createElement('strong');
          brandName.textContent = 'SILARIS';
          var organization = printDocument.createElement('span');
          organization.textContent = 'Kantor Wilayah Kementerian Hukum Sulawesi Tenggara';
          brand.appendChild(brandName);
          brand.appendChild(organization);
          printDocument.body.insertBefore(brand, printDocument.body.firstChild);
        }
      },
      { extend: 'colvis', text: '<i class="fa fa-columns"></i> Kolom', columns: isExportableColumn }
    ];
  }

  function initializeTable(table) {
    if (table.dataset.officialDatatableReady || table.closest('#codeigniter_profiler') || table.classList.contains('no-datatable')) return;
    if (DataTable.isDataTable(table)) return;

    prepareEmptyTable(table);
    if (!table.id) table.id = 'admin-datatable-' + (++sequence);

    var originalHeadings = Array.from(table.querySelectorAll('thead th'));
    var originalExportIndexes = exportColumnIndexes(table);
    table._silarisExportSchema = {
      indexes: originalExportIndexes,
      headers: originalExportIndexes.map(function (index) { return textOf(originalHeadings[index]); })
    };

    var columnCount = originalHeadings.length;
    if (!columnCount) return;

    var disabledColumns = [];
    var hiddenColumns = [];
    table.querySelectorAll('thead th').forEach(function (heading, index) {
      var label = textOf(heading).toLowerCase();
      if (heading.querySelector('input[type="checkbox"]')) {
        disabledColumns.push(index);
        hiddenColumns.push(index);
      } else if (/^(action|aksi|tindakan)$/.test(label)) {
        disabledColumns.push(index);
      }
    });

    var title = tableTitle(table);
    var legacyAjax = legacyServerAdapter(table);
    var columnDefinitions = [];
    if (disabledColumns.length) columnDefinitions.push({ orderable: false, targets: disabledColumns });
    if (hiddenColumns.length) columnDefinitions.push({ visible: false, searchable: false, targets: hiddenColumns });
    if (table.dataset.tableKind === 'users' && columnCount >= 5) {
      columnDefinitions.push(
        { className: 'user-table__identity', width: '42%', targets: 1 },
        { className: 'user-table__username', width: '23%', targets: 2 },
        { className: 'user-table__status', width: '17%', targets: 3 },
        { className: 'user-table__actions', width: '18%', targets: 4 }
      );
    }
    var options = {
      autoWidth: false,
      deferRender: true,
      pageLength: 25,
      lengthMenu: [10, 25, 50, 100],
      order: [],
      stateSave: true,
      layout: {
        topStart: [
          { pageLength: { menu: [10, 25, 50, 100] } },
          { buttons: buttonDefinitions(title, table) }
        ],
        topEnd: { search: { placeholder: 'Cari data...' } },
        bottomStart: 'info',
        bottomEnd: 'paging'
      },
      columnDefs: columnDefinitions,
      language: {
        emptyTable: 'Data tidak tersedia',
        info: 'Menampilkan _START_–_END_ dari _TOTAL_ data',
        infoEmpty: 'Tidak ada data',
        infoFiltered: '(difilter dari _MAX_ data)',
        lengthMenu: 'Tampilkan _MENU_ data',
        search: 'Cari:',
        zeroRecords: 'Data yang dicari tidak ditemukan',
        paginate: { first: 'Pertama', last: 'Terakhir', next: 'Berikutnya', previous: 'Sebelumnya' }
      }
    };

    if (legacyAjax) {
      options.ajax = legacyAjax;
      options.processing = true;
      options.serverSide = true;
      options.searchDelay = 350;
      options.ordering = false;
    } else if (table.dataset.ajaxUrl) {
      options.ajax = {
        url: table.dataset.ajaxUrl,
        dataSrc: 'data',
        cache: true
      };
      options.processing = true;
      if (table.dataset.serverSide === 'true') {
        options.serverSide = true;
        options.searchDelay = 350;
      }
    }

    new DataTable(table, options);
    table.dataset.officialDatatableReady = 'true';
    if (table.dataset.serverSide === 'true') initializeServerInteractions(table);
    var container = table.closest('.dt-container');
    if (container) container.classList.add('silaris-datatable');
  }

  function initialize() {
    if (!window.DataTable || !DataTable.Buttons) return;
    var content = document.querySelector('.app-main .content');
    if (!content) return;

    clearDetachedInstances();
    removeLegacyDuplicates(content);
    content.querySelectorAll('.table-responsive > table.dataTable, table.dataTable').forEach(initializeTable);
  }

  $(initialize);
  document.addEventListener('silaris:page-loaded', initialize);

  $(document).off('click.serverTableDelete', 'table[data-server-side="true"] .remove-data');
  $(document).on('click.serverTableDelete', 'table[data-server-side="true"] .remove-data', function (event) {
    var button = this;
    if (button.dataset.confirmingDelete === 'true') return;
    event.preventDefault();
    event.stopImmediatePropagation();
    button.dataset.confirmingDelete = 'true';

    swal({
      title: 'Apakah Anda yakin?',
      text: 'Data yang dihapus tidak dapat dikembalikan.',
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#DD6B55',
      confirmButtonText: 'Ya, hapus',
      cancelButtonText: 'Batal',
      closeOnConfirm: true
    }, function (confirmed) {
      button.dataset.confirmingDelete = 'false';
      if (confirmed) window.location.href = button.getAttribute('data-href');
    });
  });
})(jQuery);
