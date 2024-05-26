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
    public function load(int $id):EStudent | bool
    {
        $db=FConnection::getInstance()->getConnection();
        if($this->exist($id))
        {
            try
            {
                $db->exec('LOCK TABLE student READ');
                $db->beginTransaction();
                $q='SELECT * FROM student WHERE id=:id';
                $stm=$db->prepare($q);
                $stm->bindParam(':id',$id,PDO::PARAM_INT);
                $stm->execute();
                $db->commit();
                $db->exec('UNLOCK TABLES');
            }
            catch (PDOException $e)
            {
                $db->rollBack();
            }
            $row=$stm->fetch(PDO::FETCH_ASSOC);
            $BIRTH= new DateTime($row['birthDate']);
            $student = new EStudent($row['id'],$row['username'],$row['password'],$row['name'],$row['surname'],$row['picture'],$row['universityMail'],$row['courseDuration'],$row['immatricolationYear'],$BIRTH,$row['sex'],$row['smoker'],$row['animals']);
            return $student;
        }
        else
        {
            return false;
        }
    }
/*
    public function store(EStudent $student):bool
    {}
    public function delete(int $id):bool
    {}
    public function update(EStudent $student):bool
    {}
*/
}