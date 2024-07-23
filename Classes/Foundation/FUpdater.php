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
                $q="";
                $stm=$db->prepare($q);
                $stm->execute();
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