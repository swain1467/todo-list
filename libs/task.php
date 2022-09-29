<?php
require_once("../utility/db_connection.php");
require_once("../utility/error_report.php");
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
    header, content, created_on, created_by, updated_on, updated_by
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
    $task_header = isset($_POST['task_header']) ? $_POST['task_header'] : '';
    $task_content = isset($_POST['task_content']) ? $_POST['task_content'] : '';
    $id = isset($_POST['task_id']) ? $_POST['task_id'] : '';

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
            $output['status'] = 'Success';
            $output['message'] = 'Task updated successfully';
        } else{
            $output['status'] = 'Failure';
            $output['message'] = $stmt->errorInfo();
        }
        $pdo = null;
    }
    echo json_encode($output);
}
function deleteTask(){
    $id = isset($_POST['id']) ? $_POST['id'] : '';
    $output = array(	
        'status' => '',
        'message' => '',
	);

    $pdo = pdo_connect();
    
    $data = [
        'updated_on' => date("Y-m-d H:i:s"),
        'updated_by' => $_SESSION['user_name'],
        'status' => 0,
        'id' => $id
    ];
    
    $update_query = "UPDATE task_master
        SET updated_on = :updated_on, updated_by = :updated_by,
        status = :status WHERE id = :id";

    $stmt= $pdo->prepare($update_query);
    if($stmt->execute($data)){

        $output['status'] = 'Success';
        $output['message'] = 'Task deleted successfully';
    } else{
        $output['status'] = 'Failure';
        $output['message'] = $stmt->errorInfo();
    }
    $pdo = null;
    echo json_encode($output);
}
?>