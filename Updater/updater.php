
<?php 



/**
 * Updater
 * 
 * this class is used to update the whole DataBase after the first https message of the day
 * 
 * @author Matteo Maloni (UniRent) <matteo.maloni@student.univaq.it>
 * @package Updater
 */
class Updater
{
    
    private static $instance=null;
    
    /**
     * __construct
     *
     * @return self
     */
    public function __construct(){}
       
    /**
     * Method getInstance
     *
     * return the Updater singleton class
     * @return Updater
     */
    public static function getInstance():Updater
    {
        if(is_null(self::$instance))
        {
            self::$instance= new Updater();
        }
        return self::$instance;
    }
    
    /**
     * Method run
     *
     * the main method of this class used in index.php befor the call to MainController
     * @return void
     */
    public function run()
    {
        include 'day.php';
        $day=new DateTime($day);
        $now=new DateTime('now');
        $day=new DateTime($day->format('d-m-Y'));
        $now=new DateTime($now->format('d-m-Y'));
        if($day<$now)
        {
            $this->updateDayFile($now->format('d-m-Y'));
            #$this->updateDataBase();
            #print 'tutto ok';
        }
        #print 'non sei nell\'if';
    }
    
    /**
     * Method updateDayFile
     * 
     * this private method is used to update the file which contains the current day
     *
     * @param string $d [day 'day-month-year']
     *
     * @return void
     */
    private function updateDayFile(string $d)
    {
        $day = $d;
        $file = fopen(__DIR__.'/day.php', 'w');
        $content = "<?php\n\n\$day = '" . addslashes($day) . "';\n";
        $r=fwrite($file, $content);
        fclose($file);
    }

    private function updateDataBase()
    {

    }
}