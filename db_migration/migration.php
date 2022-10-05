<?php
require_once("../config.php");
require_once(DB_MIGRATION."Migration.class.php");
// How to prevent from browser and post man type of tool
$items = glob("*.txt", GLOB_NOSORT);

MigrationClass::insertFiles($items);

$all_new_files = MigrationClass::selectNewFiles();

foreach($all_new_files as $file){
    $file_name = $file['file_name'];
    $sql_statement  = file_get_contents(DB_MIGRATION.$file_name);
    $execution_status = MigrationClass::executeSql($sql_statement);
    if($execution_status){
        MigrationClass::updateFilesStatus($file_name);
    } else{
        exit();
    }
}
?>