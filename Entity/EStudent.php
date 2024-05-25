<?php 

/**
 * EStudent
 * @author Matteo Maloni (UniRent) <matteo.maloni.00@gmail.com>
 * @package Foundation
 * @
 */
class EStudent
{    
    /**
     * id
     *
     * @var int
     */
    private $id;    
    /**
     * username
     *
     * @var string
     */
    private $username;    
    /**
     * password
     *
     * @var string
     */    
    private $password;    
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
     * picture ID
     *
     * @var int
     */
    private $picture;    
    /**
     * universityMail
     *
     * @var string
     */
    private $universityMail;    
    /**
     * courseDuration
     *
     * @var int
     */
    private $courseDuration;    
    /**
     * immatricolationYear
     *
     * @var int
     */
    private $immatricolationYear;    
    /**
     * birthDate
     *
     * @var DateTime
     */
    private $birthDate;    
    /**
     * sex
     *
     * @var string
     */
    private $sex;    
    /**
     * smoker
     *
     * @var bool
     */
    private $smoker;    
    /**
     * animals
     *
     * @var bool
     */
    private $animals;
    
    /**
     * __construct
     *
     * @param  int $id
     * @param  string $username
     * @param  string $password
     * @param  string $name
     * @param  string $surname
     * @param  int $pictureID
     * @param  string $universityMail
     * @param  int $courseDuration
     * @param  int $immatricolationYear
     * @param  DateTime $birthDate
     * @param  string $sex
     * @param  bool $smoker
     * @param  bool $animals
     * @return self
     */
    public function __construct(int $id, string $username, string $password, string $name, string $surname, int $pictureID, string $universityMail, int $courseDuration, int $immatricolationYear, DateTime $birthDate, bool $sex, bool $smoker, bool $animals)
    {
        $this->id=$id;
        $this->username=$username;
        $this->password=$password;
        $this->name=$name;
        $this->surname=$surname;
        $this->picture=$pictureID;
        $this->universityMail=$universityMail;
        $this->courseDuration=$courseDuration;
        $this->immatricolationYear=$immatricolationYear;
        $this->birthDate=$birthDate;
        $this->sex=$sex;
        $this->smoker=$smoker;
        $this->animals=$animals;
    }

    // GET methods
    
    /**
     * getID
     *
     * @return int
     */
    public function getID():int
    {
        return $this->id;
    }    
    /**
     * getUsername
     *
     * @return string
     */
    public function getUsername():string
    {
        return $this->username;
    }    
    /**
     * getPassword
     *
     * @return string
     */
    public function getPassword():string
    {
        return $this->password;
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
     * getSurname
     *
     * @return string
     */
    public function getSurname():string
    {
        return $this->surname;
    }    
    /**
     * getPicture
     *
     * @return int
     */
    public function getPicture():int
    {
        return $this->picture;
    }    
    /**
     * getUniversityMail
     *
     * @return string
     */
    public function getUniversityMail():string
    {
        return $this->universityMail;
    }    
    /**
     * getCourseDuration
     *
     * @return int
     */
    public function getCourseDuration():int
    {
        return $this->courseDuration;
    }    
    /**
     * getImmatricolationYear
     *
     * @return int
     */
    public function getImmatricolationYear():int
    {
        return $this->immatricolationYear;
    }    
    /**
     * getBirthDate
     *
     * @return DateTime
     */
    public function getBirthDate():DateTime
    {
        return $this->birthDate;
    }    
    /**
     * getSex
     *
     * @return string
     */
    public function getSex():string
    {
        return $this->sex;
    }    
    /**
     * getSmoker
     *
     * @return bool
     */
    public function getSmoker():bool
    {
        return $this->smoker;
    }    
    /**
     * getAnimals
     *
     * @return bool
     */
    public function getAnimals():bool
    {
        return $this->animals;
    }

    //SET methods
    
    /**
     * setID
     *
     * @param  int $id
     * @return void
     */
    public function setID(int $id)
    {
        $this->id=$id;
    }    
    /**
     * setUsername
     *
     * @param  string $username
     * @return void
     */
    public function setUsername(string $username)
    {
        $this->username=$username;
    }    
    /**
     * setPassword
     *
     * @param  string $password
     * @return void
     */
    public function setPassword(string $password)
    {
        $this->password=$password;
    }    
    /**
     * setName
     *
     * @param  string $name
     * @return void
     */
    public function setName(string $name)
    {
        $this->name=$name;
    }    
    /**
     * setSurname
     *
     * @param  string $surname
     * @return void
     */
    public function setSurname(string $surname)
    {
        $this->surname=$surname;
    }    
    /**
     * setPicture
     *
     * @param  int $pictureID
     * @return void
     */
    public function setPicture(int $pictureID)
    {
        $this->picture=$pictureID;
    }    
    /**
     * setUniversityMail
     *
     * @param  string $email
     * @return void
     */
    public function setUniversityMail(string $email)
    {
        $this->universityMail=$email;
    }    
    /**
     * setCourseDuration
     *
     * @param  int $duration
     * @return void
     */
    public function setCourseDuration(int $duration)
    {
        $this->courseDuration=$duration;
    }    
    /**
     * setImmatricolationYear
     *
     * @param  int $year
     * @return void
     */
    public function setImmatricolationYear(int $year)
    {
        $this->immatricolationYear=$year;
    }    
    /**
     * setBirthDate
     *
     * @param  DateTime $birth
     * @return void
     */
    public function setBirthDate(DateTime $birth)
    {
        $this->birthDate=$birth;
    }    
    /**
     * setSex
     *
     * @param  string $sex
     * @return void
     */
    public function setSex(string $sex)
    {
        $this->sex=$sex;
    }    
    /**
     * setSmoker
     *
     * @param  bool $smoker
     * @return void
     */
    public function setSmoker(bool $smoker)
    {
        $this->smoker=$smoker;
    }    
    /**
     * setAnimals
     *
     * @param  bool $animals
     * @return void
     */
    public function setAnimals(bool $animals)
    {
        $this->animals=$animals;
    }

    // toSting method
        
    /**
     * __toString
     *
     * @return string
     */
    public function __toString():string
    {
        $result='ID:'.(string)$this->id.' USERNAME:'.$this->username.' PASSWORD:'.$this->password.' NAME:'.$this->name.' SURNAME:'.$this->surname.' PICTURE_ID:'.(string)$this->picture.' UNIVERSITY_MAIL:'.$this->universityMail.' COURSE_DURATION:'.(string)$this->courseDuration;
        $result.=' IMMATRICOLATION_YEAR:'.(string)$this->immatricolationYear.' BIRTH_DATE:'.$this->birthDate->format('d/m/Y').' SEX:'.$this->sex.' SMOKER:'.(string)$this->smoker.' ANIMALS:'.(string)$this->animals;
        return $result;
    }


}
