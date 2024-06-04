<?php 

require_once('FConnection.php');
require_once('../Entity/EReservation.php');

class FReservation
{
    private static $instance=null;

    private function __construct() {}


    public static function getInstance():FReservation
    {
        if(is_null(self::$instance))
        {
            self::$instance= new FReservation();
        }
        return self::$instance;
    }

    public function exist(int $id):bool
    {
        $q='SELECT * FROM reservation WHERE id=:id';
        $connection= FConnection::getInstance();
        $db=$connection->getConnection();
        try
        {
            $db->beginTransaction();
            $stm=$db->prepare($q);
            $stm->bindParam(':id',$id,PDO::PARAM_INT);
            $stm->execute();
            $db->commit();
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

    public function load (int $id):EReservation|bool
    {
        $db=FConnection::getInstance()->getConnection();
        if($this->exist($id))
        {
            try
            {
                $q='SELECT * FROM reservation WHERE id=:id';
                $db->exec('LOCK TABLES reservation READ');
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
            $row=$stm->fetch(PDO::FETCH_ASSOC);
            $FROM= new DateTime($row['fromDate']);
            $TO= new DateTime($row['toDate']);
            $result=new EReservation($FROM,$TO,$row['accommodationId'],$row['idStudent']);
            $result->setID($row['id']);
            $result->setStatus($row['statusAccept']);
            return $result;
        }
        else
        {
            return false;
        }
    }
}