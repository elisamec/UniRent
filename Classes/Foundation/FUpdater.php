<?php 

namespace Classes\Foundation;

use PDOException;
use Updater\Updater;

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
                $res=$stm->fetchAll();
                
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