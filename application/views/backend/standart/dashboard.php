<?php
$format_value = function ($value) {
  return is_numeric($value) ? number_format((float) $value, 0, ',', '.') : $value;
};
$format_date = function ($date) {
  if (!$date || $date === '0000-00-00') return 'Belum pernah';
  $months = [1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
  $time = strtotime($date);
  return $time ? date('d', $time) . ' ' . $months[(int) date('n', $time)] . ' ' . date('Y', $time) : '-';
};
$trend_max = max(1, max(array_column($dashboard_trend, 'total')));
$service_max = max(1, max(array_column($dashboard_services, 'total')));
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
.trend-chart{height:230px;padding:12px 4px 0;display:flex;align-items:stretch;gap:12px}.trend-chart__axis{padding:0 0 27px;display:flex;flex-direction:column;justify-content:space-between;color:#98a1af;font-size:8.5px;text-align:right}.trend-chart__plot{position:relative;flex:1;display:grid;grid-template-columns:repeat(var(--trend-columns,6),minmax(24px,1fr));align-items:end;gap:12px;border-bottom:1px solid #dce2eb;background:repeating-linear-gradient(to bottom,#edf0f5 0,#edf0f5 1px,transparent 1px,transparent 25%)}.trend-bar{height:100%;display:flex;flex-direction:column;align-items:center;justify-content:flex-end}.trend-bar__value{margin-bottom:6px;color:#4f5a6d;font-size:9px;font-weight:700}.trend-bar__fill{width:min(34px,68%);min-height:5px;border-radius:7px 7px 2px 2px;background:linear-gradient(180deg,#242378,var(--dash-navy));box-shadow:0 5px 12px rgba(7,6,79,.12)}.trend-bar__label{height:26px;padding-top:8px;color:#6d7788;font-size:9.5px;font-weight:600}
.compliance-wrap{min-height:230px;display:flex;flex-direction:column;align-items:center;justify-content:center}.compliance-ring{--value:0;position:relative;width:140px;height:140px;display:grid;place-items:center;border-radius:50%;background:conic-gradient(var(--dash-gold) calc(var(--value)*1%),#edf0f5 0)}.compliance-ring:before{content:'';position:absolute;width:104px;height:104px;border-radius:50%;background:#fff}.compliance-ring__value{position:relative;z-index:1;color:var(--dash-navy);font-size:25px;font-weight:800}.compliance-ring__value small{font-size:12px}.compliance-legend{width:100%;margin-top:18px;display:grid;grid-template-columns:repeat(2,1fr);gap:8px}.compliance-legend div{padding:9px;border-radius:8px;background:var(--dash-soft);text-align:center}.compliance-legend strong,.compliance-legend span{display:block}.compliance-legend strong{color:var(--dash-ink);font-size:14px}.compliance-legend span{margin-top:2px;color:#7b8595;font-size:8.5px;text-transform:uppercase}
.service-list,.region-list{display:grid;gap:13px}.service-row__meta,.region-row__meta{margin-bottom:6px;display:flex;align-items:center;justify-content:space-between;gap:12px}.service-row__name{display:flex;align-items:center;gap:8px;color:#344054;font-size:10.5px;font-weight:700}.service-row__name i{width:18px;color:#8a6b00;text-align:center}.service-row__total,.region-row__total{color:var(--dash-ink);font-size:10.5px;font-weight:800}.service-row__track,.region-row__track{height:7px;overflow:hidden;border-radius:99px;background:#eef1f5}.service-row__fill,.region-row__fill{height:100%;min-width:4px;border-radius:99px;background:var(--dash-navy)}.service-row:nth-child(2) .service-row__fill{background:#d4aa00}.service-row:nth-child(3) .service-row__fill{background:#3474bd}.service-row:nth-child(4) .service-row__fill{background:#22a26b}.service-row:nth-child(5) .service-row__fill{background:#8b5bb7}.region-row__name{max-width:80%;overflow:hidden;color:#4c586b;font-size:10px;font-weight:600;text-overflow:ellipsis;white-space:nowrap}.region-row__fill{background:#d2aa08}
.dash-monitoring-grid>.dash-panel{height:430px;display:flex;flex-direction:column;overflow:hidden}.dash-monitoring-grid>.dash-panel>.dash-panel__head{flex:0 0 auto}.dash-scroll-area{min-height:0;flex:1;overflow-y:auto;overscroll-behavior:contain;scrollbar-width:thin;scrollbar-color:#c6ccd6 transparent}.dash-scroll-area::-webkit-scrollbar{width:6px}.dash-scroll-area::-webkit-scrollbar-track{background:transparent}.dash-scroll-area::-webkit-scrollbar-thumb{border-radius:99px;background:#c6ccd6}.dash-scroll-area::-webkit-scrollbar-thumb:hover{background:#aeb6c3}.dash-scroll-area.region-list{padding-right:8px}
.activity-list{margin:0;padding:0;list-style:none}.activity-item{padding:11px 0;display:grid;grid-template-columns:34px minmax(0,1fr) auto;align-items:center;gap:10px;border-bottom:1px solid #edf0f4}.activity-item:first-child{padding-top:0}.activity-item:last-child{padding-bottom:0;border-bottom:0}.activity-item__icon{width:34px;height:34px;display:grid;place-items:center;border-radius:8px;background:#f0f2f8;color:var(--dash-navy)}.activity-item strong,.activity-item small{display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}.activity-item strong{color:#263247;font-size:10.5px}.activity-item small{margin-top:3px;color:#8992a1;font-size:9px}.activity-item time{color:#687386;font-size:9.5px;font-weight:700;white-space:nowrap}.empty-state{min-height:170px;display:flex;flex-direction:column;align-items:center;justify-content:center;color:#8b95a4;text-align:center}.empty-state i{margin-bottom:10px;color:#c5cbd5;font-size:28px}.empty-state strong{color:#596579;font-size:11px}.empty-state span{margin-top:4px;font-size:9.5px}
.attention-table{width:100%;border-collapse:collapse}.attention-table th{padding:0 10px 9px;color:#7d8797;font-size:8.5px;font-weight:800;text-align:left;text-transform:uppercase}.attention-table td{padding:10px;border-top:1px solid #edf0f4;color:#465267;font-size:9.5px;vertical-align:middle}.attention-table td strong{display:block;color:#263247;font-size:10.5px}.attention-table td small{display:block;margin-top:3px;color:#919aa8}.attention-badge{display:inline-flex;padding:4px 7px;border-radius:999px;background:#fff1f2;color:#bb3443;font-size:8.5px;font-weight:800;white-space:nowrap}
.dash-section-heading{margin:2px 0 11px;display:flex;align-items:center;justify-content:space-between}.dash-section-heading h2{margin:0;color:var(--dash-ink);font-size:14px;font-weight:800}.quick-access-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:14px}.quick-access-card{min-height:120px;padding:17px;display:flex;flex-direction:column;border:1px solid var(--dash-line);border-radius:10px;background:#fff;color:var(--dash-ink);text-decoration:none;box-shadow:0 3px 10px rgba(15,23,42,.03);transition:transform .15s,border-color .15s}.quick-access-card:hover{transform:translateY(-2px);border-color:#c6ccd7;color:var(--dash-navy)}.quick-access-card__icon{width:36px;height:36px;margin-bottom:13px;display:grid;place-items:center;border-radius:8px;background:#f0f1f8;color:var(--dash-navy);font-size:14px}.quick-access-card strong{font-size:11px;font-weight:800}.quick-access-card small{margin-top:5px;color:#7b8595;font-size:9.5px;line-height:1.5}
@media(max-width:1100px){.dash-stat-grid{grid-template-columns:repeat(2,minmax(0,1fr))}.quick-access-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}@media(max-width:860px){.dash-grid,.dash-grid--equal{grid-template-columns:1fr}.dash-hero__mark{display:none}}@media(max-width:575px){.role-dashboard{padding-bottom:0}.dash-hero{min-height:0;padding:23px 20px}.dash-hero h1{font-size:20px}.dash-stat-grid{grid-template-columns:1fr}.dash-stat{min-height:116px}.trend-chart{gap:6px}.trend-chart__plot{gap:5px}.quick-access-grid{grid-template-columns:1fr}.attention-table th:nth-child(2),.attention-table td:nth-child(2){display:none}}
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

  <div class="dash-grid">
    <article class="dash-panel">
      <div class="dash-panel__head">
        <div><h2 class="dash-panel__title">Tren Aktivitas Administrasi</h2><p class="dash-panel__subtitle">Akumulasi laporan dan layanan selama enam bulan terakhir.</p></div>
        <span class="dash-panel__tag"><?= _ent($dashboard_period); ?></span>
      </div>
      <div class="trend-chart" role="img" aria-label="Grafik tren aktivitas enam bulan terakhir">
        <div class="trend-chart__axis"><span><?= number_format($trend_max, 0, ',', '.'); ?></span><span><?= number_format($trend_max / 2, 0, ',', '.'); ?></span><span>0</span></div>
        <div class="trend-chart__plot" style="--trend-columns:<?= count($dashboard_trend); ?>">
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
    </article>

    <article class="dash-panel">
      <div class="dash-panel__head">
        <div><h2 class="dash-panel__title"><?= $dashboard_profile === 'user' ? 'Status Pelaporan Saya' : 'Kepatuhan Pelaporan'; ?></h2><p class="dash-panel__subtitle">Cakupan pelaporan pada tahun berjalan.</p></div>
      </div>
      <div class="compliance-wrap">
        <div class="compliance-ring" style="--value:<?= (int) $dashboard_compliance['percentage']; ?>"><span class="compliance-ring__value"><?= (int) $dashboard_compliance['percentage']; ?><small>%</small></span></div>
        <div class="compliance-legend">
          <div><strong><?= number_format($dashboard_compliance['submitted'], 0, ',', '.'); ?></strong><span>Sudah melapor</span></div>
          <div><strong><?= number_format($dashboard_compliance['missing'], 0, ',', '.'); ?></strong><span>Belum melapor</span></div>
        </div>
      </div>
    </article>
  </div>

  <div class="dash-grid dash-grid--equal">
    <article class="dash-panel">
      <div class="dash-panel__head"><div><h2 class="dash-panel__title">Komposisi Layanan</h2><p class="dash-panel__subtitle">Aktivitas per jenis layanan sampai hari ini.</p></div></div>
      <div class="service-list">
        <?php foreach ($dashboard_services as $service): ?>
          <div class="service-row">
            <div class="service-row__meta"><span class="service-row__name"><i class="fa <?= _ent($service['icon']); ?>"></i><?= _ent($service['label']); ?></span><strong class="service-row__total"><?= number_format($service['total'], 0, ',', '.'); ?></strong></div>
            <div class="service-row__track"><div class="service-row__fill" style="width:<?= max(1, round(($service['total'] / $service_max) * 100)); ?>%"></div></div>
          </div>
        <?php endforeach; ?>
      </div>
    </article>

    <article class="dash-panel">
      <div class="dash-panel__head"><div><h2 class="dash-panel__title">Laporan Terbaru</h2><p class="dash-panel__subtitle"><?= $dashboard_profile === 'user' ? 'Riwayat pengiriman laporan Anda.' : 'Pengiriman laporan terakhir yang tercatat.'; ?></p></div></div>
      <?php if ($dashboard_recent): ?>
        <ul class="activity-list">
          <?php foreach ($dashboard_recent as $recent): ?>
            <li class="activity-item">
              <span class="activity-item__icon"><i class="fa fa-file-text-o"></i></span>
              <span><strong><?= _ent($recent['display_name']); ?></strong><small>@<?= _ent($recent['username']); ?></small></span>
              <time datetime="<?= _ent($recent['report_date']); ?>"><?= _ent($format_date($recent['report_date'])); ?></time>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <div class="empty-state"><i class="fa fa-inbox"></i><strong>Belum ada laporan</strong><span>Data laporan akan tampil setelah pengiriman pertama.</span></div>
      <?php endif; ?>
    </article>
  </div>

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
                    <td><span class="attention-badge"><?= _ent($format_date($attention['last_report'])); ?></span></td>
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
