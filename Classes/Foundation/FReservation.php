<?php 
namespace Classes\Foundation;
require __DIR__.'../../../vendor/autoload.php';
require_once('FOwner.php');
use Classes\Tools\TError;
use Classes\Foundation\FContract;
use Classes\Foundation\FConnection;
use Classes\Entity\EReservation;
use DateTime;
use PDO;
use PDOException;

/**
 * FReservation
 * @author Matteo Maloni (UniRent)
 * @package Foundation
 */
class FReservation
{    
    /**
     * instance
     *
     * @var static $instance
     */
    private static $instance=null;
    
    /**
     * Method __construct
     *
     * @return self
     */
    private function __construct() {}

    
    /**
     * Method getInstance
     *
     * @return FReservation
     */
    public static function getInstance():FReservation
    {
        if(is_null(self::$instance))
        {
            self::$instance= new FReservation();
        }
        return self::$instance;
    }
    
    /**
     * Method exist
     *
     * @param int $id [Reservation Id]
     *
     * @return bool
     */
    public function exist(int $id):bool
    {
        $q='SELECT * FROM reservation WHERE id=:id';
        $connection= FConnection::getInstance();
        $db=$connection->getConnection();
        FPersistentManager::getInstance()->updateDataBase();
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
    
    /**
     * Method load
     *
     * @param int $id [Reservation id]
     *
     * @return EReservation
     */
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
            $result=new EReservation($FROM,$TO,$row['idAccommodation'],$row['idStudent'], new DateTime($row['made']));
            $result->setID($row['id']);
            $result->setStatus($row['statusAccept']);
            return $result;
        }
        else
        {
            return false;
        }
        
    }    
    /**
     * Method store
     *
     * @param EReservation $reserv [object Ereservation]
     *
     * @return bool
     */
    public function store(EReservation $reserv):bool
    {
        $db=FConnection::getInstance()->getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
        FPersistentManager::getInstance()->updateDataBase();
        try
        {
            $db->exec('LOCK TABLES reservation WRITE');
            $q='INSERT INTO reservation (fromDate,toDate,made,statusAccept,idAccommodation,idStudent)';
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
    
    /**
     * Method update
     *
     * @param EReservation $reserv [object EReservation]
     *
     * @return bool
     */
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
                FPersistentManager::getInstance()->updateDataBase();
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
    
    /**
     * Method getCourrentStatus
     *
     * @param EReservation $res [object EReservation]
     *
     * @return bool
     */
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
    
    /**
     * Method updateOwner
     *
     * @param EReservation $reserv [object EREservation]
     *
     * @return bool
     */
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
    
    /**
     * Method delete
     *
     * @param int $id [Reservation id]
     *
     * @return bool
     */
    public function delete(int $id):bool
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
    
    /**
     * Method getWaitingReservations
     *
     * This method return a EReservation array of waiting reservations
     * @param int $id [Reservation id]
     *
     * @return array
     */
    public function getWaitingReservations(int $id):?array
    {
        if(FOwner::getInstance()->exist($id))
        {
            $db=FConnection::getInstance()->getConnection();
            $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
            FPersistentManager::getInstance()->updateDataBase();
            try
            {
                $q='SELECT *
                    FROM reservation r INNER JOIN accommodation a ON a.id=r.idAccommodation
                    INNER JOIN owner o ON o.id=a.idOwner
                    WHERE o.id=:id AND r.statusAccept=false';
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
                $result=TError::getInstance()->errorGettingReservations();
                return $result;
            }
            $rows = $stm->fetchAll(PDO::FETCH_ASSOC);
            $result=array();

            foreach ($rows as $row) 
            {
                $FROM= new DateTime($row['fromDate']);
                $TO= new DateTime($row['toDate']);
                $r=new EReservation($FROM,$TO,$row['idAccommodation'],$row['idStudent'], new DateTime($row['made']));
                $r->setID($row['id']);
                $r->setStatus($row['statusAccept']);
                $result[$row['idAccommodation']][]=$r;
            }
            return $result;

        }
        else
        {
            return null;
        }
    }

    public function loadReservationsByStudent(int $id, string $kind) {
        $db=FConnection::getInstance()->getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
        FPersistentManager::getInstance()->updateDataBase();
        try
        {
            $q='SELECT * 
                FROM reservation
                WHERE idStudent = :id 
                AND DATEDIFF(fromDate, NOW()) > 0 
                AND id NOT IN (
                    SELECT idReservation 
                    FROM contract
                )
                ORDER BY fromDate ASC';
                
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
            $result=TError::getInstance()->errorGettingReservations();
            return $result;
        }
        $rows = $stm->fetchAll(PDO::FETCH_ASSOC);
        $resultAccepted=array();
        $resultWaiting=array();

        foreach ($rows as $row) 
        {
            $FROM= new DateTime($row['fromDate']);
            $TO= new DateTime($row['toDate']);
            $r=new EReservation($FROM,$TO,$row['idAccommodation'],$row['idStudent'], new DateTime($row['made']));
            $r->setID($row['id']);
            $r->setStatus($row['statusAccept']);
            if($r->getStatusAccept()===true)
            {
                $resultAccepted[]=$r;
            }
            else
            {
                $resultWaiting[]=$r;
            }
        }
        if ($kind==='accepted')
        {
            return $resultAccepted;
        }
        elseif ($kind==='pending')
        {
            return $resultWaiting;
        }
        else
        {
            return null;
        }
    }
}