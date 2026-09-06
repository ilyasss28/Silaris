<?php
$is_submitted = $compliance_status === 'submitted';
$status_title = $is_submitted ? 'Notaris Sudah Melapor' : 'Notaris Belum Melapor';
$status_description = $is_submitted
  ? 'Notaris yang telah mengirimkan sedikitnya satu laporan pada periode terpilih.'
  : 'Notaris yang belum memiliki laporan pada periode terpilih.';
$page_base = site_url('administrator/dashboard/compliance_notaries') . '?' . $compliance_query;
$download_url = site_url('administrator/dashboard/download_compliance') . '?' . $compliance_query . '&status=' . $compliance_status;
$back_url = site_url('administrator/dashboard') . '?' . $compliance_query;
$format_date = function ($date) {
  if (!$date || $date === '0000-00-00') return '-';
  $formatted = format_date_id($date);
  return $formatted !== '' ? $formatted : '-';
};
?>

<style>
.compliance-page{--cp-navy:#07064f;--cp-gold:#ffcf00;--cp-ink:#101828;--cp-muted:#667085;--cp-line:#dfe5ee;padding-bottom:10px}.compliance-page__header{margin-bottom:16px;padding:21px 23px;display:flex;align-items:center;justify-content:space-between;gap:18px;border:1px solid var(--cp-line);border-top:3px solid var(--cp-gold);border-radius:11px;background:#fff}.compliance-page__eyebrow{margin-bottom:5px;color:#8a6b00;font-size:10px;font-weight:800;letter-spacing:.05em;text-transform:uppercase}.compliance-page h1{margin:0;color:var(--cp-ink);font-size:22px;font-weight:800}.compliance-page__description{margin:5px 0 0;color:#7c8798;font-size:11px}.compliance-page__actions{display:flex;align-items:center;gap:8px}.cp-button{height:37px;padding:0 13px;display:inline-flex;align-items:center;justify-content:center;border:1px solid #d6dde8;border-radius:8px;background:#fff;color:#435066;font-size:10px;font-weight:800;text-decoration:none;white-space:nowrap}.cp-button:hover{border-color:#aeb8c7;color:var(--cp-navy)}.cp-button--download{border-color:var(--cp-navy);background:var(--cp-navy);color:#fff}.cp-button--download:hover{background:#111064;color:#fff}.compliance-page__card{overflow:hidden;border:1px solid var(--cp-line);border-radius:11px;background:#fff}.compliance-page__toolbar{padding:15px 18px;display:flex;align-items:center;justify-content:space-between;gap:14px;border-bottom:1px solid #edf0f4}.cp-tabs{display:flex;gap:7px}.cp-tab{height:35px;padding:0 12px;display:inline-flex;align-items:center;border:1px solid #d8dee8;border-radius:8px;color:#657084;font-size:10px;font-weight:800;text-decoration:none}.cp-tab.is-active{border-color:var(--cp-navy);background:var(--cp-navy);color:#fff}.cp-search{position:relative;width:min(300px,100%)}.cp-search i{position:absolute;top:50%;left:12px;z-index:1;transform:translateY(-50%);color:#8792a2;font-size:11px}.cp-search input{width:100%;height:37px;padding:0 12px 0 32px;border:1px solid #d8dee8!important;border-radius:8px;background:#fff!important;color:#263247!important;font-size:10.5px;outline:0}.cp-search input::placeholder{color:#98a2b3!important;opacity:1}.cp-search input:focus{border-color:#b79a1a!important;background:#fff!important;box-shadow:0 0 0 2px rgba(255,207,0,.13)}.cp-summary{padding:10px 18px;display:flex;align-items:center;justify-content:space-between;background:#fafbfc;color:#778294;font-size:10px}.cp-summary strong{color:var(--cp-ink)}.cp-table-wrap{overflow:auto}.cp-table{width:100%;border-collapse:collapse}.cp-table th{padding:11px 13px;background:#f5f7fa;color:#738095;font-size:9.5px;font-weight:800;text-align:left;text-transform:uppercase;white-space:nowrap}.cp-table td{padding:12px 13px;border-top:1px solid #edf0f4;color:#4e5a6e;font-size:10.5px;vertical-align:middle}.cp-table td strong,.cp-table td small{display:block}.cp-table td strong{color:#263247;font-size:11px}.cp-table td small{margin-top:3px;color:#929baa;font-size:10px}.cp-status{display:inline-flex;padding:5px 8px;border-radius:999px;font-size:9px;font-weight:800;white-space:nowrap}.cp-status--submitted{background:#eaf8f1;color:#168252}.cp-status--missing{background:#fff0f1;color:#c23949}.cp-empty{padding:55px 20px;color:#8791a1;text-align:center}.cp-empty i{display:block;margin-bottom:9px;color:#c5ccd6;font-size:28px}.cp-empty strong,.cp-empty span{display:block}.cp-empty strong{color:#4e596b;font-size:12px}.cp-empty span{margin-top:4px;font-size:10.5px}.cp-no-result{display:none;padding:35px 20px;color:#8791a1;font-size:10.5px;text-align:center}.cp-no-result.is-visible{display:block}@media(max-width:760px){.compliance-page__header,.compliance-page__toolbar{align-items:stretch;flex-direction:column}.compliance-page__actions,.cp-tabs{width:100%;flex-wrap:wrap}.cp-button,.cp-tab{flex:1}.cp-search{width:100%}.cp-table{min-width:720px}}
</style>

<section class="content compliance-page">
  <header class="compliance-page__header">
    <div>
      <div class="compliance-page__eyebrow">Kepatuhan Pelaporan · <?= _ent($compliance_period); ?></div>
      <h1><?= _ent($status_title); ?></h1>
      <p class="compliance-page__description"><?= _ent($status_description); ?></p>
    </div>
    <div class="compliance-page__actions">
      <a class="cp-button" href="<?= _ent($back_url); ?>"><i class="fa fa-arrow-left"></i>&nbsp; Kembali</a>
      <a class="cp-button cp-button--download" href="<?= _ent($download_url); ?>"><i class="fa fa-file-excel-o"></i>&nbsp; Unduh Excel</a>
    </div>
  </header>

  <article class="compliance-page__card">
    <div class="compliance-page__toolbar">
      <nav class="cp-tabs" aria-label="Status kepatuhan">
        <a class="cp-tab <?= $is_submitted ? 'is-active' : ''; ?>" href="<?= _ent($page_base . '&status=submitted'); ?>">Sudah Melapor</a>
        <a class="cp-tab <?= !$is_submitted ? 'is-active' : ''; ?>" href="<?= _ent($page_base . '&status=missing'); ?>">Belum Melapor</a>
      </nav>
      <label class="cp-search" for="compliance-search"><i class="fa fa-search"></i><input id="compliance-search" type="search" placeholder="Cari nama, nomor telepon, atau wilayah" autocomplete="off"></label>
    </div>
    <div class="cp-summary"><span>Periode: <strong><?= _ent($compliance_period); ?></strong></span><span><strong id="compliance-visible-count"><?= number_format(count($compliance_rows), 0, ',', '.'); ?></strong> notaris</span></div>

    <?php if ($compliance_rows): ?>
      <div class="cp-table-wrap">
        <table class="cp-table">
          <thead><tr><th>No.</th><th>Nama Notaris</th><th>Nomor Telepon</th><th>Wilayah</th><th>Status</th><th>Jumlah Laporan</th><th>Laporan Terakhir</th></tr></thead>
          <tbody>
            <?php foreach ($compliance_rows as $index => $notary): ?>
              <?php $phone_display = $notary['phone_number'] === '-' ? '-' : format_phone_number($notary['phone_number']); ?>
              <tr data-compliance-search-row data-search="<?= _ent(strtolower($notary['display_name'] . ' ' . $phone_display . ' ' . $notary['region_name'])); ?>">
                <td><?= $index + 1; ?></td>
                <td><strong><?= _ent($notary['display_name']); ?></strong></td>
                <td><?= $phone_display === '-' ? '<small>Belum tersedia</small>' : _ent($phone_display); ?></td>
                <td><?= _ent($notary['region_name']); ?></td>
                <td><span class="cp-status cp-status--<?= _ent($notary['status']); ?>"><?= $is_submitted ? 'Sudah Melapor' : 'Belum Melapor'; ?></span></td>
                <td><?= number_format($notary['report_count'], 0, ',', '.'); ?></td>
                <td class="table-date-cell"><?= _ent($format_date($notary['last_report'])); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="cp-no-result" id="compliance-no-result">Data yang dicari tidak ditemukan.</div>
    <?php else: ?>
      <div class="cp-empty"><i class="fa fa-check-circle-o"></i><strong>Tidak ada data pada status ini</strong><span>Silakan periksa status lainnya atau ubah periode dari dashboard.</span></div>
    <?php endif; ?>
  </article>
</section>

<script>
(function () {
  var search = document.getElementById('compliance-search');
  var rows = document.querySelectorAll('[data-compliance-search-row]');
  var count = document.getElementById('compliance-visible-count');
  var empty = document.getElementById('compliance-no-result');
  if (!search || !count) return;
  search.addEventListener('input', function () {
    var keyword = search.value.toLowerCase().trim();
    var visible = 0;
    Array.prototype.forEach.call(rows, function (row) {
      var match = row.getAttribute('data-search').indexOf(keyword) !== -1;
      row.hidden = !match;
      if (match) visible++;
    });
    count.textContent = visible.toLocaleString('id-ID');
    if (empty) empty.classList.toggle('is-visible', visible === 0);
  });
}());
</script>
