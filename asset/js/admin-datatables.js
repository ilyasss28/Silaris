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
          return textOf(node) || $('<div>').html(data).text().trim();
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

  function buttonDefinitions(title) {
    var filename = exportFilename(title);
    var metadata = function () { return 'Dicetak dari SILARIS pada ' + exportDate(); };
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

    var columnCount = table.querySelectorAll('thead th').length;
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
    var columnDefinitions = [];
    if (disabledColumns.length) columnDefinitions.push({ orderable: false, targets: disabledColumns });
    if (hiddenColumns.length) columnDefinitions.push({ visible: false, searchable: false, targets: hiddenColumns });
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
          { buttons: buttonDefinitions(title) }
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

    new DataTable(table, options);
    table.dataset.officialDatatableReady = 'true';
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
})(jQuery);
