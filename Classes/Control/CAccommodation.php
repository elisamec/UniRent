<?php 

namespace Classes\Control;

use Classes\Foundation\FPersistentManager;
use Classes\Utilities\USuperGlobalAccess;

require __DIR__.'/../../vendor/autoload.php';

class CAccommodation
{
    /**
     * Deactivate Accommodation
     * @param int $idAccommodation
     * @return void
     */
    public static function deactivate(int $idAccommodation):void {
        $PM= FPersistentManager::getInstance();
        $accommodation=$PM->load('EAccommodation', $idAccommodation);
        $accommodation->setStatus(false);
        $res=$PM->update($accommodation);
        if ($res) {
            
                header('Location:'.USuperGlobalAccess::getCookie('current_page').'/success');
        } else {
            
                header('Location:'.USuperGlobalAccess::getCookie('current_page').'/error');
        }
    }
    
    /**
     * Activate Accommodation
     * @param int $idAccommodation
     * @return void
     */
    public static function activate(int $idAccommodation):void {
        $PM= FPersistentManager::getInstance();
        $accommodation=$PM->load('EAccommodation', $idAccommodation);
        $accommodation->setStatus(true);
        $res=$PM->update($accommodation);
        if ($res) {
                header('Location:'.USuperGlobalAccess::getCookie('current_page').'/success');
            
        } else {
            
                header('Location:'.USuperGlobalAccess::getCookie('current_page').'/error');
        }
    }

    /**
     * Deletes the accommodation
     * @param int $id of the accommodation
     * @return void
     */
    public static function delete(int $id):void {
        $PM=FPersistentManager::getInstance();
        $result=$PM->delete('EAccommodation', $id);
        if($result)
        {
            header('Location:/UniRent/Owner/home/success');
        }
        else
        {
            header('Location:/UniRent/Owner/home/error');
        }
    }
}