<style type="text/css">
    * { font-family: Arial, Helvetica, sans-serif; color: #24324a; }
    h1 { margin: 0; color: #08064d; font-size: 16pt; }
    .brand { width: 100%; border-bottom: 3px solid #ffcf00; padding-bottom: 3mm; }
    .brand-name { color: #08064d; font-size: 12pt; font-weight: bold; }
    .organization { color: #667085; font-size: 10pt; text-align: right; }
    .document-title { margin: 5mm 0 1mm; text-align: center; }
    .document-meta { margin: 0 0 5mm; color: #667085; font-size: 11pt; text-align: center; }
    table { width: 100%; border-collapse: collapse; table-layout: fixed; }
    thead { display: table-header-group; }
    tr { page-break-inside: avoid; }
    th, td {
        border: 0.2mm solid #d8e0ea;
        padding: 2.2mm 1.8mm;
        vertical-align: top;
        font-size: 10pt;
        line-height: 1.25;
        word-wrap: break-word;
    }
    th {
        border-color: #08064d;
        background: #08064d;
        color: #ffffff;
        font-weight: bold;
        font-size: 11pt;
        text-align: left;
    }
    .number-cell { text-align: center; }
    .date-cell { white-space: nowrap; }
    .empty-row { padding: 8mm; color: #667085; text-align: center; }
    .footer { width: 100%; border-top: 0.2mm solid #d8e0ea; padding-top: 2mm; color: #667085; font-size: 9pt; }
    .footer-page { text-align: right; }
</style>
<page orientation="<?= html_escape($orientation); ?>" backtop="20mm" backright="20mm" backbottom="20mm" backleft="20mm">
    <page_header>
        <table class="brand">
            <tr>
                <td class="brand-name" style="border: none; padding: 0; width: 25%;">SILARIS</td>
                <td class="organization" style="border: none; padding: 0; width: 75%;">Kantor Wilayah Kementerian Hukum Sulawesi Tenggara</td>
            </tr>
        </table>
    </page_header>

    <h1 class="document-title"><?= html_escape($title); ?></h1>
    <p class="document-meta">Dokumen administrasi SILARIS &bull; Dicetak <?= html_escape($generated_at); ?></p>

    <table>
        <col style="width: <?= html_escape($column_widths[0]); ?>%;">
        <?php foreach ($fields as $index => $field): ?>
            <col style="width: <?= html_escape($column_widths[$index + 1]); ?>%;">
        <?php endforeach; ?>
        <thead>
            <tr>
                <th class="number-cell">No.</th>
                <?php foreach ($fields as $field): ?>
                    <th><?= html_escape(ucwords(str_replace(array('_', '-'), ' ', $field))); ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php if (!$results): ?>
                <tr><td class="empty-row" colspan="<?= count($fields) + 1; ?>">Tidak ada data untuk ditampilkan.</td></tr>
            <?php endif; ?>
            <?php foreach ($results as $row_index => $row): ?>
                <tr>
                    <td class="number-cell"><?= $row_index + 1; ?></td>
                    <?php foreach ($fields as $field): ?>
                        <?php
                            $value = isset($row->{$field}) ? $row->{$field} : '';
                            if (isset($date_fields[$field])) {
                                $value = format_date_id($value);
                            } elseif (preg_match('/(telepon|phone)/i', $field) && trim((string) $value) !== '') {
                                $value = format_phone_number($value);
                            } elseif ($field === 'wilayah') {
                                $value = format_title_case($value);
                            }
                        ?>
                        <td<?= isset($date_fields[$field]) ? ' class="date-cell"' : ''; ?>><?= html_escape($value === null ? '' : $value); ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <page_footer>
        <table class="footer">
            <tr>
                <td style="border: none; padding: 0; width: 70%;">Dokumen administrasi SILARIS</td>
                <td class="footer-page" style="border: none; padding: 0; width: 30%;">Halaman [[page_cu]] dari [[page_nb]]</td>
            </tr>
        </table>
    </page_footer>
</page>
