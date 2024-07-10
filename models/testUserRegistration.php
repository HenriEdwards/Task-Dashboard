<?php 

require_once "User.php";

$user = new User();
echo " Executing registration... ";
$user->register("test", "test");
echo " Registration completed. ";