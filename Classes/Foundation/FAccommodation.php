<?php 

require_once ('FConnection.php');
require_once ('../Entity/EAccommodation.php');

class FAccommodation{

    private static $instance=null;
    
    /**
     * __construct
     *
     * @return self
     */
    public function __construct(){}

    /**
     * getInstance
     *
     * @return FAccommodation
     */
    public static function getInstance():FAccommodation
    {
        if(is_null(self::$instance))
        {
            self::$instance= new FAccommodation();
        }
        return self::$instance;
    }

    /**
    * exist
    *
    * @param  int $accommodationId
    * @return bool
    */
    public function exist(int $accommodatioId):bool 
    {
        $q="SELECT * FROM visit WHERE id=$accommodatioId";
        $db=FConnection::getInstance()->getConnection();
        $db->beginTransaction();
        $stm=$db->prepare($q);
        $stm->execute();
        $db->commit();
        $result=$stm->rowCount();

        if ($result >0) return true;
        return false;
    }

    /**
     * load
     *  
     *  
     * @param  int $idAccommodation
     * @return EAccommodation
     */
    public function load(int $idAccommodation): EAccommodation{

        $db=FConnection::getInstance()->getConnection();
        
        try{
            $db->exec('LOCK TABLES visit READ');
            $db->beginTransaction();
            $q="SELECT * FROM visit WHERE id=$idAccommodation";    
            $stm=$db->prepare($q);
            $stm->execute();
            $db->commit();
            $db->exec('UNLOCK TABLES');

        }catch (PDOException $e){
            $db->rollBack();
        }

        $row=$stm->fetch(PDO::FETCH_ASSOC);
        $result=new EAccommodation();
        return $result;
    }
}