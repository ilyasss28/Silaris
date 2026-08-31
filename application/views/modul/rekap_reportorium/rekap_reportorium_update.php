<?php
$rekap_edit_title = 'Rekap Reportorium';
$rekap_edit_slug = 'rekap_reportorium';
$rekap_edit_id = $rekap_reportorium->id_reportorium;
$rekap_edit_record = $rekap_reportorium;
$this->load->view('modul/_partials/rekap_akta_update', compact(
   'rekap_edit_title',
   'rekap_edit_slug',
   'rekap_edit_id',
   'rekap_edit_record'
));
?>
