<?php 

namespace Classes\Control;

require __DIR__.'/../../vendor/autoload.php';

class CAccommodation
{
    public static function readJsonVisit($json_file)
    {
        $json = file_get_contents($json_file);
        $data = json_decode($json, true);
        print $data;
    }

    public static function callToReadJsonVisit($json_file)
    {
        header('Content-Type: application/json');
        header('Location: UniRent/Accommodation/readJsonVisit/$json_file');
    }
}