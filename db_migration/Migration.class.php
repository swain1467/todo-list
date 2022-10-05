<?php
session_start();
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
        $result = DBCore::executeQuery($sql_statement,$data);
        return $result['status'];
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