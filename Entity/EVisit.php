<?php

class EVisit
{
    private int $idVisit;
    private DateTime $date;
    private int $idStudent;
    private int $idAccommodation;

    private static $entity = EVisit::class;

    public function __construct(int $idVisit, DateTime $date, int $idStudent, int $idAccommodation){
        $this->idVisit=$idVisit;
        $this->date=$date;
        $this->idStudent=$idStudent;
        $this->idAccommodation=$idAccommodation;
    }

    public function getEntity():string {
        return $this->entity;
    }

    public function getIdVisit():int {
        return $this->idVisit;
    }

    public function getDate():DateTime{
        return $this->date;
    }

    public function getIdStudent():int {
        return $this->idStudent;
    }

    public function getIdAccommodation():int {
        return $this->idAccommodation;
    }

    public function setDate(DateTime $date) {
        $this->date = $date;
    }



}