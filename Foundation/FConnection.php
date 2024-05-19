<?php



/**
 * The class FConnection provide to connect to database.
 * @author Matteo Maloni ('UniRent')
 * @package Foundation
 */

class FConnection
{
	/** connection */
	private $db;

	private static $ClassItSelf=null;
    
    /**constuctor */
	private function __construct()
	{
		require '..\config.php';

		try
		{
            $this->db = new PDO ("mysql:host=$hostname;dbname=$dbname", $username, $password);
        }
        catch ( PDOException $e) 
        {
            print $e->getMessage() . "\n";
            exit;
        } 
	}

     /**
    * This method provide to get the instance
    */
	public static function getInstance()
	{
		if(self::$ClassItSelf==null)
		{
			self::$ClassItSelf =new FConnection();
		}
		return self::$ClassItSelf;
	}
    
    /**
    * This method provide to get the database connection
    */
	public function getConnection()
	{
		return $this->db;
	}
 
     /**
    * This method provide to close the connection to database
    */
	public static function close()
	{
		self::$db=null;
		self::$ClassItSelf=null;
	} 

    /** avoid to clone the istance */
	public function __clone(){}
    
    /** avoid to deserialize the istance */
	public function __wakeup(){}
}
$a=FConnection::getInstance();
if(!is_null($a))
{
	echo "Tutto ok";
}
