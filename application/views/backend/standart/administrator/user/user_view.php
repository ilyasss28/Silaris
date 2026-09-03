<?php
$this->load->view('backend/standart/administrator/user/_user_profile_card', array(
  'user' => $user,
  'groups' => isset($groups) ? $groups : array(),
  'region_name' => isset($region_name) ? $region_name : null,
  'mpd_region_names' => isset($mpd_region_names) ? $mpd_region_names : array(),
  'profile_mode' => 'notary',
));
?>
