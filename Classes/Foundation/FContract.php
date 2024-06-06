<?php 

class FContract
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

    public function exist(int $reservID):bool 
    {
        $q='SELECT * FROM contract WHERE reservationId=:reservID';
        $connection= FConnection::getInstance();
        $db=$connection->getConnection();
        $db->beginTransaction();
        $stm=$db->prepare($q);
        $stm->bindParam(':reservID',$reservID,PDO::PARAM_INT);
        $stm->execute();
        $db->commit();
        $result=$stm->rowCount();

        if ($result >0)
        {
            return true;
        }
        return false;

    }
}