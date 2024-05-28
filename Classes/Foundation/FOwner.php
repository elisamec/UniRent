<?php 

require_once ('FConnection.php');
require_once ('../Entity/EOwner.php');
require_once('FPhoto.php');
require_once('../Tools/TError.php');
/**
 * This class provide to make query to EOwner class
 * @author Matteo Maloni ('UniRent')
 * @package Foundation
 */

 class FOwner
 {
    /**static attribute that contains the instance of the class */
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
            self::$instance = new FOwner();
        }
        return self::$instance;
    }

    public function exist(int $id):bool 
    {
        $q='SELECT * FROM owner WHERE id=:idOwner';
        $connection= FConnection::getInstance();
        $db=$connection->getConnection();
        $db->beginTransaction();
        $stm=$db->prepare($q);
        $stm->bindParam(':idOwner',$id,PDO::PARAM_INT);
        $stm->execute();
        $db->commit();
        $result=$stm->rowCount();

        if ($result >0)
        {
            return true;
        }
        return false;

    }
    
    public function load(int $id): EOwner {
        $db=FConnection::getInstance()->getConnection();
        try
        {
            $db->exec('LOCK TABLES owner READ');
            $db->beginTransaction();
            $q='SELECT * FROM owner WHERE id=:id';
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
        $row=$stm->fetch(PDO::FETCH_ASSOC);
        $photo=FPhoto::getInstance()->load($row['picture']);
        $result=new EOwner($row['id'],$row['username'], $row['password'], $row['name'], $row['surname'], $photo, $row['email'], $row['phonenumber'], $row['iban']);
        return $result;
    }

    public function store(EOwner $owner):bool {
    $db=FConnection::getInstance()->getConnection();
    try
    {
        $storePicture = FPhoto::getInstance()->store($owner->getPhoto());
        if ($storePicture===false){
            return false;
        }
        $db->exec('LOCK TABLES owner WRITE');
        $db->beginTransaction();
        $q='INSERT INTO owner (username, password, name, surname, picture, email, phonenumber, iban)';
        $q=$q.'VALUES (:user, :pass, :name, :surname, :picture, :email, :phone, :iban)';
        $stm = $db->prepare($q);
        $stm->bindValue(':user', $owner->getUsername(), PDO::PARAM_STR);
        $stm->bindValue(':pass', $owner->getPassword(), PDO::PARAM_STR);
        $stm->bindValue(':name', $owner->getName(), PDO::PARAM_STR);
        $stm->bindValue(':surname', $owner->getSurname(), PDO::PARAM_STR);
        $stm->bindValue(':picture', $owner->getPhoto()->getId(), PDO::PARAM_INT);
        $stm->bindValue(':email', $owner->getMail(), PDO::PARAM_STR);
        $stm->bindValue(':phone', $owner->getPhoneNumber(), PDO::PARAM_STR);
        $stm->bindValue(':iban', $owner->getIBAN(), PDO::PARAM_STR);
        $stm->execute();
        $id=$db->lastInsertId();
        $db->commit();
        $db->exec('UNLOCK TABLES');
        $owner->setId($id);
        return true;
        
    }
    catch (PDOException $e) {
        $db->rollBack();
        $errorType = TError::getInstance()->handleDuplicateError($e);
        if ($errorType) {
            echo "Error: " . $errorType . "\n"; //quando faremo view leghiamolo a view
        } else {
            echo "An unexpected error occurred: " . $e->getMessage() . "\n";
        }
        return false;
    }
}


    /*
        try {
            $stmt = $pdo->prepare("INSERT INTO users (id, email) VALUES (?, ?)");
            $stmt->execute([2, 'example@example.com']);
        } catch (PDOException $e) {
            // MySQL specific error code for duplicate entry is 1062
            if ($e->getCode() == 23000) {
                echo "Duplicate entry detected: " . $e->getMessage();
            } else {
                // Re-throw the exception if it's not a duplicate entry error
                throw $e;
            }
        }
    }
        */

 #   public function update(EOwner $owner):bool {}

 #   public function delete(int $id): bool {}

 }