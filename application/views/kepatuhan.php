<?php
  $total_notaris = $summary['total_notaris'] ?? 0;
  $total_laporan = $summary['total_laporan'] ?? 0;
  $aktif_melapor = $summary['aktif_melapor'] ?? 0;
  $tingkat       = $summary['tingkat_persen'] ?? 0;

  if (!function_exists('format_gelar')) {
      /**
       * @param string $nama
       * @return string
       */
      function format_gelar($nama) {
          $nama = ucwords(strtolower($nama));
          
          // Standarisasi gelar dengan koma di akhir
          $nama = preg_replace('/\bS[\.\s]*H\b[\.\s]*/i', 'S.H., ', $nama);
          $nama = preg_replace('/\bM[\.\s]*K[\.\s]*N\b[\.\s]*/i', 'M.Kn., ', $nama);
          $nama = preg_replace('/\bM[\.\s]*H\b[\.\s]*/i', 'M.H., ', $nama);
          $nama = preg_replace('/\bS[\.\s]*E\b[\.\s]*/i', 'S.E., ', $nama);
          $nama = preg_replace('/\bS[\.\s]*T\b[\.\s]*/i', 'S.T., ', $nama);
          
          // Pastikan ada tanda koma dan spasi sebelum gelar
          $nama = preg_replace('/[\s,]+(S\.H\.|M\.Kn\.|M\.H\.|S\.E\.|S\.T\.)/', ', $1', $nama);
          
          // Bersihkan sisa koma ganda
          $nama = str_replace(',,', ',', $nama);
          $nama = preg_replace('/\s+/', ' ', $nama);
          
          return trim($nama, ' ,');
      }
  }
?>
<main id="main">

  <!-- ======= Hero ======= -->
  <section class="guest-hero page-hero">
    <span class="hero-orb hero-orb-1" aria-hidden="true"></span>
    <span class="hero-orb hero-orb-2" aria-hidden="true"></span>
    <span class="hero-orb hero-orb-3" aria-hidden="true"></span>
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-9 text-center">
          <span class="hero-badge" data-aos="fade-up" data-aos-delay="100"><i class="dot"></i> MONITORING KEPATUHAN <i class="dot"></i></span>
          <h1 class="hero-title" data-aos="fade-up" data-aos-delay="200">KEPATUHAN <em>NOTARIS</em></h1>
          <p class="lead" data-aos="fade-up" data-aos-delay="300">Pemantauan kepatuhan notaris se-Sulawesi Tenggara dalam menyampaikan laporan bulanan kepada Kanwil Kemenkum, berdasarkan data laporan yang tercatat di SILARIS.</p>

          <div class="row stat-row justify-content-center" data-aos="fade-up" data-aos-delay="400">
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
        <li><a class="homeLink" href="<?= site_url('home'); ?>"><i class="icofont-home"></i> Beranda /</a></li>
        <li class="active">Kepatuhan Notaris</li>
      </ul>

      <div class="section-title text-start" data-aos="fade-up">
        <h2>Daftar Kepatuhan Pelaporan</h2>
        <p>Status penyampaian laporan bulanan per akun notaris. Notaris yang belum pernah menyampaikan laporan ditandai "Belum Melapor".</p>
      </div>

      <div class="compliance-search" data-aos="fade-up" data-aos-delay="100">
        <input type="text" id="searchInput" placeholder="Cari nama atau username notaris..." autocomplete="off">
        <button type="button" id="searchBtn"><i class="icofont-search"></i> Cari</button>
      </div>

      <div class="table-responsive compliance-table-wrap" data-aos="fade-up" data-aos-delay="200">
        <table class="table compliance-table" id="complianceTable">
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
                  <td class="compliance-name"><?= _ent(format_gelar($row->full_name)); ?></td>
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

<script>
document.addEventListener("DOMContentLoaded", function() {
  const searchInput = document.getElementById('searchInput');
  const searchBtn = document.getElementById('searchBtn');
  const tableRows = document.querySelectorAll('#complianceTable tbody tr');

  function filterTable() {
    const term = searchInput.value.toLowerCase();
    let hasResult = false;
    let emptyRow = document.getElementById('emptySearchRow');

    tableRows.forEach(function(row) {
      if (row.id === 'emptySearchRow' || row.querySelector('.compliance-empty')) return;
      const text = row.textContent.toLowerCase();
      if (text.indexOf(term) > -1) {
        row.style.display = '';
        hasResult = true;
      } else {
        row.style.display = 'none';
      }
    });

    if (!hasResult && tableRows.length > 0) {
      if (!emptyRow) {
        const tbody = document.querySelector('#complianceTable tbody');
        emptyRow = document.createElement('tr');
        emptyRow.id = 'emptySearchRow';
        emptyRow.innerHTML = '<td colspan="5" class="compliance-empty"><i class="icofont-search-document"></i><p>Tidak ada data notaris yang cocok dengan pencarian Anda.</p></td>';
        tbody.appendChild(emptyRow);
      } else {
        emptyRow.style.display = '';
      }
    } else if (emptyRow) {
      emptyRow.style.display = 'none';
    }
  }

  searchInput.addEventListener('keyup', filterTable);
  searchBtn.addEventListener('click', filterTable);
});
</script>
