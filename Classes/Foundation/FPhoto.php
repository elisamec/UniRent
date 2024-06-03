<?php
require_once('FConnection.php');
require_once('../Entity/EPhoto.php');

class FPhoto {
    private static $instance=null;

    /**Constructor */
    private function __construct(){}

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

    /**
    * storeAvatar
    * store user's or owner's avatar
    *
    * @param  EPhoto $EPhoto
    * @return bool
    */
    public function storeAvatar(EPhoto $EPhoto):bool 
    {
        $db=FConnection::getInstance()->getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
        
        try
        { 
            $db->exec('LOCK TABLES photo WRITE');
            $db->beginTransaction();

            $photo = $EPhoto->getPhoto();
            $relative = $EPhoto->getRelativeTo();

            $q='INSERT INTO photo ( photo, relativeTo, idAccommodation, idReview) ';
            $q=$q.' VALUES (:photo, :relativeTo, :idAccommodation, :idReview)';

            $stm=$db->prepare($q);
            $stm->bindValue(':photo',$photo,PDO::PARAM_STR);
            $stm->bindValue(':relativeTo',$relative,PDO::PARAM_STR);
            $stm->bindValue(':idAccommodation',NULL,PDO::PARAM_INT);
            $stm->bindValue(':idReview',NULL,PDO::PARAM_INT);
            
            $stm->execute();
            $id=$db->lastInsertId();
            $db->commit();
            $db->exec('UNLOCK TABLES');
            $EPhoto->setId($id);
            return true;
        }      
        catch(PDOException $e)
        {
            $db->rollBack();
            return false;
        }

    }

    /**
    * store
    * Store accommodation's or review's photos
    *
    * @param  array $EPhoto
    * @return bool
    */
    public function store(array $EPhoto):bool {

        $db=FConnection::getInstance()->getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
        
        try{

            foreach($EPhoto as $ph){

                $db->exec('LOCK TABLES photo WRITE');
                $db->beginTransaction();

                $photo = $ph->getPhoto();
                $relative = $ph->getRelativeTo();
                //$id = $ph->getIdAccommodation();

                $q='INSERT INTO photo (photo, relativeTo, idAccommodation, idReview) ';
                $q=$q.' VALUES (:photo, :relativeTo, :idAccommodation, :idReview)';

                $stm=$db->prepare($q);
                $stm->bindValue(':photo',$photo,PDO::PARAM_STR);
                $stm->bindValue(':relativeTo',$relative,PDO::PARAM_STR);

                if($relative == 'accommodation'){
                    $id = $ph->getIdAccommodation();
                    $stm->bindValue(':idAccommodation',$id,PDO::PARAM_INT);
                    $stm->bindValue(':idReview',NULL,PDO::PARAM_INT);
                }else{
                    $id = $ph->getIdReview();
                    $stm->bindValue(':idAccommodation',NULL,PDO::PARAM_INT);
                    $stm->bindValue(':idReview',$id,PDO::PARAM_INT);
                }

                $stm->execute();
                $id=$db->lastInsertId();
                $db->commit();
                $db->exec('UNLOCK TABLES');
                $ph->setId($id);
                
            }
            return true;
        }      
        catch(PDOException $e){

            $db->rollBack();
            return false;
        }

    }


    /**
    * update
    *
    * @param  EPhoto $EPhoto
    * @return bool
    */
    public function update(EPhoto $EPhoto):bool 
    {
        $db=FConnection::getInstance()->getConnection();
        $FP=FPhoto::getInstance();

        $photoId = $EPhoto->getId();
        
        if($FP->exist($photoId)){
            try{
                $db->exec('LOCK TABLES photo WRITE');
                $db->beginTransaction();

                $photo = $EPhoto->getPhoto();
                
                $q='UPDATE photo SET photo = :photo  WHERE id=:id';
                $stm=$db->prepare($q);
                $stm->bindValue(':photo',$photo,PDO::PARAM_STR);
                $stm->bindValue(':id',$photoId,PDO::PARAM_INT);

                $stm->execute();           
                $db->commit();
                $db->exec('UNLOCK TABLES');

                return true;
            }
            catch(PDOException $e)
            {
                $db->rollBack();
                return false;
            }
        } else return false;
        
    }

    /**
    * delete
    *
    * @param  int $idPhoto
    * @return bool
    */
    public function delete(int $idPhoto): bool 
    {
        $db=FConnection::getInstance()->getConnection();
        $FP=FPhoto::getInstance();
        
        if($FP->exist($idPhoto)){
            try
            {  
                $db->exec('LOCK TABLES photo WRITE');
                $db->beginTransaction();
                $q='DELETE FROM photo WHERE id=:id';
                $stm=$db->prepare($q);
                $stm->bindValue(':id',$idPhoto, PDO::PARAM_INT);
                $stm->execute();    
                $db->commit();
                $db->exec('UNLOCK TABLES');

                return true;
            }
            catch(PDOException $e)
            {
                $db->rollBack();
                return false;
            }

        } else return false;
        
    }
}