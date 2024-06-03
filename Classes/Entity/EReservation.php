<?php 

class EReservation
{
    private ?int $id=null;
    private DateTime $fromDate;
    private DateTime $toData;
    private DateTime $made;
    private bool $statusAccept=false;
    private int $accomodationId;
    private int $idStudent;

    public function __construct(DateTime $from, DateTime $to, int $accomodationId, int $idStudent)
    {
        $this->fromDate=$from;
        $this->toData=$$to;
        $this->accomodationId=$accomodationId;
        $this->idStudent=$idStudent;
        $this->made=new DateTime('now');
    }

    public function getID():?int
    {
        return $this->id;
    }
    public function getFromDate():DateTime
    {
        return $this->fromDate;
    }

    public function getToDate():DateTime
    {
        return $this->toData;
    }
    public function getMade():DateTime
    {
        return $this->made;
    }
    public function getStatusAccept():bool
    {
        return $this->statusAccept;
    }
    public function getAccomodationId():int
    {
        return $this->accomodationId;
    }
    public function getIdStudent():int
    {
        return $this->idStudent;
    }
 
    public function setID(int $id):void
    {
       $this->id=$id;
    }
    public function setFromDate(DateTime $d):void
    {
        $this->fromDate=$d;
    }
    public function setToDate(DateTime $d):void
    {
        $this->toData=$d;
    }
    public function setMade(DateTime $d):void
    {
        $this->made=$d;
    }
    public function setStatus(bool $b):void
    {
        $this->statusAccept=$b;
    }
    public function setAccomodationId(?int $id):void
    {
        $this->accomodationId=$id;
    }
    public function setIdStudent(?int $id):void
    {
        $this->idStudent=$id;
    }
}