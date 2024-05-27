<?php
require_once ('FConnection.php');
require_once ('../Entity/ESupportRequest.php');
require_once('../utility/Type.php');

class FSupportRequest {
    private static $instance=null;
    /**Constructor */
    private function __construct()
    {}
    /**This static method gives the istance of this singleton class
     * @return  
    */
    public static function getInstance()
    {
        if(is_null(self::$instance))
        {
            self::$instance = new FSupportRequest();
        }
        return self::$instance;
    }
}