<?php

use Classes\Entity\EAdministrator;

require_once ('FConnection.php');
require_once ('../Entity/EAdministrator.php');

class FAdministrator
{
    private static $instance=null;

    private function __construct() {}

    public static function getInstance():FAdministrator
    {
        if(is_null(self::$instance))
        {
            self::$instance= new FAdministrator();
        }
        return self::$instance;
    }
    public function exist(int $id):bool
    {
        $q='SELECT * FROM administrator WHERE id=:id';
        $connection= FConnection::getInstance();
        $db=$connection->getConnection();
        $db->beginTransaction();
        $stm=$db->prepare($q);
        $stm->bindParam(':id',$id,PDO::PARAM_INT);
        $stm->execute();
        $db->commit();
        $result=$stm->rowCount();

        if ($result >0)
        {
            return true;
        }
        return false;

    }
    public function load(int $id):EAdministrator|false
    {
        $db=FConnection::getInstance()->getConnection();
        if($this->exist($id))
        {
            try
            {
                $q='SELECT * FROM administrator WHERE id=:id';
                $db->exec('LOCK TABLES administrator READ');
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
            return new EAdministrator($row['username'],$row['password'],$row['email']);
        }
    }
}