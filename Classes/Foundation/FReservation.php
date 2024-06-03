<?php 

class FReservation
{
    private $instance=null;

    public static function getInstance():FReservation
    {
        if(is_null(self::$instance))
        {
            self::$instance= new FReservation();
        }
        return self::$instance;
    }

    public function exist(int $id):bool
    {
        
    }
}