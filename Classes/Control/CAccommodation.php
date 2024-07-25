<?php 

namespace Classes\Control;

use Classes\Foundation\FPersistentManager;
use Classes\View\VError;

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
            $viewError= new VError();
            $viewError->error(500);
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
            $viewError= new VError();
$viewError->error(500);
        }
    }
    public static function delete(int $id) {
        $PM=FPersistentManager::getInstance();
        $result=$PM->delete('EAccommodation', $id);
        if($result)
        {
            header('Location:/UniRent/Owner/home');
        }
        else
        {
            $viewError= new VError();
$viewError->error(500);
        }
    }
}