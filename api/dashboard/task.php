<?php
require_once("../libs/tasks.php");

$action = $_REQUEST['action'];

switch($action){
    case 'getTaskDetails':
        getTaskDetails();//Get task list
        break;
    case 'saveTask'://Add task
        saveTask();
        break;	
    case 'saveTask'://Update Task
        updateTask();
        break;	
    case 'deleteTask'://Update Task
        deleteTask();
        break;	                                      	                                         	   
}
?>