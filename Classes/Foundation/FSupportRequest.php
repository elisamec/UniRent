<?php
namespace Classes\Foundation;
require __DIR__.'../../../vendor/autoload.php';
use PDO;
use PDOException;
use Classes\Foundation\FConnection;
use Classes\Entity\ESupportRequest;
use Classes\Tools\TType;
use Classes\Tools\TRequestType;
use Classes\Tools\TStatus;
use Classes\Tools\TStatusSupport;

/**
 * This class provides all the methods to interact with the database regarding the support request
 * 
 * @package Foundation
 */
class FSupportRequest {
    private static $instance=null;
    /**Constructor */
    private function __construct()
    {}
    /**This static method gives the istance of this singleton class
     * @return  
    */
    public static function getInstance()
    {
        if(is_null(self::$instance))
        {
            self::$instance = new FSupportRequest();
        }
        return self::$instance;
    }

    /**
     * Method exist
     *
     * this method check if the support request exist
     * @param int $id
     * @return bool
     */
    public function exist(int $id):bool 
    {
        $q='SELECT * FROM supportrequest WHERE id=:id';
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
    
    /**
     * Method load
     *
     * this method load the support request from the database
     * @param int $id
     * @return ESupportRequest
     */
    public function load(int $id): ESupportRequest {
        $db=FConnection::getInstance()->getConnection();
        try
        {
            $db->exec('LOCK TABLES supportrequest READ');
            $db->beginTransaction();
            $q='SELECT * FROM supportrequest WHERE id=:id';
            $stm=$db->prepare($q);
            $stm->bindparam(':id', $id, PDO::PARAM_INT);
            $stm->execute();
            $db->commit();
            $db->exec('UNLOCK TABLES');
        }
        catch (PDOException $e) 
        {
            $db->rollBack();
        }
        $row=$stm->fetch(PDO::FETCH_ASSOC);
        if ($row['idStudent']!=null) {
            $author=$row['idStudent'];
            $authType=TType::STUDENT;
        } else if ($row['idOwner']!=null) {
            $author=$row['idOwner'];
            $authType=TType::OWNER;
        } else {
            $author=null;
            $authType=null;
        }
        $result=new ESupportRequest($row['id'],$row['message'], TRequestType::tryFrom($row['topic']), $author, $authType);
        $result->setStatus(TStatusSupport::tryFrom($row['status']));
        $result->setSupportReply($row['supportReply']);
        return $result;
    }

    /**
     * Method store
     *
     * this method store the support request in the database
     * @param ESupportRequest $supportrequest
     * @return bool
     */
    public function store(ESupportRequest $supportrequest):bool {
        $db=FConnection::getInstance()->getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
        try
        {   
            $authType=$supportrequest->getAuthorType()->value;
            if ($authType!=null) {
                $authCol='id'.ucfirst($authType);
                $value= ':author';
            } else {
                $authCol='idStudent, idOwner';
                $value= ':idStudent, :idOwner';
            }
            $db->exec('LOCK TABLES supportrequest WRITE');
            $db->beginTransaction();
            $q='INSERT INTO supportrequest (message, topic,'.$authCol.', status, supportReply, statusRead)';
            $q=$q.'VALUES (:message, :topic,'.$value.', :status, :supportReply, :statusRead)';
            $stm = $db->prepare($q);
            $stm->bindValue(':message', $supportrequest->getMessage(), PDO::PARAM_STR);
            $stm->bindValue(':topic', $supportrequest->getTopic()->value, PDO::PARAM_STR);
            if ($authType!=null) {
                $stm->bindValue(':author', $supportrequest->getAuthorID(), PDO::PARAM_INT);
            } else {
                $stm->bindValue(':idStudent', null, PDO::PARAM_NULL);
                $stm->bindValue(':idOwner', null, PDO::PARAM_NULL);
            }
            if ($supportrequest->getSupportReply()!=null) {
                $stm->bindValue(':supportReply', $supportrequest->getSupportReply(), PDO::PARAM_STR);
            } else {
                $stm->bindValue(':supportReply', null, PDO::PARAM_NULL);
            }
            $stm->bindValue(':statusRead', $supportrequest->getStatusRead(), PDO::PARAM_INT);
            $stm->bindValue(':status', $supportrequest->getStatus()->value, PDO::PARAM_INT);
            $stm->execute();
            $id=$db->lastInsertId();
            $db->commit();
            $db->exec('UNLOCK TABLES');
            $supportrequest->setID($id);
            return true;
            
        }
        catch (PDOException $e) {
            $db->rollBack();
            return false;
        }
    }

    /**
     * Method update
     *
     * this method update the support request in the database
     * @param ESupportRequest $supportrequest
     * @return bool
     */
    public static function update(ESupportRequest $supportrequest):bool {
        $db=FConnection::getInstance()->getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
        try
        { 
            $db->exec('LOCK TABLES supportrequest WRITE');
            $db->beginTransaction();
            $q='UPDATE supportrequest SET status = :status, supportReply=:supportReply, statusRead = :statusRead WHERE id = :id';
            $stm = $db->prepare($q);
            $stm->bindValue(':id', $supportrequest->getId(), PDO::PARAM_INT);
            if ($supportrequest->getSupportReply()!=null) {
                $stm->bindValue(':supportReply', $supportrequest->getSupportReply(), PDO::PARAM_STR);
            } else {
                $stm->bindValue(':supportReply', null, PDO::PARAM_NULL);
            }
            $stm->bindValue(':statusRead', $supportrequest->getStatusRead(), PDO::PARAM_INT);
            $stm->bindValue(':status', $supportrequest->getStatus()->value, PDO::PARAM_INT);
            $stm->execute();
            $db->commit();
            $db->exec('UNLOCK TABLES');
            return true;
            
        }
        catch (PDOException $e) {
            $db->rollBack();
            return false;
        }
    }

    /**
     * Method delete
     *
     * this method delete the support request from the database
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool {
        $db=FConnection::getInstance()->getConnection();
        try
        {   
            $db->exec('LOCK TABLES supportrequest WRITE');
            $db->beginTransaction();
            $q='DELETE FROM supportrequest WHERE id= :id';
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
    
    /**
     * Method getAllRequest
     *
     * this method return all the supportRequests
     * @return array
     */
    public function getAllRequest():array
    {
        $db=FConnection::getInstance()->getConnection();
        try
        {
            $q="SELECT *
                FROM supportrequest sr
                ORDER BY sr.status ASC
                LOCK IN SHARE MODE ";
            $db->beginTransaction();
            $stm=$db->prepare($q);
            $stm->execute();
            $db->commit();
        }
        catch(PDOException $e)
        {
            $db->rollBack();
            return array();
        }
        $rows=$stm->fetchAll(PDO::FETCH_ASSOC);
        $result=array();
        foreach($rows as $row)
        {
            $sr=$this->load($row['id']);
            $sr->setStatusRead($row['statusRead']);
            $sr->setSupportReply($row['supportReply']);
            $result[]=$sr;
        }
        return $result;
    }
}