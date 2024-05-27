<?php 

require_once ('FConnection.php');
require_once ('../Entity/ECreditCard.php');
/**
 * FCreditCard
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
    public function exist(int $number):bool 
    {
        $q='SELECT * FROM creditcard WHERE number=:number';
        $connection= FConnection::getInstance();
        $db=$connection->getConnection();
        $db->beginTransaction();
        $stm=$db->prepare($q);
        $stm->bindParam(':number',$number,PDO::PARAM_INT);
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
    public function load(int $number): ECreditCard 
    {
        $db=FConnection::getInstance()->getConnection();
        
        try
        {
            $db->exec('LOCK TABLES creditcard READ');
            $db->beginTransaction();
            $q='SELECT * FROM creditcard WHERE number=:number';    
            $stm=$db->prepare($q);
            $stm->bindParam(':number',$number,PDO::PARAM_INT);
            $stm->execute();
            $db->commit();
            $db->exec('UNLOCK TABLES');
        }

        catch (PDOException $e)
        {
            $db->rollBack();
        }
        $row=$stm->fetch(PDO::FETCH_ASSOC);
        $result=new ECreditCard($row['number'],$row['name'],$row['surname'],$row['expiry'],$row['cvv'],$row['studentId']);
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
    $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
    
    try
    { 
        $db->exec('LOCK TABLES creditcard WRITE');
        $db->beginTransaction();
        $q='INSERT INTO creditcard (number, name , surname, expiry, cvv, studentId)';
        $q=$q.' VALUES (:number, :name, :surname, :expiry, :cvv, :studentId)';
        $stm=$db->prepare($q);
        $stm->bindValue(':number',$CreditCard->getNumber(),PDO::PARAM_INT);
        $stm->bindValue(':name',$CreditCard->getName(),PDO::PARAM_STR);
        $stm->bindValue(':surname',$CreditCard->getSurname(),PDO::PARAM_STR);
        $stm->bindValue(':expiry',$CreditCard->getExpiry(),PDO::PARAM_STR);
        $stm->bindValue(':cvv',$CreditCard->getCVV(),PDO::PARAM_INT);
        $stm->bindValue(':studentId',$CreditCard->getStudentID(),PDO::PARAM_INT);
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
                $q='UPDATE creditcard SET name = :name, surname = :surname, expiry = :expiry, cvv = :cvv, studentId = :studentId  WHERE number=:number';
                $stm=$db->prepare($q);
                $stm->bindValue(':name',$CreditCard->getName(),PDO::PARAM_STR);
                $stm->bindValue(':surname',$CreditCard->getSurname(),PDO::PARAM_STR);
                $stm->bindValue(':expiry',$CreditCard->getExpiry(),PDO::PARAM_STR);
                $stm->bindValue(':cvv',$CreditCard->getCVV(),PDO::PARAM_INT);
                $stm->bindValue(':studentId',$CreditCard->getStudentID(),PDO::PARAM_INT);
                $stm->bindValue(':number',$CreditCard->getNumber(),PDO::PARAM_INT);
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
    public function delete(int $number): bool 
    {
        $db=FConnection::getInstance()->getConnection();
       # $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);  Serve per il debug!
        try
        {  
            $db->exec('LOCK TABLES creditcard WRITE');
            $db->beginTransaction();
            $q='DELETE FROM creditcard WHERE number= :number';
            $stm=$db->prepare($q);
            $stm->bindValue(':number',$number, PDO::PARAM_INT);
            print ' BindValue eseguita !';
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