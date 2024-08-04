<?php
namespace Classes\Foundation;

use PDO;
use PDOException;
//use const UniRent\{DB_HOST, DB_NAME, DB_PASS, DB_USER, SQL_FILE_PATH};

require __DIR__ . '/../../config.php';
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
	private static $hostname =DB_HOST;
    private static $username =DB_USER;
    private static $password =DB_PASS;
    private static $dbname = DB_NAME;

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
	
	/**
	 * Method getStatistics
	 * 
	 * this method gets all the statistics from the Data Base for the amministrator
	 *
	 * @return array
	 */
	public function getStatistics():array
	{
		$result=array();
		$db=$this->getConnection();
		try
		{
			$db->beginTransaction();

			$q="SELECT COUNT(*) AS n_student
                FROM student LOCK IN SHARE MODE";
			$stm=$db->prepare($q);
			$stm->execute();
			$r=$stm->fetch(PDO::FETCH_ASSOC);
			$result['n_student']=$r['n_student'];

			$q="SELECT COUNT(*) AS n_owner
                FROM owner LOCK IN SHARE MODE";
			$stm=$db->prepare($q);
			$stm->execute();
			$r=$stm->fetch(PDO::FETCH_ASSOC);
			$result['n_owner']=$r['n_owner'];

			$q="SELECT COUNT(*) AS n_accommodation
                FROM accommodation LOCK IN SHARE MODE";
			$stm=$db->prepare($q);
			$stm->execute();
			$r=$stm->fetch(PDO::FETCH_ASSOC);
			$result['n_accommodation']=$r['n_accommodation'];

			$q="SELECT AVG(TIMESTAMPDIFF(YEAR,s.birthDate,NOW())) AS avg_students_age
                FROM student s LOCK IN SHARE MODE";
			$stm=$db->prepare($q);
			$stm->execute();
            $r=$stm->fetch(PDO::FETCH_ASSOC);
			$result['avg_students_age']=$r['avg_students_age'];

			$q="SELECT ad.city AS city, COUNT(*) AS n_contract , AVG(a.price) AS prize
                FROM address ad INNER JOIN accommodation a ON ad.id=a.address
                INNER JOIN reservation r ON r.idAccommodation=a.id
                INNER JOIN contract c ON c.idReservation=r.id
                GROUP BY ad.city
                ORDER BY n_contract DESC 
                LIMIT 3
				LOCK IN SHARE MODE";
			$stm=$db->prepare($q);
			$stm->execute();
			$rows=$stm->fetchAll(PDO::FETCH_ASSOC);
			$result['city_list']=array();
			foreach($rows as $row)
			{
				$result['city_list'][$row['city']]=$row['prize'];
			}

			$q="SELECT COUNT(*) AS this_month_n_contracts
                FROM contract c 
                WHERE MONTH(c.paymentDate)=MONTH(NOW())
                AND YEAR(c.paymentDate)=YEAR(NOW())
				LOCK IN SHARE MODE";
			$stm=$db->prepare($q);
			$stm->execute();
            $r=$stm->fetch(PDO::FETCH_ASSOC);
			$result['this_month_n_contracts']=$r['this_month_n_contracts'];

			$q="SELECT DATE_FORMAT(c.paymentDate, '%d-%m-%Y') AS days, COUNT(*) AS n_contracts_per_day_this_month
                FROM contract c 
                WHERE MONTH(c.paymentDate) = MONTH(NOW()) 
                AND YEAR(c.paymentDate) = YEAR(NOW())
                GROUP BY DATE_FORMAT(c.paymentDate, '%d-%m-%Y')
				LOCK IN SHARE MODE";
			$stm=$db->prepare($q);
			$stm->execute();
			$rows=$stm->fetchAll(PDO::FETCH_ASSOC);
			$result['n_contracts_per_day_this_month']=array();
			foreach($rows as $row)
			{
				$result['n_contracts_per_day_this_month'][$row['days']]=$row['n_contracts_per_day_this_month'];
			}

			$q="SELECT AVG(daily_count) AS avg_contracts_per_day
                FROM (
                        SELECT DATE(c.paymentDate) AS day, COUNT(*) AS daily_count
                        FROM contract c
                        GROUP BY DATE(c.paymentDate)
                     ) AS daily_counts 
				LOCK IN SHARE MODE";
			$stm=$db->prepare($q);
			$stm->execute();
			$r=$stm->fetch(PDO::FETCH_ASSOC);
			$result['avg_contracts_per_day']=$r['avg_contracts_per_day'];

			$db->commit();
		}
		catch(PDOException $e)
		{
			$db->rollBack();
			return $result;
		}
		return $result;
	}
}
/*
$a=FConnection::getInstance();
if(!is_null($a))
{
	echo "Tutto ok";
}
*/


