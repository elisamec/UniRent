<?php 
namespace Classes\Foundation;

require __DIR__.'../../../vendor/autoload.php';


use Classes\Entity\EStudent;
use Classes\Foundation\FConnection;
use DateTime;
use PDO;
use PDOException;
use Classes\Tools\TError;
use Classes\Utilities\USession;

/**
 * FStudent
 * 
 */
class FStudent
{
    private static $instance=null;
    
    /**
     * Method __construct
     *
     * @return void
     */
    private function __construct(){}
    
    /**
     * Method getInstance
     *
     * used to get the instance of the class
     * @return FStudent
     */
    public static function getInstance():FStudent
    {
        if(is_null(self::$instance))
        {
            self::$instance= new FStudent();
        }
        return self::$instance;
    }    
    /**
     * Method exist
     * method used to verify if a student exists
     * @param int $id [student's id in the data base]
     *
     * @return bool
     */
    public function exist(int $id):bool
    {
        $db=FConnection::getInstance()->getConnection();
        try
        {
            $q='SELECT * FROM student  WHERE id=:id';
            $db->exec('LOCK TABLES student READ');
            $db->beginTransaction();
            $stm=$db->prepare($q);
            $stm->bindParam(':id',$id,PDO::PARAM_INT);
            $stm->execute();
            $db->commit();
            $db->exec('UNLOCK TABLES');
        }
        catch(PDOException $e)
        {
            $db->rollBack();
            return false;
        }
        $result=$stm->rowCount();

        if ($result >0)
        {
            return true;
        }
        return false;
    }    
    /**
     * Method load
     *
     * method used to load a student from the data base
     * @param int $id [student ID]
     *
     * @return EStudent
     */
    public function load(int $id):?EStudent
    {
        $db=FConnection::getInstance()->getConnection();
        if($this->exist($id))
        {
            try
            {
                $db->exec('LOCK TABLE student READ');
                $db->beginTransaction();
                $q='SELECT * FROM student WHERE id=:id';
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
            $BIRTH= new DateTime($row['birthDate']);
            $photoID=$row['picture'];
            if(is_null($photoID))
            {
                $student = new EStudent($row['username'],$row['password'],$row['name'],$row['surname'],$photoID,$row['universityMail'],$row['courseDuration'],$row['immatricolationYear'],$BIRTH,$row['sex'],$row['smoker'],$row['animals'],$row['status']);             
            }
            else
            {
                $photo=FPhoto::getInstance()->loadAvatar($photoID);
                $student = new EStudent($row['username'],$row['password'],$row['name'],$row['surname'],$photo,$row['universityMail'],$row['courseDuration'],$row['immatricolationYear'],$BIRTH,$row['sex'],$row['smoker'],$row['animals'],$row['status']);
            }
            $student->setID($id);
            return $student;
        }
        else
        {
            return null;
        }
    }    
    /**
     * Method store
     *
     * this method is used to store a EStudent object into the data base
     * @param EStudent $student [EStudent object]
     *
     * @return bool
     */
    public function store(EStudent $student):bool
    {
        $db=FConnection::getInstance()->getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
            try
            {
                $db->exec('LOCK TABLES student WRITE');
                $q='INSERT INTO student (username,password,name,surname,picture,universityMail,courseDuration,immatricolationYear,birthDate,sex,smoker,animals, status)';
                $q=$q.' VALUES (:username, :password, :name, :surname, :picture, :universityMail,:courseDuration,:immatricolationYear,:birthDate,:sex,:smoker,:animals, :status)';
                $db->beginTransaction();
                $stm=$db->prepare($q);
                $stm->bindValue(':username',$student->getUsername(),PDO::PARAM_STR);
                $stm->bindValue(':password',$student->getPassword(),PDO::PARAM_STR);
                $stm->bindValue(':name',$student->getName(),PDO::PARAM_STR);
                $stm->bindValue(':surname',$student->getSurname(),PDO::PARAM_STR);
                if(!is_null($student->getPhoto()))
                {
                    FPhoto::getInstance()->storeAvatar($student->getPhoto());
                    $stm->bindValue(':picture',$student->getPhoto()->getId(),PDO::PARAM_INT);
                } 
                else
                {      
                    $stm->bindValue(':picture',null,PDO::PARAM_NULL);
                }
                $stm->bindValue(':universityMail',$student->getUniversityMail(),PDO::PARAM_STR);
                $stm->bindValue(':courseDuration',$student->getCourseDuration(),PDO::PARAM_INT);
                $stm->bindValue(':immatricolationYear',$student->getImmatricolationYear(),PDO::PARAM_INT);
                $stm->bindValue(':birthDate',$student->getBirthDate()->format('Y-m-d H:i:s'),PDO::PARAM_STR);
                $stm->bindValue(':sex',$student->getSex(),PDO::PARAM_STR);
                $stm->bindValue(':smoker',$student->getSmoker(),PDO::PARAM_BOOL);
                $stm->bindValue(':animals',$student->getAnimals(),PDO::PARAM_BOOL);
                $stm->bindValue(':status',$student->getStatus()->value,PDO::PARAM_STR);
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
     * Method update
     * 
     * this method is used to update a stuent into the data base
     * @param EStudent $student [EStudent object]
     *
     * @return bool
     */
    public function update(EStudent $student):bool 
    {      
        $db=FConnection::getInstance()->getConnection();
        if($this->exist($student->getID())){
            try
            {
                $CPhoto=$student->getPhoto();
                if(!is_null($CPhoto))  #se non ha inserito una foto 
                {
                    FPhoto::getInstance()->update($CPhoto);
                }
                $db->exec('LOCK TABLES student WRITE');
                $db->beginTransaction();
                $q='UPDATE student SET username = :user, password = :pass, name = :name, surname = :surname, picture = :picture, universityMail = :email, ';
                $q.='courseDuration = :courseDuration, immatricolationYear = :immatricolationYear, birthDate = :birthDate, sex = :sex, smoker = :smoker, animals = :animals, status = :status WHERE id=:id';
                $stm=$db->prepare($q);
                $stm->bindValue(':id',$student->getID());
                $stm->bindValue(':user', $student->getUsername(), PDO::PARAM_STR);
                $stm->bindValue(':pass', $student->getPassword(), PDO::PARAM_STR);
                $stm->bindValue(':name', $student->getName(), PDO::PARAM_STR);
                $stm->bindValue(':surname', $student->getSurname(), PDO::PARAM_STR);
                $stm->bindValue(':email', $student->getUniversityMail(), PDO::PARAM_STR);
                $stm->bindValue(':courseDuration', $student->getCourseDuration(), PDO::PARAM_INT);
                $stm->bindValue(':immatricolationYear', $student->getImmatricolationYear(), PDO::PARAM_INT);
                $stm->bindValue(':birthDate',$student->getBirthDate()->format('Y-m-d H:i:s'),PDO::PARAM_STR);
                $stm->bindValue(':sex',$student->getSex(),PDO::PARAM_STR);
                $stm->bindValue(':smoker',$student->getSmoker(),PDO::PARAM_BOOL);
                $stm->bindValue(':animals',$student->getAnimals(),PDO::PARAM_BOOL);
                $stm->bindValue(':status',$student->getStatus()->value,PDO::PARAM_STR);

                if ($student->getPhoto()!=null) 
                {
                    $stm->bindValue(':picture', $student->getPhoto()->getId(), PDO::PARAM_INT);
                } 
                else 
                {
                    $stm->bindValue(':picture', null, PDO::PARAM_NULL);;
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
        else
        {
            return false;
        }
    }
        
    /**
     * Method currentPhoto
     *
     * this method return the id of current photo in data base of student
     * @param int $id [student ID]
     *
     * @return int
     */
    private function currentPhoto(int $id): ?int  
    {
        $db=FConnection::getInstance()->getConnection();
        try
        {
            $db->exec('LOCK TABLES student READ');
            $db->beginTransaction();
            $q='SELECT picture FROM student WHERE id=:id';
            $stm=$db->prepare($q);
            $stm->bindparam(':id', $id, PDO::PARAM_INT);
            $stm->execute();
            $db->commit();
            $db->exec('UNLOCK TABLES');
        }
        catch (PDOException $e) 
        {
            $db->rollBack();
        }
        $photoID=$stm->fetch(PDO::FETCH_ASSOC)['picture'];
        return $photoID;
    }
    
    /**
     * Method delete
     *
     * this method is used to delete a student from the data base
     * @param EStudent $student [EStuednt object]
     *
     * @return bool
     */
    public function delete(EStudent $student): bool 
    {
        $db=FConnection::getInstance()->getConnection();
        try
        {   
            $db->exec('LOCK TABLES student WRITE');
            $db->beginTransaction();
            $q='DELETE FROM student WHERE id= :id';
            $stm=$db->prepare($q);
            $stm->bindValue(':id',$student->getID(), PDO::PARAM_INT);
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
     * Method verifyEmail
     *
     * this method verify if the email is used by someone in the UniRent data base
     * @param string $email [email to verify]
     *
     * @return bool
     */
    public function verifyEmail(string $email):bool
    {
        $q='SELECT * FROM student WHERE universityMail=:email LOCK IN SHARE MODE';
        $connection= FConnection::getInstance();
        $db=$connection->getConnection();
        $db->exec('LOCK TABLES student READ');
        $db->beginTransaction();
        $stm=$db->prepare($q);
        $stm->bindParam(':email',$email,PDO::PARAM_STR);
        $stm->execute();
        $db->commit();
        $db->exec('UNLOCK TABLES');
        $result=$stm->rowCount();
        if ($result >0)
        {
            return true;
        }
        return false;
    }
    
    /**
     * Method verifyUsername
     *
     * this method verify if the username is already in use in UniRent data base
     * @param string $username [username to verify]
     *
     * @return bool
     */
    public function verifyUsername(string $username):bool|int
    {
        $q='SELECT * FROM student WHERE username=:username';
        $connection= FConnection::getInstance();
        $db=$connection->getConnection();
        $db->exec('LOCK TABLES student READ');
        $db->beginTransaction();
        $stm=$db->prepare($q);
        $stm->bindParam(':username',$username,PDO::PARAM_STR);
        $stm->execute();
        $db->commit();
        $db->exec('UNLOCK TABLES');
        $result=$stm->rowCount();
        $row=$stm->fetch(PDO::FETCH_ASSOC);
        if ($result >0)
        {
            return $row['id'];
        }
        return false;
    }
        
    /**
     * Method getStudentByUsername
     *
     * this method return the EStudent object from db by his username, or null if the username
     * is not in the data base
     * @param $user $user [username]
     *
     * @return EStudent
     */
    public function getStudentByUsername($user):?EStudent
    {
        $db=FConnection::getInstance()->getConnection();
        try
        {
            $q='SELECT * FROM student WHERE username= :user';
            $db->exec('LOCK TABLES student READ');
            $db->beginTransaction();
            $stm=$db->prepare($q);
            $stm->bindParam(':user',$user,PDO::PARAM_STR);
            $stm->execute();
            $db->commit();
            $db->exec('UNLOCK TABLES');
        }
        catch(PDOException $e)
        {
            $db->rollBack();
            return null;
        }
        $result_array=$stm->fetch(PDO::FETCH_ASSOC);
        if($result_array>0){
            $username=$result_array['username'];
            $password=$result_array['password'];
            $name=$result_array['name'];
            $surname=$result_array['surname'];
            $email=$result_array['universityMail'];
            $courseDuration=$result_array['courseDuration'];
            $immatricolation=$result_array['immatricolationYear'];
            $sex=$result_array['sex'];
            $smoker=$result_array['smoker'];
            $animals=$result_array['animals'];
            $status=$result_array['status'];
            $birth=new DateTime($result_array['birthDate']);
        } else {
            return null;
        }
        if(is_null($result_array['picture']))
        {
            $photo=null;
        }
        else
        {
            $photo=FPhoto::getInstance()->loadAvatar($result_array['picture']);
        }
        $student= new EStudent($username,$password,$name,$surname,$photo,$email,$courseDuration,$immatricolation,$birth,$sex,$smoker,$animals,$status);
        $student->setID($result_array['id']);
        return $student;
    }
    
    /**
     * Method deleteStudentByUsername
     * 
     * This method remove from the db the student with username given
     * @param $user $user [student's username]
     *
     * @return bool
     */
    public function deleteStudentByUsername($user):bool
    {
        $db=FConnection::getInstance()->getConnection();
        try
        {
            $q='DELETE FROM student WHERE username = :user';
            $db->beginTransaction();
            $stm=$db->prepare($q);
            $stm->bindParam(':user',$user,PDO::PARAM_STR);
            $stm->execute();
            $db->commit();
            return true;
        }
        catch(PDOException $e)
        {
            $db->rollBack();
            return false;
        }
    }
    
    /**
     * Method getIdByUsername
     *
     * This method returns the id of the student with the username given
     * @param $user $user [student's username]
     *
     * @return int|bool
     */
    public function getIdByUsername($user):int|bool
    { 
        $db=FConnection::getInstance()->getConnection();
        try
        {
            $q='SELECT id FROM student WHERE username = :user';
            $db->exec('LOCK TABLES student READ');
            $db->beginTransaction();
            $stm=$db->prepare($q);
            $stm->bindParam(':user',$user,PDO::PARAM_STR);
            $stm->execute();
            $db->commit();
            $db->exec('UNLOCK TABLES');
        }
        catch(PDOException $e)
        {
            $db->rollBack();
            return false;
        }
        return $stm->fetch(PDO::FETCH_ASSOC)['id'];
    }
    
    /**
     * Method getEmailByUsername
     *
     * This method return the student's mail from the db using the given username
     * @param $user $user [student's username]
     *
     * @return string|bool
     */
    public function getEmailByUsername($user):string|bool
    {
        $db=FConnection::getInstance()->getConnection();
        try
        {
            $q='SELECT universityMail FROM student WHERE username = :user';
            $db->exec('LOCK TABLES student READ');
            $db->beginTransaction();
            $stm=$db->prepare($q);
            $stm->bindParam(':user',$user);
            $stm->execute();
            $db->commit();
            $db->exec('UNLOCK TABLES');
        }
        catch(PDOException $e)
        {
            $db->rollBack();
            return false;
        }
        $result_array=$stm->fetch(PDO::FETCH_ASSOC);
        return $result_array['universityMail'];
    }

    /**
     * Method getPhotoByUsername
     *
     * This method return the student's photo
     * @param $user $user [student's username]
     *
     * @return int|bool
     */
    public function getPhotoIdByUsername($user):int|bool|null
    {
        $db=FConnection::getInstance()->getConnection();
        try
        {
            $q='SELECT picture FROM student WHERE username = :user';
            $db->exec('LOCK TABLES student READ');
            $db->beginTransaction();
            $stm=$db->prepare($q);
            $stm->bindParam(':user',$user);
            $stm->execute();
            $db->commit();
            $db->exec('UNLOCK TABLES');
        }
        catch(PDOException $e)
        {
            $db->rollBack();
            return false;
        }

        $result_array=$stm->fetch(PDO::FETCH_ASSOC);        
        return $result_array['picture'];
    }
    
    /**
     * Method findStudentRating
     *
     * this method return the student rating
     * @param int $id [Student ID into database]
     *
     * @return int
     */
    public function findStudentRating(int $id):int
    {
        $db=FConnection::getInstance()->getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
        try
        {
            $q='SELECT AVG(r.valutation) AS rateS
                FROM student s INNER JOIN studentreview sr ON s.id=sr.idStudent
                INNER JOIN review r ON r.id=sr.idReview
                WHERE s.id=:id LOCK IN SHARE MODE';
            $db->beginTransaction();
            $stm=$db->prepare($q);
            $stm->bindParam(':id',$id,PDO::PARAM_INT);
            $stm->execute();
            $db->commit();
        }
        catch(PDOException $e)
        {
            $db->rollBack();
            return 0;
        }
        $row=$stm->fetch(PDO::FETCH_ASSOC);
        if(is_null($row['rateS']))
        {
            return 0;
        }
        else
        {
            return $row['rateS'];
        }
    }
    
    /**
     * Method getBannedStudents
     *
     * this method return an array of banned students from Data Base
     * @return array
     */
    public function getBannedStudents():array
    {
        $result=array();
        $db=FConnection::getInstance()->getConnection();
        try
        {
            $q="SELECT s.id AS ID
                FROM student s 
                WHERE s.`status`='banned'";
            $db->beginTransaction();
            $stm=$db->prepare($q);
            $stm->execute();
            $db->commit();
        }
        catch(PDOException $e)
        {
            $db->rollBack();
            return $result;
        }
        $rows=$stm->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row)
        {
            $student=$this->load($row['ID']);
            $result[]=$student;
        }
        return $result;
    }
    
    /**
     * Method getSupportReply
     * 
     * this method return the student support replies 
     *
     * @param int $id [student ID]
     *
     * @return array
     */
    public function getSupportReply(int $id):array
    {
        $db=FConnection::getInstance()->getConnection();
        try
        {
            if(!$db->inTransaction())
            {
                $db->beginTransaction();
            }
            $q="SELECT *
                FROM supportrequest s
                WHERE idOwner IS NULL 
                AND s.idStudent=:id
                AND s.supportReply IS NOT NULL
                LOCK IN SHARE MODE";
            
            $stm=$db->prepare($q);
            $stm->bindParam(':id',$id,PDO::PARAM_INT);
            $stm->execute();
            if($db->inTransaction())
            {
                $db->commit();
            }
        }
        catch(PDOException $e)
        {
            if(!$db->inTransaction())
            {
                $db->rollBack();
            }
            return array();
        }
        $rows=$stm->fetchAll(PDO::FETCH_ASSOC);
        $result=array();
        foreach($rows as $row)
        {
            $supportreply= FPersistentManager::getInstance()->load('ESupportRequest',$row['id']);
            $supportreply->setStatusRead($row['statusRead']);
            $supportreply->setSupportReply($row['supportReply']);
            $result[]=$supportreply;
        }
        return $result;
    }    
    /**
     * Method getUsernameById
     *
     * this method is used to get the student username by student's ID
     * @param int $id [student ID]
     *
     * @return string
     */
    public function getUsernameById(int $id): string | bool {
        $db=FConnection::getInstance()->getConnection();
        try
        {
            $db->exec('LOCK TABLES student READ');
            $db->beginTransaction();
            $q='SELECT username FROM student WHERE id=:id';
            $stm=$db->prepare($q);
            $stm->bindparam(':id', $id, PDO::PARAM_INT);
            $stm->execute();
            $db->commit();
            $db->exec('UNLOCK TABLES');
        }
        catch (PDOException $e) 
        {
            $db->rollBack();
            return false;
        }
        $row=$stm->fetch(PDO::FETCH_ASSOC);
        return $row['username'];
    }
    
    
    /**
     * Method getMate
     *
     * this method return the student's mate by contract ID
     * @param int $contractID [contract ID]
     * @return array
     */
    public function getMate(int $contractID):array
    {
        $result=array();
        $db=FConnection::getInstance()->getConnection();
        FPersistentManager::getInstance()->updateDataBase();
        try
        {
            $q="WITH  studenti_contratto AS (SELECT s.id AS studentID, c.idReservation AS contractID, r.idAccommodation AS accommodationID, YEAR(r.fromDate) AS year
                                             FROM student s INNER JOIN reservation r ON r.idStudent=s.id
                                             INNER JOIN contract c ON c.idReservation=r.id)

                SELECT sc2.studentID AS ID
                FROM studenti_contratto sc1 INNER JOIN studenti_contratto sc2 ON sc1.studentID!=sc2.studentID
                WHERE sc1.year=sc2.year
                AND sc1.accommodationID=sc2.accommodationID
                AND sc1.contractID=:contractID";
                $db->exec('LOCK TABLES student READ, reservation READ, contract READ');
                if(!$db->inTransaction())
                {
                    $db->beginTransaction();
                }
                $stm=$db->prepare($q);
                $stm->bindParam(':contractID',$contractID,PDO::PARAM_INT);
                $stm->execute();
                if($db->inTransaction())
                {
                    $db->commit();
                }
        }
        catch(PDOException $e)
        {
            if($db->inTransaction())
            {
                $db->rollBack();
            }
            $db->exec('UNLOCK TABLES');
            return $result;
        }
        $db->exec('UNLOCK TABLES');
        $rows=$stm->fetchAll(PDO::FETCH_ASSOC);
        foreach($rows as $row)
        {
            $student=$this->load($row['ID']);
            $result[]=$student;
        }
        return $result;
    }
}
  

