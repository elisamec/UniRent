<?php
namespace Classes\Entity;
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
     * @var string
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
     * main
     *
     * @var bool
     */
    private $main;
    
    /**
     * title
     *
     * @var string
     */
    private $title;
    private static $entity =ECreditCard::class;
    
    /**
     * __construct
     *
     * @param  string $number
     * @param  string $name
     * @param  string $surname
     * @param  string $expiry
     * @param  int $cvv
     * @param  int $studentID
     * @param  bool $main
     * @return void
     */
    public function __construct(string $number,string $name,string $surname,string $expiry, int $cvv, int $studentID, bool $main, string $title)
    {
        $this->number=$number;
        $this->name=$name;
        $this->surname=$surname;
        $this->expiry=$expiry;
        $this->cvv=$cvv;
        $this->studentID=$studentID;
        $this->main=$main;
        $this->title=$title;
    }

    //GET methods
        
    /**
     * getEntity
     *
     * @return string
     */
    public function getEntity():string 
    {
        return $this->entity;
    }
    /**
     * getNumber
     *
     * @return int
     */
    public function getNumber():string
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
    public function getSurname():string
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
    
    /**
     * Method getMain
     *
     * @return bool
     */
    public function getMain():bool
    {
        return $this->main;
    }
    
    /**
     * Method getTitle
     *
     * @return string
     */
    public function getTitle():string
    {
        return $this->title;
    }

    //SET methods
    
    /**
     * setNumber
     *
     * @param  int $number
     * @return void
     */
    public function setNumber(string $number):void
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
    public function setSurname(string $surname):void
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
     * Method setMain
     *
     * @param bool $x [if is the main credit card]
     *
     * @return void
     */
    public function setMain(bool $x):void
    {
        $this->main=$x;
    }
    
    /**
     * Method setTitle
     *
     * @param string $title [title of the card]
     *
     * @return void
     */
    public function setTitle(string $title):void
    {
        $this->title=$title;
    }
    
    /**
     * __toString
     *
     * @return string
     */
    public function __toString():string
    {
        return 'TITLE:'.$this->title.'  NUMBER:'.$this->number.'  NAME:'.$this->name.'  SURNAME:'.$this->surname.'  EXPIRY:'.$this->expiry.'  CVV:'.$this->cvv.'  STUDENT_ID:'.$this->studentID;
    }
    
}