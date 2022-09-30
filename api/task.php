<?php
require_once("../utility/error_report.php");
require_once("../libs/task.php");
require_once("../utility/check_req.php");

$action = $_REQUEST['action'];

$user_name = $_SESSION['user_name'];

switch($action){
    case 'getTaskDetails':{
        $length = isset($_GET['length']) ? $_GET['length'] : '';
        $start = isset($_GET['start']) ? $_GET['start'] : '';
        $search = isset($_GET['search']['value']) ? $_GET['search']['value'] : '';
        $status = 1;
        
        $output = TaskModel::getTaskDetails ($length, $start, $search, $user_name, $status); //Get task list
        echo json_encode($output);
        break;
    }
    case 'saveTask':{
        $task_header = isset($_POST['task_header']) ? $_POST['task_header'] : '';
        $task_content = isset($_POST['task_content']) ? $_POST['task_content'] : '';
        $status = 1;
        
        $output = TaskModel::saveTask($task_header, $task_content, $user_name, $status); //Add task
        echo json_encode($output);
        break;	
    }
    case 'updateTask':{
        $task_header = isset($_POST['task_header']) ? $_POST['task_header'] : '';
        $task_content = isset($_POST['task_content']) ? $_POST['task_content'] : '';
        $id = isset($_POST['task_id']) ? $_POST['task_id'] : '';
        
        $output = TaskModel::updateTask($task_header, $task_content, $id); //Update Task
        echo json_encode($output);
        break;	
    }
    case 'deleteTask':{
        $id = isset($_POST['id']) ? $_POST['id'] : '';
        $status = 0;

        $output = TaskModel::deleteTask($id, $status); //Update Task
        echo json_encode($output);
        break;	                                      	                                         	   
    }
}
?>