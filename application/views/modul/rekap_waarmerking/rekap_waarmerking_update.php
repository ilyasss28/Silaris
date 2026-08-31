<?php
$rekap_edit_title = 'Rekap Waarmerking';
$rekap_edit_slug = 'rekap_waarmerking';
$rekap_edit_id = $rekap_waarmerking->id_waarmerking;
$rekap_edit_record = $rekap_waarmerking;
$this->load->view('modul/_partials/rekap_akta_update', compact(
   'rekap_edit_title',
   'rekap_edit_slug',
   'rekap_edit_id',
   'rekap_edit_record'
));
?>
