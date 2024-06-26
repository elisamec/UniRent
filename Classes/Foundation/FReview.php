<?php
namespace Classes\Foundation;
require __DIR__.'../../../vendor/autoload.php';
use Classes\Entity\EReview;
use Classes\Tools\TType;
use Classes\Foundation\FConnection;
use Classes\Foundation\FPhoto;
use DateTime;
use PDO;
use PDOException;

/**
 * This class provide to make query to EOwner class
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
        $photo= FPhoto::getInstance()->loadReview($id);;
        $result=new EReview($rowRev['id'],$rowRev['title'],$rowRev['valutation'],$rowRev['description'], $photo, $recipientType,$date, $authType, $author, $recipient);
        return $result;
    }
    //DA SISTEMARE
    /*
    public function loadByRecipient(int $idRec, TType $recipientType): array
    {
        
        [$authType, $author, $idReview] = FReview::loadSpecificReviewByRec($idRec, $recipientType);
        foreach ()
        $rowRev = FReview::loadReview($idReview);
        $date=DateTime::createFromFormat('Y-m-d H:i:s',$rowRev['creationDate']);
        $photo= FPhoto::getInstance()->loadReview($idReview);
        $result=new EReview($idReview,$rowRev['title'],$rowRev['valutation'],$rowRev['description'], $photo, $recipientType,$date, $authType, $author, $idRec);
        return $result;
    }
    private function loadSpecificReviewByRec(int $idRec, TType $recipientType):array
    {
        $db=FConnection::getInstance()->getConnection();

        try
        {
            $db->exec('LOCK TABLES '.$recipientType->value.'review READ');
            $db->beginTransaction();
            $q='SELECT * FROM '.$recipientType->value.'review WHERE id'.$recipientType->value.'=:idRec';    
            $stm=$db->prepare($q);
            $stm->bindParam(':idRec',$idRec,PDO::PARAM_INT);
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
        }
        $idReview = $row['idReview'];
        return [$authType, $author, $idReview];
    }
        */
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
        $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
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
        $photosDB = FPhoto::getInstance()->loadReview($Review->getId());
        foreach ($photosDB as $DB) {
            $el=array_search($DB, $photos);
            if ($el!==false) {
                $complete=FPhoto::getInstance()->update($photos[$el]);
            } else {
                $complete=FPhoto::getInstance()->delete($DB->getId());
            }
            if ($complete===false) {
                return false;
            }
        }
        foreach ($photos as $ph) {
            $el=array_search($ph, $photosDB);
            $add=[];
            if ($el===false) {
                $add[]=$photos[$el];
            }
            $complete=FPhoto::getInstance()->store($add);
            if ($complete===false) {
                return false;
            }
        }
        
        return true;
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