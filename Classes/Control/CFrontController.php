<?php

namespace Classes\Control;

/**
 * Front controller class
 *
 * This class is responsible for routing the request to the appropriate controller
 * 
 * @package Classes\Control
 *
 */
class CFrontController{
    
    /**
     * Run the front controller
     * 
     * @param string $requestUri The request URI
     * 
     */
    public function run($requestUri){
        

        $requestUri = trim($requestUri, '/');
        $uriParts = explode('/', $requestUri);

        array_shift($uriParts);

        // Extract controller and method names
        $controllerName = !empty($uriParts[0]) ? ucfirst($uriParts[0]) : 'User';
        $methodName = !empty($uriParts[1]) ? $uriParts[1] : 'login';

        // Load the controller class
        $controllerClass = 'C' . $controllerName;
        $controllerFile = __DIR__ . "/$controllerClass.php";


        if (file_exists($controllerFile)) {
            require_once $controllerFile;

            // Check if the method exists in the controller
            if (method_exists("Classes\Control\\".$controllerClass, $methodName)) {

                // Call the method
                $params = array_slice($uriParts, 2); // Get optional parameters
                call_user_func_array(["Classes\Control\\".$controllerClass, $methodName], $params);

            } else {
                // Method not found, handle appropriately (e.g., show 404 page)
                header('Location: /UniRent/Student/home');
            }
        } else {
            // Controller not found, handle appropriately (e.g., show 404 page)
            header('Location: /UniRent/Student/home');
        }
    }
}