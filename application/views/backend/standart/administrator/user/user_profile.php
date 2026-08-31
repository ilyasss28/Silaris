<?php
$this->load->view('backend/standart/administrator/user/_user_profile_card', array(
  'user' => $user,
  'groups' => isset($groups) ? $groups : array(),
  'region_name' => isset($region_name) ? $region_name : null,
  'profile_mode' => 'account',
));
?>
