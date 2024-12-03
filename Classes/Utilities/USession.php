<?php

namespace Classes\Utilities;
require __DIR__.'/../../vendor/autoload.php';

use Configuration\Config;

/**
 * class to access to the $_SESSION superglobal array, you Must use this class instead of using directly the array
 */
class USession
{
    /**
     * singleton class
     * class for the session, if you want to manipulate the _SESSION superglobal ypu need to use this class
     */

    private static $instance;

    private function __construct() 
    {
        //session_set_cookie_params(2592000); //set the duration of the session cookie
        session_set_cookie_params(Config::COOKIE_EXP_TIME); //set the duration of the session cookie
        session_start(); //start the session
    }
 
    public static function getInstance() 
    {
        if (self::$instance == null) 
        {
            self::$instance = new USession();
        }
 
        return self::$instance;
    }

    /**
     * return session status. If you want to check if the session is staretd you can use this
     */
    public function getSessionStatus()
    {
        return session_status();
    }

    /**
     * unset all the elements in the _SESSION superglobal
     */
    public function unsetSession()
    {
        session_unset();
    }

    /**
     * unset of an element of _SESSION superglobal
     */
    public function unsetSessionElement($id)
    {
        unset($_SESSION[$id]);
    }

    /**
     * destroy the session
     */
    public function destroySession()
    {
        session_destroy();
    }

    /**
     * get element in the _SESSION superglobal
     */
    public function getSessionElement($id)
    {
        if(isset($_SESSION[$id]))
        {
            return $_SESSION[$id];
        }  
        else
        {
            return null;
        }  
    }
    
    /**
     * Method getAllSessionElementReqested
     * 
     * this method get all the session element request in one time
     *
     * @param array $a [array of requests]
     *
     * @return array
     */    public function getAllSessionElementReqested(array $a):array
    {
       $result=array();
       foreach($a as $element)
       {
        if(isset($_SESSION[$element]))
        {
            $result[$element]=$_SESSION[$element];
        }
        else
        {
            $result[$element]=$_SESSION[$element];
        }
       } 
       return $result;
    }

    /**
     * set an element in _SESSION superglobal
     */
    public function setSessionElement($id, $value)
    {
        $_SESSION[$id] = $value;
    }

    /**
     * check if an element is set or not
     * @return boolean
     */
    public function isSetSessionElement($id)
    {
        if(isset($_SESSION[$id]))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    /**
     * Method booleanSolver
     * 
     * this method solve the boolean value
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function booleanSolver($value):?bool
    {
        if($value===1 || $value===true || $value==='true')
        {
            return true;
        }
        elseif($value===0 || $value===false || $value==='false' || $value==='' || $value===null)
        {
            return false;
        }
    }
}