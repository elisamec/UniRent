<?php 

namespace Classes\Utilities;

class UAccessUniversityFile
{
    public static $path = __DIR__ . '/UniIta.json';
    public static $instance= null;
    private static $list=array();

    private function __construct()
    {
        $path=__DIR__.'/UniIta.json';
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
    
    /**
     * Method getUniversityByCity
     *
     * this method retrives a Unniversity by the city
     * @param string $city 
     *
     * @return array
     */
    public function getUniversityByCity(string $city):array
    {
        $result=array();
        $json=file_get_contents(self::$path);
        $myarray=json_decode($json,true);
        $università=$myarray['records'];
        $city=strtoupper($city);
        foreach($università as $key=>$value)
        {
            if(($università[$key][8]==$city))
            {
                $result[]=$università[$key][2];
            }
        }
        return $result;
    }
    
    /**
     * Method getCitybyUniversityName
     *
     * this method retrive the city by an university name
     * @param string $university [explicite description]
     *
     * @return string
     */
    public function getCitybyUniversityName(string $university):string
    {
        $result=array();
        $json=file_get_contents(self::$path);
        $myarray=json_decode($json,true);
        $università=$myarray['records'];
        foreach($università as $key=>$value)
        {
            if(strtolower($università[$key][2])==strtolower($university))
            {
                return $università[$key][8];
            }
        }  
    }
}