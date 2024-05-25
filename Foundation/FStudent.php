<?php 

require_once('FConnection.php');
require_once('../Entity/EStudent.php');
class FStudent
{
    private static $instance=null;

    private function __construct(){}

    public static function getInstance():FStudent
    {
        if(is_null(self::$instance))
        {
            self::$instance= new FStudent();
        }
        return self::$instance;
    }
    public function exist(int $id):bool
    {
        $q='SELECT * FROM student  WHERE id=:id';
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
/*  public function load(int $id):EStudent
    {}
    public function store(EStudent $student):bool
    {}
    public function delete(int $id):bool
    {}
    public function update(EStudent $student):bool
    {}
*/
}