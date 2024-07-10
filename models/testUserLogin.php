<?php 

require_once "User.php";

$user = new User();
echo " Executing... ";
$user->login("test", "test");
echo " Executing DONE ";