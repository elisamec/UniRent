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
    
    /**
     * Method store
     *
     * @param EContract $con [contract]
     *
     * @return bool
     */
    public function store(EContract $con):bool
    {
        if(FReservation::getInstance()->exist($con->getID())===false)  //if dose not exist the reservation
        {
            return false;  //return store and not store the contract
        }
        $db=FConnection::getInstance()->getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
        try
        {
            $db->exec('LOCK TABLES contract WRITE');
            $q='INSERT INTO contract (reservationId,status,paymentDate,cardNumber)';
            $q.=' VALUES (:RID,:ST,:PD,:CN)';
            $db->beginTransaction();
            $stm=$db->prepare($q);
            $stm->bindValue(':RID',$con->getID(),PDO::PARAM_INT);
            $stm->bindValue(':ST',$con->getStatus(),PDO::PARAM_STR);
            $stm->bindValue(':PD',$con->getPaymentDate()->format('Y-m-d H:i:s'),PDO::PARAM_STR);
            $stm->bindValue(':CN',$con->getCardNumber(),PDO::PARAM_INT);
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
            try
            {
                $q='SELECT * FROM contract WHERE reservationId=:id';
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
}