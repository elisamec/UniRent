<?php

namespace Configuration;
use PDO;
use PDOException;

use Configuration\Config;

/**
 * class for checking if the db exist and if not create it
 */
class Installation{
    /**
     * check if the db exist and if not create it
     * @return bool
     */
    public static function install(){
        try{
            $conn =  new PDO("mysql:host=".Config::DB_HOST."; charset=utf8;", Config::DB_USER, Config::DB_PASS);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '" . Config::DB_NAME . "'");
            if($stmt->rowCount() == 0){
                // Database does not exist, create it
                
                $sql = "CREATE DATABASE " . Config::DB_NAME;
                $conn->exec($sql);
                $conn->exec("USE " . Config::DB_NAME);
                $sql = file_get_contents(Config::SQL_FILE_PATH);
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