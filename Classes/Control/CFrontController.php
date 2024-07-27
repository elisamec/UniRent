<?php

namespace Classes\Control;

use Classes\Foundation\FPersistentManager;
use Classes\Tools\TStatusUser;
use Classes\Utilities\USession;
use Classes\View\VError;

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
        $session = USession::getInstance();
        if ($session::getSessionElement('id') != null) {
            $PM = FPersistentManager::getInstance();
            $userType = $session::getSessionElement('userType');
            $user = $PM->load('E' . $userType, $session::getSessionElement('id'));
            if ($user->getStatus() == TStatusUser::BANNED) {
                $viewError = new VError();
                $viewError->error(600);
                return;
            }
        }

        array_shift($uriParts);

        // Extract controller and method names
        $controllerName = !empty($uriParts[0]) ? ucfirst($uriParts[0]) : 'User';
        $methodName = !empty($uriParts[1]) ? $uriParts[1] : 'home';
        

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
                header('Location: /UniRent/User/home');
            }
        } else {
            // Controller not found, handle appropriately (e.g., show 404 page)
            header('Location: /UniRent/User/home');
        }
    }
}