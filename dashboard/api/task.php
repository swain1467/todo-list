<?php
include("../../asset/error_report.php");
require_once("../../asset/db_connection.php");
require_once("check_req.php");

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
function getTaskDetails(){
    $pdo = pdo_connect();

    $task_list = array();

    $data = [
        'user_name' => $_SESSION['user_name'],
        'status' => 1
    ];

    $selectQuery = "SELECT id, header, content, 
    created_on, created_by, update_on, updated_by,
    FROM task_master WHERE created_by = :user_name AND status = :status";
    
    $stmt= $pdo->prepare($selectQuery);
    if($stmt->execute($data)){
        $result = $stmt->fetchAll();
        foreach($result as $row){
            $task_list = $row;
        }
    } else{
        $msg = $stmt->errorInfo();
    }
    $pdo = null;
    echo json_encode($task_list);
}
function saveTask(){
    $pdo = pdo_connect();

    $header = isset($_POST['txtHeader']) ? $_POST['txtHeader'] : '';
    $content = isset($_POST['txtContent']) ? $_POST['txtContent'] : '';

    $data = [
        'header' => $txtUserName,
        'content' => $content,
        'created_on' => date("Y-m-d H:i:s"),
        'created_by' => $_SESSION['user_name'],
        'updated_on' => date("Y-m-d H:i:s"),
        'updated_by' => $_SESSION['user_name'],
        'status' => 1
    ];
    
    $insert_query = "INSERT INTO task_master(header, content, created_on, created_by, updated_on, updated_by, status)
            VALUES (:header, :content, :created_on, :created_by, :updated_on, :updated_by, :status)";
    $stmt= $pdo->prepare($insert_query);
    if($stmt->execute($data)){
        $msg = "Task added successfully";
    } else{
        $msg = $stmt->errorInfo();
    }
    $pdo = null;
    echo json_encode($msg);
}
function updateTask(){
    $pdo = pdo_connect();

    $header = isset($_POST['txtHeader']) ? $_POST['txtHeader'] : '';
    $content = isset($_POST['txtContent']) ? $_POST['txtContent'] : '';
    $id = isset($_POST['txtTaskId']) ? $_POST['txtTaskId'] : '';

    $data = [
        'header' => $header,
        'content' => $content,
        'updated_on' => date("Y-m-d H:i:s"),
        'updated_by' => $_SESSION['user_name'],
        'id' => $id
    ];
    
    $update_query = "UPDATE task_master
        SET header = :header, content = :content,
            updated_on = :updated_on, updated_by = :updated_by
        WHERE id = :id";

    $stmt= $pdo->prepare($update_query);
    if($stmt->execute($data)){
        $msg = "Task updated successfully";
    } else{
        $msg = $stmt->errorInfo();
    }
    $pdo = null;
    echo json_encode($msg);
}
function deleteTask(){
    $pdo = pdo_connect();

    $id = isset($_POST['txtTaskId']) ? $_POST['txtTaskId'] : '';

    $data = [
        'updated_on' => date("Y-m-d H:i:s"),
        'updated_by' => $_SESSION['user_name'],
        'id' => $id
    ];
    
    $update_query = "UPDATE task_master
        SET updated_on = :updated_on, updated_by = :updated_by
        WHERE id = :id";

    $stmt= $pdo->prepare($update_query);
    if($stmt->execute($data)){
        $msg = "Task deleted successfully";
    } else{
        $msg = $stmt->errorInfo();
    }
    $pdo = null;
    echo json_encode($msg);
}
?>