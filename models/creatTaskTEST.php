<?php 

require_once "Task.php";

echo " CREATING TASK ";

$task = new Task();
$task->createTasks("Lorem", "Lorem","2024-08-15", "Pending", 1,);

echo " TASK COMPLETED ";