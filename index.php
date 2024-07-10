<?php
session_start();

if (isset($_SESSION['username'])) {
  // User has an active session, redirect to the tasks page
  header("Location: /Task-Dashboard/views/tasks.php") ;
  exit;
} else {
  // no active session
  header("Location: /Task-Dashboard/views/login.php") ;
  exit;
}