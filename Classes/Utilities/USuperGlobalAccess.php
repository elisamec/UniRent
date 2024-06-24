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
 * @author Matteo Maloni (UniRent) <matteo.maloni@student.univaq.it>
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
        if (isset($_POST[$key])) 
        {
            return $_POST[$key];
        } 
        else 
        {
            return null;
        }
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
      *@param string $key The key of the value to retrieve.
      *@return mixed The value associated with the key, or null if the key does not exist.
      */ 
      public static function getFiles($key) 
      { 
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Verifica se il campo del file esiste e se è stato caricato un file
            if (isset($_FILES['img']) && $_FILES['img']['error'] == UPLOAD_ERR_OK) {
                // Un file è stato caricato
                $fileTmpPath = $_FILES['img']['tmp_name'];
                $fileName = $_FILES['img']['name'];
                $fileSize = $_FILES['img']['size'];
                $fileType = $_FILES['img']['type'];
                $fileNameCmps = explode(".", $fileName);
                $fileExtension = strtolower(end($fileNameCmps));
        
                // Verifica l'estensione del file
                /*$allowedfileExtensions = array('jpg', 'gif', 'png', 'jpeg');
                if (in_array($fileExtension, $allowedfileExtensions)) {
                    // Carica il file
                    $uploadFileDir = './uploaded_files/';
                    $dest_path = $uploadFileDir . $fileName;
        
                    if(move_uploaded_file($fileTmpPath, $dest_path)) {
                        $message ='File is successfully uploaded.';
                    } else {
                        $message = 'There was some error moving the file to upload directory. Please make sure the upload directory is writable by web server.';
                    }
                } else {
                    $message = 'Upload failed. Allowed file types: ' . implode(',', $allowedfileExtensions);
                }*/
            } else {
                // Nessun file caricato
                return null;
            }
        } else return null;
      }

}
