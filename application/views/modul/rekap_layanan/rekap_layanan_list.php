<?php
$filter_query = $rekap_filters;
$filter_query['page'] = null;
$export_query = array_filter($filter_query, function ($value) { return $value !== '' && $value !== null; });
$route_map = array(
    'laporan' => 'laporan',
    'reportorium' => 'reportorium',
    'daftar_proses' => 'daftar_proses',
    'legalisasi' => 'legalisasi',
    'waarmerking' => 'waarmerking',
    'fidusia' => 'fidusia',
);
?>
<section class="content service-recap-page">
  <div class="service-recap-shell">
    <header class="service-recap-header">
      <div>
        <span class="service-recap-eyebrow">PEMANTAUAN TERPADU</span>
        <h1>Rekap Layanan</h1>
        <p>Ringkasan seluruh laporan dalam satu sumber data, tanpa membuat salinan data baru.</p>
      </div>
      <div class="service-recap-header__actions">
        <span class="service-recap-period"><i class="fa fa-calendar"></i><?= _ent($rekap_period_label); ?></span>
        <a class="btn service-recap-export" href="<?= site_url('rekap-layanan/export').'?'.http_build_query($export_query); ?>">
          <i class="fa fa-file-excel-o"></i> Unduh Excel
        </a>
      </div>
    </header>

    <form class="service-recap-filter" method="get" action="<?= site_url('rekap-layanan'); ?>">
      <div class="service-recap-filter__title">
        <span><i class="fa fa-filter"></i></span>
        <div><strong>Filter Rekap</strong><small>Persempit data berdasarkan periode, layanan, wilayah, atau Notaris.</small></div>
      </div>
      <div class="service-recap-filter__grid">
        <label>Periode
          <select name="mode" id="recap-mode">
            <option value="month" <?= $rekap_filters['mode'] === 'month' ? 'selected' : ''; ?>>Per Bulan</option>
            <option value="quarter" <?= $rekap_filters['mode'] === 'quarter' ? 'selected' : ''; ?>>Per Triwulan</option>
            <option value="semester" <?= $rekap_filters['mode'] === 'semester' ? 'selected' : ''; ?>>Per 6 Bulan</option>
            <option value="year" <?= $rekap_filters['mode'] === 'year' ? 'selected' : ''; ?>>Per Tahun</option>
          </select>
        </label>
        <label>Tahun
          <select name="year">
            <?php foreach ($rekap_years as $year): ?><option value="<?= $year; ?>" <?= (int) $rekap_filters['year'] === (int) $year ? 'selected' : ''; ?>><?= $year; ?></option><?php endforeach; ?>
          </select>
        </label>
        <label class="recap-period-option" data-mode="month">Bulan
          <select name="month">
            <?php foreach (array(1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember') as $number => $month): ?>
              <option value="<?= $number; ?>" <?= (int) $rekap_filters['month'] === $number ? 'selected' : ''; ?>><?= $month; ?></option>
            <?php endforeach; ?>
          </select>
        </label>
        <label class="recap-period-option" data-mode="quarter">Triwulan
          <select name="quarter"><?php for ($i = 1; $i <= 4; $i++): ?><option value="<?= $i; ?>" <?= (int) $rekap_filters['quarter'] === $i ? 'selected' : ''; ?>>Triwulan <?= $i; ?></option><?php endfor; ?></select>
        </label>
        <label class="recap-period-option" data-mode="semester">Periode 6 Bulan
          <select name="semester"><option value="1" <?= (int) $rekap_filters['semester'] === 1 ? 'selected' : ''; ?>>Januari–Juni</option><option value="2" <?= (int) $rekap_filters['semester'] === 2 ? 'selected' : ''; ?>>Juli–Desember</option></select>
        </label>
        <label>Jenis Layanan
          <select name="service"><option value="">Semua Layanan</option><?php foreach ($rekap_services as $key => $label): ?><option value="<?= _ent($key); ?>" <?= $rekap_filters['service'] === $key ? 'selected' : ''; ?>><?= _ent($label); ?></option><?php endforeach; ?></select>
        </label>
        <label>Wilayah
          <select name="region"><option value="">Semua Wilayah</option><?php foreach ($rekap_regions as $code => $name): ?><option value="<?= _ent($code); ?>" <?= $rekap_filters['region'] === $code ? 'selected' : ''; ?>><?= _ent(ucwords(strtolower($name))); ?></option><?php endforeach; ?></select>
        </label>
        <label>Notaris
          <select name="notary"><option value="">Semua Notaris</option><?php foreach ($rekap_notaries as $notary): ?><option value="<?= _ent($notary['username']); ?>" <?= $rekap_filters['notary'] === $notary['username'] ? 'selected' : ''; ?>><?= _ent(format_gelar($notary['full_name'])); ?></option><?php endforeach; ?></select>
        </label>
        <label class="service-recap-search">Pencarian
          <input type="search" name="q" value="<?= _ent($rekap_filters['q']); ?>" placeholder="Nama, username, nomor akta, atau keterangan">
        </label>
      </div>
      <div class="service-recap-filter__actions">
        <a href="<?= site_url('rekap-layanan'); ?>" class="btn service-recap-reset"><i class="fa fa-undo"></i> Reset</a>
        <button type="submit" class="btn service-recap-submit"><i class="fa fa-search"></i> Tampilkan</button>
      </div>
    </form>

    <div class="service-recap-summary">
      <?php foreach ($rekap_summary as $item): ?>
        <?php $card_query = $export_query; $card_query['service'] = $item['key']; ?>
        <a href="<?= site_url('rekap-layanan').'?'.http_build_query($card_query); ?>" class="service-recap-summary__item <?= $rekap_filters['service'] === $item['key'] ? 'is-active' : ''; ?>">
          <span><?= _ent($item['label']); ?></span><strong><?= number_format($item['total'], 0, ',', '.'); ?></strong><small>data tercatat</small>
        </a>
      <?php endforeach; ?>
    </div>

    <article class="service-recap-table-card">
      <div class="service-recap-table-head">
        <div><h2>Daftar Aktivitas Layanan</h2><p><?= number_format($rekap_total, 0, ',', '.'); ?> data pada <?= _ent(strtolower($rekap_period_label)); ?>.</p></div>
        <span>Hanya-baca</span>
      </div>
      <div class="table-responsive">
        <table class="table table-bordered table-striped service-recap-table">
          <thead><tr><th>No.</th><th>Layanan</th><th class="table-date-cell">Tanggal</th><th>Nama Notaris</th><th>Nomor Telepon</th><th>Wilayah</th><th class="table-number-cell">Nomor Akta</th><th>Keterangan</th><th>Aksi</th></tr></thead>
          <tbody>
            <?php if (!$rekap_rows): ?>
              <tr><td colspan="9" class="service-recap-empty"><i class="fa fa-folder-open-o"></i><strong>Data tidak ditemukan</strong><span>Ubah filter untuk melihat periode atau layanan lainnya.</span></td></tr>
            <?php else: ?>
              <?php foreach ($rekap_rows as $index => $row): ?>
                <tr>
                  <td><?= (($rekap_page - 1) * 25) + $index + 1; ?></td>
                  <td><span class="service-recap-badge"><?= _ent($row['service_label']); ?></span></td>
                  <td class="table-date-cell"><span class="table-date"><?= _ent(format_date_id($row['record_date'])); ?></span></td>
                  <td><strong><?= _ent(format_gelar($row['nama_notaris'])); ?></strong><small class="service-recap-muted">@<?= _ent($row['username']); ?></small></td>
                  <td class="service-recap-phone"><?= $row['phone_number'] === '-' ? '-' : _ent(format_phone_number($row['phone_number'])); ?></td>
                  <td><?= _ent(ucwords(strtolower($row['wilayah']))); ?></td>
                  <td class="table-number-cell"><?= $row['nomor_akta'] === '' ? '-' : _ent($row['nomor_akta']); ?></td>
                  <td><?= $row['description'] === '' ? '-' : _ent($row['description']); ?></td>
                  <td class="table-actions"><a class="table-action table-action--view" href="<?= site_url($route_map[$row['service_key']].'/view/'.(int) $row['record_id']); ?>" title="Lihat detail"><i class="fa fa-eye"></i></a></td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
      <?php if ($rekap_pages > 1): ?>
        <nav class="service-recap-pagination" aria-label="Halaman rekap">
          <?php for ($page = 1; $page <= $rekap_pages; $page++): ?>
            <?php if ($page === 1 || $page === $rekap_pages || abs($page - $rekap_page) <= 2): $page_query = $export_query; $page_query['page'] = $page; ?>
              <a class="<?= $page === $rekap_page ? 'is-active' : ''; ?>" href="<?= site_url('rekap-layanan').'?'.http_build_query($page_query); ?>"><?= $page; ?></a>
            <?php elseif ($page === 2 || $page === $rekap_pages - 1): ?><span>…</span><?php endif; ?>
          <?php endfor; ?>
        </nav>
      <?php endif; ?>
    </article>
  </div>
</section>

<script>
(function () {
  var mode = document.getElementById('recap-mode');
  if (!mode) return;
  function syncPeriodFields() {
    document.querySelectorAll('.recap-period-option').forEach(function (field) {
      field.hidden = field.getAttribute('data-mode') !== mode.value;
    });
  }
  mode.addEventListener('change', syncPeriodFields);
  syncPeriodFields();
}());
</script>
