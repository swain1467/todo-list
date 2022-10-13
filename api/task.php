<?php
require_once("../config.php");
require_once(UTILITY_DIR."file_include.php");
require_once(UTILITY_DIR."check_req.php");
require_once(LIB_DIR."/task.php");

$action = $_REQUEST['action'];

$user_name = $_SESSION['user_name'];

try{
    switch($action){
        case 'getTaskDetails':{
            $task_status = isset($_GET['task_status']) ? $_GET['task_status'] : '';
            $length = isset($_GET['length']) ? $_GET['length'] : '';
            $start = isset($_GET['start']) ? $_GET['start'] : '';
            $search = isset($_GET['search']['value']) ? $_GET['search']['value'] : '';
            $status = 1;
            $output = TaskModel::getTaskDetails($task_status, $length, $start, $search, $user_name, $status); //Get task list
            break;
        }
        case 'saveTask':{
            $task_header = isset($_POST['task_header']) ? $_POST['task_header'] : '';
            $task_content = isset($_POST['task_content']) ? $_POST['task_content'] : '';
            $status = 1;
            $output = TaskModel::saveTask($task_header, $task_content, $user_name, $status); //Add task
            break;	
        }
        case 'updateTask':{
            $task_header = isset($_POST['task_header']) ? $_POST['task_header'] : '';
            $task_content = isset($_POST['task_content']) ? $_POST['task_content'] : '';
            $id = isset($_POST['task_id']) ? $_POST['task_id'] : '';
            $output = TaskModel::updateTask($task_header, $task_content, $id); //Update Task
            break;	
        }
        case 'deleteTask':{
            $id = isset($_POST['id']) ? $_POST['id'] : '';
            $status = 0;
            $output = TaskModel::deleteTask($id, $status); //Update Task
            break;	                                      	                                         	   
        }
        case 'markDone':{
            $id = isset($_POST['id']) ? $_POST['id'] : '';
            $status = 1;
            $output = TaskModel::markDone($id, $status); //Update Task
            break;	                                      	                                         	   
        }
    }
}catch(Exception $e){
    $output = array(	
        'status' => 'Error',
        'message' => $e->getMessage(),
    );
}finally{
    echo json_encode($output);
}
?>