<?php
class FReview {
    private static $table="review";
    private static $values="(NULL, :Title,:valutation, :description, :photo)";
    private static $key="id";
    public static function getTable(){
        return self::$table;
    }

    public static function getValue(){
        return self::$values;
    }

    public static function getClass(){
        return self::class;
    }

    public static function getKey(){
        return self::$key;
    }

    //public static function createReviewOdj($queryResult) {} SERVONO LE ENTITY PER QUESTO
}