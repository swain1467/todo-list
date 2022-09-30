<?php
require_once("../utility/db_connection.php");
require_once("../utility/error_report.php");
require_once("query_builder.php");

function getTaskDetails($length, $start, $search, $user_name, $status) {
    $pdo = pdo_connect();
    $output = array(	
		'aaData' => array(),
        'status' => ''
	);

    $data = [
        'user_name' => $user_name,
        'status' => $status
    ];

    $selectQuery = "SELECT id, CONCAT(header,':',content) as task, header, content
        FROM task_master WHERE created_by = :user_name AND status = :status
        ORDER BY updated_on DESC";

    $stmt= $pdo->prepare($selectQuery);
    $stmt->execute($data);
    $output['recordsTotal'] = COUNT($stmt->fetchAll()); 
    $output['recordsFiltered'] = COUNT($stmt->fetchAll()); 
    
    // $selectQuery = "SELECT id, CONCAT(header,':',content) as task, header, content
    // FROM task_master WHERE created_by = :user_name AND status = :status $where
    // ORDER BY updated_on DESC LIMIT $length OFFSET $start";

    $selectQuery = (new SelectQueryBuilder())
                    ->select('id', 'CONCAT(header,":",content) as task', 'header', 'content')
                    ->from('task_master')
                    ->where('created_by = :user_name', 'status = :status')
                    ->orderBy('created_on')
                    ->limit($length)
                    ->offSet($start);

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
    return $output;
}
function saveTask($task_header, $task_content, $user_name, $status) {
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
            'created_by' => $user_name,
            'updated_on' => date("Y-m-d H:i:s"),
            'status' => $status
        ];
        
        // $insert_query = "INSERT INTO task_master(header, content, created_on, created_by, updated_on, status)
        //         VALUES (:header, :content, :created_on, :created_by, :updated_on, :status)";
        $insert_query = (new InsertQueryBuilder())
        ->insert('task_master')
        ->columns('header', 'content', 'created_on', 'created_by', 'updated_on', 'status');
        
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
    return $output;
}
function updateTask($task_header, $task_content, $id) {
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
            'id' => $id
        ];
        
        // $update_query = "UPDATE task_master
        //     SET header = :header, content = :content,
        //         updated_on = :updated_on
        //     WHERE id = :id";

        $update_query = (new UpdateQueryBuilder())
        ->update('task_master')
        ->set('header', 'content', 'updated_on')
        ->where('id = :id');
    
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
    return $output;
}
function deleteTask($id, $status) {
    $output = array(	
        'status' => '',
        'message' => '',
	);

    $pdo = pdo_connect();
    
    $data = [
        'updated_on' => date("Y-m-d H:i:s"),
        'status' => 0,
        'id' => $id
    ];
    
    $update_query = "UPDATE task_master
        SET updated_on = :updated_on, status = :status WHERE id = :id";

    $stmt= $pdo->prepare($update_query);
    if($stmt->execute($data)){

        $output['status'] = 'Success';
        $output['message'] = 'Task deleted successfully';
    } else{
        $output['status'] = 'Failure';
        $output['message'] = $stmt->errorInfo();
    }
    $pdo = null;
    return $output;
}
?>