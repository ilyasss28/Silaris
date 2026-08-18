<?php
  $total_notaris = $summary['total_notaris'] ?? 0;
  $total_laporan = $summary['total_laporan'] ?? 0;
  $aktif_melapor = $summary['aktif_melapor'] ?? 0;
  $tingkat       = $summary['tingkat_persen'] ?? 0;
?>
<main id="main">

  <!-- ======= Hero ======= -->
  <section class="guest-hero page-hero">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-9 text-center">
          <span class="hero-badge"><i class="dot"></i> MONITORING KEPATUHAN <i class="dot"></i></span>
          <h1 class="hero-title">KEPATUHAN <em>NOTARIS</em></h1>
          <p class="lead">Pemantauan kepatuhan notaris se-Sulawesi Tenggara dalam menyampaikan laporan bulanan kepada Kanwil Kemenkum, berdasarkan data laporan yang tercatat di SILARIS.</p>

          <div class="row stat-row justify-content-center">
            <div class="col-6 col-md-3">
              <div class="stat-tile">
                <div class="stat-number"><?= $total_notaris; ?></div>
                <div class="stat-label">Akun Notaris</div>
              </div>
            </div>
            <div class="col-6 col-md-3">
              <div class="stat-tile">
                <div class="stat-number"><?= $aktif_melapor; ?></div>
                <div class="stat-label">Aktif Melapor</div>
              </div>
            </div>
            <div class="col-6 col-md-3">
              <div class="stat-tile">
                <div class="stat-number"><?= $total_laporan; ?></div>
                <div class="stat-label">Laporan Diterima</div>
              </div>
            </div>
            <div class="col-6 col-md-3">
              <div class="stat-tile">
                <div class="stat-number"><?= $tingkat; ?>%</div>
                <div class="stat-label">Tingkat Kepatuhan</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ======= Compliance table ======= -->
  <section id="services" class="services compliance-section">
    <div class="container">

      <ul class="breadcrumb">
        <li><a class="homeLink" href="<?php echo base_url().'home/index'?>"><i class="icofont-home"></i> Beranda /</a></li>
        <li class="active">Kepatuhan Notaris</li>
      </ul>

      <div class="section-title text-start">
        <h2>Daftar Kepatuhan Pelaporan</h2>
        <p>Status penyampaian laporan bulanan per akun notaris. Notaris yang belum pernah menyampaikan laporan ditandai "Belum Melapor".</p>
      </div>

      <form method="get" class="compliance-search">
        <input type="text" name="q" value="<?= isset($q) ? _ent($q) : ''; ?>" placeholder="Cari nama atau username notaris...">
        <button type="submit"><i class="icofont-search"></i> Cari</button>
      </form>

      <div class="table-responsive compliance-table-wrap">
        <table class="table compliance-table">
          <thead>
            <tr>
              <th>Nama Notaris</th>
              <th>Username</th>
              <th class="text-center">Jumlah Laporan</th>
              <th>Laporan Terakhir</th>
              <th class="text-center">Status</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($notaris)): ?>
              <tr>
                <td colspan="5" class="compliance-empty">
                  <i class="icofont-search-document"></i>
                  <p>Tidak ada data notaris yang cocok.</p>
                </td>
              </tr>
            <?php else: ?>
              <?php foreach ($notaris as $row): ?>
                <?php $patuh = $row->jumlah_laporan > 0; ?>
                <tr>
                  <td class="compliance-name"><?= _ent(ucwords(strtolower($row->full_name))); ?></td>
                  <td class="compliance-username">@<?= _ent($row->username); ?></td>
                  <td class="text-center"><b><?= $row->jumlah_laporan; ?></b></td>
                  <td><?= $row->laporan_terakhir ? date('d M Y', strtotime($row->laporan_terakhir)) : '&mdash;'; ?></td>
                  <td class="text-center">
                    <?php if ($patuh): ?>
                      <span class="status-badge status-ok"><i class="icofont-check-circled"></i> Aktif Melapor</span>
                    <?php else: ?>
                      <span class="status-badge status-warn"><i class="icofont-close-circled"></i> Belum Melapor</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

    </div>
  </section>

</main>
