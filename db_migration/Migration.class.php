<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once(LIB_DIR."/db/query_builder.php");
require_once(LIB_DIR."/db/DBCore.class.php");
class MigrationClass{
    public static function insertFiles($items){
        array_multisort(array_map('filemtime', $items), SORT_NUMERIC, SORT_DESC, $items);
        foreach($items AS $file_name){
            $data = [
                'file_name' => $file_name
            ];
            $selectQuery = (new SelectQueryBuilder())
            ->select('file_name')
            ->from('db_migration')
            ->where('file_name = :file_name')
            ->orderBy('file_name')
            ->limit(1)
            ->offSet(0);
        
            $result = DBCore::executeQuery($selectQuery,$data);
            $all_rows = DBCore::getAllRows($result);
        
            if(count($all_rows)==0){
                $insert_query = (new InsertQueryBuilder())
                ->insert('db_migration')
                ->columns('file_name');
                $result = DBCore::executeQuery($insert_query,$data);
            }
        }
    }
    public static function selectNewFiles(){
        $data = [
            'status' => 0
        ];
        $selectQuery = (new SelectQueryBuilder())
            ->select('file_name')
            ->from('db_migration')
            ->where('status = :status')
            ->orderBy('id');
            $result = DBCore::executeQuery($selectQuery,$data);
            $all_rows = DBCore::getAllRows($result);
           return $all_rows;
    }
    public static function executeSql($sql_statement){
        $data = null;
        $all_statement = array();
        $all_statement = explode(";",$sql_statement);
        $ex_status_count = 0;
        $pdo = pdo_connect();
        $pdo->beginTransaction();
        for($count=0; $count<count($all_statement); $count++){
            $result = DBCore::executeQuery($all_statement[$count],$data);
            if($result['status']){
                $ex_status_count ++;
            } else{
                $ex_status_count;
            }
        }
        if($ex_status_count == count($all_statement)){
            $pdo->commit();
            $execution_status = 1;
        } else{
            $pdo->rollBack();
            $execution_status = 1;
        }
        return $execution_status;
    }
    public static function updateFilesStatus($file_name){
        $data = [
            'status' => 1,
            'file_name' => $file_name
        ];
        $update_query = (new UpdateQueryBuilder())
        ->update('db_migration')
        ->set('status')
        ->where('file_name = :file_name');
        $result = DBCore::executeQuery($update_query,$data);
        return $result['status'];
    }
}


?>