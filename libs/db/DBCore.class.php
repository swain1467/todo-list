<?php
require_once("db_connection.php");

class DBCore {

	public static function executeQuery($sqlString, $data) {
		$pdo = pdo_connect();
    	$pdoStmt = $pdo->prepare($sqlString);
    	$pdoStatus = $pdoStmt->execute($data);
		$pdo = null;
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