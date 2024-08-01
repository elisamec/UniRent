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

class FStudent
{
    private static $instance=null;

    private function __construct(){}

    public static function getInstance():FStudent
    {
        if(is_null(self::$instance))
        {
            self::$instance= new FStudent();
        }
        return self::$instance;
    }
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
                print ' commit ha lanciato la exception!';
                $db->rollBack();
                $errorType = TError::getInstance()->handleDuplicateError($e);
                if ($errorType) 
                {
                    echo "Error: " . $errorType . "\n"; //quando faremo view leghiamolo a view
                } else 
                {
                    echo "An unexpected error occurred: " . $e->getMessage() . "\n";
                }
                return false;
            }
    }
    public function update(EStudent $student):bool 
    {      
        print "Sto in update<br>";
        $db=FConnection::getInstance()->getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);

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
                $errorType = TError::getInstance()->handleDuplicateError($e);
                if ($errorType) 
                {
                    echo "Error: " . $errorType . "\n"; //quando faremo view leghiamolo a view
                } 
                else 
                {
                    echo "An unexpected error occurred: " . $e->getMessage() . "\n";
                }
                return false;
            }
        } 
        else
        {
            return false;
        }
    }
    
    private function currentPhoto(int $id): ?int   #restituisce l'ID della foto del profilo dello studente corrente
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

    public function verifyEmail(string $email):bool
    {
        $q='SELECT * FROM student WHERE universityMail=:email';
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
            $db->exec('LOCK TABLES student WRITE');
            $db->beginTransaction();
            $stm=$db->prepare($q);
            $stm->bindParam(':user',$user,PDO::PARAM_STR);
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

    public function findStudentRating(int $id):int
    {
        
        $db=FConnection::getInstance()->getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
        try
        {
            $db->exec('LOCK TABLES student READ, studentreview READ, review READ');
            $q='SELECT AVG(r.valutation) AS rateS
                FROM student s INNER JOIN studentreview sr ON s.id=sr.idStudent
                INNER JOIN review r ON r.id=sr.idReview
                WHERE s.id=:id';
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
}
  

