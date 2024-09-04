<?php 

namespace Classes\Utilities;

class UAccessUniversityFile
{
    /**
     * $path
     *
     * @var string
     */
    public static $path = __DIR__ . '/UniIta.json';
    /**
     * $instance
     *
     * @var UAccessUniversityFile
     */
    public static $instance= null;
    /**
     * $list
     *
     * @var array
     */
    private static $list=array();

    /**
     * __construct
     *
     * @return void
     */
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
    /**
     * getInstance
     *
     * @return UAccessUniversityFile
     */
    public static function getInstance():UAccessUniversityFile
    {
        if(is_null(self::$instance))
        {
            self::$instance= new UAccessUniversityFile();
        }
        return self::$instance;
    }
    /**
     * getUniversityEmailList
     *
     * @return array
     */
    public function getUniversityEmailList():array
    {
        $result=self::$list;
        return $result;
    }
    /**
     * close
     *
     * @return void
     */
    public function close():void
    {
        self::$instance=null;
    }

    /**
     * Method addElement
     * this method add an element to the json file
     * 
     * @param array $element
     * @return void
     * 
     */
    public function addElement(string $domain, string $uniName, string $city):void{

        $element = [0, 0, $uniName, "", "Attivo  ", "", "", "", $city, "", "", "", $domain, ""];
        $json=file_get_contents(self::$path);
        $myarray=json_decode($json,true);
        $myarray['records'][]=$element;
        $json=json_encode($myarray);
        file_put_contents(self::$path,$json);
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
    public function getCityByUniversityName(string $university):string
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
        return '';
    }

    /**
     * Method getCities
     *
     * this method retrive the cities
     *
     * @return array
     */
    public function getCities()
    {
        $result=array();
        $json=file_get_contents(self::$path);
        $myarray=json_decode($json,true);
        $università=$myarray['records'];
        foreach($università as $key=>$value)
        {
            if(!array_key_exists($università[$key][8],$result))
            {
                $result[$università[$key][8]]=array();
                $result[$università[$key][8]][]=$università[$key][2];
            }
            else
            {
                $result[$università[$key][8]][]=$università[$key][2];
            }
        }
        ksort($result);
        return $result;
    }
}