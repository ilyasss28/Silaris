<?php
$editing = !empty($profile);
$action = $editing ? site_url('data_mpd/edit_save/' . $profile->id_mpd) : site_url('data_mpd/add_save');
$selected_user = set_value('user_id', $editing ? $profile->user_id : '');
$selected_regions = set_value('wilayah', $selected_regions);
$selected_regions = is_array($selected_regions) ? $selected_regions : array();
$errors = validation_errors();
?>
<section class="content fidusia-form-page mpd-registry-page mpd-registry-form-page">
  <div class="fidusia-form-shell">
    <header class="fidusia-form-header">
      <div class="fidusia-form-header__copy">
        <span class="fidusia-form-header__icon"><i class="fa <?= $editing ? 'fa-pencil' : 'fa-plus'; ?>" aria-hidden="true"></i></span>
        <div><span class="fidusia-form-eyebrow">DATA INDUK MPD</span><h1><?= $editing ? 'Edit Data MPD' : 'Tambah Data MPD'; ?></h1><p>Hubungkan identitas MPD, akun SILARIS, dan seluruh kabupaten/kota yang diawasi.</p></div>
      </div>
      <span class="fidusia-form-status"><i class="fa <?= $editing ? 'fa-edit' : 'fa-file-o'; ?>" aria-hidden="true"></i><?= $editing ? 'Mode edit' : 'Data baru'; ?></span>
    </header>
    <?= form_open($action, array('id' => 'data-mpd-form', 'class' => 'fidusia-form mpd-form')); ?>
      <?php if ($errors): ?><div class="message fidusia-form-message mpd-form-message" role="alert"><?= $errors; ?></div><?php endif; ?>
      <div class="fidusia-form-grid mpd-form-grid-modern">
        <section class="fidusia-form-card">
          <div class="fidusia-form-card__heading"><span><i class="fa fa-user" aria-hidden="true"></i></span><div><h2>Identitas dan Akun</h2><p>Data MPD ditautkan ke satu akun dengan role MPD.</p></div></div>
          <div class="fidusia-form-fields fidusia-form-fields--document">
            <div class="fidusia-form-field fidusia-form-field--full">
              <label for="user_id">Akun MPD <i class="required">*</i></label>
              <select class="form-control" name="user_id" id="user_id" required><option value="">Pilih akun MPD</option><?php foreach ($accounts as $account): ?><option value="<?= (int) $account->id; ?>" data-name="<?= _ent($account->full_name); ?>" data-email="<?= _ent($account->email); ?>" <?= (string) $selected_user === (string) $account->id ? 'selected' : ''; ?>><?= _ent(format_person_name($account->full_name)); ?> — <?= _ent($account->username); ?><?= $account->banned ? ' (Nonaktif)' : ''; ?></option><?php endforeach; ?></select>
              <small><i class="fa fa-link" aria-hidden="true"></i>Satu akun hanya dapat terhubung ke satu Data MPD.</small>
            </div>
            <div class="fidusia-form-field"><label for="nama_mpd">Nama Lengkap <i class="required">*</i></label><input class="form-control" id="nama_mpd" name="nama_mpd" maxlength="150" required placeholder="Masukkan nama lengkap MPD" value="<?= _ent(set_value('nama_mpd', $editing ? $profile->nama_mpd : '')); ?>"><small><i class="fa fa-id-card-o" aria-hidden="true"></i>Gunakan nama sesuai identitas resmi.</small></div>
            <div class="fidusia-form-field"><label for="jabatan">Jabatan <i class="required">*</i></label><input class="form-control" id="jabatan" name="jabatan" maxlength="100" required placeholder="Contoh: Ketua MPD" value="<?= _ent(set_value('jabatan', $editing ? $profile->jabatan : 'Anggota MPD')); ?>"><small><i class="fa fa-briefcase" aria-hidden="true"></i>Jabatan aktif dalam susunan MPD.</small></div>
            <div class="fidusia-form-field"><label for="email">Email <i class="required">*</i></label><input type="email" class="form-control" id="email" name="email" maxlength="150" required placeholder="nama@contoh.go.id" value="<?= _ent(set_value('email', $editing ? $profile->email : '')); ?>"><small><i class="fa fa-envelope-o" aria-hidden="true"></i>Gunakan alamat email yang aktif.</small></div>
            <div class="fidusia-form-field"><label for="no_telepon">Nomor Telepon <i class="required">*</i></label><input type="tel" inputmode="numeric" pattern="08[0-9]{8,11}" class="form-control" id="no_telepon" name="no_telepon" minlength="10" maxlength="13" required placeholder="Contoh: 081234567890" value="<?= _ent(set_value('no_telepon', $editing ? format_phone_number($profile->no_telepon) : '')); ?>"><small><i class="fa fa-phone" aria-hidden="true"></i>Gunakan 10–13 digit dalam format 08xxxxxxxxxx.</small></div>
            <div class="fidusia-form-field fidusia-form-field--full"><label for="alamat">Alamat</label><textarea class="form-control" id="alamat" name="alamat" rows="3" maxlength="255" placeholder="Masukkan alamat sekretariat atau domisili MPD"><?= _ent(set_value('alamat', $editing ? $profile->alamat : '')); ?></textarea><small><i class="fa fa-map-o" aria-hidden="true"></i>Maksimal 255 karakter.</small></div>
          </div>
        </section>
        <section class="fidusia-form-card">
          <div class="fidusia-form-card__heading"><span><i class="fa fa-map-marker" aria-hidden="true"></i></span><div><h2>Penugasan dan Wilayah</h2><p>Satu MPD dapat mengawasi beberapa kabupaten/kota.</p></div></div>
          <div class="fidusia-form-fields fidusia-form-fields--document">
            <div class="fidusia-form-field fidusia-form-field--full mpd-region-field">
              <div class="mpd-region-label-row">
                <label for="wilayah">Wilayah Pengawasan <i class="required">*</i></label>
                <div class="mpd-region-actions" role="group" aria-label="Tindakan pilihan wilayah">
                  <button type="button" id="mpd-select-all-regions"><i class="fa fa-check-square-o" aria-hidden="true"></i> Pilih semua</button>
                  <button type="button" id="mpd-clear-regions"><i class="fa fa-times" aria-hidden="true"></i> Hapus pilihan</button>
                </div>
              </div>
              <select class="form-control chosen chosen-select mpd-region-select" name="wilayah[]" id="wilayah" multiple="multiple" aria-required="true" aria-describedby="mpd-region-help mpd-region-error" data-placeholder="Cari dan pilih kabupaten/kota"><?php foreach ($regions as $region): ?><option value="<?= _ent($region->kd_wilayah); ?>" <?= in_array((string) $region->kd_wilayah, array_map('strval', $selected_regions), true) ? 'selected' : ''; ?>><?= _ent(format_title_case($region->nama)); ?> [<?= _ent($region->kd_wilayah); ?>]</option><?php endforeach; ?></select>
              <div class="mpd-region-meta"><small id="mpd-region-help"><i class="fa fa-info-circle" aria-hidden="true"></i>Klik kolom lalu pilih beberapa kabupaten/kota. Setiap pilihan dapat dihapus kembali.</small><span id="mpd-region-count" aria-live="polite"></span></div>
              <small class="mpd-region-error" id="mpd-region-error" role="alert" hidden><i class="fa fa-exclamation-circle" aria-hidden="true"></i>Pilih minimal satu kabupaten/kota.</small>
            </div>
            <div class="fidusia-form-field fidusia-form-field--full"><label for="nomor_sk">Nomor Surat Keputusan</label><input class="form-control" id="nomor_sk" name="nomor_sk" maxlength="120" placeholder="Masukkan nomor surat keputusan" value="<?= _ent(set_value('nomor_sk', $editing ? $profile->nomor_sk : '')); ?>"><small><i class="fa fa-file-text-o" aria-hidden="true"></i>Isi sesuai dokumen pengangkatan MPD.</small></div>
            <div class="fidusia-form-field"><label for="tanggal_mulai">Mulai Masa Jabatan</label><input type="date" class="form-control native-date-input" id="tanggal_mulai" name="tanggal_mulai" value="<?= _ent(set_value('tanggal_mulai', $editing ? $profile->tanggal_mulai : '')); ?>"><small><i class="fa fa-calendar" aria-hidden="true"></i>Tanggal mulai penugasan.</small></div>
            <div class="fidusia-form-field"><label for="tanggal_selesai">Selesai Masa Jabatan</label><input type="date" class="form-control native-date-input" id="tanggal_selesai" name="tanggal_selesai" value="<?= _ent(set_value('tanggal_selesai', $editing ? $profile->tanggal_selesai : '')); ?>"><small><i class="fa fa-calendar-check-o" aria-hidden="true"></i>Harus sama dengan atau setelah tanggal mulai.</small></div>
            <div class="mpd-verification fidusia-form-field--full"><label><input type="hidden" name="is_verified" value="0"><input type="checkbox" name="is_verified" value="1" <?= set_checkbox('is_verified', '1', $editing && (int) $profile->is_verified === 1); ?>><span><strong>Data telah diverifikasi</strong><small>Centang setelah identitas, SK, dan wilayah dipastikan benar. Akun tetap nonaktif sebelum data diverifikasi.</small></span></label></div>
          </div>
        </section>
      </div>
      <footer class="fidusia-form-actions">
        <div class="fidusia-form-actions__hint"><i class="fa fa-info-circle" aria-hidden="true"></i><span>Perubahan wilayah langsung berlaku pada laporan dan rekap MPD.</span></div>
        <div class="fidusia-form-actions__buttons"><a class="btn admin-button admin-button--neutral" id="mpd_cancel" href="<?= site_url('data_mpd'); ?>"><i class="fa fa-times" aria-hidden="true"></i> Batal</a><button class="btn admin-button admin-button--save" type="submit"><i class="fa fa-save" aria-hidden="true"></i> Simpan Data MPD</button></div>
      </footer>
    <?= form_close(); ?>
  </div>
