<?php 

require_once ('FConnection.php');
require_once ('../Entity/EVisit.php');

/**
 * FVisit
 */
class FVisit
{
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
     * @return FVisit
     */
    public static function getInstance():FVisit
    {
        if(is_null(self::$instance))
        {
            self::$instance= new FVisit();
        }
        return self::$instance;
    }
    
    /**
     * exist
     *
     * @param  int $visitId
     * @return bool
     */
    public function exist(int $visitId):bool 
    {
        $q="SELECT * FROM visit WHERE id=$visitId";
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
     * @param  int $visitId
     * @return EVisit
     */
    public function load(int $visitId): EVisit{

        $db=FConnection::getInstance()->getConnection();
        
        try{
            $db->exec('LOCK TABLES visit READ');
            $db->beginTransaction();
            $q="SELECT * FROM visit WHERE id=$visitId";    
            $stm=$db->prepare($q);
            $stm->execute();
            $db->commit();
            $db->exec('UNLOCK TABLES');

        }catch (PDOException $e){
            $db->rollBack();
        }

        $row=$stm->fetch(PDO::FETCH_ASSOC);
        $result=new EVisit($row['id'],$row['day'],$row['idStudent'],$row['idAccommodation']);
        return $result;
    }
    


}