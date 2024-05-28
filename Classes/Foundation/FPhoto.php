<?php
require_once('FConnection.php');
require_once('../Entity/EPhoto.php');

class FPhoto {
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
            self::$instance = new FPhoto();
        }
        return self::$instance;
    }

    /* ELISABETTA:
     * Mi per trovare quali sono le immagini che sono state eliminate attualmente e
     * far partire il delete solo per gli oggetti per cui mi serve effettivamente.
    */ 
    public function loadCurrentPhotos(int $idReview):array {
        $result=[];
        return $result;
    }

    public function load(int $id):EPhoto
    {
        return new EPhoto();
    }
    public function store(EPhoto $photo):bool
    {
        return true;
    }
    public function delete(int $id):bool
    {
        return true;
    }
}