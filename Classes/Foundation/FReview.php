<?php
require_once ('FConnection.php');
require_once ('../Entity/EReview.php');
require_once('../utility/TType.php');
require_once('FPhoto.php');
/**
 * This class provide to make query to EOwner class
 * @author Elisabetta Mecozzi ('UniRent')
 * @package Foundation
 */
class FReview {
    /**static attribute that contains the instance of the class */
    private static $instance=null;
    /**
     * __construct
     *
     * @return self
     */
    private function __construct()
    {}
    /**
     * getInstance
     *
     * @return FReview
     */
    public static function getInstance()
    {
        if(is_null(self::$instance))
        {
            self::$instance = new FReview();
        }
        return self::$instance;
    }
    /**
     * exist
     *
     * @param  int $id
     * @return bool
     */
    public function exist(int $id):bool 
    {
        $q='SELECT * FROM review WHERE id=:id';
        $connection= FConnection::getInstance();
        $db=$connection->getConnection();
        $db->beginTransaction();
        $stm=$db->prepare($q);
        $stm->bindParam(':id',$id,PDO::PARAM_INT);
        $stm->execute();
        $db->commit();
        $result=$stm->rowCount();

        if ($result >0)
        {
            return true;
        }
        return false;

    }
    /**
     * load
     *
     * @param  int $id
     * @param TType $recipientType
     * @return EReview
     */
    public function load(int $id, TType $recipientType): EReview 
    {
        $rowRev = FReview::loadReview($id);
        [$authType, $author, $recipient] = FReview::loadSpecificReview($id, $recipientType);
        $date=DateTime::createFromFormat('Y-m-d H:i:s',$rowRev['creationDate']);
        $photo= FReview::loadPhotos($id);
        $result=new EReview($rowRev['id'],$rowRev['title'],$rowRev['valutation'],$rowRev['description'], $photo, $recipientType,$date, $authType, $author, $recipient);
        return $result;
    }
    /**
     * loadReview
     *
     * @param  int $id
     * @return mixed
     */
    private function loadReview(int $id):mixed {
        $db=FConnection::getInstance()->getConnection();
        
        try
        {
            $db->exec('LOCK TABLES review READ');
            $db->beginTransaction();
            $q='SELECT * FROM review WHERE id=:id';    
            $stm=$db->prepare($q);
            $stm->bindParam(':id',$id,PDO::PARAM_INT);
            $stm->execute();
            $db->commit();
            $db->exec('UNLOCK TABLES');
        }

        catch (PDOException $e)
        {
            $db->rollBack();
        }
        $row=$stm->fetch(PDO::FETCH_ASSOC);
        return $row;
    }
    /**
     * loadSpecificReview
     *
     * @param  int $id
     * @param TType $recipientType
     * @return array
     */
    private function loadSpecificReview(int $id, TType $recipientType):array
    {
        $db=FConnection::getInstance()->getConnection();

        try
        {
            $db->exec('LOCK TABLES '.$recipientType->value.'review READ');
            $db->beginTransaction();
            $q='SELECT * FROM '.$recipientType->value.'review WHERE idReview=:id';    
            $stm=$db->prepare($q);
            $stm->bindParam(':id',$id,PDO::PARAM_INT);
            $stm->execute();
            $db->commit();
            $db->exec('UNLOCK TABLES');
        }

        catch (PDOException $e)
        {
            $db->rollBack();
        }
        $row=$stm->fetch(PDO::FETCH_ASSOC);
        if ($recipientType===TType::STUDENT) {
            $authType = TType::tryFrom($row['authorType']);
            $recipient=$row['idStudent'];
            if ($authType===TType::STUDENT) {
                $author = $row['authorStudent'];
            }
            else {
                $author = $row['authorOwner'];
            }
        }
        else {
            $authType = TType::STUDENT;
            $author = $row['idAuthor'];
            if ($recipientType === TType::OWNER) {
                $recipient = $row['idOwner'];
            }
            else {
                $recipient = $row['idAccommodation'];
            }
        }
        
        return [$authType, $author, $recipient];
    }
  
