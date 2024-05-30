<?php 
require_once ('EPhoto.php');
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
    private int $id;    
    /**
     * username
     *
     * @var string
     */
    private string $username;    
    /**
     * password
     *
     * @var string
     */    
    private string $password;    
    /**
     * name
     *
     * @var string
     */
    private string $name;    
    /**
     * surname
     *
     * @var string
     */
    private string $surname;    
    /**
     * picture 
     *
     * @var EPhoto
     */
    private EPhoto|null $picture;    
    /**
     * universityMail
     *
     * @var string
     */
    private string $universityMail;    
    /**
     * courseDuration
     *
     * @var int
     */
    private int $courseDuration;    
    /**
     * immatricolationYear
     *
     * @var int
     */
    private int $immatricolationYear;    
    /**
     * birthDate
     *
     * @var DateTime
     */
    private DateTime $birthDate;    
    /**
     * sex
     *
     * @var string
     */
    private string $sex;    
    /**
     * smoker
     *
     * @var bool
     */
    private bool $smoker;    
    /**
     * animals
     *
     * @var bool
     */
    private bool $animals;
    private static $entity =EStudent::class;
    
    /**
     * __construct
     *
     * @param  int $id
     * @param  string $username
     * @param  string $password
     * @param  string $name
     * @param  string $surname
     * @param  ?EPhoto $picture
     * @param  string $universityMail
     * @param  int $courseDuration
     * @param  int $immatricolationYear
     * @param  DateTime $birthDate
     * @param  string $sex
     * @param  bool $smoker
     * @param  bool $animals
     * @return self
     */
    public function __construct(string $username, string $password, string $name, string $surname, EPhoto|null $picture, string $universityMail, int $courseDuration, int $immatricolationYear, DateTime $birthDate, string $sex, bool $smoker, bool $animals)
    {
        $this->username=$username;
        $this->password=password_get_info($password)['algoName'] !== PASSWORD_DEFAULT ? password_hash($password, PASSWORD_DEFAULT) : $password;
        $this->name=$name;
        $this->surname=$surname;
        $this->picture=$picture;
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
     * getEntity
     *
     * @return string
     */
    public function getEntity():string
    {
        return $this->entity;
    }
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
     * @return EPhoto|null
     */
    public function getPicture():?EPhoto
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
        $this->password=password_hash($password, PASSWORD_DEFAULT);
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
     * @param  int $picture
     * @return void
     */
    public function setPicture(EPhoto|null $picture)
    {
        $this->picture=$picture;
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
        $result='ID:'.(string)$this->id.' USERNAME:'.$this->username.' PASSWORD:'.$this->password.' NAME:'.$this->name.' SURNAME:'.$this->surname.' UNIVERSITY_MAIL:'.$this->universityMail.' COURSE_DURATION:'.(string)$this->courseDuration;
        $result.=' IMMATRICOLATION_YEAR:'.(string)$this->immatricolationYear.' BIRTH_DATE:'.$this->birthDate->format('d/m/Y').' SEX:'.$this->sex.' SMOKER:'.(string)$this->smoker.' ANIMALS:'.(string)$this->animals.' PICTURE: '.$this->getPicture();
        return $result;
    }


}
