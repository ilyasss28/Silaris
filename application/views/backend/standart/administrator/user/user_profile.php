<?php
$this->load->view('backend/standart/administrator/user/_user_profile_card', array(
  'user' => $user,
  'groups' => isset($groups) ? $groups : array(),
  'region_name' => isset($region_name) ? $region_name : null,
  'notary_profile' => isset($notary_profile) ? $notary_profile : false,
  'notary_completeness' => isset($notary_completeness) ? $notary_completeness : null,
  'is_notary_profile' => !empty($is_notary_profile),
  'is_mpd_profile' => !empty($is_mpd_profile),
  'mpd_profile' => isset($mpd_profile) ? $mpd_profile : false,
  'profile_mode' => 'account',
));
?>
