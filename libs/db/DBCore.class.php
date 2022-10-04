<?php
require_once(ROOT_DIR."utility/file_include.php");
require_once(LIB_DIR."db/db_connection.php");
class DBCore {
	public static function executeQuery($sqlString, $data) {
		$pdo = pdo_connect();
    	$pdoStmt = $pdo->prepare($sqlString);
    	$pdoStatus = $pdoStmt->execute($data);
		$pdo = null;
		if(DEFAULT_ERROR_LOG){
			DefaultErrorLog::defaultLog('DB_Log_Query->'.$sqlString);
			DefaultErrorLog::defaultLog($data);
		}
		if(DB_LOG){
			ErrorLog::log('DB_Log_Query->'.$sqlString);
			ErrorLog::log($data);
		}
		if(!$pdoStatus){
			ErrorLog::log($pdoStmt->errorInfo());
			DefaultErrorLog::defaultLog($pdoStmt->errorInfo());
		}
    	return [
    		'status' => $pdoStatus ? 1: 0,
    		'error' => $pdoStatus ? '': $pdoStmt->errorInfo(),
    		'result_obj' => $pdoStmt
    	];
	}

	public static function getAllRows($dbCoreRes) {
		if($dbCoreRes['status'] === 0) {
			return [];
		}
		return $dbCoreRes['result_obj']->fetchAll();
	}
};