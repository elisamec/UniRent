<?php 

/**
 * EReservation
 * @author Matteo Maloni (UniRent) <matteo.maloni@student.univaq.it>
 * @package Foundation
 */
class EReservation
{    
    /**
     * id
     *
     * @var ?int $id
     */
private ?int $id=null;    
    /**
     * fromDate
     *
     * @var DateTime $fromDate
     */
    private DateTime $fromDate;    
    /**
     * toData
     *
     * @var DateTime $toData
     */
    private  DateTime $toData;    
    /**
     * made
     *
     * @var DateTime $made
     */
    private  DateTime $made;    
    /**
     * statusAccept
     *
     * @var bool $statusAccept
     */
    private  bool $statusAccept=false;    
    /**
     * accomodationId
     *
     * @var int $accomodationId
     */
    private  int $accomodationId;    
    /**
     * idStudent
     *
     * @var int $idStudent
     */
    private int $idStudent;
    
    /**
     * __construct
     *
     * @param  mixed $from
     * @param  mixed $to
     * @param  mixed $accomodationId
     * @param  mixed $idStudent
     * @return void
     */
    public function __construct(DateTime $from, DateTime $to, int $accomodationId, int $idStudent)
    {
        $this->fromDate=$from;
        $this->toData=$$to;
        $this->accomodationId=$accomodationId;
        $this->idStudent=$idStudent;
        $this->made=new DateTime('now');
    }
    
    /**
     * getID
     *
     * @return ?int
     */
    public function getID():?int
    {
        return $this->id;
    }    
    /**
     * getFromDate
     *
     * @return DateTime
     */
    public function getFromDate():DateTime
    {
        return $this->fromDate;
    }
    
    /**
     * getToDate
     *
     * @return DateTime
     */
    public function getToDate():DateTime
    {
        return $this->toData;
    }    
    /**
     * getMade
     *
     * @return DateTime
     */
    public function getMade():DateTime
    {
        return $this->made;
    }    
    /**
     * getStatusAccept
     *
     * @return bool
     */
    public function getStatusAccept():bool
    {
        return $this->statusAccept;
    }    
    /**
     * getAccomodationId
     *
     * @return int
     */
    public function getAccomodationId():int
    {
        return $this->accomodationId;
    }    
    /**
     * getIdStudent
     *
     * @return int
     */
    public function getIdStudent():int
    {
        return $this->idStudent;
    }
     
    /**
     * setID
     *
     * @param  int $id
     * @return void
     */
    public function setID(int $id):void
    {
       $this->id=$id;
    }    
    /**
     * setFromDate
     *
     * @param  DateTime $d
     * @return void
     */
    public function setFromDate(DateTime $d):void
    {
        $this->fromDate=$d;
    }    
    /**
     * setToDate
     *
     * @param  DateTime $d
     * @return void
     */
    public function setToDate(DateTime $d):void
    {
        $this->toData=$d;
    }    
    /**
     * setMade
     *
     * @param  DateTime $d
     * @return void
     */
    public function setMade(DateTime $d):void
    {
        $this->made=$d;
    }    
    /**
     * setStatus
     *
     * @param  bool $b
     * @return void
     */
    public function setStatus(bool $b):void
    {
        $this->statusAccept=$b;
    }    
    /**
     * setAccomodationId
     *
     * @param  ?int $id
     * @return void
     */
    public function setAccomodationId(?int $id):void
    {
        $this->accomodationId=$id;
    }    
    /**
     * setIdStudent
     *
     * @param  ?int $id
     * @return void
     */
    public function setIdStudent(?int $id):void
    {
        $this->idStudent=$id;
    }
}