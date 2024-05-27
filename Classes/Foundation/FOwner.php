<?php 

require_once ('FConnection.php');
require_once ('../Entity/EOwner.php');
require_once('FPhoto.php');
/**
 * This class provide to make query to EOwner class
 * @author Matteo Maloni ('UniRent')
 * @package Foundation
 */

 class FOwner
 {
    /**static attribute that contains the instance of the class */
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
            self::$instance = new FOwner();
        }
        return self::$instance;
    }

    public function exist(int $id):bool 
    {
        $q='SELECT * FROM owner WHERE id=:idOwner';
        $connection= FConnection::getInstance();
        $db=$connection->getConnection();
        $db->beginTransaction();
        $stm=$db->prepare($q);
        $stm->bindParam(':idOwner',$id,PDO::PARAM_INT);
        $stm->execute();
        $db->commit();
        $result=$stm->rowCount();

        if ($result >0)
        {
            return true;
        }
        return false;

    }
    
#    public function load(int $id): EOwner {}

#    public function store(EOwner $owner):bool {
    /*
    try {
        $stmt = $pdo->prepare("INSERT INTO users (id, email) VALUES (?, ?)");
        $stmt->execute([2, 'example@example.com']);
    } catch (PDOException $e) {
        // MySQL specific error code for duplicate entry is 1062
        if ($e->getCode() == 23000) {
            echo "Duplicate entry detected: " . $e->getMessage();
        } else {
            // Re-throw the exception if it's not a duplicate entry error
            throw $e;
        }
    }
}
        */

 #   public function update(EOwner $owner):bool {}

 #   public function delete(int $id): bool {}

 }