<?php
require_once(LIB_DIR."/db/query_builder.php");
require_once(LIB_DIR."/db/DBCore.class.php");

function getRowCount($data){

    $selectQuery = (new SelectQueryBuilder())
    ->select('id', 'CONCAT(header,":",content) as task', 'header', 'content')
    ->from('task_master')
    ->where('created_by = :user_name', 'status = :status')
    ->orderBy('created_on');

    $result = DBCore::executeQuery($selectQuery,$data);
    $all_rows = DBCore::getAllRows($result);

    return count($all_rows);
}
class TaskModel {
    //Task Select  
    public static function getTaskDetails($length, $start, $search, $user_name, $status) {

        $output = array(	
            'aaData' => array(),
            'status' => ''
        );
    
        $data = [
            'user_name' => $user_name,
            'status' => $status
        ];

        $output['recordsTotal'] = getRowCount($data);
        $output['recordsFiltered'] = getRowCount($data);
    
        $selectQuery = (new SelectQueryBuilder())
                        ->select('id', 'CONCAT(header,":",content) as task', 'header', 'content')
                        ->from('task_master')
                        ->where('created_by = :user_name', 'status = :status')
                        ->orderBy('created_on')
                        ->limit($length)
                        ->offSet($start);
        $result = DBCore::executeQuery($selectQuery,$data);
        
        $all_rows = DBCore::getAllRows($result);

      if($result['status']){

        foreach($all_rows as $row){
            $output['aaData'][] = $row;
            $output['status'] = 'Success';
        }
      } else {
        $output['status'] = 'Failure';
      }
        
        return $output;
    }
    //Task Insert 
    public static function saveTask($task_header, $task_content, $user_name, $status) {

        if(!$task_header){
            throw new Exception('Header is required');
        }
        if(!$task_content){
            throw new Exception('Content is required');
        } 
        $data = [
            'header' => $task_header,
            'content' => $task_content,
            'created_on' => date("Y-m-d H:i:s"),
            'created_by' => $user_name,
            'updated_on' => date("Y-m-d H:i:s"),
            'status' => $status
        ];
        
        $insert_query = (new InsertQueryBuilder())
        ->insert('task_master')
        ->columns('header', 'content', 'created_on', 'created_by', 'updated_on', 'status');
        
        $result = DBCore::executeQuery($insert_query,$data);
    
        if($result['status']){
            $output['status'] = 'Success';
            $output['message'] = 'Task added successfully';
        } else{
            $output['status'] = 'Failure';
            $output['message'] = 'Oops! something Went wrong';
        }
        
        return $output;
    }
    //Task Update 
    public static function updateTask($task_header, $task_content, $id) {
        $output = array(	
            'status' => '',
            'message' => '',
        );

        if(!$task_header){
            throw new Exception('Header is required');
        }
        if(!$task_content){
            throw new Exception('Content is required');
        } 
        $data = [
            'header' => $task_header,
            'content' => $task_content,
            'updated_on' => date("Y-m-d H:i:s"),
            'id' => $id
        ];
        
        $update_query = (new UpdateQueryBuilder())
        ->update('task_master')
        ->set('header', 'content', 'updated_on')
        ->where('id = :id');
    
        $result = DBCore::executeQuery($update_query,$data);

        if($result['status']){
            $output['status'] = 'Success';
            $output['message'] = 'Task updated successfully';
        } else{
            $output['status'] = 'Failure';
            $output['message'] = 'Oops! something Went wrong';
        }
    
        return $output;
    }
    //Task Delete 
    public static function deleteTask($id, $status) {
        $output = array(	
            'status' => '',
            'message' => '',
        );
        
        $data = [
            'updated_on' => date("Y-m-d H:i:s"),
            'status' => 0,
            'id' => $id
        ];
    
        $update_query = (new UpdateQueryBuilder())
        ->update('task_master')
        ->set('updated_on', 'status')
        ->where('id = :id');
    
        $result = DBCore::executeQuery($update_query,$data);
    
            if($result['status']){
                $output['status'] = 'Success';
                $output['message'] = 'Task deleted successfully';
            } else{
                $output['status'] = 'Failure';
                $output['message'] = 'Oops! something Went wrong';
            }
        return $output;
    }
}
?>