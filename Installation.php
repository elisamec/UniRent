<?php

namespace UniRent;
use PDO;
use PDOException;
//use const UniRent\{DB_HOST, DB_NAME, DB_PASS, DB_USER, SQL_FILE_PATH};

require __DIR__ . '/config.php';

/**
 * class for checking if the db exist and if not create it
 */
class Installation{
    public static function install(){
        try{
            $conn =  new PDO("mysql:host=".DB_HOST."; charset=utf8;", DB_USER, DB_PASS);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '" . DB_NAME . "'");
            if($stmt->rowCount() == 0){
                // Database does not exist, create it
                
                $sql = "CREATE DATABASE " . DB_NAME;
                $conn->exec($sql);
                $conn->exec("USE " . DB_NAME);
                $sql = file_get_contents(SQL_FILE_PATH);
                $conn->exec($sql);
            }
            $conn = null;
            return true;
        }catch(PDOException $e){
            echo "Connection failed: " . $e->getMessage();
            return false;
        }
    }
}