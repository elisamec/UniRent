<?php
namespace Classes\Utilities;
require __DIR__ . '/../../vendor/autoload.php';

use DateTime;

class USort {
    // Method to sort contracts by the difference in days from today
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

    // Private method to compare two contracts
    private static function compareContracts($a, $b): int
    {
        $today = new DateTime();
        $fromDateA = $a->getFromDate();
        $fromDateB = $b->getFromDate();
        
        $diffA = $today->diff($fromDateA)->days;
        $diffB = $today->diff($fromDateB)->days;
        
        return $diffA - $diffB;
    }
    private static function compareReservations($a, $b): int
    {
        $dateA = $a->getMade();
        $dateB = $b->getMade();
                
        return $dateA <=> $dateB;
    }
    private static function compareVisitsByStartTime($a, $b): int {
        $startTimeA = substr($a['time'], 0, 5); // Extract the start time (HH:MM)
        $startTimeB = substr($b['time'], 0, 5); // Extract the start time (HH:MM)
        return strcmp($startTimeA, $startTimeB);
    }
    private static function compareVisitsByDate($a, $b) {
        $dateA = sprintf('%04d-%02d-%02d', $a['year'], $a['month'], $a['day']);
        $dateB = sprintf('%04d-%02d-%02d', $b['year'], $b['month'], $b['day']);
        return strcmp($dateA, $dateB);
    }
    private static function compareReports($a, $b) {
        $ad = new DateTime($a['Report']->getBanDate());
        $bd = new DateTime($b['Report']->getBanDate());
    
        // Compare dates: newest first
        return $bd <=> $ad; // Use spaceship operator for clean comparison
    }

}