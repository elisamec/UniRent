<?php 
namespace Classes\Foundation;
require __DIR__.'../../../vendor/autoload.php';
use Classes\Foundation\FReservation;
use Classes\Entity\EContract;
use Classes\Tools\TStatusContract;
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
     * this method is used to verify if a contract exists
     * @param int $reservID [Reservarion id]
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
     * method to store a EContract
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
     * metod used to load a EContract object in the data base
     * @param int $id [reservation/contract id]
     *
     * @return ?EContract
     */
    public function load(int $id):?EContract
    {
        $db=FConnection::getInstance()->getConnection();
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
            return null;
        }
        $row=$stm->fetch(PDO::FETCH_ASSOC);
        if($row['status']=='finished')
        {
            $row['status']=TStatusContract::FINISHED;
        }
        elseif($row['status']=='onGoing')
        {
            $row['status']=TStatusContract::ONGOING;
        }
        else
        {
            $row['status']=TStatusContract::FUTURE;
        }
        $reservationAssociated=FReservation::getInstance()->load($id);
        $date= new DateTime($row['paymentDate']);
        $result= new EContract($row['status'],$row['cardNumber'],$reservationAssociated, $date);
        return $result;
    }    
    /**
     * Method getContractsByStudent
     * 
     * this method return an array of all Student'contracts or false
     *
     * @param int $id [explicite description]
     * @param ?int $idAccommodation [explicite description]
     * @param ?string $kind [explicite description]
     *
     * @return array|bool
     */
    public function getContractsByStudent(int $id, ?int $idAccommodation=null, ?string $kind=null):array|bool
    {  
        $db=FConnection::getInstance()->getConnection();
        FPersistentManager::getInstance()->updateDataBase();
        $contracts=[];
        try
        {
            $db->exec('LOCK TABLES contract READ');
            $param=array();
            $q='SELECT * FROM contract WHERE idReservation IN (SELECT id FROM reservation WHERE idStudent=:id';
            if(!is_null($idAccommodation))
            {
                $q.=' AND idAccommodation=:idAccommodation) AND status != "future"';
                $param[':idAccommodation']=$idAccommodation;
            } else {
                $q.=')';
            }
            if (!is_null($kind))
            {
                $q.=' AND status=:kind';
                $param[':kind']=$kind;
            }
            $db->beginTransaction();
            $stm=$db->prepare($q);
            $stm->bindParam(':id',$id,PDO::PARAM_INT);
            if (!is_null($idAccommodation)) {
                $stm->bindParam(':idAccommodation',$idAccommodation,PDO::PARAM_INT);
            }
            if (!is_null($kind)) {
                $stm->bindParam(':kind',$kind,PDO::PARAM_STR);
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
    /**
     * Method getContractsByOwner
     *
     * this metod is used to return all owner'scontracts or false  
     * @param int $id [owner's ID]
     * @param string $kind [status of contract (finished, onGoing or future)]
     *
     * @return array|bool
     */
    public function getContractsByOwner(int $id, string $kind):array|bool {
        $db = FConnection::getInstance()->getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        FPersistentManager::getInstance()->updateDataBase();
        $contracts = [];
        try {
            $db->exec('LOCK TABLES contract READ');
            $q = 'SELECT contract.*, reservation.idAccommodation 
                  FROM contract 
                  JOIN reservation ON contract.idReservation = reservation.id 
                  JOIN accommodation ON reservation.idAccommodation = accommodation.id 
                  WHERE accommodation.idOwner = :id AND contract.status = :kind;';
            $db->beginTransaction();
            $stm = $db->prepare($q);
            $stm->bindParam(':id', $id, PDO::PARAM_INT);
            $stm->bindParam(':kind', $kind, PDO::PARAM_STR);
            $stm->execute();
            $db->commit();
            $db->exec('UNLOCK TABLES');
        } catch (PDOException $e) {
            $db->rollBack();
            return false;
        }
        $row = $stm->fetchAll(PDO::FETCH_ASSOC);
        foreach ($row as $r) {
            $reservationAssociated = FReservation::getInstance()->load($r['idReservation']);
            $date = new DateTime($r['paymentDate']);
            $contract = new EContract($r['status'], $r['cardNumber'], $reservationAssociated, $date);
            $contracts[$r['idAccommodation']][] = $contract;
        }
        return $contracts;
    }
    
    /**
     * Method getOnGoingContractsByAccommodationId
     *
     * this method return an array with the Accommodation's ID as key an an array of EContract as value, where the contract status is 'onGoing'
     * @param int $id [idAccommodation]
     *
     * @return array
     */
    public function getOnGoingContractsByAccommodationId(int $id):array
    {
        $db=FConnection::getInstance()->getConnection();
        $contracts=array();
        try
        {
            $q="SELECT *
                FROM accommodation a INNER JOIN reservation r ON r.idAccommodation=a.id
                INNER JOIN contract c ON r.id=c.idReservation
                WHERE c.`status`='onGoing'
                AND a.id= :id";
            $db->exec('LOCK TABLES accommodation READ, reservation READ, contract READ');
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
            $db->exec('UNLOCK TABLES');
            return $contracts;
        }
        $rows=$stm->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) 
        {
            $reservationAssociated = FPersistentManager::getInstance()->load('EReservation',$row['idReservation']);
            $date = new DateTime($row['paymentDate']);
            $contract = new EContract($row['status'], $row['cardNumber'], $reservationAssociated, $date);
            $contracts[$row['idAccommodation']][] = $contract;
        }
        return $contracts;
    }
    
}