<?php
$this->load->view('backend/standart/administrator/user/_user_profile_card', array(
  'user' => $user,
  'groups' => isset($groups) ? $groups : array(),
  'profile_mode' => 'account',
));
?>
