<?php 
namespace Classes\Foundation;

require __DIR__.'../../../vendor/autoload.php';


use Classes\Entity\EStudent;
use Classes\Foundation\FConnection;
use DateTime;
use PDO;
use PDOException;
use Classes\Tools\TError;

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
        $q='SELECT * FROM student  WHERE id=:id';
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
    public function load(int $id):EStudent |bool
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
                $student = new EStudent($row['username'],$row['password'],$row['name'],$row['surname'],$photoID,$row['universityMail'],$row['courseDuration'],$row['immatricolationYear'],$BIRTH,$row['sex'],$row['smoker'],$row['animals']);             
            }
            else
            {
                $photo=FPhoto::getInstance()->loadAvatar($photoID);
                $student = new EStudent($row['username'],$row['password'],$row['name'],$row['surname'],$photo,$row['universityMail'],$row['courseDuration'],$row['immatricolationYear'],$BIRTH,$row['sex'],$row['smoker'],$row['animals']);
            }
            $student->setID($id);
            return $student;
        }
        else
        {
            return false;
        }
    }
    public function store(EStudent $student):bool
    {
        $db=FConnection::getInstance()->getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
            try
            {
                $db->exec('LOCK TABLES student WRITE');
                $q='INSERT INTO student (username,password,name,surname,picture,universityMail,courseDuration,immatricolationYear,birthDate,sex,smoker,animals)';
                $q=$q.' VALUES (:username, :password, :name, :surname, :picture, :universityMail,:courseDuration,:immatricolationYear,:birthDate,:sex,:smoker,:animals)';
                $db->beginTransaction();
                $stm=$db->prepare($q);
                $stm->bindValue(':username',$student->getUsername(),PDO::PARAM_STR);
                $stm->bindValue(':password',$student->getPassword(),PDO::PARAM_STR);
                $stm->bindValue(':name',$student->getName(),PDO::PARAM_STR);
                $stm->bindValue(':surname',$student->getSurname(),PDO::PARAM_STR);
                if(!is_null($student->getPicture()))
                {
                    FPhoto::getInstance()->storeAvatar($student->getPicture());
                    $stm->bindValue(':picture',$student->getPicture()->getId(),PDO::PARAM_INT);
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
                $stm->execute();
                print 'sono in FStudent';
                $db->commit();
                print 'commit eseguita';
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
        $db=FConnection::getInstance()->getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
        if($this->exist($student->getID()))
        {
            try
            {
                
                $CPhoto=$student->getPicture();
                FPhoto::getInstance()->update($CPhoto);
                $db->exec('LOCK TABLES student WRITE');
                $db->beginTransaction();
                $q='UPDATE student SET username = :user, password = :pass, name = :name, surname = :surname, picture = :picture, universityMail = :email, ';
                $q.='courseDuration = :courseDuration, immatricolationYear = :immatricolationYear, birthDate = :birthDate, sex = :sex, smoker = :smoker, animals = :animals WHERE id=:id';
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
                if ($student->getPicture()!=null) 
                {
                    $stm->bindValue(':picture', $student->getPicture()->getId(), PDO::PARAM_INT);
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

}
  

