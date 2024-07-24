<?php 

namespace Classes\Foundation;

use PDOException;
use Updater\Updater;
use PDO;

class FUpdater
{
    private static $instance;
    private function __construct(){}

    public static function getInstance()
    {
        if (!self::$instance) 
        {
            self::$instance = new FUpdater();
        }
        return self::$instance;
    }

    public function updateDB()
    {
        $db=FConnection::getInstance()->getConnection();
        try
        {
            $db->exec('LOCK TABLES contract WRITE, reservation WRITE');
            require_once __DIR__.'/../../Updater/day.php';
            if($cont==0)
            {
                $db->beginTransaction();

                #seleziona gli id dei contratti che dovrebbero essere messi come onGoing
                $q="SELECT 	r.id 
                    FROM reservation r 
                    INNER JOIN contract c ON r.id = c.idReservation             
                    WHERE DateDiff(fromDate, NOW())<0
                    AND DateDiff(toDate, NOW())>0";
                $stm=$db->prepare($q);
                $stm->execute();
                $res=$stm->fetchAll(PDO::FETCH_COLUMN,0);

                //update contract to onGoing
                if(!empty($res))
                {
                    $ids = implode(',', array_map('intval', $res));
                    $updateQuery = "UPDATE contract SET status = 'onGoing' WHERE idReservation IN ($ids)";
                    $updateStm = $db->prepare($updateQuery);
                    $updateStm->execute();
                }

                //update contract to finished
                $q1="SELECT r.id 
                     FROM reservation r 
                     INNER JOIN contract c ON r.id = c.idReservation           #seleziona gli id dei contratti che dovrebbero essere messi come finished
                     WHERE DateDiff(toDate, NOW())<0";
                
                $result=$db->commit();
                if($result)
                {
                    Updater::getInstance()->updateDayFile($day,1);
                }
                $db->exec('UNLOCK TABLES');
                return;
            }
            else
            {
                $db->exec('UNLOCK TABLES');
                return;
            }
        }
        catch(PDOException $e)
        {
            $db->rollBack();
            $db->exec('UNLOCK TABLES');
            http_response_code(500);
        }
    }
}