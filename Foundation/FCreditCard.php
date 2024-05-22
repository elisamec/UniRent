<?php 

require_once ('FConnection.php');
require_once ('../Entity/ECreditCard.php');
class FCreditCard
{
    private static $instance=null;

    public function __construct(){}

    public static function getInstance():FCreditCard
    {
        if(is_null(self::$instance))
        {
            self::$instance= new FCreditCard();
        }
        return self::$instance;
    }

    public function exist(int $number):bool 
    {
        $q='SELECT * FROM owner WHERE number=:number';
        $connection= FConnection::getInstance();
        $db=$connection->getConnection();
        $db->beginTransaction();
        $stm=$db->prepare($q);
        $stm->bindParam(':number',$number,PDO::PARAM_INT);
        $stm->execute();
        $db->commit();
        $result=$stm->rowCount();

        if ($result >0)
        {
            return true;
        }
        return false;

    }

    public function load(int $number): ECreditCard 
    {
        try
        {
            print 'inizio try';
            $q='SELECT * FROM creditcard WHERE number=$number';
            print(' query scritta');
            $db=FConnection::getInstance()->getConnection();
            print( 'connessione al db raggiunta');
            $db->beginTransaction();
            print ('Transazione iniziata');
            $db->exec('LOCK TABLES creditcard READ');
            print ('  Blocco tabella  ');
            $stm=$db->prepare($q);
            print ('  query preparata  ');
           # $stm->bindParam(':number',$number,PDO::PARAM_INT);
            $stm->execute();
            print (' Query eseguita ');
            $db->exec('UNLOCK TABLES');
            $db->commit();
            print (' Fine try ');
        }
        catch (PDOException $e)
        {
            $db->rollBack();
        }
        $row=$stm->fetch(PDO::FETCH_ASSOC);
        $result=new ECreditCard($row['number'],$row['name'],$row['surname'],$row['expiry'],$row['cvv'],$row['studentId']);
        return $result;
    }

#    public function store(EOwner $owner):bool {}

 #   public function update(EOwner $owner):bool {}

 #   public function delete(int $id): bool {}


}