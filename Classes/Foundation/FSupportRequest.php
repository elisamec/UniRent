<?php
require_once ('FConnection.php');
require_once ('../Entity/ESupportRequest.php');
require_once('../Tools/TType.php');
require_once('../Tools/TRequestType.php');
require_once('../Tools/TStatus.php');

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
        $authType=$row['authorType'];
        if ($authType===TType::STUDENT) {
            $author=$row['idStudent'];
        } else {
            $author=$row['idOwner'];
        }
        $result=new ESupportRequest($row['id'],$row['message'], TRequestType::tryFrom($row['topic']), $author, TType::tryFrom($authType));
        return $result;
    }

    public function store(ESupportRequest $supportrequest):bool {
        $db=FConnection::getInstance()->getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
        try
        {   
            $authType=$supportrequest->getAuthorType()->value;
            $authCol='id'.ucfirst($authType);
            $db->exec('LOCK TABLES supportrequest WRITE');
            $db->beginTransaction();
            $q='INSERT INTO supportrequest (message, topic,'.$authCol.', authorType, status)';
            $q=$q.'VALUES (:message, :topic, :author, :authType, :status)';
            $stm = $db->prepare($q);
            $stm->bindValue(':message', $supportrequest->getMessage(), PDO::PARAM_STR);
            $stm->bindValue(':topic', $supportrequest->getTopic()->value, PDO::PARAM_STR);
            $stm->bindValue(':author', $supportrequest->getAuthorID(), PDO::PARAM_INT);
            $stm->bindValue(':authType', $supportrequest->getAuthorType()->value, PDO::PARAM_STR);
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
    public static function update(ESupportRequest $supportrequest):bool {
        $db=FConnection::getInstance()->getConnection();
        try
        { 
            $db->exec('LOCK TABLES supportrequest WRITE');
            $db->beginTransaction();
            $q='UPDATE supportrequest SET message = :message, status = :status';
            $stm = $db->prepare($q);
            $stm->bindValue(':message', $supportrequest->getMessage(), PDO::PARAM_STR);
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
    public function delete(ESupportRequest $supportrequest): bool {
        $db=FConnection::getInstance()->getConnection();
        //$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
        try
        {   
            $db->exec('LOCK TABLES supportrequest WRITE');
            $db->beginTransaction();
            $q='DELETE FROM supportrequest WHERE id= :id';
            $stm=$db->prepare($q);
            $stm->bindValue(':id',$supportrequest->getId(), PDO::PARAM_INT);
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