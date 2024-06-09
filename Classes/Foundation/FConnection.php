<?php
namespace Classes\Foundation;

use PDO;
use PDOException;

/**
 * The class FConnection provide to connect to database.
 * @author Matteo Maloni ('UniRent')
 * @package Foundation
 */

/**
 * FConnection
 */
class FConnection
{

	/**
	 * db
	 *
	 * @var mixed
	 */
	private $db;
	private static $hostname ="127.0.0.1";
    private static $username ="root";
    private static $password ="pippo";
    private static $dbname = "unirent";

	private static $ClassItSelf=null;
    	
	/**
	 * __construct
	 *
	 * @return void
	 */
	private function __construct()
	{
		$h=self::$hostname;
        $d=self::$dbname;
		$un=self::$username;
		$pw=self::$password;
		try
		{
            $this->db = new PDO ("mysql:host=$h;dbname=$d", $un, $pw);
        }
        catch ( PDOException $e) 
        {
            print $e->getMessage() . "\n";
            exit;
        } 
	}

	
	/**
	 * getInstance
	 *
	 * @return self
	 */
	public static function getInstance():self
	{
		if(self::$ClassItSelf==null)
		{
			self::$ClassItSelf =new FConnection();
		}
		return self::$ClassItSelf;
	}
    
    /**
     * This method provide to get the database connection
	 * getConnection
	 *
	 * @return PDO
	 */
	public function getConnection():PDO
	{
		return $this->db;
	}
 

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
/*
$a=FConnection::getInstance();
if(!is_null($a))
{
	echo "Tutto ok";
}
*/

