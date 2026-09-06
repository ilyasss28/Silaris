<?php
$format_value = function ($value) {
  return is_numeric($value) ? number_format((float) $value, 0, ',', '.') : $value;
};
$format_date = function ($date) {
  if (!$date || $date === '0000-00-00') return 'Belum pernah';
  $formatted = format_date_id($date);
  return $formatted !== '' ? $formatted : '-';
};
$trend_max = max(1, max(array_column($dashboard_trend, 'total')));
$service_max = max(1, max(array_column($dashboard_services, 'total')));
$chart_total = array_sum(array_column($dashboard_trend, 'total'));
$service_total = array_sum(array_column($dashboard_services, 'total'));
$month_names = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
$compliance_filter_query = http_build_query([
  'chart_mode' => $dashboard_chart_filter['mode'],
  'chart_year' => $dashboard_chart_filter['year'],
  'chart_month' => $dashboard_chart_filter['month'],
  'chart_quarter' => $dashboard_chart_filter['quarter'],
  'chart_semester' => $dashboard_chart_filter['semester'],
]);
$compliance_download_url = site_url('administrator/dashboard/download_compliance') . '?' . $compliance_filter_query;
$compliance_page_url = site_url('administrator/dashboard/compliance_notaries') . '?' . $compliance_filter_query;
$region_max = $dashboard_regions ? max(1, max(array_column($dashboard_regions, 'total'))) : 1;
$profile_copy = [
  'executive' => [
    'eyebrow' => 'Dashboard Eksekutif Kantor Wilayah',
    'title' => 'Ringkasan Kinerja Layanan Administrasi',
    'description' => 'Pantau kepatuhan pelaporan, aktivitas layanan, serta persebaran notaris untuk mendukung keputusan pimpinan.',
  ],
  'mpd' => [
    'eyebrow' => 'Dashboard Pengawasan MPD',
    'title' => 'Pemantauan Notaris ' . ($dashboard_region ? $dashboard_region : 'Wilayah Anda'),
    'description' => 'Fokus pada kepatuhan dan aktivitas notaris yang berada dalam wilayah pengawasan akun Anda.',
  ],
  'user' => [
    'eyebrow' => 'Dashboard Notaris',
    'title' => 'Ringkasan Aktivitas dan Pelaporan Saya',
    'description' => 'Pantau laporan serta pencatatan layanan Anda selama tahun berjalan dalam satu tampilan.',
  ],
];
$hero = $profile_copy[$dashboard_profile];
?>

