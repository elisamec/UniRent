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

    /**
    * exist
    *
    * @param  int $idPhoto
    * @return bool
    */
    public function exist(int $idPhoto):bool 
    {
        $q="SELECT * FROM photo WHERE id=$idPhoto";
        $db=FConnection::getInstance()->getConnection();
        $db->beginTransaction();
        $stm=$db->prepare($q);
        $stm->execute();
        $db->commit();
        $result=$stm->rowCount();

        if ($result >0) return true;
        return false;
    }

    /**
    * exist photo refered to review
    *
    * @param  int $idPhoto
    * @return bool
    */
    public function existReview(int $idReview):bool 
    {
        $q="SELECT * FROM photo WHERE idReview=$idReview";
        $db=FConnection::getInstance()->getConnection();
        $db->beginTransaction();
        $stm=$db->prepare($q);
        $stm->execute();
        $db->commit();
        $result=$stm->rowCount();

        if ($result >0) return true;
        return false;
    }

    /**
    * exist photo refered to accommodation
    *
    * @param  int $idPhoto
    * @return bool
    */
    public function existAccommodation(int $idAccommodation):bool 
    {
        $q="SELECT * FROM photo WHERE idAccommodation=$idAccommodation";
        $db=FConnection::getInstance()->getConnection();
        $db->beginTransaction();
        $stm=$db->prepare($q);
        $stm->execute();
        $db->commit();
        $result=$stm->rowCount();

        if ($result >0) return true;
        return false;
    }

    /**
     * This method loads the photos of a review
     * @param int $idReview
     * @return array
     */
    public function loadReview(int $idReview):array
    {
        $db=FConnection::getInstance()->getConnection();
        $FP=FPhoto::getInstance();
        
        if(!$FP->existReview($idReview)){
            
            try{
                $db->exec('LOCK TABLES photo READ');
                $db->beginTransaction();
                $q="SELECT * FROM photo WHERE relativeTo = 'review' AND idReview = $idReview";    
                $stm=$db->prepare($q);
                $stm->execute();
                $db->commit();
                $db->exec('UNLOCK TABLES');

            }catch (PDOException $e){
                $db->rollBack();
            }

            $rows = $stm->fetchAll(PDO::FETCH_ASSOC);

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

            return $photos;
        }else{  
            return null;
        }
    }

    /**
     * This method loads the photos of an accommodation
     * @param int $idAccommodation
     * @return array
     */
    public function loadAccommodation(int $idAccommodation):array
    {
        $db=FConnection::getInstance()->getConnection();
        $FP=FPhoto::getInstance();
        
        if(!$FP->existAccommodation($idAccommodation)){
        
            try{
                $db->exec('LOCK TABLES photo READ');
                $db->beginTransaction();
                $q="SELECT * FROM photo WHERE relativeTo = 'accommodation' AND idAccommodation = $idAccommodation";    
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

            return $photos;
        }else{  
            return null;
        }
    }

    /**
     * This method loads the photos of an owner or student
     * @param int $id
     * @return EPhoto
     */
    public function loadAvatar(int $id):EPhoto
    {
        $db=FConnection::getInstance()->getConnection();
        $FP=FPhoto::getInstance();
        
        if($FP->exist($id)){
        
            try{
                $db->exec('LOCK TABLES photo READ');
                $db->beginTransaction();
                $q="SELECT * FROM photo WHERE id = $id AND relativeTo = 'other'";    
                $stm=$db->prepare($q);
                $stm->execute();
                $db->commit();
                $db->exec('UNLOCK TABLES');

            }catch (PDOException $e){
                $db->rollBack();
            }

            $row = $stm->fetch(PDO::FETCH_ASSOC);


            $photo = new EPhoto($row['id'], $row['photo'], $row['relativeTo'], $row['idAccommodation'], $row['idReview']);
            return $photo;
        }else{  
            return null;
        }
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