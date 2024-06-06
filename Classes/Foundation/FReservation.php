<?php 

require_once('FConnection.php');
require_once('../Entity/EReservation.php');
require_once('FContract.php');
require_once('../Tools/TError.php');

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
    public function store(EReservation $reserv):bool
    {
        $db=FConnection::getInstance()->getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
        try
        {
            $db->exec('LOCK TABLES reservation WRITE');
            $q='INSERT INTO reservation (fromDate,toDate,made,statusAccept,accommodationId,idStudent)';
            $q.=' VALUES (:from,:to,:made,:status,:accId,:stId)';
            $db->beginTransaction();
            $stm=$db->prepare($q);
            $stm->bindValue(':from',$reserv->getFromDate()->format('Y-m-d H:i:s'),PDO::PARAM_STR);
            $stm->bindValue(':to',$reserv->getToDate()->format('Y-m-d H:i:s'),PDO::PARAM_STR);
            $stm->bindValue(':made',$reserv->getMade()->format('Y-m-d H:i:s'),PDO::PARAM_STR);
            $stm->bindValue(':status',$reserv->getStatusAccept(),PDO::PARAM_BOOL);
            $stm->bindValue(':accId',$reserv->getAccomodationId(),PDO::PARAM_INT);
            $stm->bindValue(':stId',$reserv->getIdStudent(),PDO::PARAM_INT);
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

    public function update(EReservation $reserv):bool
    {
        if($this->exist($reserv->getID()))
        {
           if(FContract::getInstance()->exist($reserv->getID()))
           {
                $result=TError::getInstance()->modificationReservationHendler();
                return $result;
           }
           else
           {
                $curStat=$this->getCourrentStatus($reserv);
                if($curStat===true)
                {
                    $result=TError::getInstance()->modificationAfterAccept();
                    return $result;
                }
                
                $db=FConnection::getInstance()->getConnection();
                try
                {
                    $db->exec('LOCK TABLES reservation WRITE');
                    $q='UPDATE reservation SET fromDate=:from,toDate=:to WHERE id=:id';
                    $db->beginTransaction();
                    $stm=$db->prepare($q);
                    $stm->bindValue(':id',$reserv->getID(),PDO::PARAM_INT);
                    $stm->bindValue(':from',$reserv->getFromDate()->format('Y-m-d H:i:s'),PDO::PARAM_STR);
                    $stm->bindValue(':to',$reserv->getToDate()->format('Y-m-d H:i:s'),PDO::PARAM_STR);
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
        else
        {
            return false;
        }
    }

    private function getCourrentStatus(EReservation $res):?bool
    {
         $db=FConnection::getInstance()->getConnection();
        try
        {  
            $q='SELECT statusAccept FROM reservation WHERE id=:id';
            $db->exec('LOCK TABLES reservation READ');
            $db->beginTransaction();
            $stm=$db->prepare($q);
            $stm->bindValue(':id',$res->getID(),PDO::PARAM_INT);
            $stm->execute();
            $db->commit();
            $row=$stm->fetch(PDO::FETCH_ASSOC);
            return $row['statusAccept'];
        }
        catch(PDOException $e)
        {
            $db->rollBack();
            return null;
        }
    }

    public function updateOwner(EReservation $reserv):bool
    {
        if($this->exist($reserv->getID()))
        {
           if(FContract::getInstance()->exist($reserv->getID()))
           {
                $result=TError::getInstance()->modificationReservationHendler();
                return $result;
           }
           else
           {
                $db=FConnection::getInstance()->getConnection();
                try
                {
                    $db->exec('LOCK TABLES reservation WRITE');
                    $q='UPDATE reservation SET statusAccept=:status WHERE id=:id';
                    $db->beginTransaction();
                    $stm=$db->prepare($q);
                    $stm->bindValue(':id',$reserv->getID(),PDO::PARAM_INT);
                    $stm->bindValue(':status',$reserv->getStatusAccept(),PDO::PARAM_BOOL);
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
        else
        {
            return false;
        }
    }

    public function Delete(int $id):bool
    {
        if($this->exist($id))
        {
           if(FContract::getInstance()->exist($id))
           {
                $result=TError::getInstance()->deleteReservationHendler();
                return $result;
           }
           else
           {
                $db=FConnection::getInstance()->getConnection();
                try
                {
                    $db->exec('LOCK TABLES reservation WRITE');
                    $q='DELETE FROM reservation WHERE id=:id';
                    $db->beginTransaction();
                    $stm=$db->prepare($q);
                    $stm->bindParam(':id',$id,PDO::PARAM_INT);
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
        else
        {
            return true;
        }
    }



     // getWaitingReservations(int $idProprietario), chiama il metodo relativo alla ricerca delle reservation in attesa di conferma relative a un proprietario, @return array<Reservation>
    //getAcceptedReservations(int $idProprietario), chiama il metodo relativo alla ricerca delle reservation accettate e in attesa di pagamento relative a un proprietario, @return array<Reservation>
}