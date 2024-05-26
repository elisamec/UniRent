<?php

class EVisit
{
    private int $idVisit;
    private DateTime $date;
    private int $idStudent;
    private int $idAccommodation;

    private static $entity = EVisit::class;

    public function __construct(int $idVisit, String $date, int $idStudent, int $idAccommodation){
        $this->idVisit = $idVisit;
        $this->date = new DateTime($date);
        $this->idStudent = $idStudent;
        $this->idAccommodation = $idAccommodation;
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

    public function __toString():string
    {
        $day = $this->date->format('Y-m-d H:i:s');
        return "ID: $this->idVisit  DATE: $day  STUDENT: $this->idStudent  ACCOMMODATION: $this->idAccommodation";
    }



}