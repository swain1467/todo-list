<?php
require_once("../config.php");
require_once(DB_MIGRATION."Migration.class.php");
// How to prevent from browser and post man type of tool
ini_set('register_argc_argv', 0);  

if (!isset($argc) || is_null($argc))
{ 
    echo '<h1 style="color:red; font-weight:bold; text-align:center;">Sorry! Access denied</h1>';
} else {
    $ex_status = MigrationClass::createMigrationTable();
    if($ex_status){
        $items = glob("*.sql", GLOB_NOSORT);

        MigrationClass::insertFiles($items);
        
        $all_new_files = MigrationClass::selectNewFiles();
        
        foreach($all_new_files as $file){
            $file_name = $file['file_name'];
            $sql_statement  = file_get_contents(DB_MIGRATION.$file_name);
            // MigrationClass::dbTransaction();
            $execution_status = MigrationClass::executeSql($sql_statement);
            if($execution_status){
                MigrationClass::updateFilesStatus($file_name);
                // MigrationClass::dbCommit();
            } else{
                echo"Oops! Something went wrong\n";
                // MigrationClass::dbRollBack();
                exit();
            }
        }
        echo"Database migration succesfully completed\n";
    } else{
        echo"Something went wrong please run the all sql statement of every file inside the db_migration folder\n";
    }
}
?>