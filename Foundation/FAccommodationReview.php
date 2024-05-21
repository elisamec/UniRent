<?php
class FAccommodationReview {
    private static $table = "accommodationreview";
    private static $value = "(:idReview, :idAccommodation, :idAuthor)";


    public static function getTable(){
        return self::$table;
    }

    public static function getValue(){
        return self::$value;
    }

    public static function getClass(){
        return self::class;
    }
    
}