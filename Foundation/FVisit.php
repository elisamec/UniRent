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
        $result=new EVisit($row['id'],$row['visitDay'],$row['idStudent'],$row['idAccommodation']);
        return $result;
    }

    /**
   * store
   *
   * @param  EVisit $visit
   * @return bool
   */
    public function store(EVisit $visit):bool 
    {
        $db=FConnection::getInstance()->getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
        
        try
        { 
            $db->exec('LOCK TABLES creditcard WRITE');
            $db->beginTransaction();

            $day = $visit->getDate()->format('Y-m-d H:i:s');
            $idStudent = $visit->getIdStudent();
            $idAccommodation = $visit->getIdAccommodation();

            $q='INSERT INTO visit ( visitDay, idStudent, idAccommodation)';
            $q=$q.' VALUES (:day, :idStudent, :idAccommodation)';

            $stm=$db->prepare($q);
            $stm->bindValue(':day',$day,PDO::PARAM_STR);
            $stm->bindValue(':idStudent',$idStudent,PDO::PARAM_STR);
            $stm->bindValue(':idAccommodation',$idAccommodation,PDO::PARAM_STR);
            
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