<?php
namespace Classes\Utilities;
require __DIR__ . '/../../vendor/autoload.php';

use DateTime;

class USort {
    /**
     * Method to sort an array of objects
     * @param array $array
     * @param string $kind
     * @return array
     */
    public static function sortArray(array $array, string $kind): array
    {
        switch($kind) {
            case 'contract':
                $method = 'compareContracts';
                break;
            case 'reservation':
                $method = 'compareReservations';
                break;
            case 'visitByStartTime':
                $method = 'compareVisitsByStartTime';
                break;
            case 'visitByDate':
                $method = 'compareVisitsByDate';
                break;
            case 'report':
                $method = 'compareReports';
                break;
            default:
                return $array;
        }
        usort($array, [self::class, $method]);
        return $array;
    }

    /**
     * Method to sort contracts by the number of days from today
     * @param object $a
     * @param object $b
     * @return int
     */
    private static function compareContracts($a, $b): int
    {
        $today = new DateTime();
        $fromDateA = $a->getFromDate();
        $fromDateB = $b->getFromDate();
        
        $diffA = $today->diff($fromDateA)->days;
        $diffB = $today->diff($fromDateB)->days;
        
        return $diffA - $diffB;
    }
    /**
     * Method to sort reservations by the date they were made
     * @param object $a
     * @param object $b
     * @return int
     */
    private static function compareReservations($a, $b): int
    {
        $dateA = $a->getMade();
        $dateB = $b->getMade();
                
        return $dateA <=> $dateB;
    }
    /**
     * Method to sort visits by the start time
     * @param object $a
     * @param object $b
     * @return int
     */
    private static function compareVisitsByStartTime($a, $b): int {
        $startTimeA = substr($a['time'], 0, 5); // Extract the start time (HH:MM)
        $startTimeB = substr($b['time'], 0, 5); // Extract the start time (HH:MM)
        return strcmp($startTimeA, $startTimeB);
    }
    /**
     * Method to sort visits by the date
     * @param object $a
     * @param object $b
     * @return int
     */
    private static function compareVisitsByDate($a, $b) {
        $dateA = sprintf('%04d-%02d-%02d', $a['year'], $a['month'], $a['day']);
        $dateB = sprintf('%04d-%02d-%02d', $b['year'], $b['month'], $b['day']);
        return strcmp($dateA, $dateB);
    }
    /**
     * Method to sort reports by the date they were made
     * @param object $a
     * @param object $b
     * @return int
     */
    private static function compareReports($a, $b) {
        $ad = new DateTime($a['Report']->getBanDate());
        $bd = new DateTime($b['Report']->getBanDate());
    
        // Compare dates: newest first
        return $bd <=> $ad; // Use spaceship operator for clean comparison
    }

}