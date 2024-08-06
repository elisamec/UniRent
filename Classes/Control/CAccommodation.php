<?php 

namespace Classes\Control;

use Classes\Foundation\FPersistentManager;
use Classes\View\VError;

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
            if (self::checkURL($_COOKIE['current_page'], 'success')) {
                header('Location:'.$_COOKIE['current_page']);
            } else {
                header('Location:'.$_COOKIE['current_page'].'/success');
            }
        } else {
            if (self::checkURL($_COOKIE['current_page'], 'error')) {
                header('Location:'.$_COOKIE['current_page']);
            } else {
                header('Location:'.$_COOKIE['current_page'].'/success');
            }
        }
    }
    private static function checkURL(string $url, string $val):bool {
        $currentPage = isset($url) ? $url : '';

        // Step 2: Parse the URL to get the path
        $parsedUrl = parse_url($currentPage);
        $path = isset($parsedUrl['path']) ? $parsedUrl['path'] : '';

        // Step 3: Split the path into segments
        $pathSegments = explode('/', trim($path, '/'));

        // Step 4: Check if the last segment is 'success'
        $lastSegment = end($pathSegments);
        if ($lastSegment === $val) {
            return true;
        }
        return false;
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
            if (self::checkURL($_COOKIE['current_page'], 'success')) {
                header('Location:'.$_COOKIE['current_page']);
            } else {
                header('Location:'.$_COOKIE['current_page'].'/success');
            }
        } else {
            if (self::checkURL($_COOKIE['current_page'], 'error')) {
                header('Location:'.$_COOKIE['current_page']);
            } else {
                header('Location:'.$_COOKIE['current_page'].'/success');
            }
        }
    }
    public static function delete(int $id) {
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