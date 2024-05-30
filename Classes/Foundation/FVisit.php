<?php 

require_once ('FConnection.php');
require_once ('../Entity/EVisit.php');

/**
 * FVisit
 * 
 * @package Foundation
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
    * @param  int $idVisit
    * @return EVisit
    */
    public function load(int $idVisit): EVisit{

        $db=FConnection::getInstance()->getConnection();
        $FV=FVisit::getInstance();
        
        if($FV->exist($idVisit)){
            try{
                $db->exec('LOCK TABLES visit READ');
                $db->beginTransaction();
                $q="SELECT * FROM visit WHERE id=$idVisit";    
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
        }else{
            return null;
        }
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
            $db->exec('LOCK TABLES visit WRITE');
            $db->beginTransaction();

            $day = $visit->getDate()->format('Y-m-d H:i:s');
            $idStudent = $visit->getIdStudent();
            $idAccommodation = $visit->getIdAccommodation();

            $q='INSERT INTO visit ( visitDay, idStudent, idAccommodation)';
            $q=$q.' VALUES (:day, :idStudent, :idAccommodation)';

            $stm=$db->prepare($q);
            $stm->bindValue(':day',$day,PDO::PARAM_STR);
            $stm->bindValue(':idStudent',$idStudent,PDO::PARAM_INT);
            $stm->bindValue(':idAccommodation',$idAccommodation,PDO::PARAM_INT);
            
            $stm->execute();
            $id=$db->lastInsertId();
            $db->commit();
            $db->exec('UNLOCK TABLES');
            $visit->setIdVisit($id);
            return true;
        }      
        catch(PDOException $e)
        {
            $db->rollBack();
            return false;
        }

    }

    /**
    * update
    *
    * @param  EVisit $visit
    * @return bool
    */
    public function update(EVisit $visit):bool 
    {
        $db=FConnection::getInstance()->getConnection();
        $FV=FVisit::getInstance();

        $visitId = $visit->getIdVisit();

        
        if($FV->exist($visitId)){
            try{
                $db->exec('LOCK TABLES visit WRITE');
                $db->beginTransaction();

                $day = $visit->getDate()->format('Y-m-d H:i:s');
                
                $q='UPDATE visit SET visitDay = :day  WHERE id=:id';
                $stm=$db->prepare($q);
                $stm->bindValue(':day',$day,PDO::PARAM_STR);
                $stm->bindValue(':id',$visitId,PDO::PARAM_INT);

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
        } else return false;
        
    }

    /**
    * delete
    *
    * @param  int $idVisit
    * @return bool
    */
    public function delete(int $idVisit): bool 
    {
        $db=FConnection::getInstance()->getConnection();
        $FV=FVisit::getInstance();
        
        if($FV->exist($idVisit)){
            try
            {  
                $db->exec('LOCK TABLES visit WRITE');
                $db->beginTransaction();
                $q='DELETE FROM visit WHERE id=:id';
                $stm=$db->prepare($q);
                $stm->bindValue(':id',$idVisit, PDO::PARAM_INT);
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

        } else return false;
        
    }
}