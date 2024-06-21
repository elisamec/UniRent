<?php

namespace Classes\Control;

class CFrontController{
    
    public function run($requestUri){
        // Parse the request URI
        // print $requestUri;
        

        $requestUri = trim($requestUri, '/');
        $uriParts = explode('/', $requestUri);

        array_shift($uriParts);
        // var_dump($uriParts);

        // Extract controller and method names
        $controllerName = !empty($uriParts[0]) ? ucfirst($uriParts[0]) : 'User';
        // var_dump($controllerName);
        $methodName = !empty($uriParts[1]) ? $uriParts[1] : 'login';
        var_dump($methodName);

        // Load the controller class
        $controllerClass = 'C' . $controllerName;
        // var_dump($controllerClass);
        $controllerFile = __DIR__ . "/$controllerClass.php";
        // var_dump($controllerFile);


        if (file_exists($controllerFile)) {
            require_once $controllerFile;

            // Check if the method exists in the controller
            if (method_exists("Classes\Control\\".$controllerClass, $methodName)) {
                // Call the method
                $params = array_slice($uriParts, 2); // Get optional parameters
                var_dump($params);
                call_user_func_array(["Classes\Control\\".$controllerClass, $methodName], $params);
            } else {
                // Method not found, handle appropriately (e.g., show 404 page)
                header('Location: /UniRent/CStudent/home');
            }
        } else {
            // Controller not found, handle appropriately (e.g., show 404 page)
            header('Location: /UniRent/CStudent/home');
        }
    }
}