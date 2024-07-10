<?php 

require_once('task.php');

$task = new Task;
echo ' update process started ';
$task->updateTasks("New Title", "new description", "2024-05-21", "In Progress", 8);
echo ' update process finished ';