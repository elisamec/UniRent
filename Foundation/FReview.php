<?php
require_once ('FConnection.php');
require_once ('../Entity/EReview.php');
require_once('../utility/Type.php');
require_once('FPhoto.php');

class FReview {
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
            self::$instance = new FReview();
        }
        return self::$instance;
    }
    public function exist(int $id):bool 
    {
        $q='SELECT * FROM owner WHERE id=:id';
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
     
    public function load(int $id, Type $recipientType): EReview 
    {
        $rowRev = FReview::loadReview($id);

        switch ($recipientType) {
            case 'student':
                $rowSpecific = FReview::loadStudentReview($id);
                if ($rowSpecific['authorStudent']!== null) {
                    $author = $rowSpecific['authorStudent'];
                }
                else {
                    $rowSpecific['authorOwner'];
                }
                $recipient = $rowSpecific['idStudent'];
                break;
            case 'owner':
                $rowSpecific = FReview::loadOwnerReview($id);
                $rowSpecific['authorType'] = 'student';
                $author = $rowSpecific['idAuthor'];
                $recipient = $rowSpecific['idOwner'];
                break;
            
            case 'accommodation':
                $rowSpecific = FReview::loadAccomReview($id);
                $rowSpecific['authorType'] = 'student';
                $author = $rowSpecific['idAuthor'];
                $recipient = $rowSpecific['idAccommodation'];
                break;
            
        }

        $result=new EReview($rowRev['id'],$rowRev['title'],$rowRev['valutation'],$rowRev['description'],$rowRev['type'],$rowRev['creationDate'], $rowSpecific['authorType'], $author, $recipient);
        return $result;
    }
   
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
    private function loadStudentReview(int $id):mixed {
        $db=FConnection::getInstance()->getConnection();
        
        try
        {
            $db->exec('LOCK TABLES studentreview READ');
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
    private function loadOwnerReview(int $id):mixed {
        $db=FConnection::getInstance()->getConnection();
        
        try
        {
            $db->exec('LOCK TABLES ownerreview READ');
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
    private function loadAccomReview(int $id):mixed {
        $db=FConnection::getInstance()->getConnection();
        
        try
        {
            $db->exec('LOCK TABLES accommodationreview READ');
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
    
    $type = $Review->getRecipientType();
    switch ($type) {
        case 'student':
            $storeSpec = FReview::storeStudentRev($Review);
            if ($storeSpec===false){
                return false;
            }
            break;
        case 'owner':
            $storeSpec = FReview::storeOwnerRev($Review);
            if ($storeSpec===false){
                return false;
            }
            break;
        case 'accommodation':
            $storeSpec = FReview::storeAccommRev($Review);
            if ($storeSpec===false){
                return false;
            }
            break;
    }
    return true;
  }
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
        echo 'Query ready';
        $stm=$db->prepare($q);
        echo 'Query prepared';
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
        echo 'All binded';
        $stm->execute();
        echo 'Executed';
        $db->commit();
        echo 'Committed';
        $db->exec('UNLOCK TABLES');
        return true;
    }      
    catch(PDOException $e)
    {
        $db->rollBack();
        return false;
    }
  }
  private function storeStudentRev(EReview $Review):bool 
  {
    $db=FConnection::getInstance()->getConnection();
    $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
    $authorType=$Review->getAuthorType();
    $author=$Review->getIDAuthor();
    if ($authorType === 'student') {
        $notnull = ':stud';
        $null= ':own';
    }
    else {
        $null = ':stud';
        $notnull= ':own';
    }
    try
    { 
        $db->exec('LOCK TABLES studentreview WRITE');
        $db->beginTransaction();
        $q='INSERT INTO studentreview (idStudent, idReview , authorType, authorStudent, authorOwner)';
        $q=$q.' VALUES (:idStud, :idRev, :type, :stud, :own)';
        $stm=$db->prepare($q);
        $stm->bindValue(':idStud',$Review->getIDRecipient(),PDO::PARAM_INT);
        $stm->bindValue(':idRev',$Review->getId(),PDO::PARAM_INT);
        $stm->bindValue(':type',$authorType,PDO::PARAM_STR);
        $stm->bindValue($null,null,PDO::PARAM_NULL);
        $stm->bindValue($notnull,$author,PDO::PARAM_INT);
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
  private function storeOwnerRev(EReview $Review):bool 
  {
    $db=FConnection::getInstance()->getConnection();
    $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
    try
    { 
        $db->exec('LOCK TABLES ownerreview WRITE');
        $db->beginTransaction();
        $q='INSERT INTO ownerreview (idOwner, idReview , idAuthor)';
        $q=$q.' VALUES (:own, :rev, :auth)';
        $stm=$db->prepare($q);
        $stm->bindValue(':own',$Review->getIDRecipient(),PDO::PARAM_INT);
        $stm->bindValue(':rev',$Review->getId(),PDO::PARAM_INT);
        $stm->bindValue(':auth',$Review->getIDAuthor(),PDO::PARAM_INT);
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
  private function storeAccommRev(EReview $Review):bool 
  {
    $db=FConnection::getInstance()->getConnection();
    $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
    try
    { 
        $db->exec('LOCK TABLES accommodationreview WRITE');
        $db->beginTransaction();
        $q='INSERT INTO review (idReview, idAccommodation , idAuthor)';
        $q=$q.' VALUES (:rev, :accom, :auth)';
        $stm=$db->prepare($q);
        $stm->bindValue(':rev',$Review->getId(),PDO::PARAM_INT);
        $stm->bindValue(':accom',$Review->getIDRecipient(),PDO::PARAM_INT);
        $stm->bindValue(':auth',$Review->getIDAuthor(),PDO::PARAM_INT);
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
    private function updatePhotos(EReview $Review) {
        $photos=$Review->getPhotos();
        $photosDB = FReview::loadPhotos($Review);
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
    }

    private function loadPhotos(EReview $Review):array {
        $id=$Review->getId();
        return FPhoto::getInstance()->loadCurrentPhotos($id);
    }
    private function updateReview(EReview $Review):bool {
        $db=FConnection::getInstance()->getConnection();      
        try
        {
            $db->exec('LOCK TABLES review WRITE');
            $db->beginTransaction();
            $q='UPDATE review SET title = :title, valutation = :valutation, description = :description WHERE id=:id';
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
    
    
    public function delete(EReview $Review): bool 
    {
        $db=FConnection::getInstance()->getConnection();
       # $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);  Serve per il debug!
        try
        {   
            $photos = $Review->getPhotos();
            if ($photos!==null){
                foreach ($photos as $photo) {
                    $deleted= FPhoto::getInstance()->delete($photo->getId());
                    if ($deleted===false) {
                        return false;
                    }
                }
            }
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