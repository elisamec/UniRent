<?php

namespace Classes\Entity;


use DateTime;

/**
 * Class EVisit
 * Entity class for Visit
 *
 * @package Entity
 */
class EVisit
{
    /**
     * @var ?int $idVisit The unique identifier of the visit itself.
     * @var DateTime $date The date of the visit.
     * @var int $idStudent The identifier of the student who made the visit.
     * @var int $idAccommodation The identifier of the accommodation visited.
     */
    private ?int $idVisit;
    private DateTime $date;
    private int $idStudent;
    private int $idAccommodation;

    /**
     * @var string $entity 
     */
    private static $entity = EVisit::class;

    /**
     * __construct
     * Constructor for the EVisit class
     * @var int $idVisit The unique identifier of the visit itself.
     * @var DateTime $date The date of the visit.
     * @var int $idStudent The identifier of the student who made the visit.
     * @var int $idAccommodation The identifier of the accommodation visited.
     * 
     */
    public function __construct(?int $idVisit, DateTime $date, int $idStudent, int $idAccommodation){
        $this->idVisit = $idVisit;
        $this->date = $date;
        $this->idStudent = $idStudent;
        $this->idAccommodation = $idAccommodation;
    }

    /**
     * getEntity
     * Returns the entity class
     * @return string
     */
    public function getEntity():string {
        return $this->entity;
    }

    /**
     * getIdVisit
     * Returns the id of the visit
     * @return int
     */
    public function getIdVisit():int {
        return $this->idVisit;
    }

    /**
     * getDate
     * Returns the date of the visit
     * @return DateTime
     */
    public function getDate():DateTime{
        return $this->date;
    }

    /**
     * getIdStudent
     * Returns the id of the student who made the visit
     * @return int
     */
    public function getIdStudent():int {
        return $this->idStudent;
    }

    /**
     * getIdAccommodation
     * Returns the id of the accommodation visited
     * @return int
     */
    public function getIdAccommodation():int {
        return $this->idAccommodation;
    }

    /**
     * setIdVisit
     * Sets the id of the visit
     * @param int $idVisit
     * @return void
     */
    public function setIdVisit(int $idVisit) {
        $this->idVisit = $idVisit;
    }

    /**
     * setDate
     * Sets the date of the visit
     * @param DateTime $date
     * @return void
     */
    public function setDate(DateTime $date) {
        $this->date = $date;
    }

    /**
     * setIdStudent
     * Sets the id of the student who made the visit
     * @param int $idStudent
     * @return void
     */
    public function setIdStudent(int $idStudent) {
        $this->idStudent = $idStudent;
    }

    /**
     * setIdAccommodation
     * Sets the id of the accommodation visited
     * @param int $idAccommodation
     * @return void
     */
    public function setIdAccommodation(int $idAccommodation) {
        $this->idAccommodation = $idAccommodation;
    }

    /**
     * __toString
     * Returns a string that represents the visit
     * @return string
     */
    public function __toString():string
    {
        $day = $this->date->format('Y-m-d H:i:s');
        return "ID: $this->idVisit  DATE: $day  STUDENT: $this->idStudent  ACCOMMODATION: $this->idAccommodation";
    }

}