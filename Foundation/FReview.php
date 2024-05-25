<?php
class FReview {
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
            self::$instance = new FReview();
        }
        return self::$instance;
    }
    public function exist(int $id):bool 
    {
        $q='SELECT * FROM owner WHERE idReview=:idReview';
        $connection= FConnection::getInstance();
        $db=$connection->getConnection();
        $db->beginTransaction();
        $stm=$db->prepare($q);
        $stm->bindParam(':ID',$id,PDO::PARAM_INT);
        $stm->execute();
        $db->commit();
        $result=$stm->rowCount();

        if ($result >0)
        {
            return true;
        }
        return false;

    }
    public function load(int $id): EReview {
        $db=FConnection::getInstance()->getConnection();
        $db->beginTransaction();
      try
        {  
            $q='SELECT * FROM review WHERE idReview=:$id';
            $db->exec('LOCK TABLES review READ');
            $stm=$db->prepare($q);
            $stm->bindParam(':idReview',$id,PDO::PARAM_INT);
            $stm->execute();
            $db->exec('UNLOCK TABLES');   
        } 

        catch (PDOException $e)
        {
            $db->rollBack();
        }
        $row=$stm->fetch(PDO::FETCH_ASSOC);
        $result=new EReview($row['idReview'],$row['title'],$row['valutation'],$row['description'],$row['type'], $row['creationDate']);
        return $result;
    }

#    public function store(EOwner $owner):bool {}

 #   public function update(EOwner $owner):bool {}

 #   public function delete(int $id): bool {}
}