</section>
<script>
$(function () {
  var regionSelect = $('#wilayah');

  if ($.fn.chosen) {
    if (regionSelect.data('chosen')) regionSelect.chosen('destroy');
    regionSelect.chosen({
      width: '100%',
      search_contains: true,
      hide_results_on_select: false,
      no_results_text: 'Wilayah tidak ditemukan:'
    });
  }

  function updateRegionCounter() {
    var total = (regionSelect.val() || []).length;
    $('#mpd-region-count').text(total + ' wilayah dipilih');
    regionSelect.next('.chosen-container').toggleClass('mpd-region-select--invalid', total === 0 && $('#mpd-region-error').is(':visible'));
    if (total > 0) $('#mpd-region-error').prop('hidden', true).hide();
  }

  regionSelect.on('change.dataMpdRegions', updateRegionCounter);
  $('#mpd-select-all-regions').on('click', function () {
    regionSelect.find('option').prop('selected', true);
    regionSelect.trigger('chosen:updated').trigger('change');
  });
  $('#mpd-clear-regions').on('click', function () {
    regionSelect.val([]).trigger('chosen:updated').trigger('change');
  });
  updateRegionCounter();

  $('#data-mpd-form').on('submit.dataMpdRegions', function (event) {
    if ((regionSelect.val() || []).length) return;
    event.preventDefault();
    $('#mpd-region-error').prop('hidden', false).show();
    regionSelect.next('.chosen-container').addClass('mpd-region-select--invalid');
    regionSelect.next('.chosen-container').find('.chosen-choices').trigger('click');
  });

  $('#user_id').on('change', function () {
    var option = this.options[this.selectedIndex];
    if (!option || !option.value) return;
    if (!$('#nama_mpd').val()) $('#nama_mpd').val(option.getAttribute('data-name') || '');
    if (!$('#email').val()) $('#email').val(option.getAttribute('data-email') || '');
  });
  $(document).off('keydown.dataMpdForm').on('keydown.dataMpdForm', function (event) {
    if (!event.ctrlKey) return;
    var key = String(event.key).toLowerCase();
    if (key === 's') { event.preventDefault(); $('#data-mpd-form').trigger('submit'); }
    if (key === 'x') { event.preventDefault(); document.getElementById('mpd_cancel').click(); }
  });
});
</script>
