<?php 

namespace Classes\Utilities;

class UAccessUniversityFile
{
    public static $instance= null;
    private static $list=array();

    private function __construct()
    {
        $path='UniIta.json';
        $json=file_get_contents($path);
        $myarray=json_decode($json,true);
        $università=$myarray['records'];
        $indirizzi_web=array();
        foreach($università as $key=>$value)
        {
            $domain = substr(strrchr($università[$key][12], "www"), 1);
            if($domain!='')
            {
                $indirizzi_web[]=$domain;
            }   
        }
        self::$list=$indirizzi_web;
    }

    public static function getInstance():UAccessUniversityFile
    {
        if(is_null(self::$instance))
        {
            self::$instance= new UAccessUniversityFile();
        }
        return self::$instance;
    }

    public function getUniversityEmailList():array
    {
        $result=self::$list;
        return $result;
    }
    public function close():void
    {
        self::$instance=null;
    }
}