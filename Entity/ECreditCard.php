<?php

/**
 * ECreditCard
 * 
 * This class depicts a Credit Card
 * 
 * @author Matteo Maloni <matteo.maloni.00@gmail.com>
 * @package Entity
 */
class ECreditCard
{    
    /**
     * number
     *
     * @var int
     */
    private $number;    
    /**
     * name
     *
     * @var string
     */
    private $name;    
    /**
     * surname
     *
     * @var string
     */
    private $surname;    
    /**
     * expiry
     *
     * @var string
     */
    private $expiry;    
    /**
     * cvv
     *
     * @var int
     */
    private $cvv;    
    /**
     * studentID
     *
     * @var int
     */
    private $studentID;
    
    /**
     * __construct
     *
     * @param  int $number
     * @param  string $name
     * @param  string $surname
     * @param  string $expiry
     * @param  int $cvv
     * @param  int $studentID
     * @return void
     */
    public function __construct(int $number,string $name,string $surname,string $expiry, int $cvv, int $studentID)
    {
        $this->number=$number;
        $this->name=$name;
        $this->surname=$surname;
        $this->expiry=$expiry;
        $this->cvv=$cvv;
        $this->studentID=$studentID;
    }

    //GET methods
        
    /**
     * getNumber
     *
     * @return int
     */
    public function getNumber():int
    {
        return $this->number;
    }    
    /**
     * getName
     *
     * @return string
     */
    public function getName():string
    {
        return $this->name;
    }    
    /**
     * getSuranem
     *
     * @return string
     */
    public function getSuranem():string
    {
        return $this->surname;
    }    
    /**
     * getExpiry
     *
     * @return string
     */
    public function getExpiry():string
    {
        return $this->expiry;
    }    
    /**
     * getCVV
     *
     * @return int
     */
    public function getCVV():int
    {
        return $this->cvv;
    }    
    /**
     * getStudentID
     *
     * @return int
     */
    public function getStudentID():int
    {
        return $this->studentID;
    }

    //SET methods
    
    /**
     * setNumber
     *
     * @param  int $number
     * @return void
     */
    public function setNumber(int $number):void
    {
        $this->number=$number;
    }    
    /**
     * setName
     *
     * @param  string $name
     * @return void
     */
    public function setName(string $name):void
    {
        $this->name=$name;
    }    
    /**
     * setSuranem
     *
     * @param  string $surname
     * @return void
     */
    public function setSuranem(string $surname):void
    {
        $this->surname=$surname;
    }    
    /**
     * setExpiry
     *
     * @param  string $expiry
     * @return void
     */
    public function setExpiry(string $expiry):void
    {
        $this->expiry=$expiry;
    }    
    /**
     * setCVV
     *
     * @param  int $CVV
     * @return void
     */
    public function setCVV(int $CVV):void
    {
        $this->cvv=$CVV;
    }    
    /**
     * setStudentID
     *
     * @param  int $studentID
     * @return void
     */
    public function setStudentID(int $studentID):void
    {
        $this->studentID=$studentID;
    }
    
    /**
     * __toString
     *
     * @return string
     */
    public function __toString():string
    {
        return 'NUMBER:'.$this->number.'  NAME:'.$this->name.'  SURNAME:'.$this->surname.'  EXPIRY:'.$this->expiry.'  CVV:'.$this->cvv.'  STUDENT_ID:'.$this->studentID;
    }
}