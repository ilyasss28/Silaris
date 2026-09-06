<?php $search_query = trim((string) $this->input->get('q')); ?>
<section class="content mpd-registry-page mpd-registry-list">
  <div class="mpd-registry-card">
    <header class="mpd-registry-header">
      <div><span class="mpd-registry-eyebrow">MASTER DATA PENGAWASAN</span><h1>Data MPD</h1><p>Daftar Majelis Pengawas Daerah <span class="mpd-count-badge"><?= (int) $data_mpd_count; ?> Item</span></p></div>
      <?php is_allowed('data_mpd_add', function () { ?><a class="btn admin-button admin-button--save" href="<?= site_url('data_mpd/add'); ?>"><i class="fa fa-plus-square-o" aria-hidden="true"></i> Tambah Data</a><?php }); ?>
    </header>

    <form class="mpd-list-form" method="get" action="<?= site_url('data_mpd/index'); ?>">
      <div class="table-responsive mpd-table-wrap">
        <table class="table table-bordered table-striped dataTable mpd-registry-table" aria-label="Data MPD">
          <thead><tr><th>Nama MPD</th><th>Akun</th><th>Wilayah Pengawasan</th><th>SK / Masa Jabatan</th><th>Verifikasi</th><th>Status Akun</th><th>Aksi</th></tr></thead>
          <tbody>
            <?php foreach ($data_mpd as $mpd): ?>
              <?php
              $avatar = !empty($mpd->avatar) ? basename($mpd->avatar) : 'default.png';
              $avatar_url = is_file(FCPATH . 'uploads/user/' . $avatar) ? base_url('uploads/user/' . $avatar) : base_url('uploads/user/default.png');
              ?>
              <tr>
                <td><div class="mpd-person"><img src="<?= _ent($avatar_url); ?>" alt=""><span><strong><?= _ent(format_person_name($mpd->nama_mpd)); ?></strong><small><?= _ent($mpd->jabatan ?: 'Anggota MPD'); ?></small></span></div></td>
                <td><strong><?= _ent($mpd->username ?: '-'); ?></strong><small class="mpd-table-muted"><?= _ent($mpd->email ?: '-'); ?></small></td>
                <td><span class="mpd-region-summary"><?= _ent($mpd->wilayah_nama ?: 'Belum ditentukan'); ?></span><small class="mpd-table-muted"><?= _ent($mpd->wilayah_kode ?: '-'); ?></small></td>
                <td><strong><?= _ent($mpd->nomor_sk ?: '-'); ?></strong><small class="mpd-table-muted table-date"><?= _ent(format_date_id($mpd->tanggal_mulai) ?: '-'); ?> sampai <?= _ent(format_date_id($mpd->tanggal_selesai) ?: '-'); ?></small></td>
                <td><span class="mpd-badge <?= $mpd->is_verified ? 'mpd-badge--verified' : 'mpd-badge--pending'; ?>"><i class="fa <?= $mpd->is_verified ? 'fa-check-circle' : 'fa-clock-o'; ?>" aria-hidden="true"></i><?= $mpd->is_verified ? 'Terverifikasi' : 'Belum diverifikasi'; ?></span></td>
                <td><span class="mpd-badge <?= $mpd->banned ? 'mpd-badge--inactive' : 'mpd-badge--active'; ?>"><i class="fa fa-circle" aria-hidden="true"></i><?= $mpd->banned ? 'Nonaktif' : 'Aktif'; ?></span></td>
                <td class="mpd-actions">
                  <?php is_allowed('data_mpd_view', function () use ($mpd) { ?><a class="label-default" title="Detail" href="<?= site_url('data_mpd/view/' . $mpd->id_mpd); ?>"><i class="fa fa-eye" aria-hidden="true"></i></a><?php }); ?>
                  <?php is_allowed('data_mpd_update', function () use ($mpd) { ?><a class="label-default mpd-action-edit" title="Edit" href="<?= site_url('data_mpd/edit/' . $mpd->id_mpd); ?>"><i class="fa fa-pencil" aria-hidden="true"></i></a><?php }); ?>
                  <?php is_allowed('data_mpd_delete', function () use ($mpd) { ?><button type="button" class="label-default mpd-delete-button" title="Hapus" data-url="<?= site_url('data_mpd/delete/' . $mpd->id_mpd); ?>"><i class="fa fa-trash" aria-hidden="true"></i></button><?php }); ?>
                </td>
              </tr>
            <?php endforeach; ?>
            <?php if (!$data_mpd): ?><tr><td class="mpd-empty-state" colspan="7"><i class="fa fa-inbox" aria-hidden="true"></i><strong><?= $search_query !== '' ? 'Data MPD tidak ditemukan' : 'Data MPD belum tersedia'; ?></strong><span><?= $search_query !== '' ? 'Coba gunakan kata kunci pencarian yang berbeda.' : 'Tambahkan Data MPD untuk memulai pengaturan wilayah pengawasan.'; ?></span></td></tr><?php endif; ?>
          </tbody>
        </table>
      </div>
      <footer class="mpd-list-footer"><span>Menampilkan <?= count($data_mpd); ?> dari <?= (int) $data_mpd_count; ?> data</span><div class="dataTables_paginate"><?= $pagination; ?></div></footer>
    </form>
  </div>
</section>
<script>
$(document).on('click', '.mpd-delete-button', function () {
  if (!window.confirm('Hapus Data MPD ini? Akun terkait akan dinonaktifkan.')) return;
  var form = document.createElement('form');
  form.method = 'post'; form.action = this.getAttribute('data-url');
  var csrfInput = document.createElement('input');
  csrfInput.type = 'hidden'; csrfInput.name = csrf; csrfInput.value = token;
  form.appendChild(csrfInput); document.body.appendChild(form); form.submit();
});
</script>
