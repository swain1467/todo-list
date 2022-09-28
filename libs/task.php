<?php
require_once("../utility/db_connection.php");
session_start();
function getTaskDetails(){
    $pdo = pdo_connect();

    $output = array(	
		'aaData' => array(),
        'status' => ''
	);

    $data = [
        'user_name' => $_SESSION['user_name'],
        'status' => 1
    ];
    $selectQuery = "SELECT id, CONCAT(header,':',content) as task, 
    created_on, created_by, updated_on, updated_by
    FROM task_master WHERE created_by = :user_name AND status = :status";

    $stmt= $pdo->prepare($selectQuery);
    if($stmt->execute($data)){
        $result = $stmt->fetchAll();
        foreach($result as $row){
            $output['aaData'][] = $row;
            $output['status'] = 'Success';
        }
    } else{
        $output['status'] = $stmt->errorInfo();
    }
    $pdo = null;
    echo json_encode($output);
}
function saveTask(){
    $task_header = isset($_POST['task_header']) ? $_POST['task_header'] : '';
    $task_content = isset($_POST['task_content']) ? $_POST['task_content'] : '';
    $output = array(	
        'status' => '',
        'message' => '',
	);
    if(!$task_header){
        $output['status'] = 'Error';
        $output['message'] = 'Header is required';
    } else if(!$task_content){
        $output['status'] = 'Error';
        $output['message'] = 'Content is required';
    } else{
        $pdo = pdo_connect();
        $data = [
            'header' => $task_header,
            'content' => $task_content,
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
            $output['status'] = 'Success';
            $output['message'] = 'Task added successfully';
        } else{
            $output['status'] = 'Failure';
            $output['message'] = $stmt->errorInfo();
        }
        $pdo = null;
    }
    echo json_encode($output);
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