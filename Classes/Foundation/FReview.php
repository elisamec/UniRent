<?php
namespace Classes\Foundation;
require __DIR__.'../../../vendor/autoload.php';
use Classes\Entity\EReview;
use Classes\Tools\TType;
use Classes\Foundation\FConnection;
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
    public function load(int $id): EReview 
    {
        $rowRev = FReview::loadReview($id);
        [$authType, $author, $recipient] = FReview::loadSpecificReview($id, TType::tryFrom($rowRev['type']));
        $date=DateTime::createFromFormat('Y-m-d H:i:s',$rowRev['creationDate']);
        $result=new EReview($rowRev['id'],$rowRev['title'],$rowRev['valutation'],$rowRev['description'], TType::tryFrom($rowRev['type']),$date, $authType, $author, $recipient, $rowRev['reported'], $rowRev['banned']);
        return $result;
    }
    //DA SISTEMARE
    
    public function loadByRecipient(int $idRec, TType $recipientType): array
    {
        
        $reviews= FReview::loadSpecificReviewByRec($idRec, $recipientType);
        $result=[];
        if ($recipientType===TType::STUDENT) {
            foreach ($reviews as $rev) {
                $idReview = $rev['idReview'];
                $rowRev = FReview::loadReview($idReview);
                $date=DateTime::createFromFormat('Y-m-d H:i:s',$rowRev['creationDate']);
                if ($rev['authorStudent']!==null) {
                    $author = $rev['authorStudent'];
                    $authType = TType::STUDENT;
                }
                else {
                    $author = $rev['authorOwner'];
                    $authType = TType::OWNER;
                }
                $result[]=new EReview($idReview,$rowRev['title'],$rowRev['valutation'],$rowRev['description'], $recipientType, $date, $authType, $author, $idRec, $rowRev['reported'], $rowRev['banned']);
            }
        }
        else {
            foreach ($reviews as $rev) {
                $idReview = $rev['idReview'];
                $rowRev = FReview::loadReview($idReview);
                $date=DateTime::createFromFormat('Y-m-d H:i:s',$rowRev['creationDate']);
                $authType = TType::STUDENT;
                $author = $rev['idAuthor'];
                $result[]=new EReview($idReview,$rowRev['title'],$rowRev['valutation'],$rowRev['description'], $recipientType, $date, $authType, $author, $idRec, $rowRev['reported'], $rowRev['banned']);
            }
        }
        return $result;
    }
    private function loadSpecificReviewByRec(int $idRec, TType $recipientType):array
    {
        $db=FConnection::getInstance()->getConnection();

        try
        {
            $db->exec('LOCK TABLES '.$recipientType->value.'review READ');
            $db->beginTransaction();
            $q='SELECT * FROM '.$recipientType->value.'review WHERE id'.ucfirst($recipientType->value).'=:idRec';    
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

        $row=$stm->fetchall(PDO::FETCH_ASSOC);
        return $row;
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
            $recipient=$row['idStudent'];
            if ($row['authorStudent']!==null) {
                $author = $row['authorStudent'];
                $authType = TType::STUDENT;
            }
            else {
                $author = $row['authorOwner'];
                $authType = TType::OWNER;
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
            $q='INSERT INTO review (title , valutation, description, type, reported, banned)';
            $q=$q.' VALUES (:title, :valutation, :description, :type, :reported, :banned)';
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
            $stm->bindValue(':reported',$Review->isReported(),PDO::PARAM_BOOL);
            $stm->bindValue(':banned',$Review->isBanned(),PDO::PARAM_BOOL);
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
                $thirdCol = 'authorStudent, authorOwner';
                $thirdVal = ':student, :owner';
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
     * updateReview
     *
     * @param  EReview $Review
     * @return bool
     */
    public function update(EReview $Review):bool {
        $db=FConnection::getInstance()->getConnection();      
        try
        {
            $db->exec('LOCK TABLES review WRITE');
            $db->beginTransaction();
            $q='UPDATE review SET title = :title, valutation = :valutation, description = :description, reported= :reported, banned= :banned WHERE id=:id';
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
            $stm->bindValue(':reported',$Review->isReported(),PDO::PARAM_BOOL);
            $stm->bindValue(':banned',$Review->isBanned(),PDO::PARAM_BOOL);
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
    public function delete(int $id): bool 
    {
        $db=FConnection::getInstance()->getConnection();
        //$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
        try
        {   
            $db->exec('LOCK TABLES review WRITE');
            $db->beginTransaction();
            $q='DELETE FROM review WHERE id= :id';
            $stm=$db->prepare($q);
            $stm->bindValue(':id',$id, PDO::PARAM_INT);
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

    public function loadByAuthor(int $idAuth, TType $authorType): array
    {   
        $result=[];
        $studentReviews= FReview::loadStudentReviewByAuth($idAuth, $authorType);
        if ($authorType===TType::STUDENT) {
            $ownerReviews= FReview::loadOwnerReviewByAuth($idAuth);
            $accommodationReviews= FReview::loadAccommodationReviewByAuth($idAuth);
            foreach ($ownerReviews as $oRev) {
                $idReview = $oRev['idReview'];
                $rowRev = FReview::loadReview($idReview);
                $date=DateTime::createFromFormat('Y-m-d H:i:s',$rowRev['creationDate']);
                $result[]=new EReview($idReview,$rowRev['title'],$rowRev['valutation'],$rowRev['description'], TType::OWNER, $date, $authorType, $idAuth, $oRev['idOwner'], $rowRev['reported'], $rowRev['banned']);
            }
            foreach ($accommodationReviews as $aRev) {
                $idReview = $aRev['idReview'];
                $rowRev = FReview::loadReview($idReview);
                $date=DateTime::createFromFormat('Y-m-d H:i:s',$rowRev['creationDate']);
                $result[]=new EReview($idReview,$rowRev['title'],$rowRev['valutation'],$rowRev['description'], TType::ACCOMMODATION, $date, $authorType, $idAuth, $aRev['idAccommodation'], $rowRev['reported'], $rowRev['banned']);
            }
        }
        foreach ($studentReviews as $sRev) {
            $idReview = $sRev['idReview'];
            $rowRev = FReview::loadReview($idReview);
            $date=DateTime::createFromFormat('Y-m-d H:i:s',$rowRev['creationDate']);
            $result[]=new EReview($idReview,$rowRev['title'],$rowRev['valutation'],$rowRev['description'], TType::STUDENT, $date, $authorType, $idAuth, $sRev['idStudent'], $rowRev['reported'], $rowRev['banned']);
        }
        return $result;
    }
    private static function loadStudentReviewByAuth(int $idAuth, TType $authorType):array
    {
        $db=FConnection::getInstance()->getConnection();

        try
        {
            $db->exec('LOCK TABLES studentreview READ');
            $db->beginTransaction();
            $q='SELECT * FROM studentreview WHERE author'.ucfirst($authorType->value).'=:idAuth';    
            $stm=$db->prepare($q);
            $stm->bindParam(':idAuth',$idAuth,PDO::PARAM_INT);
            $stm->execute();
            $db->commit();
            $db->exec('UNLOCK TABLES');
        }

        catch (PDOException $e)
        {
            $db->rollBack();
        }

        $row=$stm->fetchall(PDO::FETCH_ASSOC);
        return $row;
    }
    private static function loadOwnerReviewByAuth(int $idAuth):array
    {
        $db=FConnection::getInstance()->getConnection();

        try
        {
            $db->exec('LOCK TABLES ownerreview READ');
            $db->beginTransaction();
            $q='SELECT * FROM ownerreview WHERE idAuthor=:idAuth';    
            $stm=$db->prepare($q);
            $stm->bindParam(':idAuth',$idAuth,PDO::PARAM_INT);
            $stm->execute();
            $db->commit();
            $db->exec('UNLOCK TABLES');
        }

        catch (PDOException $e)
        {
            $db->rollBack();
        }

        $row=$stm->fetchall(PDO::FETCH_ASSOC);
        return $row;
    }
    private static function loadAccommodationReviewByAuth(int $idAuth):array
    {
        $db=FConnection::getInstance()->getConnection();

        try
        {
            $db->exec('LOCK TABLES accommodationreview READ');
            $db->beginTransaction();
            $q='SELECT * FROM accommodationreview WHERE idAuthor=:idAuth';    
            $stm=$db->prepare($q);
            $stm->bindParam(':idAuth',$idAuth,PDO::PARAM_INT);
            $stm->execute();
            $db->commit();
            $db->exec('UNLOCK TABLES');
        }

        catch (PDOException $e)
        {
            $db->rollBack();
        }

        $row=$stm->fetchall(PDO::FETCH_ASSOC);
        return $row;
    } 
    
    /**
     * Method remainingReviewStudentToStudent
     *
     * this method get the number of remaining review that student 1 can make about a student 2
     * @param int $id1 [student 1]
     * @param int $id2 [student 2]
     *
     * @param int  
     */
    public function remainingReviewStudentToStudent(int $id1, int $id2):int
    {
        $db=FConnection::getInstance()->getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
        try
        {
            $q="SELECT 
                (SELECT COUNT(*) 
                 FROM student s1 INNER JOIN reservation r ON r.idStudent=s1.id
                 INNER JOIN contract c ON c.idReservation=r.id
                 INNER JOIN reservation r2 ON r.idAccommodation=r2.idAccommodation
                 INNER JOIN accommodation a ON a.id=r.idAccommodation
                 INNER JOIN student s2 ON s2.id=r2.idStudent
                 WHERE s1.id != s2.id
                 AND YEAR(r.fromDate)=YEAR(r2.fromDate)
                 AND c.`status`!='future'
                 AND s1.id=:id1
                 AND s2.id=:id2)
                 -
                (SELECT COUNT(*)
                 FROM student s INNER JOIN studentreview sr ON s.id=sr.authorStudent
                 WHERE s.id=:id1
                 AND sr.idStudent=:id2) AS RR

                LOCK IN SHARE MODE";

            $db->beginTransaction();
            $stm=$db->prepare($q);
            $stm->bindParam(':id1',$id1,PDO::PARAM_INT);
            $stm->bindParam('id2',$id2,PDO::PARAM_INT);
            $stm->execute();
            $db->commit();
        }
        catch(PDOException $e)
        {
            $db->rollBack();
            return 0;
        }
        $result=$stm->fetch(PDO::FETCH_ASSOC);
        return (int)$result['RR'];
    }
    
    /**
     * Method remainingReviewStudentToOwner
     *
     * this method get the number of remaining review that student 1 can make about an owner
     * @param int $id1 [student , the one who makes the reviews]
     * @param int $id2 [owner]
     *
     * @return int
     */
    public function remainingReviewStudentToOwner(int $id1, int $id2):int
    {
        $db=FConnection::getInstance()->getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
        try
        {
            $q="SELECT 
                (SELECT COUNT(*) 
                 FROM student s INNER JOIN reservation r ON r.idStudent=s.id
                 INNER JOIN contract c ON c.idReservation=r.id
                 INNER JOIN accommodation a ON a.id=r.idAccommodation
                 WHERE a.idOwner=:id2
                 AND c.`status`!='future'
                 AND s.id=:id1)
                 -
                (SELECT COUNT(*)
                 FROM student s INNER JOIN ownerreview orr ON s.id=orr.idAuthor
                 WHERE s.id=:id1
                 AND orr.idOwner=:id2) AS RR

                LOCK IN SHARE MODE";

            $db->beginTransaction();
            $stm=$db->prepare($q);
            $stm->bindParam(':id1',$id1,PDO::PARAM_INT);
            $stm->bindParam('id2',$id2,PDO::PARAM_INT);
            $stm->execute();
            $db->commit();
        }
        catch(PDOException $e)
        {
            $db->rollBack();
            return 0;
        }
        $result=$stm->fetch(PDO::FETCH_ASSOC);
        return (int)$result['RR'];
    }
    /*
    public function getReportedReviews():array
    {
        //da fare
    }
        */


}