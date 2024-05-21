<?php 

require 'FConnection.php';
require '../Entity/ECreditCard.php';

class FCreditCard
{
    private static $instance=null;

    public function __construct(){}

    public static function getInstance():FCreditCard
    {
        if(is_null(self::$instance))
        {
            self::$instance= new FCreditCard();
        }
        return self::$instance;
    }

    public function exist(int $number):bool 
    {
        $q='SELECT * FROM owner WHERE number=:number';
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

    public function load(int $number): ECreditCard 
    {
        try
        {
            $q='SELECT * FROM creditcard WHERE number=:nubmer';
            $db=FConnection::getInstance()->getConnection();
            $db->beginTransaction();
            $db->exec('LOCK TABLES creditcard READ');
            $stm=$db->prepare($q);
            $stm->bindParam(':number',$number,PDO::PARAM_INT);
            $stm->execute();
            $db->exec('UNLOCK TABLES');
            $db->commit();
        }
        catch (PDOException $e)
        {
            $db->rollBack();
        }
        $row=$stm->fetch(PDO::FETCH_ASSOC);
        $result=new ECreditCard($row['number'],$row['name'],$row['surname'],$row['expiry'],$row['cvv'],$row['studentId']);
        return $result;
    }

#    public function store(EOwner $owner):bool {}

 #   public function update(EOwner $owner):bool {}

 #   public function delete(int $id): bool {}


}