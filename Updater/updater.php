<?php 
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
     * getInstance
     *
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

    public function updateDayFile(string $d)
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