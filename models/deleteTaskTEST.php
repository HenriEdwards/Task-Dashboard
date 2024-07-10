<?php

require_once('Task.php');

echo " Starting to delete ";

$task = new Task;
$task->deleteTasks(6);

echo " Deletion action complete ";