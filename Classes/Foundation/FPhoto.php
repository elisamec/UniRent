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


    public function loadAccommodation(int $idAccommodation):array
    {
        $db=FConnection::getInstance()->getConnection();
        
        try{
            $db->exec('LOCK TABLES visit READ');
            $db->beginTransaction();
            $q="SELECT * FROM photo WHERE idAccommodation = $idAccommodation";    
            $stm=$db->prepare($q);
            $stm->execute();
            $db->commit();
            $db->exec('UNLOCK TABLES');

        }catch (PDOException $e){
            $db->rollBack();
        }

        $rows = $stm->fetchAll(PDO::FETCH_ASSOC);

        // Iterate over each row and create an EAccommodation object
        foreach ($rows as $row) {
            $photo = new EPhoto(
                $row['id'],
                $row['photo'],
                $row['relativeTo'],
                $row['idAccommodation'],
                $row['idReview']
            );
            $photos[] = $photo;
        }

        print_r($photos);

        return $photos;
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