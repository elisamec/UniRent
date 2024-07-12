<?php
namespace Classes\Foundation;
require __DIR__.'../../../vendor/autoload.php';
use Classes\Foundation\FConnection;
use Classes\Entity\EPhoto;
use PDO;
use PDOException;

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
        $q="SELECT * FROM photo WHERE id=:idPhoto";
        $db=FConnection::getInstance()->getConnection();
        $db->beginTransaction();
        $stm=$db->prepare($q);
        $stm->bindParam(':idPhoto',$idPhoto,PDO::PARAM_INT);
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
        $q="SELECT * FROM photo WHERE idAccommodation=:id";
        $db=FConnection::getInstance()->getConnection();
        $db->beginTransaction();
        $stm=$db->prepare($q);
        $stm->bindParam(':id',$idAccommodation,PDO::PARAM_INT);
        $stm->execute();
        $db->commit();
        $result=$stm->rowCount();

        if ($result >0) return true;
        return false;
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
        
        try{
            $db->exec('LOCK TABLES photo READ');
            $db->beginTransaction();
            $q="SELECT * FROM photo WHERE relativeTo = 'accommodation' AND idAccommodation = :id";    
            $stm=$db->prepare($q);
            $stm->bindParam(':id',$idAccommodation,PDO::PARAM_INT);
            $stm->execute();
            $db->commit();
            $db->exec('UNLOCK TABLES');

        }catch (PDOException $e){
            $db->rollBack();
            return [];
        }

        $photos = [];
        $rows = $stm->fetchAll(PDO::FETCH_ASSOC);

        // Iterate over each row and create an EAccommodation object
        foreach ($rows as $row) {
            $photo = new EPhoto(
                $row['id'],
                $row['photo'],
                $row['relativeTo'],
                $row['idAccommodation']
            );
            $photos[] = $photo;
        }

        return $photos;
    }

    /**
     * This method loads the photos of an owner or student
     * @param int $id
     * @return EPhoto
     */
    public function loadAvatar(int $id):?EPhoto
    {
        $db=FConnection::getInstance()->getConnection();
        $FP=FPhoto::getInstance();
        
        if($FP->exist($id)){
        
            try{
                $db->exec('LOCK TABLES photo READ');
                $db->beginTransaction();
                $q="SELECT * FROM photo WHERE id = :id AND relativeTo = 'other'";    
                $stm=$db->prepare($q);
                $stm->bindParam(':id',$id,PDO::PARAM_INT);
                $stm->execute();
                $db->commit();
                $db->exec('UNLOCK TABLES');

            }catch (PDOException $e){
                $db->rollBack();
            }

            $row = $stm->fetch(PDO::FETCH_ASSOC);


            $photo = new EPhoto($row['id'], $row['photo'], $row['relativeTo'], $row['idAccommodation']);

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

            $q='INSERT INTO photo ( photo, relativeTo, idAccommodation) ';
            $q=$q.' VALUES (:photo, :relativeTo, :idAccommodation)';

            $stm=$db->prepare($q);
            $stm->bindValue(':photo',$photo,PDO::PARAM_STR);
            $stm->bindValue(':relativeTo',$relative,PDO::PARAM_STR);
            $stm->bindValue(':idAccommodation',NULL,PDO::PARAM_INT);
            
            $stm->execute();
            $id=$db->lastInsertId();
            //$db->commit();
            //$db->exec('UNLOCK TABLES');
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
    * Store accommodation's photos
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

                $q='INSERT INTO photo (photo, relativeTo, idAccommodation) ';
                $q=$q.' VALUES (:photo, :relativeTo, :idAccommodation)';

                $stm=$db->prepare($q);
                $stm->bindValue(':photo',$photo,PDO::PARAM_STR);
                $stm->bindValue(':relativeTo',$relative,PDO::PARAM_STR);
                $id = $ph->getIdAccommodation();
                $stm->bindValue(':idAccommodation',$id,PDO::PARAM_INT);

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