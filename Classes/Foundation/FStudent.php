<?php 

require_once('FPhoto.php');
require_once('../Tools/TError.php');
require_once('FConnection.php');
require_once('../Entity/EStudent.php');
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
    public function load(int $id):EStudent | bool
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
                $student = new EStudent($row['id'],$row['username'],$row['password'],$row['name'],$row['surname'],$photoID,$row['universityMail'],$row['courseDuration'],$row['immatricolationYear'],$BIRTH,$row['sex'],$row['smoker'],$row['animals']);
            }
            else
            {
                $photo=FPhoto::getInstance()->load($photoID);
                $student = new EStudent($row['id'],$row['username'],$row['password'],$row['name'],$row['surname'],$photo,$row['universityMail'],$row['courseDuration'],$row['immatricolationYear'],$BIRTH,$row['sex'],$row['smoker'],$row['animals']);
            }
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
        if($this->exist($student->getID()))
        {
            #already stored in database
            return false;
        }
        else
        {
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
                if(is_null($student->getPicture()))
                {
                    $stm->bindValue(':picture',$student->getPicture(),PDO::PARAM_NULL);
                }
                else
                {
                    $photo=$student->getPicture();
                    $q2='INSERT INTO photo (photo,relativeTo,idAccomodation,idReview)';
                    $q2.=" VALUES ('$photo',:relativeTo,:idAccomodation,:idReview)";
                    $stm2=$db->prepare($q2);
                    $stm2->bindValue(':relativeTo','other',PDO::PARAM_STR);
                    $stm2->bindValue(':idAccomodation',null,PDO::PARAM_NULL);
                    $stm2->bindValue('idReview',null,PDO::PARAM_NULL);
                    $stm2->execute();
                    $photoID=$db->lastInsertId();
                    $stm->bindParam(':picture',$photoID,PDO::PARAM_INT);
                }
                $stm->bindValue(':universityMail',$student->getUniversityMail(),PDO::PARAM_STR);
                $stm->bindValue(':courseDuration',$student->getCourseDuration(),PDO::PARAM_INT);
                $stm->bindValue(':immatricolationYear',$student->getImmatricolationYear(),PDO::PARAM_INT);
                $stm->bindValue(':birthDate',$student->getBirthDate()->format('Y-m-d H:i:s'),PDO::PARAM_STR);
                $stm->bindValue(':sex',$student->getSex(),PDO::PARAM_STR);
                $stm->bindValue(':smoker',$student->getSmoker(),PDO::PARAM_BOOL);
                $stm->bindValue(':animals',$student->getAnimals(),PDO::PARAM_BOOL);
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
                } else 
                {
                    echo "An unexpected error occurred: " . $e->getMessage() . "\n";
                }
                return false;
            }
        }
    }
    public function update(EStudent $student):bool
    {
        $db=FConnection::getInstance()->getConnection();
        if($this->exist($student->getID()))
        {
            try
            {
                $db->exec('LOCK TABLES student WRITE');
                $db->beginTransaction();
                $q='UPDATE student SET username= :username, password= :password, name= :name, surname= :surname, picture= :picture, universityMail= :universityMail, courseDuration= :courseDuration, immatricolationYear= :immatricolationYear, birthDate= :birthDate, sex= :sex, smoker= :smoker, animals= :animals WHERE id=:id';
                $stm=$db->prepare($q);
                $stm->bindValue(':id',$student->getID(),PDO::PARAM_INT);
                $stm->bindValue(':username',$student->getUsername(),PDO::PARAM_STR);
                $stm->bindValue(':password',$student->getPassword(),PDO::PARAM_STR);
                $stm->bindValue(':name',$student->getName(),PDO::PARAM_STR);
                $stm->bindValue(':surname',$student->getSurname(),PDO::PARAM_STR);
                if(is_null($student->getPicture()))
                {
                    $stm->bindValue(':picture',null,PDO::PARAM_NULL);
                }
                else
                {
                    $photo=$student->getPicture();
                    $q2="UPDATE photo SET photo='$photo', relativeTo=:relativeTo, idAccomodation=:idAccomodation, idReview=:idReview WHERE id=:id";
                    $stm2=$db->prepare($q2);
                    $stm2->bindValue(':relativeTo','other',PDO::PARAM_STR);
                    $stm2->bindValue(':idAccomodation',null,PDO::PARAM_NULL);
                    $stm2->bindValue(':idReview',null,PDO::PARAM_NULL);
                    $stm2->execute();

                }
                $stm->bindValue(':id',$student->getID(),PDO::PARAM_INT);

                $stm->bindValue(':universityMail',$student->getUniversityMail(),PDO::PARAM_STR);
                $stm->bindValue(':courseDuration',$student->getCourseDuration(),PDO::PARAM_INT);
                $stm->bindValue(':immatricolationYear',$student->getImmatricolationYear(),PDO::PARAM_INT);
                $stm->bindValue(':birthDate',$student->getBirthDate()->format('Y-m-d H:i:s'),PDO::PARAM_STR);
                $stm->bindValue(':sex',$student->getSex(),PDO::PARAM_STR);
                $stm->bindValue(':smoker',$student->getSmoker(),PDO::PARAM_BOOL);
                $stm->bindValue(':animals',$student->getAnimals(),PDO::PARAM_BOOL);
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
    private function VerifyCourrentPhoto(EPhoto $photo):bool
    {
        
    }

}