    /**
     * store
     *
     * @param  EReview $Review
     * @return bool
     */
    public function store(EReview $Review):bool 
    {
        
        $storeRev = FReview::storeReview($Review);
        if ($storeRev===false){
            return false;
        }
        $photos=$Review->getPhotos();
        if ($photos!==null) {
            foreach ($photos as $photo) {
                $storePictures = FPhoto::getInstance()->store($photo);
            }
            if ($storePictures===false){
                return false;
            }
        }
        $storeSpec = FReview::storeSpecificReview($Review);
        if ($storeSpec===false){
            return false;
        }
        return true;
    }
    /**
     * storeReview
     *
     * @param  EReview $Review
     * @return bool
     */
    private function storeReview(EReview $Review):bool 
    {
        $db=FConnection::getInstance()->getConnection();
        //$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
        try
        { 
            $db->exec('LOCK TABLES review WRITE');
            $db->beginTransaction();
            $q='INSERT INTO review (title , valutation, description, type)';
            $q=$q.' VALUES (:title, :valutation, :description, :type)';
            $stm=$db->prepare($q);
            $stm->bindValue(':title',$Review->getTitle(),PDO::PARAM_STR);
            $stm->bindValue(':valutation',$Review->getValutation(),PDO::PARAM_INT);
            $description = $Review->getDescription();
            if ($description!==null) {
                $stm->bindValue(':description',$description,PDO::PARAM_STR);
            }
            else {
                $stm->bindValue(':description', $description, PDO::PARAM_NULL);
            }
            $stm->bindValue(':type',$Review->getRecipientType()->value,PDO::PARAM_STR);
            $stm->execute();
            $id=$db->lastInsertId();
            $db->commit();
            $db->exec('UNLOCK TABLES');
            $Review->setId($id);
            return true;
        }      
        catch(PDOException $e)
        {
            $db->rollBack();
            return false;
        }
    }
    /**
     * storeSpecificReview
     *
     * @param  EReview $Review
     * @return bool
     */
    private function storeSpecificReview(EReview $Review): bool {
        $db = FConnection::getInstance()->getConnection();
        //$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
        try
        {
            $recipientType = $Review->getRecipientType()->value;
            $table=$recipientType.'review';
            $firstCol='id'. ucfirst($recipientType);
            if ($recipientType==='student') {
                $thirdCol = 'authorType, authorStudent, authorOwner';
                $thirdVal = ':authType, :student, :owner';
                $authType=$Review->getAuthorType()->value;
                if ($authType==='student') {
                    $other='owner';
                }
                else {
                    $other = 'student';
                }
            }
            else {
                $thirdCol = 'idAuthor';
                $thirdVal=':student';
                $authType='student';
            }
            $db->exec('LOCK TABLES '.$table.' WRITE');
            $db->beginTransaction();
            $q = 'INSERT INTO '.$table.' ('.$firstCol.', idReview, '.$thirdCol.')';
            $q = $q.' VALUES (:idRec, :idRev, '.$thirdVal.')';
            $stm=$db->prepare($q);
            $stm->bindValue(':idRec', $Review->getIDRecipient(), PDO::PARAM_INT);
            $stm->bindValue(':idRev', $Review->getId(), PDO::PARAM_INT);
            if ($recipientType==='student') {
                $stm->bindValue(':authType', $Review->getAuthorType()->value, PDO::PARAM_STR);
                $stm->bindValue(':'.$other, null, PDO::PARAM_NULL);
            }
            $stm->bindValue(':'.$authType, $Review->getIDAuthor(), PDO::PARAM_INT);
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
     * update
     *
     * @param  EReview $Review
     * @return bool
     */
    public function update(EReview $Review):bool 
    {
        $updateRev = FReview::updateReview($Review);
        if ($updateRev===false){
            return false;
        }
        $updatePhotos = FReview::updatePhotos($Review);
        if ($updatePhotos===false){
            return false;
        }
    return true;

    }
    /**
     * updatePhotos
     *
     * @param  EReview $Review
     * @return bool
     */
    private function updatePhotos(EReview $Review):bool {
        $photos=$Review->getPhotos();
        $photosDB = FReview::loadPhotos($Review->getId());
        if ($photos===null) {
            if (count($photosDB)!==0){
                foreach($photosDB as $d) {
                    $delete=FPhoto::getInstance()->delete($d->getId());
                    if ($delete===false) {
                        return false;
                    }
                }
            }
            return true;
        }
        $toBeAdded = [];
        $toBeDeleted = [];
        foreach ($photos as $photo) {
            foreach ($photosDB as $DB) {
                $countAdd=1;
                $countDel=1;
                if ($photo->getId()===$DB->getId()) {
                    $countAdd-=1;
                    $countDel-=1;
                }
                if ($countAdd===1) {
                $toBeAdded[]= $photo;
                }
                if ($countDel === 1) {
                $toBeDeleted[] = $DB;
                }
            }
        }
        foreach ($toBeAdded as $add) {
            $updatedPictures=FPhoto::getInstance()->store($add);
            if ($updatedPictures===false){
                return false;
            }
        }
        foreach ($toBeDeleted as $del) {
            $updatedPictures=FPhoto::getInstance()->delete($del->getId());
            if ($updatedPictures===false){
                return false;
            }
        }
        return true;
    }
    /**
     * loadPhotos
     *
     * @param  EReview $Review
     * @return array
     */

    private function loadPhotos(int $id):array {
        return FPhoto::getInstance()->loadCurrentPhotos($id);
    }
    /**
     * updateReview
     *
     * @param  EReview $Review
     * @return bool
     */
    private function updateReview(EReview $Review):bool {
        $db=FConnection::getInstance()->getConnection();      
        try
        {
            $db->exec('LOCK TABLES review WRITE');
            $db->beginTransaction();
            $q='UPDATE review SET title = :title, valutation = :valutation, description = :description WHERE id=:id';
            $stm=$db->prepare($q);
            $stm->bindValue(':id', $Review->getId(), PDO::PARAM_INT);
            $stm->bindValue(':title',$Review->getTitle(),PDO::PARAM_STR);
            $stm->bindValue(':valutation',$Review->getValutation(),PDO::PARAM_INT);
            $description = $Review->getDescription();
            if ($description!==null) {
                $stm->bindValue(':description',$description,PDO::PARAM_STR);
            }
            else {
                $stm->bindValue(':description', $description, PDO::PARAM_NULL);
            }
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
     * @param  EReview $Review
     * @return bool
     */
    public function delete(EReview $Review): bool 
    {
        $db=FConnection::getInstance()->getConnection();
        //$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
        try
        {   
            $db->exec('LOCK TABLES review WRITE');
            $db->beginTransaction();
            $q='DELETE FROM review WHERE id= :id';
            $stm=$db->prepare($q);
            $stm->bindValue(':id',$Review->getId(), PDO::PARAM_INT);
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


}