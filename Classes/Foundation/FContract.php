<?php 
namespace Classes\Foundation;
require __DIR__.'../../../vendor/autoload.php';
use Classes\Foundation\FReservation;
use Classes\Entity\EContract;
use DateTime;
use PDO;
use PDOException;

/**
 * FContract
 * @author Matteo Maloni <matteo.maloni@student.univaq.it>
 * @package Foundation
 */
class FContract
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
     * @return void
     */
    public function __construct(){}
    
    /**
     * Method getInstance
     *
     * @return FContract
     */
    public static function getInstance():FContract
    {
        if(is_null(self::$instance))
        {
            self::$instance= new FContract();
        }
        return self::$instance;
    }
    
    /**
     * Method exist
     *
     * @param int $reservID [Reservation id]
     *
     * @return bool
     */
    public function exist(int $reservID):bool 
    {
        $PM=FPersistentManager::getInstance();
        $PM->updateDataBase();
        $q='SELECT * FROM contract WHERE idReservation=:reservID';
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
    
    /**
     * Method store
     *
     * @param EContract $con [contract]
     *
     * @return bool
     */
    public function store(EContract $con):bool
    {   
        print $con->getID();
        if(FReservation::getInstance()->exist($con->getID())===false)  //if dose not exist the reservation
        {
            return false;  //return store and not store the contract
        }
        $db=FConnection::getInstance()->getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
        FPersistentManager::getInstance()->updateDataBase();
        try
        {
            $db->exec('LOCK TABLES contract WRITE');
            $q='INSERT INTO contract (idReservation,status,paymentDate,cardNumber)';
            $q.=' VALUES (:RID,:ST,:PD,:CN)';
            $db->beginTransaction();
            $stm=$db->prepare($q);
            $stm->bindValue(':RID',$con->getID(),PDO::PARAM_INT);
            $stm->bindValue(':ST',$con->getStatus()->value,PDO::PARAM_STR);
            $stm->bindValue(':PD',$con->getPaymentDate()->format('Y-m-d H:i:s'),PDO::PARAM_STR);
            $stm->bindValue(':CN',$con->getCardNumber(),PDO::PARAM_STR);
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
     * Method load
     *
     * @param int $id [reservation/contract id]
     *
     * @return Econtract
     */
    public function load(int $id):Econtract |bool
    {
        $db=FConnection::getInstance()->getConnection();
        if($this->exist($id))
        {
            FPersistentManager::getInstance()->updateDataBase();
            try
            {
                $q='SELECT * FROM contract WHERE idReservation=:id';
                $db->exec('LOCK TABLES contract READ');
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
            $reservationAssociated=FReservation::getInstance()->load($id);
            $row=$stm->fetch(PDO::FETCH_ASSOC);
            $date= new DateTime($row['paymentDate']);
            $result= new EContract($row['status'],$row['cardNumber'],$reservationAssociated, $date);
            return $result;
        }
        else
        {
            return false;
        }
    }
    public function getContractsByStudent(int $id, ?int $idAccommodation=null, ?string $kind=null):array|bool
    {
        $db=FConnection::getInstance()->getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
        FPersistentManager::getInstance()->updateDataBase();
        $contracts=[];
        try
        {
            $db->exec('LOCK TABLES contract READ');
            $q='SELECT * FROM contract WHERE idReservation IN (SELECT id FROM reservation WHERE idStudent=:id';
            if(!is_null($idAccommodation))
            {
                $q.=' AND idAccommodation=:idAccommodation) AND status!="future"';
            } else {
                $q.=')';
            }
            if (!is_null($kind))
            {
                $q.=' AND status=:kind';
            }
            $db->beginTransaction();
            $stm=$db->prepare($q);
            $stm->bindValue(':id',$id,PDO::PARAM_INT);
            if (!is_null($idAccommodation))
            {
            $stm->bindValue(':idAccommodation',$idAccommodation, PDO::PARAM_INT);
            }
            $stm->execute();
            $db->commit();
            $db->exec('UNLOCK TABLES');
        }
        catch(PDOException $e)
        {
            $db->rollBack();
            return false;
        }
        $row=$stm->fetchAll(PDO::FETCH_ASSOC);
        foreach ($row as $r)
        {
            $reservationAssociated=FReservation::getInstance()->load($r['idReservation']);
            $date= new DateTime($r['paymentDate']);
            $contract= new EContract($r['status'],$r['cardNumber'],$reservationAssociated, $date);
            $contracts[]=$contract;
        }
        return $contracts;
    }
}