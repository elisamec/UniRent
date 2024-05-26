<?php 

require_once ('FConnection.php');
require_once ('../Entity/FVisit.php');

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
     * @param  int $number
     * @return bool
     */
    public function exist(int $visitid):bool 
    {
        $q='SELECT * FROM visit WHERE id=$visitid';
        $connection= FConnection::getInstance();
        $db=$connection->getConnection();
        $db->beginTransaction();
        $stm=$db->prepare($q);
        $stm->execute();
        $db->commit();
        $result=$stm->rowCount();

        if ($result >0)
        {
            return true;
        }
        return false;

    }
    
    


}