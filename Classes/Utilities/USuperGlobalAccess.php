<?php 

namespace Classes\Utilities;

/**
 * The `USuperGlobalAccess` class provides a convenient way to access superglobal variables in PHP.
 * 
 * This class encapsulates the logic for accessing superglobal variables such as `$_GET`, `$_POST`, `$_SESSION`.
 * It provides methods to retrieve values from these superglobal variables and handle any necessary sanitization or validation.
 * 
 * Usage:
 * $superGlobalAccess = new USuperGlobalAccess();
 * $value = $superGlobalAccess->get('key');
 * 
 * @package Utilities  
 */
class USuperGlobalAccess {
    /**
     * Get a value from the $_GET superglobal array.
     * 
     * @param string $key The key of the value to retrieve.
     * @return mixed The value associated with the key, or null if the key does not exist.
     */
    public static function get($key) 
    {
        if (isset($_GET[$key])) 
        {
            return $_GET[$key];
        } 
        else 
        {
            return null;
        }
    }

    /**
     * Get a value from the $_POST superglobal array.
     * 
     * @param string $key The key of the value to retrieve.
     * @return mixed The value associated with the key, or null if the key does not exist.
     */
    public static function getPost($key) 
    {
        if (isset($_POST[$key])) {
            
            return $_POST[$key];
        } 
        else 
        {
            return null;
        }
    }
    
    /**
     * Method getAllPost
     *
     * this method get all the elements of a http post request in one time
     * @param array $a [array of requests from POST]
     *
     * @return array
     */
    public static function getAllPost(array $a):array
    {
        $result=array();
        foreach($a as $element)
        {
            if(isset($_POST[$element]))
            {
                $result[$element]= $_POST[$element];
            }
            else
            {
                $result[$element]=null;
            }
        }
        return $result;
    }

     /**
      *Get a value from the $_SERVER superglobal array.
      *@param string $key The key of the value to retrieve.
      *@return mixed The value associated with the key, or null if the key does not exist.
      */ 
    public static function getServer($key) 
    {
        if (isset($_SERVER[$key])) 
        {
            return $_SERVER[$key];
        } 
        else 
        {
            return null;
        }
    }

    /**
     *Get a value from the $_FILES superglobal array.
     *
     *@param string $key The key of the value to retrieve.
     *@return mixed The value associated with the key, or null if the key does not exist.
     */ 
    public static function getPhoto($key): ?array {

        $max_size=300000;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // verify if an image has been uploaded without errors
            if (isset($_FILES[$key]) && $_FILES[$key]['error'] == UPLOAD_ERR_OK) {

                // Get photo info
                $fileTmpPath = $_FILES[$key]['tmp_name'];
                $fileName = $_FILES[$key]['name'];
                $fileSize = $_FILES[$key]['size'];
                $fileNameCmps = explode(".", $fileName);
                $fileExtension = strtolower(end($fileNameCmps));
        
                // Verify file extension
                $allowedfileExtensions = array('jpg', 'gif', 'png', 'jpeg');
                if (in_array($fileExtension, $allowedfileExtensions) ) {

                    $img = file_get_contents($fileTmpPath);
                    $result = array();
                    $result['type'] = $_FILES[$key]['type'];
                    $result['img'] = $img;
                    return $result;
                    
                } else {

                    return null;
                }
            } else {

                return null;
            }
        } else return null;
    }
    /**
     * Method getCookie
     * 
     * Get a value from the $_COOKIE superglobal array.
     * @param string $key
     * @return string
     */
    public static function getCookie(string $key):string
    {
        if (isset($_COOKIE[$key])) 
        {
            return $_COOKIE[$key];
        } 
        else 
        {
            return null;
        }
    }
    
    

}