<style>
.role-dashboard{--dash-navy:#07064f;--dash-gold:#ffcf00;--dash-ink:#101828;--dash-muted:#667085;--dash-line:#e1e6ee;--dash-soft:#f7f9fc;padding-bottom:8px}.dash-hero{position:relative;min-height:174px;margin-bottom:18px;padding:28px 30px;display:flex;align-items:center;justify-content:space-between;gap:24px;overflow:hidden;border-radius:12px;background:var(--dash-navy);color:#fff;box-shadow:0 7px 20px rgba(7,6,79,.1)}.dash-hero:after{content:'';position:absolute;inset:0 0 0 auto;width:7px;background:var(--dash-gold)}.dash-hero:before{content:'';position:absolute;width:260px;height:260px;right:28px;bottom:-190px;border:1px solid rgba(255,207,0,.28);border-radius:48%;box-shadow:0 0 0 22px rgba(255,207,0,.03)}.dash-hero__content{position:relative;z-index:2;max-width:720px}.dash-hero__eyebrow{margin-bottom:11px;display:flex;align-items:center;gap:8px;color:var(--dash-gold);font-size:10.5px;font-weight:800;letter-spacing:.07em;text-transform:uppercase}.dash-hero h1{margin:0 0 9px;color:#fff;font-size:25px;font-weight:800;line-height:1.3;letter-spacing:-.025em}.dash-hero p{max-width:650px;margin:0;color:rgba(255,255,255,.72);font-size:12.5px;line-height:1.7}.dash-hero__mark{position:relative;z-index:2;width:104px;height:104px;flex:0 0 104px;display:flex;align-items:center;justify-content:center}.dash-hero__mark img{width:100%;height:100%;display:block;object-fit:contain;filter:none}
.dash-stat-grid{margin-bottom:18px;display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:14px}.dash-stat{min-height:130px;padding:18px;display:flex;flex-direction:column;border:1px solid var(--dash-line);border-radius:11px;background:#fff;box-shadow:0 3px 10px rgba(15,23,42,.035)}.dash-stat__top{display:flex;align-items:flex-start;justify-content:space-between;gap:12px}.dash-stat__label{color:#596579;font-size:10.5px;font-weight:700;letter-spacing:.025em;text-transform:uppercase}.dash-stat__icon{width:38px;height:38px;flex:0 0 38px;display:grid;place-items:center;border-radius:9px;background:#eeeff8;color:var(--dash-navy);font-size:15px}.dash-stat--gold .dash-stat__icon{background:#fff7d8;color:#927000}.dash-stat--blue .dash-stat__icon{background:#edf4ff;color:#2765b2}.dash-stat--green .dash-stat__icon{background:#eaf8f1;color:#168252}.dash-stat--red .dash-stat__icon{background:#fff0f1;color:#c23949}.dash-stat__value{margin:8px 0 5px;color:var(--dash-ink);font-size:25px;font-weight:800;line-height:1.1;letter-spacing:-.03em}.dash-stat__detail{margin-top:auto;color:#7b8595;font-size:10px;line-height:1.5}
.dash-grid{margin-bottom:18px;display:grid;grid-template-columns:minmax(0,1.65fr) minmax(290px,.75fr);gap:14px}.dash-grid--equal{grid-template-columns:repeat(2,minmax(0,1fr))}.dash-panel{min-width:0;padding:20px;border:1px solid var(--dash-line);border-radius:11px;background:#fff;box-shadow:0 3px 10px rgba(15,23,42,.035)}.dash-panel__head{margin-bottom:18px;display:flex;align-items:flex-start;justify-content:space-between;gap:14px}.dash-panel__title{margin:0;color:var(--dash-ink);font-size:14px;font-weight:800}.dash-panel__subtitle{margin:4px 0 0;color:#7b8595;font-size:10px;line-height:1.5}.dash-panel__tag{padding:5px 9px;border:1px solid #ead370;border-radius:999px;background:#fff9df;color:#846700;font-size:9px;font-weight:800;white-space:nowrap}
.dash-chart-filter{margin-bottom:18px;padding:18px 20px;border:1px solid var(--dash-line);border-top:3px solid var(--dash-gold);border-radius:11px;background:#fff}.dash-chart-filter__intro{margin-bottom:14px;padding-bottom:13px;border-bottom:1px solid #edf0f4}.dash-chart-filter__intro strong,.dash-chart-filter__intro span{display:block}.dash-chart-filter__intro strong{color:var(--dash-ink);font-size:14px;font-weight:800}.dash-chart-filter__intro span{margin-top:4px;color:#7b8595;font-size:10px;line-height:1.5}.dash-chart-filter__fields{display:grid;grid-template-columns:minmax(150px,1.2fr) minmax(110px,.7fr) minmax(160px,1.2fr) auto;align-items:end;gap:10px}.dash-filter-field{display:grid;gap:5px}.dash-filter-field[hidden]{display:none}.dash-filter-field label{color:#596579;font-size:8.5px;font-weight:800;letter-spacing:.03em;text-transform:uppercase}.dash-filter-field select{width:100%;height:38px;padding:0 32px 0 11px;border:1px solid #d8dee8;border-radius:8px;background:#fff;color:#263247;font-size:10px;font-weight:600;outline:0}.dash-filter-field select:focus{border-color:#b79a1a;box-shadow:0 0 0 2px rgba(255,207,0,.13)}.dash-filter-actions{display:flex;align-items:center;gap:8px}.dash-filter-submit,.dash-filter-reset{height:38px;padding:0 15px;display:inline-flex;align-items:center;justify-content:center;border-radius:8px;font-size:9.5px;font-weight:800;white-space:nowrap}.dash-filter-submit{border:1px solid var(--dash-navy);background:var(--dash-navy);color:#fff}.dash-filter-submit:hover{background:#111064}.dash-filter-reset{border:1px solid #d8dee8;color:#596579;text-decoration:none}.dash-filter-reset:hover{color:var(--dash-navy);border-color:#aeb8c7}
.trend-chart{height:230px;padding:12px 4px 0;display:flex;align-items:stretch;gap:12px}.trend-chart__axis{padding:0 0 27px;display:flex;flex-direction:column;justify-content:space-between;color:#98a1af;font-size:8.5px;text-align:right}.trend-chart__viewport{min-width:0;flex:1;overflow-x:auto;overflow-y:hidden;scrollbar-width:thin;scrollbar-color:#c6ccd6 transparent}.trend-chart__plot{position:relative;height:100%;display:grid;grid-template-columns:repeat(var(--trend-columns,6),minmax(24px,1fr));align-items:end;gap:12px;border-bottom:1px solid #dce2eb;background:repeating-linear-gradient(to bottom,#edf0f5 0,#edf0f5 1px,transparent 1px,transparent 25%)}.trend-bar{height:100%;display:flex;flex-direction:column;align-items:center;justify-content:flex-end}.trend-bar__value{margin-bottom:6px;color:#4f5a6d;font-size:9px;font-weight:700}.trend-bar__fill{width:min(34px,68%);min-height:5px;border-radius:7px 7px 2px 2px;background:linear-gradient(180deg,#242378,var(--dash-navy));box-shadow:0 5px 12px rgba(7,6,79,.12)}.trend-bar__label{height:26px;padding-top:8px;color:#6d7788;font-size:9.5px;font-weight:600}
.compliance-wrap{min-height:230px;display:flex;flex-direction:column;align-items:center;justify-content:center}.compliance-ring{--value:0;position:relative;width:140px;height:140px;display:grid;place-items:center;border-radius:50%;background:conic-gradient(var(--dash-gold) calc(var(--value)*1%),#edf0f5 0)}.compliance-ring:before{content:'';position:absolute;width:104px;height:104px;border-radius:50%;background:#fff}.compliance-ring__value{position:relative;z-index:1;color:var(--dash-navy);font-size:25px;font-weight:800}.compliance-ring__value small{font-size:12px}.compliance-legend{width:100%;margin-top:18px;display:grid;grid-template-columns:repeat(2,1fr);gap:8px}.compliance-legend__link{padding:9px;display:block;border:1px solid transparent;border-radius:8px;background:var(--dash-soft);text-align:center;text-decoration:none;transition:border-color .15s,transform .15s,background .15s}.compliance-legend__link:hover{transform:translateY(-1px);border-color:#d9c45e;background:#fffbed}.compliance-legend strong,.compliance-legend span{display:block}.compliance-legend strong{color:var(--dash-ink);font-size:14px}.compliance-legend span{margin-top:2px;color:#7b8595;font-size:8.5px;text-transform:uppercase}.compliance-legend__link:hover span{color:#725b00}
.service-list,.region-list{display:grid;gap:13px}.service-row__meta,.region-row__meta{margin-bottom:6px;display:flex;align-items:center;justify-content:space-between;gap:12px}.service-row__name{display:flex;align-items:center;gap:8px;color:#344054;font-size:10.5px;font-weight:700}.service-row__name i{width:18px;color:#8a6b00;text-align:center}.service-row__total,.region-row__total{color:var(--dash-ink);font-size:10.5px;font-weight:800}.service-row__track,.region-row__track{height:7px;overflow:hidden;border-radius:99px;background:#eef1f5}.service-row__fill,.region-row__fill{height:100%;min-width:4px;border-radius:99px;background:var(--dash-navy)}.service-row:nth-child(2) .service-row__fill{background:#d4aa00}.service-row:nth-child(3) .service-row__fill{background:#3474bd}.service-row:nth-child(4) .service-row__fill{background:#22a26b}.service-row:nth-child(5) .service-row__fill{background:#8b5bb7}.region-row__name{max-width:80%;overflow:hidden;color:#4c586b;font-size:10px;font-weight:600;text-overflow:ellipsis;white-space:nowrap}.region-row__fill{background:#d2aa08}
.dash-monitoring-grid>.dash-panel{height:430px;display:flex;flex-direction:column;overflow:hidden}.dash-monitoring-grid>.dash-panel>.dash-panel__head{flex:0 0 auto}.dash-scroll-area{min-height:0;flex:1;overflow-y:auto;overscroll-behavior:contain;scrollbar-width:thin;scrollbar-color:#c6ccd6 transparent}.dash-scroll-area::-webkit-scrollbar{width:6px}.dash-scroll-area::-webkit-scrollbar-track{background:transparent}.dash-scroll-area::-webkit-scrollbar-thumb{border-radius:99px;background:#c6ccd6}.dash-scroll-area::-webkit-scrollbar-thumb:hover{background:#aeb6c3}.dash-scroll-area.region-list{padding-right:8px}
.compliance-detail{height:300px;display:flex;flex-direction:column}.compliance-detail .dash-panel__head{margin-bottom:12px}.compliance-toolbar{margin-bottom:10px;display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap}.compliance-tabs,.compliance-downloads{display:flex;align-items:center;gap:7px}.compliance-tab,.compliance-download{height:31px;padding:0 10px;display:inline-flex;align-items:center;justify-content:center;border:1px solid #dce2eb;border-radius:7px;background:#fff;color:#596579;font-size:8.5px;font-weight:800;text-decoration:none}.compliance-tab{cursor:pointer}.compliance-tab.is-active{border-color:var(--dash-navy);background:var(--dash-navy);color:#fff}.compliance-download:hover{border-color:#b79a1a;color:#725b00}.compliance-table-wrap{min-height:0;flex:1;overflow:auto;scrollbar-width:thin;scrollbar-color:#c6ccd6 transparent}.compliance-table{width:100%;border-collapse:collapse}.compliance-table th{position:sticky;top:0;z-index:1;padding:8px;background:#f8f9fc;color:#788396;font-size:8px;font-weight:800;text-align:left;text-transform:uppercase}.compliance-table td{padding:9px 8px;border-bottom:1px solid #edf0f4;color:#566176;font-size:9px;vertical-align:middle}.compliance-table td strong,.compliance-table td small{display:block}.compliance-table td strong{max-width:210px;overflow:hidden;color:#263247;font-size:9.5px;text-overflow:ellipsis;white-space:nowrap}.compliance-table td small{margin-top:2px;color:#929baa}.compliance-status{display:inline-flex;padding:4px 7px;border-radius:999px;font-size:8px;font-weight:800;white-space:nowrap}.compliance-status--submitted{background:#eaf8f1;color:#168252}.compliance-status--missing{background:#fff0f1;color:#c23949}.compliance-row[hidden]{display:none}.compliance-empty{padding:35px 10px;color:#8b95a4;font-size:10px;text-align:center}.empty-state{min-height:170px;display:flex;flex-direction:column;align-items:center;justify-content:center;color:#8b95a4;text-align:center}.empty-state i{margin-bottom:10px;color:#c5cbd5;font-size:28px}.empty-state strong{color:#596579;font-size:11px}.empty-state span{margin-top:4px;font-size:9.5px}
.attention-table{width:100%;border-collapse:collapse}.attention-table th{padding:0 10px 9px;color:#7d8797;font-size:8.5px;font-weight:800;text-align:left;text-transform:uppercase}.attention-table td{padding:10px;border-top:1px solid #edf0f4;color:#465267;font-size:9.5px;vertical-align:middle}.attention-table td strong{display:block;color:#263247;font-size:10.5px}.attention-table td small{display:block;margin-top:3px;color:#919aa8}.attention-badge{display:inline-flex;padding:4px 7px;border-radius:999px;background:#fff1f2;color:#bb3443;font-size:8.5px;font-weight:800;white-space:nowrap}
.dash-section-heading{margin:2px 0 11px;display:flex;align-items:center;justify-content:space-between}.dash-section-heading h2{margin:0;color:var(--dash-ink);font-size:14px;font-weight:800}.quick-access-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:14px}.quick-access-card{min-height:120px;padding:17px;display:flex;flex-direction:column;border:1px solid var(--dash-line);border-radius:10px;background:#fff;color:var(--dash-ink);text-decoration:none;box-shadow:0 3px 10px rgba(15,23,42,.03);transition:transform .15s,border-color .15s}.quick-access-card:hover{transform:translateY(-2px);border-color:#c6ccd7;color:var(--dash-navy)}.quick-access-card__icon{width:36px;height:36px;margin-bottom:13px;display:grid;place-items:center;border-radius:8px;background:#f0f1f8;color:var(--dash-navy);font-size:14px}.quick-access-card strong{font-size:11px;font-weight:800}.quick-access-card small{margin-top:5px;color:#7b8595;font-size:9.5px;line-height:1.5}
@media(max-width:1100px){.dash-stat-grid{grid-template-columns:repeat(2,minmax(0,1fr))}.quick-access-grid{grid-template-columns:repeat(2,minmax(0,1fr))}.dash-chart-filter__fields{grid-template-columns:repeat(3,minmax(120px,1fr))}.dash-filter-actions{grid-column:1/-1;justify-content:flex-end}}@media(max-width:860px){.dash-grid,.dash-grid--equal{grid-template-columns:1fr}.dash-hero__mark{display:none}}@media(max-width:575px){.role-dashboard{padding-bottom:0}.dash-hero{min-height:0;padding:23px 20px}.dash-hero h1{font-size:20px}.dash-stat-grid{grid-template-columns:1fr}.dash-stat{min-height:116px}.dash-chart-filter{padding:16px}.dash-chart-filter__fields{grid-template-columns:1fr}.dash-filter-actions{grid-column:auto}.dash-filter-submit,.dash-filter-reset{flex:1}.trend-chart{gap:6px}.trend-chart__plot{gap:5px}.quick-access-grid{grid-template-columns:1fr}.attention-table th:nth-child(2),.attention-table td:nth-child(2){display:none}}
</style>

<section class="content role-dashboard">
  <?php cicool()->eventListen('dashboard_content_top'); ?>

  <header class="dash-hero">
    <div class="dash-hero__content">
      <div class="dash-hero__eyebrow"><i class="fa fa-shield"></i> <?= _ent($hero['eyebrow']); ?></div>
      <h1><?= _ent($hero['title']); ?></h1>
      <p><?= _ent($hero['description']); ?></p>
    </div>
    <div class="dash-hero__mark">
      <img src="<?= base_url('assets/assets-guest/img/kemenkumham.png'); ?>" alt="Logo Pengayoman">
    </div>
  </header>

  <div class="dash-stat-grid">
    <?php foreach ($dashboard_stats as $stat): ?>
      <article class="dash-stat dash-stat--<?= _ent($stat['tone']); ?>">
        <div class="dash-stat__top">
          <span class="dash-stat__label"><?= _ent($stat['label']); ?></span>
          <span class="dash-stat__icon"><i class="fa <?= _ent($stat['icon']); ?>"></i></span>
        </div>
        <strong class="dash-stat__value"><?= _ent($format_value($stat['value'])); ?></strong>
        <span class="dash-stat__detail"><?= _ent($stat['detail']); ?></span>
      </article>
    <?php endforeach; ?>
  </div>

  <form class="dash-chart-filter" method="get" action="<?= site_url('administrator/dashboard'); ?>" id="dashboard-chart-filter">
    <div class="dash-chart-filter__intro">
      <strong>Filter Grafik Dashboard</strong>
      <span>Atur periode akumulasi aktivitas, komposisi layanan, dan kepatuhan pelaporan.</span>
    </div>
    <div class="dash-chart-filter__fields">
      <div class="dash-filter-field">
        <label for="chart_mode">Periode</label>
        <select name="chart_mode" id="chart_mode">
          <option value="month" <?= $dashboard_chart_filter['mode'] === 'month' ? 'selected' : ''; ?>>Per Bulan</option>
          <option value="quarter" <?= $dashboard_chart_filter['mode'] === 'quarter' ? 'selected' : ''; ?>>Per Triwulan</option>
          <option value="semester" <?= $dashboard_chart_filter['mode'] === 'semester' ? 'selected' : ''; ?>>Per 6 Bulan</option>
          <option value="year" <?= $dashboard_chart_filter['mode'] === 'year' ? 'selected' : ''; ?>>Per Tahun</option>
        </select>
      </div>
      <div class="dash-filter-field">
        <label for="chart_year">Tahun</label>
        <select name="chart_year" id="chart_year">
          <?php foreach ($dashboard_chart_years as $year): ?>
            <option value="<?= (int) $year; ?>" <?= (int) $dashboard_chart_filter['year'] === (int) $year ? 'selected' : ''; ?>><?= (int) $year; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="dash-filter-field" id="chart_month_field" <?= $dashboard_chart_filter['mode'] !== 'month' ? 'hidden' : ''; ?>>
        <label for="chart_month">Bulan</label>
        <select name="chart_month" id="chart_month">
          <?php foreach ($month_names as $number => $month): ?>
            <option value="<?= (int) $number; ?>" <?= (int) $dashboard_chart_filter['month'] === (int) $number ? 'selected' : ''; ?>><?= _ent($month); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="dash-filter-field" id="chart_quarter_field" <?= $dashboard_chart_filter['mode'] !== 'quarter' ? 'hidden' : ''; ?>>
        <label for="chart_quarter">Triwulan</label>
        <select name="chart_quarter" id="chart_quarter">
          <?php for ($quarter = 1; $quarter <= 4; $quarter++): ?>
            <option value="<?= $quarter; ?>" <?= (int) $dashboard_chart_filter['quarter'] === $quarter ? 'selected' : ''; ?>>Triwulan <?= ['I', 'II', 'III', 'IV'][$quarter - 1]; ?></option>
          <?php endfor; ?>
        </select>
      </div>
      <div class="dash-filter-field" id="chart_semester_field" <?= $dashboard_chart_filter['mode'] !== 'semester' ? 'hidden' : ''; ?>>
        <label for="chart_semester">Periode 6 Bulan</label>
        <select name="chart_semester" id="chart_semester">
          <option value="1" <?= (int) $dashboard_chart_filter['semester'] === 1 ? 'selected' : ''; ?>>Januari–Juni</option>
          <option value="2" <?= (int) $dashboard_chart_filter['semester'] === 2 ? 'selected' : ''; ?>>Juli–Desember</option>
        </select>
      </div>
      <div class="dash-filter-actions">
        <button class="dash-filter-submit" type="submit"><i class="fa fa-filter"></i>&nbsp; Terapkan Filter</button>
        <a class="dash-filter-reset" href="<?= site_url('administrator/dashboard'); ?>">Reset</a>
      </div>
    </div>
  </form>

  <div class="dash-grid <?= $dashboard_profile === 'user' ? 'dash-grid--equal' : ''; ?>">
    <article class="dash-panel">
      <div class="dash-panel__head">
        <div><h2 class="dash-panel__title">Akumulasi Laporan dan Layanan</h2><p class="dash-panel__subtitle">Pergerakan seluruh aktivitas pada periode yang dipilih.</p></div>
        <span class="dash-panel__tag"><?= _ent($dashboard_period); ?> · <?= number_format($chart_total, 0, ',', '.'); ?> aktivitas</span>
      </div>
      <div class="trend-chart" role="img" aria-label="Grafik akumulasi aktivitas periode <?= _ent($dashboard_period); ?>">
        <div class="trend-chart__axis"><span><?= number_format($trend_max, 0, ',', '.'); ?></span><span><?= number_format($trend_max / 2, 0, ',', '.'); ?></span><span>0</span></div>
        <div class="trend-chart__viewport">
          <div class="trend-chart__plot" style="--trend-columns:<?= count($dashboard_trend); ?>;min-width:<?= count($dashboard_trend) > 14 ? count($dashboard_trend) * 38 : 0; ?>px">
            <?php foreach ($dashboard_trend as $trend): ?>
              <?php $height = $trend['total'] > 0 ? max(5, round(($trend['total'] / $trend_max) * 100)) : 2; ?>
              <div class="trend-bar" title="<?= _ent($trend['label']); ?>: <?= number_format($trend['total'], 0, ',', '.'); ?> aktivitas">
                <span class="trend-bar__value"><?= number_format($trend['total'], 0, ',', '.'); ?></span>
                <span class="trend-bar__fill" style="height:<?= (int) $height; ?>%"></span>
                <span class="trend-bar__label"><?= _ent($trend['label']); ?></span>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </article>

    <?php if ($dashboard_profile === 'user'): ?>
      <article class="dash-panel">
        <div class="dash-panel__head"><div><h2 class="dash-panel__title">Komposisi Layanan</h2><p class="dash-panel__subtitle">Aktivitas per jenis layanan pada periode <?= _ent($dashboard_period); ?>.</p></div><span class="dash-panel__tag">Total <?= number_format($service_total, 0, ',', '.'); ?></span></div>
        <div class="service-list">
          <?php foreach ($dashboard_services as $service): ?>
            <div class="service-row">
              <div class="service-row__meta"><span class="service-row__name"><i class="fa <?= _ent($service['icon']); ?>"></i><?= _ent($service['label']); ?></span><strong class="service-row__total"><?= number_format($service['total'], 0, ',', '.'); ?></strong></div>
              <div class="service-row__track"><div class="service-row__fill" style="width:<?= max(1, round(($service['total'] / $service_max) * 100)); ?>%"></div></div>
            </div>
          <?php endforeach; ?>
        </div>
      </article>
    <?php else: ?>
    <article class="dash-panel">
      <div class="dash-panel__head">
        <div><h2 class="dash-panel__title"><?= $dashboard_profile === 'user' ? 'Status Pelaporan Saya' : 'Kepatuhan Pelaporan'; ?></h2><p class="dash-panel__subtitle">Cakupan pelaporan periode <?= _ent($dashboard_period); ?>.</p></div>
        <span class="dash-panel__tag"><?= _ent($dashboard_period); ?></span>
      </div>
      <div class="compliance-wrap">
        <div class="compliance-ring" style="--value:<?= (int) $dashboard_compliance['percentage']; ?>"><span class="compliance-ring__value"><?= (int) $dashboard_compliance['percentage']; ?><small>%</small></span></div>
        <div class="compliance-legend">
          <a class="compliance-legend__link" href="<?= _ent($compliance_page_url . '&status=submitted'); ?>"><strong><?= number_format($dashboard_compliance['submitted'], 0, ',', '.'); ?></strong><span>Sudah melapor</span></a>
          <a class="compliance-legend__link" href="<?= _ent($compliance_page_url . '&status=missing'); ?>"><strong><?= number_format($dashboard_compliance['missing'], 0, ',', '.'); ?></strong><span>Belum melapor</span></a>
        </div>
      </div>
    </article>
    <?php endif; ?>
  </div>

  <?php if ($dashboard_profile !== 'user'): ?>
  <div class="dash-grid dash-grid--equal">
    <article class="dash-panel">
      <div class="dash-panel__head"><div><h2 class="dash-panel__title">Komposisi Layanan</h2><p class="dash-panel__subtitle">Aktivitas per jenis layanan pada periode <?= _ent($dashboard_period); ?>.</p></div><span class="dash-panel__tag">Total <?= number_format($service_total, 0, ',', '.'); ?></span></div>
      <div class="service-list">
        <?php foreach ($dashboard_services as $service): ?>
          <div class="service-row">
            <div class="service-row__meta"><span class="service-row__name"><i class="fa <?= _ent($service['icon']); ?>"></i><?= _ent($service['label']); ?></span><strong class="service-row__total"><?= number_format($service['total'], 0, ',', '.'); ?></strong></div>
            <div class="service-row__track"><div class="service-row__fill" style="width:<?= max(1, round(($service['total'] / $service_max) * 100)); ?>%"></div></div>
          </div>
        <?php endforeach; ?>
      </div>
    </article>

    <article class="dash-panel compliance-detail">
      <div class="dash-panel__head"><div><h2 class="dash-panel__title">Daftar Kepatuhan Notaris</h2><p class="dash-panel__subtitle">Rincian status pelaporan periode <?= _ent($dashboard_period); ?>.</p></div></div>
      <div class="compliance-toolbar">
        <div class="compliance-tabs" role="tablist" aria-label="Status kepatuhan">
          <button class="compliance-tab is-active" type="button" data-compliance-tab="missing">Belum Melapor (<?= number_format($dashboard_compliance['missing'], 0, ',', '.'); ?>)</button>
          <button class="compliance-tab" type="button" data-compliance-tab="submitted">Sudah Melapor (<?= number_format($dashboard_compliance['submitted'], 0, ',', '.'); ?>)</button>
        </div>
        <div class="compliance-downloads">
          <a class="compliance-download" href="<?= _ent($compliance_download_url . '&status=missing'); ?>" title="Unduh Excel daftar belum melapor"><i class="fa fa-file-excel-o"></i>&nbsp; Belum</a>
          <a class="compliance-download" href="<?= _ent($compliance_download_url . '&status=submitted'); ?>" title="Unduh Excel daftar sudah melapor"><i class="fa fa-file-excel-o"></i>&nbsp; Sudah</a>
        </div>
      </div>
      <div class="compliance-table-wrap">
        <table class="compliance-table">
          <thead><tr><th>Notaris</th><th>Wilayah</th><th>Status</th><th>Jumlah</th><th>Terakhir</th></tr></thead>
          <tbody>
            <?php foreach ($dashboard_compliance_rows as $notary): ?>
              <tr class="compliance-row" data-compliance-status="<?= _ent($notary['status']); ?>" <?= $notary['status'] !== 'missing' ? 'hidden' : ''; ?>>
                <td><strong title="<?= _ent($notary['display_name']); ?>"><?= _ent($notary['display_name']); ?></strong><small><?= $notary['phone_number'] === '-' ? 'Telepon belum tersedia' : _ent(format_phone_number($notary['phone_number'])); ?></small></td>
                <td><?= _ent($notary['region_name']); ?></td>
                <td><span class="compliance-status compliance-status--<?= _ent($notary['status']); ?>"><?= $notary['status'] === 'submitted' ? 'Sudah Melapor' : 'Belum Melapor'; ?></span></td>
                <td><?= number_format($notary['report_count'], 0, ',', '.'); ?></td>
                <td class="table-date-cell"><?= _ent($format_date($notary['last_report'])); ?></td>
              </tr>
            <?php endforeach; ?>
            <tr class="compliance-empty" data-compliance-empty="missing" <?= $dashboard_compliance['missing'] > 0 ? 'hidden' : ''; ?>><td colspan="5">Tidak ada notaris berstatus belum melapor.</td></tr>
            <tr class="compliance-empty" data-compliance-empty="submitted" hidden><td colspan="5">Tidak ada notaris berstatus sudah melapor.</td></tr>
          </tbody>
        </table>
      </div>
    </article>
  </div>
  <?php endif; ?>

  <?php if ($dashboard_profile !== 'user'): ?>
    <div class="dash-grid dash-grid--equal dash-monitoring-grid">
      <?php if ($dashboard_regions): ?>
        <article class="dash-panel">
          <div class="dash-panel__head"><div><h2 class="dash-panel__title">Persebaran Notaris</h2><p class="dash-panel__subtitle">Seluruh kabupaten dan kota di Sulawesi Tenggara.</p></div></div>
          <div class="region-list dash-scroll-area">
            <?php foreach ($dashboard_regions as $region): ?>
              <div class="region-row">
                <div class="region-row__meta"><span class="region-row__name" title="<?= _ent($region['label']); ?>"><?= _ent(ucwords(strtolower($region['label']))); ?></span><strong class="region-row__total"><?= number_format($region['total'], 0, ',', '.'); ?></strong></div>
                <div class="region-row__track"><div class="region-row__fill" style="width:<?= $region['total'] > 0 ? max(1, round(($region['total'] / $region_max) * 100)) : 0; ?>%"></div></div>
              </div>
            <?php endforeach; ?>
          </div>
        </article>
      <?php endif; ?>

      <article class="dash-panel">
        <div class="dash-panel__head"><div><h2 class="dash-panel__title">Perlu Perhatian</h2><p class="dash-panel__subtitle">Notaris yang belum tercatat melapor pada tahun berjalan.</p></div></div>
        <?php if ($dashboard_attention): ?>
          <div class="table-responsive dash-scroll-area">
            <table class="attention-table">
              <thead><tr><th>Notaris</th><th>Wilayah</th><th>Laporan Terakhir</th></tr></thead>
              <tbody>
                <?php foreach ($dashboard_attention as $attention): ?>
                  <tr>
                    <td><strong><?= _ent($attention['full_name']); ?></strong><small>@<?= _ent($attention['username']); ?></small></td>
                    <td><?= _ent($attention['region_name'] ?: 'Belum diatur'); ?></td>
                    <td><span class="attention-badge table-date"><?= _ent($format_date($attention['last_report'])); ?></span></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php else: ?>
          <div class="empty-state"><i class="fa fa-check-circle"></i><strong>Semua sudah tertib</strong><span>Tidak ada notaris yang memerlukan perhatian.</span></div>
        <?php endif; ?>
      </article>
    </div>
  <?php endif; ?>

  <div class="dash-section-heading"><h2>Akses Cepat</h2></div>
  <div class="quick-access-grid">
    <?php foreach ($dashboard_quick_links as $link): ?>
      <a class="quick-access-card" href="<?= _ent($link['url']); ?>">
        <span class="quick-access-card__icon"><i class="fa <?= _ent($link['icon']); ?>"></i></span>
        <strong><?= _ent($link['label']); ?></strong>
        <small><?= _ent($link['description']); ?></small>
      </a>
    <?php endforeach; ?>
  </div>

  <?php cicool()->eventListen('dashboard_content_bottom'); ?>
</section>

<script>
(function () {
  var mode = document.getElementById('chart_mode');
  var year = document.getElementById('chart_year');
  var month = document.getElementById('chart_month');
  var quarter = document.getElementById('chart_quarter');
  var semester = document.getElementById('chart_semester');
  var monthField = document.getElementById('chart_month_field');
  var quarterField = document.getElementById('chart_quarter_field');
  var semesterField = document.getElementById('chart_semester_field');
  if (!mode || !year || !month || !quarter || !semester || !monthField || !quarterField || !semesterField) return;
  function syncPeriodFields() {
    year.disabled = false;
    month.disabled = mode.value !== 'month';
    quarter.disabled = mode.value !== 'quarter';
    semester.disabled = mode.value !== 'semester';
    monthField.hidden = mode.value !== 'month';
    quarterField.hidden = mode.value !== 'quarter';
    semesterField.hidden = mode.value !== 'semester';
  }
  mode.addEventListener('change', syncPeriodFields);
  syncPeriodFields();

  var complianceTabs = document.querySelectorAll('[data-compliance-tab]');
  var complianceRows = document.querySelectorAll('[data-compliance-status]');
  var complianceEmpty = document.querySelectorAll('[data-compliance-empty]');
  function showComplianceStatus(status) {
    var visibleRows = 0;
    Array.prototype.forEach.call(complianceTabs, function (tab) {
      tab.classList.toggle('is-active', tab.getAttribute('data-compliance-tab') === status);
    });
    Array.prototype.forEach.call(complianceRows, function (row) {
      var visible = row.getAttribute('data-compliance-status') === status;
      row.hidden = !visible;
      if (visible) visibleRows++;
    });
    Array.prototype.forEach.call(complianceEmpty, function (empty) {
      empty.hidden = empty.getAttribute('data-compliance-empty') !== status || visibleRows > 0;
    });
  }
  Array.prototype.forEach.call(complianceTabs, function (tab) {
    tab.addEventListener('click', function () {
      showComplianceStatus(tab.getAttribute('data-compliance-tab'));
    });
  });
  showComplianceStatus('missing');
}());
</script>
