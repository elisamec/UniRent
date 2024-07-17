<?php 

namespace Classes\Control;

use Classes\Foundation\FPersistentManager;

require __DIR__.'/../../vendor/autoload.php';

class CAccommodation
{
    public static function deactivate($idAccommodation) {
        $PM= FPersistentManager::getInstance();
        $accommodation=$PM->load('EAccommodation', $idAccommodation);
        $accommodation->setStatus(false);
        $res=$PM->update($accommodation);
        $requestUri = trim($_SERVER['HTTP_REFERER'], '/');
        $uriParts = explode('/', $requestUri);
        print_r($uriParts);
        if ($res) {
            header('Location:/UniRent/'.$uriParts[4].'/'.$uriParts[5].'/'.$accommodation->getIdAccommodation());
        } else {
            http_response_code(500);
        }
    }

    public static function activate($idAccommodation) {
        $PM= FPersistentManager::getInstance();
        $accommodation=$PM->load('EAccommodation', $idAccommodation);
        $accommodation->setStatus(true);
        $res=$PM->update($accommodation);
        $requestUri = trim($_SERVER['HTTP_REFERER'], '/');
        $uriParts = explode('/', $requestUri);
        print_r($uriParts);
        if ($res) {
            header('Location:/UniRent/'.$uriParts[4].'/'.$uriParts[5].'/'.$accommodation->getIdAccommodation());
        } else {
            http_response_code(500);
        }
    }
}