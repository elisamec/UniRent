<?php 

namespace Classes\Foundation;

use Classes\Entity\ECreditCard;
use PDO;
use PDOException;

require __DIR__.'/../../vendor/autoload.php';
/**
 * FCreditCard
 * @author Matteo Maloni (UniRent) <matteo.maloni@student.univaq.it>
 * @package Foundation
 */
class FCreditCard
{
    private static $instance=null;
    
    /**
     * __construct
     *
     * @return self
     */
    public function __construct(){}
    
    /**
     * getInstance
     *
     * @return FCreditCard
     */
    public static function getInstance():FCreditCard
    {
        if(is_null(self::$instance))
        {
            self::$instance= new FCreditCard();
        }
        return self::$instance;
    }
    
    /**
     * exist
     *
     * @param  int $number
     * @return bool
     */
    public function exist(string $number):bool 
    {
        $q='SELECT * FROM creditcard WHERE number=:number';
        $connection= FConnection::getInstance();
        $db=$connection->getConnection();
        $db->beginTransaction();
        $stm=$db->prepare($q);
        $stm->bindParam(':number',$number,PDO::PARAM_STR);
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
     * @param  int $number
     * @return ECreditCard
     */
    public function load(string $number): ECreditCard 
    {
        $db=FConnection::getInstance()->getConnection();
        
        try
        {
            $db->exec('LOCK TABLES creditcard READ');
            $db->beginTransaction();
            $q='SELECT * FROM creditcard WHERE number=:number';    
            $stm=$db->prepare($q);
            $stm->bindParam(':number',$number,PDO::PARAM_STR);
            $stm->execute();
            $db->commit();
            $db->exec('UNLOCK TABLES');
        }

        catch (PDOException $e)
        {
            $db->rollBack();
        }
        $row=$stm->fetch(PDO::FETCH_ASSOC);
        $result=new ECreditCard($row['number'],$row['name'],$row['surname'],$row['expiry'],$row['cvv'],$row['idStudent'],$row['main'],$row['title']);
        return $result;
    }
  
  /**
   * store
   *
   * @param  ECreditCard $CreditCard
   * @return bool
   */
  public function store(ECreditCard $CreditCard):bool 
  {
    $db=FConnection::getInstance()->getConnection();
    
    try
    { 
        $db->exec('LOCK TABLES creditcard WRITE');
        $db->beginTransaction();
        $q='INSERT INTO creditcard (number, name , surname, expiry, cvv, idStudent, main, title)';
        $q=$q.' VALUES (:number, :name, :surname, :expiry, :cvv, :idStudent, :main, :title)';
        $stm=$db->prepare($q);
        $stm->bindValue(':number',$CreditCard->getNumber(),PDO::PARAM_STR);
        $stm->bindValue(':name',$CreditCard->getName(),PDO::PARAM_STR);
        $stm->bindValue(':surname',$CreditCard->getSurname(),PDO::PARAM_STR);
        $stm->bindValue(':expiry',$CreditCard->getExpiry(),PDO::PARAM_STR);
        $stm->bindValue(':cvv',$CreditCard->getCVV(),PDO::PARAM_INT);
        $stm->bindValue(':idStudent',$CreditCard->getStudentID(),PDO::PARAM_INT);
        $stm->bindValue(':main',$CreditCard->getMain(),PDO::PARAM_BOOL);
        $stm->bindValue(':title',$CreditCard->getTitle(),PDO::PARAM_STR);
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
     * @param  ECreditCard $CreditCard
     * @return bool
     */
    public function update(ECreditCard $CreditCard):bool 
    {
        $db=FConnection::getInstance()->getConnection(); 
        if($this->exist($CreditCard->getNumber())) 
        {    
            try
            {
                $db->exec('LOCK TABLES creditcard WRITE');
                $db->beginTransaction();
                $q='UPDATE creditcard SET name = :name, surname = :surname, expiry = :expiry, cvv = :cvv, idStudent = :idStudent, main = :main, title = :title  WHERE number=:number';
                $stm=$db->prepare($q);
                $stm->bindValue(':name',$CreditCard->getName(),PDO::PARAM_STR);
                $stm->bindValue(':surname',$CreditCard->getSurname(),PDO::PARAM_STR);
                $stm->bindValue(':expiry',$CreditCard->getExpiry(),PDO::PARAM_STR);
                $stm->bindValue(':cvv',$CreditCard->getCVV(),PDO::PARAM_INT);
                $stm->bindValue(':idStudent',$CreditCard->getStudentID(),PDO::PARAM_INT);
                $stm->bindValue(':number',$CreditCard->getNumber(),PDO::PARAM_STR);
                $stm->bindValue(':main',$CreditCard->getMain(),PDO::PARAM_BOOL);
                $stm->bindValue(':title',$CreditCard->getTitle(),PDO::PARAM_STR);
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
     * delete
     *
     * @param  int $number
     * @return bool
     */
    public function delete(string $number): bool 
    {
        $db=FConnection::getInstance()->getConnection();
       # $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);  Serve per il debug!
        try
        {  
            $db->exec('LOCK TABLES creditcard WRITE');
            $db->beginTransaction();
            $q='DELETE FROM creditcard WHERE number= :number';
            $stm=$db->prepare($q);
            $stm->bindValue(':number',$number, PDO::PARAM_STR);
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
     * Method isMain
     *
     * this method is used to verify if the card is the main card for the student with given ID
     * @param int $studentID [student's ID]
     * @param int $number [credit card's number to verify]
     *
     * @return bool
     */
    public function isMain(int $studentID, string $number):bool
    {
        $db=FConnection::getInstance()->getConnection();
        try
        {
            $db->exec('LOCK TABLES creditcard WRITE');
            $q='SELECT number FROM creditcard WHERE idStudent = :id AND main = true';
            $db->beginTransaction();
            $stm=$db->prepare($q);
            $stm->bindParam(':id',$studentID,PDO::PARAM_INT);
            $stm->execute();
            $db->commit();
            $db->exec('UNLOCK TABLES');
        }
        catch(PDOException $e)
        {
            $db->rollBack();
        }
        $row=$stm->fetch(PDO::FETCH_ASSOC);
        if($row['number']===$number)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Method loadStudentCards
     *
     * this method retun all the student's credit cards
     * @param $idStudent $idStudent [student's ID]
     *
     * @return array
     */
    public function loadStudentCards($idStudent):array
    {
        $result=array();
        $db=FConnection::getInstance()->getConnection();
        try
        {
            $q='SELECT * FROM creditcard WHERE idStudent = :id';
            $db->exec('LOCK TABLES creditcard READ');
            $db->beginTransaction();
            $stm=$db->prepare($q);
            $stm->bindParam(':id',$idStudent,PDO::PARAM_INT);
            $stm->execute();
            $db->commit();
            $db->exec('UNLOCK TABLES');
        }
        catch(PDOException $e)
        {
            $db->rollBack();
            return $result;
        }

        $cards=$stm->fetchAll(PDO::FETCH_ASSOC);
        foreach($cards as $card)
        {
            $cc= new ECreditCard($card['number'],$card['name'],$card['surname'],$card['expiry'],$card['cvv'],$card['idStudent'],$card['main'],$card['title']);
            $result[]=$cc;
        }
        return $result;

    }
    
    /**
     * Method getMainCard
     *
     * this method return the main card of a student with given id
     * @param string $studentId [student' ID]
     *
     * @return ?ECreditCard
     */
    public function getMainCard(string $studentId):?ECreditCard
    {
        $db=FConnection::getInstance()->getConnection();
        try
        {
            $q='SELECT * FROM creditcard WHERE idStudent = :id AND main = true';
            $db->exec('LOCK TABLES creditcard READ');
            $db->beginTransaction();
            $stm=$db->prepare($q);
            $stm->bindParam(':id',$studentId);
            $stm->execute();
            $db->commit();
            $db->exec('UNLOCK TABLES');
        }
        catch(PDOException $e)
        {
            $db->rollBack();
            return null;
        }

        $row=$stm->fetch(PDO::FETCH_ASSOC);
        $c= new ECreditCard($row['number'],$row['name'],$row['surname'],$row['expiry'],(int)$row['cvv'],(int)$row['idStudent'],true,$row['title']);
        return $c;
    }


}