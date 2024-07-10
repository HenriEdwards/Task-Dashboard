<?php

session_start();
session_destroy();

// Redirect user
header("location: /Task-Dashboard/index.php");
exit;