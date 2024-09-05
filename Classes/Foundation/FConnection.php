<?php
namespace Classes\Foundation;

use PDO;
use PDOException;
//use const UniRent\{DB_HOST, DB_NAME, DB_PASS, DB_USER, SQL_FILE_PATH};

use Configuration\Config;
/**
 * The class FConnection provide to connect to database.
 * 
 * @package Foundation
 */
class FConnection
{

	/**
	 * db
	 *
	 * @var mixed
	 */
	private $db;
	private static $hostname =Config::DB_HOST;
    private static $username =Config::DB_USER;
    private static $password =Config::DB_PASS;
    private static $dbname = Config::DB_NAME;

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
	 * return the istance of this class 
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
 
	
	/**
	 * Method close
	 *
	 * @return void
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

			$result=$this->numberOfStudents($result,$db);
			$result=$this->numberOfOwners($result,$db);
            $result=$this->numberOfAccommodations($result,$db);
			$result=$this->avgStudentAge($result,$db);
			$result=$this->CityList($result,$db);
            $result=$this->thisMonthNumberOfContracts($result,$db);
            $result=$this->contractsPerDayThisMonth($result,$db);
			$result=$this->avgContractsPerDay($result,$db);

			$db->commit();
		}
		catch(PDOException $e)
		{
			$db->rollBack();
			return $result;
		}
		return $result;
	}
	
	/**
	 * Method numberOfStudents
	 *
	 * private method to reach the number of students for the statistics
	 * @param array $result [the array which contains the results]
	 * @param PDO $db [FConnetion class istance]
	 *
	 * @return array
	 */
	private function numberOfStudents(array $result, PDO $db):array
	{
		$q="SELECT COUNT(*) AS n_student
            FROM student LOCK IN SHARE MODE";
		$stm=$db->prepare($q);
		$stm->execute();
		$r=$stm->fetch(PDO::FETCH_ASSOC);
		$result['n_student']=$r['n_student'];
		return $result;
	}
	
	/**
	 * Method numberOfOwners
	 *
	 * this private method is used to reach the number of owners for the statistics
	 * @param array $result [the array of result of statistics]
	 * @param PDO $db [FConnection class istrance]
	 *
	 * @return array
	 */
	private function numberOfOwners(array $result, PDO $db):array
	{
		$q="SELECT COUNT(*) AS n_owner
            FROM owner LOCK IN SHARE MODE";
		$stm=$db->prepare($q);
		$stm->execute();
		$r=$stm->fetch(PDO::FETCH_ASSOC);
		$result['n_owner']=$r['n_owner'];
		return $result;
	}
	
	/**
	 * Method numberOfAccommodation
	 *
	 * this private method returns the number of Accommodation
	 * @param array $result [the array of statistics]
	 * @param PDO $db [FConnection class instance]
	 *
	 * @return array
	 */
	private function numberOfAccommodations(array $result, PDO $db):array
	{
		$q="SELECT COUNT(*) AS n_accommodation
            FROM accommodation LOCK IN SHARE MODE";
		$stm=$db->prepare($q);
		$stm->execute();
		$r=$stm->fetch(PDO::FETCH_ASSOC);
		$result['n_accommodation']=$r['n_accommodation'];
		return $result;
	}
	
	/**
	 * Method avgStudentAge
	 *
	 * this private method is used to get the avarange age of Students
	 * @param array $result [array of statistics]
	 * @param PDO $db [FConnection class instance]
	 *
	 * @return array
	 */
	private function avgStudentAge(array $result,PDO $db):array
	{
		$q="SELECT AVG(TIMESTAMPDIFF(YEAR,s.birthDate,NOW())) AS avg_students_age
                FROM student s LOCK IN SHARE MODE";
		$stm=$db->prepare($q);
		$stm->execute();
        $r=$stm->fetch(PDO::FETCH_ASSOC);
		$result['avg_students_age']=$r['avg_students_age'];
        return $result;
	}
	
	/**
	 * Method CityList
	 *
	 * this private method return a list of cities for the statistics
	 * @param array $result [array of results]
	 * @param PDO $db [FConnection class instance]
	 *
	 * @return array
	 */
	private function CityList(array $result, PDO $db):array
	{
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
		return $result;
	}
	
	/**
	 * Method thisMonthNumberOfContracts
	 *
	 * this private method return the number of contracts for this month for the statistics
	 * @param array $result [array of results]
	 * @param PDO $db [FConnection class instance]
	 *
	 * @return array
	 */
	private function thisMonthNumberOfContracts(array $result, PDO $db):array
	{
		$q="SELECT COUNT(*) AS this_month_n_contracts
            FROM contract c 
            WHERE MONTH(c.paymentDate)=MONTH(NOW())
            AND YEAR(c.paymentDate)=YEAR(NOW())
			LOCK IN SHARE MODE";
		$stm=$db->prepare($q);
		$stm->execute();
        $r=$stm->fetch(PDO::FETCH_ASSOC);
		$result['this_month_n_contracts']=$r['this_month_n_contracts'];
		return $result;
	}
	
	/**
	 * Method contractsParDayThisMonth
	 *
	 * this private method return the number of contracts par day in this month
	 * @param array $result [array of statistics]
	 * @param PDO $db [FConnection class instance]
	 *
	 * @return array
	 */
	private function contractsPerDayThisMonth(array $result, PDO $db):array
	{
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
		return $result;
	}
	
	/**
	 * Method avgContractsPerDay
	 *
	 * this private method returns the avarange contract per day for statistics
	 * @param array $result [array of statistics]
	 * @param PDO $db [FConnection class instance]
	 *
	 * @return array
	 */
	private function avgContractsPerDay(array $result, PDO $db):array
	{
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
		return $result;
	}
}



