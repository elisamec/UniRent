<?php
namespace Classes\Foundation;
use Classes\Entity\EAdministrator;
use Classes\Foundation\FConnection;
use PDO;
use PDOException;

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
    public function exist(?int $id):bool
    {
        if(is_null($id))
        {
            return false;
        }
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
    public function load(int $id):EAdministrator|bool
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
            $result=new EAdministrator($row['username'],$row['password'],$row['email']);
            $result->setID($row['id']);
            return $result;
        }
        else
        {
            return false;
        }
    }
    public function store(EAdministrator $admin):bool
    {
        $db=FConnection::getInstance()->getConnection();

        try
        {
            $db->exec('LOCK TABLES administrator WRITE');
            $db->beginTransaction();
            $q="INSERT INTO administrator (username,password,email) VALUES (:user,:psw,:email)";
            $stm=$db->prepare($q);
            $stm->bindValue(':user',$admin->getUsername(),PDO::PARAM_STR);
            $stm->bindValue(':psw',$admin->getPassword(),PDO::PARAM_STR);
            $stm->bindValue(':email',$admin->getEmail(),PDO::PARAM_STR);
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

    public function update(EAdministrator $admin):bool
    {
        $db=FConnection::getInstance()->getConnection();
        if($this->exist($admin->getID()))
        {
            try
            {
                $db->exec('LOCK TABLES administrator WRITE');
                $db->beginTransaction();
                $q="UPDATE administrator SET username=:username, password=:password, email=:email WHERE id=:id";
                $stm=$db->prepare($q);
                $stm->bindValue(':id',$admin->getID(),PDO::PARAM_INT);
                $stm->bindValue(':username',$admin->getUsername(),PDO::PARAM_STR);
                $stm->bindValue(':password',$admin->getPassword(),PDO::PARAM_STR);
                $stm->bindValue(':email',$admin->getEmail(),PDO::PARAM_STR);
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
        else
        {
            return false;
        }
    }

    public function delete(int $id):bool
    {
        $db=FConnection::getInstance()->getConnection();
        if($this->exist($id))
        {
            try
            {
                $db->exec('LOCK TABLES administrator WRITE');
                $db->beginTransaction();
                $q='DELETE FROM administrator WHERE id=:id';
                $stm=$db->prepare($q);
                $stm->bindValue(':id',$id, PDO::PARAM_INT);
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
        else
        {
            return false;
        }
    }
}