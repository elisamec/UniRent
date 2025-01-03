<?php
namespace Classes\Entity;

require __DIR__ . '/../../vendor/autoload.php';

use Classes\Foundation\FPersistentManager;
use CommerceGuys\Addressing\Address;
use DateTime;

/**
 * Class EAccommodation
 * Entity class for Accommodation
 * 
 * @package Entity
 *
 */
class EAccommodation
{   
    /**
     * @var int $idAccommodation The unique identifier of the accommodation itself. 
     * @var array $photo The photos of the accommodation.
     * @var string $title The title of the accommodation.
     * @var Address $address The address of the accommodation.
     * @var float $price The price of the accommodation.
     * @var DateTime $start The start date of the accommodation.
     * @var string $description The description of the accommodation.
     * @var int $places The number of places of the accommodation.
     * @var float $deposit The deposit of the accommodation.
     * @var array $visit The avaiability for visit.
     * @var int $visitDuration The duration of the visit in minutes.
     * @var bool $man If only mens are required is true.
     * @var bool $woman If only womens are required is true.
     * @var bool $pets If pets are allowed is true.
     * @var bool $smokers If smokers are allowed is true.
     * @var bool $status If the accommodation is active is true, else if the accommodation is desabilited is false.
     * @var int $idOwner The identifier of the owner of the accommodation.
     */
    private ?int $idAccommodation;
    private array $photo;
    private string $title;
    private Address $address;
    private float $price;
    private DateTime $start;    
    private String $description;
    private int $places;
    private ?float $deposit;
    private array $visit;
    private int $visitDuration;
    private bool $man;
    private bool $woman;
    private bool $pets;
    private bool $smokers;
    private bool $status;
    private int $idOwner;

    /**
     * @var string $entity 
     */
    private static $entity = EAccommodation::class;

    /**
     * __construct
     * Constructor for the EAccommodation class
     * @var int $idAccommodation The unique identifier of the accommodation itself.
     * @var array $photo The photos of the accommodation.
     * @var string $title The title of the accommodation.  
     * @var Address $address The address of the accommodation.
     * @var float $price The price of the accommodation.
     * @var DateTime $start The start date of the accommodation.
     * @var string $description The description of the accommodation.
     * @var int $places The number of places of the accommodation.
     * @var float $deposit The deposit of the accommodation.
     * @var array $visit The avaiability for visit.
     * @var int $visitDuration The duration of the visit in minutes.
     * @var bool $man If only mens are required is true.
     * @var bool $woman If only womens are required is true.
     * @var bool $pets If pets are allowed is true.
     * @var bool $smokers If smokers are allowed is true.
     * @var bool $status If the accommodation is active is true, else if the accommodation is desabilited is false.
     * @var int $idOwner The identifier of the owner of the accommodation.
     */
    public function __construct(?int $idAccommodation, array $photo, string $title, Address $address, float $price,
                                DateTime $start, ?String $description, int $places, ?float $deposit, array $visit, int $visitDuration, 
                                bool $man, bool $woman, bool $pets, bool $smokers, bool $status, int $idOwner){

        $this->idAccommodation = $idAccommodation;
        $this->photo = $photo;
        $this->title = $title;
        $this->address = $address;
        $this->price = $price;
        $this->start = $start;
        $this->description = $description;
        $this->places = $places;
        $this->deposit = $deposit;
        $this->visit = $visit;
        $this->visitDuration = $visitDuration;
        $this->man = $man;
        $this->woman = $woman;
        $this->pets = $pets;
        $this->smokers = $smokers;
        $this->status = $status;
        $this->idOwner = $idOwner;
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
     * getIdAccommodation
     * Returns the id of the accommodation
     * @return int
     */
    public function getIdAccommodation(): ?int{
        return $this->idAccommodation;
    }

    /**
     * getPhoto
     * Returns the photos of the accommodation
     * @return array
     */
    public function getPhoto(): array{
        return $this->photo;
    }

    /**
     * getTitle
     * Returns the title of the accommodation
     * @return string
     */
    public function getTitle(): string{
        return $this->title;
    }   

    /**
     * getAddress
     * Returns the address of the accommodation
     * @return Address
     */
    public function getAddress(): Address{
        return $this->address;
    }

    /**
     * getPrice
     * Returns the price of the accommodation
     * @return float
     */
    public function getPrice(): float{
        return $this->price;
    }

    /**
     * getStart
     * Returns the start date of the accommodation
     * @return DateTime
     */
    public function getStart(): DateTime{
        return $this->start;
    }

    /**
     * getDescription
     * Returns the description of the accommodation
     * @return string
     */
    public function getDescription(): ?String{
        return $this->description;
    }   

    /**
     * getPlaces
     * Returns the number of places of the accommodation
     * @return int
     */
    public function getPlaces(): int{
        return $this->places;   
    }

    /**
     * getDeposit
     * Returns the deposit of the accommodation
     * @return float
     */
    public function getDeposit(): ?float{
        return $this->deposit;
    }   

    /**
     * getVisit
     * Returns the availability for visit
     * @return array
     */
    public function getVisit(): array{
        return $this->visit;
    }

    /**
     * getAverageRating
     * Returns the average rating of the accommodation
     * @return float
     */
    public function getAverageRating() {
        $PM = FPersistentManager::getInstance();
        return $PM->getAccommodationRating($this->idAccommodation);
    }

    /**
     * getVisitDuration
     * Returns the duration of the visit in minutes
     * @return int
     */ 
    public function getVisitDuration(): int{
        return $this->visitDuration;
    }

    /**
     * getMan
     * Returns true if only mens are required
     * @return bool
     */
    public function getMan(): bool{
        return $this->man;
        
    }

    /**
     * getWoman
     * Returns true if only womens are required
     * @return bool
     */
    public function getWoman(): bool{
        return $this->woman;
    }

    /**
     * getPets
     * Returns true if pets are allowed
     * @return bool
     */
    public function getPets(): bool{
        return $this->pets;
    }

    /**
     * getSmokers
     * Returns true if smokers are allowed
     * @return bool
     */
    public function getSmokers(): bool{
        return $this->smokers;
    }

    /**
     * getStatus
     * Returns true if accommodation is active, else if it's disabilited the function returns false
     * @return bool
     */
    public function getStatus(): bool{
        return $this->status;
    }

    /**
     * getIdOwner
     * Returns the id of the owner of the accommodation
     * @return int
     */
    public function getIdOwner(): int{
        return $this->idOwner;
    }
    
    /**
     * setIdAccommodation   
     * Sets the id of the accommodation
     * @param int $idAccommodation
     * @return void
     */
    public function setIdAccommodation(int $idAccommodation): void{
        $this->idAccommodation = $idAccommodation;
    }

    /**
     * setPhoto
     * Sets the photos of the accommodation
     * @param array $photo
     * @return void
     */
    public function setPhoto(array $photo): void{
        $this->photo = $photo;
    }

    /**
     * setTitle
     * Sets the title of the accommodation
     * @param string $title
     * @return void
     */
    public function setTitle(string $title): void{
        $this->title = $title;
    }

    /**
     * setAddress
     * Sets the address of the accommodation
     * @param Address $address
     * @return void
     */
    public function setAddress(Address $address): void{
        $this->address = $address;
    }

    /**
     * setPrice
     * Sets the price of the accommodation
     * @param float $price
     * @return void
     */
    public function setPrice(float $price): void{
        $this->price = $price;
    }

    /**
     * setStart
     * Sets the start date of the accommodation
     * @param DateTime $start
     * @return void
     */
    public function setStart(DateTime $start): void{
        $this->start = $start;
    }

    /**
     * setDescription
     * Sets the description of the accommodation
     * @param string $description
     * @return void
     */
    public function setDescription(String $description): void{
        $this->description = $description;
    }

    /**
     * setPlaces
     * Sets the number of places of the accommodation
     * @param int $places
     * @return void
     */
    public function setPlaces(int $places): void{
        $this->places = $places;
        
    }

    /**
     * setDeposit
     * Sets the deposit of the accommodation
     * @param float $deposit
     * @return void
     */
    public function setDeposit(float $deposit): void{
        $this->deposit = $deposit;
    }

    /**
     * setVisit
     * Sets the availability for visit
     * @param array $visit
     * @return void
     */
    public function setVisit(array $visit): void{
        $this->visit = $visit;
    }

    /**
     * setVisitDuration
     * Sets the duration of the visit in minutes
     * @param int $visitDuration
     * @return void
     */ 
    public function setVisitDuration(int $visitDuration): void{
        $this->visitDuration = $visitDuration;
    }

    /**
     * setMan
     * Sets true if only mens are required
     * @param bool $man
     * @return void
     */
    public function setMan(bool $man): void{
        $this->man = $man;
    }

    /**
     * setWoman 
     * Sets true if only womens are required
     * @param bool $woman
     * @return void
     */
    public function setWoman(bool $woman): void{
        $this->woman = $woman;
    }

    /**
     * setPets
     * Sets true if pets are allowed
     * @param bool $pets
     * @return void
     */
    public function setPets(bool $pets): void{
        $this->pets = $pets;
    }       

    /**
     * setSmokers
     * Sets true if smokers are allowed
     * @param bool $smokers
     * @return void
     */
    public function setSmokers(bool $smokers): void{
        $this->smokers = $smokers;
    }

    /**
     * setStatus
     * Sets true if accommodation is active, else if it's disabilited the function sets false
     * @param bool $status
     * @return void
     */
    public function setStatus(bool $status): void{
        $this->status = $status;
    }

    /**
     * setIdOwner
     * Sets the id of the owner of the accommodation
     * @param int $idOwner
     * @return void
     */
    public function setIdOwner(int $idOwner): void{
        $this->idOwner = $idOwner;
    }

    /**
     * __toString
     * Returns a string that represents the accommodation 
     * @return string
     */
    public function __toString():string{
        $address = $this->address->getSortingCode() . ", " . $this->address->getAddressLine1() . ", " . $this->address->getLocality() . ", " . $this->address->getPostalCode();
        $start = $this->start->format('Y-m-d');
        $str = "ID: $this->idAccommodation  \n" . "Photos: \n";
        foreach($this->photo as $photo){
            $str = $str . "$photo  \n";
        }
        $str = $str . "Title: $this->title  \n".
            "Address: $address  \n".
            "Price: $this->price  \n".
            "Start: $start  \n".
            "Description: $this->description  \n".
            "Places: $this->places  \n".
            "Deposit: $this->deposit  \n" .
            "Visit:  ";
        $visit = $this->visit;
        $days = array_keys($visit);
        foreach($days as $day){
            $str = $str . "\n    $day:  ";
            foreach ($visit[$day] as $time){
            $str = $str . " $time  ";
            }
        }
        $str = $str . "\nVisit Duration: $this->visitDuration  \n".
            "Man: $this->man \n".
            "Woman: $this->woman \n".
            "Pets: $this->pets \n".
            "Smokers: $this->smokers \n".
            "Status: $this->status \n".
            "ID Owner: $this->idOwner \n";
        return $str;
    }
    
    /**
     * Method fromJsonToArrayOfVisit
     * 
     * return an array of visit from JSON file created by JS in visitAvailabilities
     * @param $json
     *
     * @return array
     */
    public static function fromJsonToArrayOfVisit($json):array
    {
        $myarray=json_decode($json,true);
        $result=self::resultOne($myarray);
        $result_2=self::resultTwo($result);
        $final_result=array();
        foreach($result_2 as $key=>$value)
        {
            if(in_array($value['day'],array_keys($final_result)))
            {
                foreach($value['times'] as $time)
                {
                    if(in_array($time,$final_result[$value['day']])){}
                    else
                    {
                        $final_result[$value['day']][]=$time;
                    }
                }
            }
            else
            {
                $final_result[$value['day']]=$value['times'];
            }
        }
        return $final_result;
    }

    /**
     * Method resultOne
     * 
     * return the result of the first part of the method fromJsonToArrayOfVisit
     * @param array $myarray
     *
     * @return array
     */
    private static function resultOne(array $myarray):array {
        $result=array();
        foreach($myarray as $key=>$value)
        {
            $start=EAccommodation::stringInMinutes($value['start']);
            $end=EAccommodation::stringInMinutes($value['end']);
            if($start>=$end) //i will not consider negative period
            {
                $value['diff']=-1;
            }
            else
            {
                $difference=date_diff(date_create($value['start']),date_create($value['end']));
                $totalMinutes = $difference->h * 60 + $difference->i;
                $value['diff']=(int)$totalMinutes;
            }
            if($value['diff']<=0){continue;}
            else
            {
                if(empty($value['duration']) || !is_numeric($value['duration']) || (int)$value['duration'] <= 0)
                {
                    continue; // Go to the next iteration if duration is not valid
                }
                else
                {
                    $value['number_of_visits']=(int)($value['diff']/(int)$value['duration']);
                    $result[$key]=$value;
                }
            }  
        }
        return $result;
    }
    /**
     * Method resultTwo
     * 
     * return the result of the second part of the method fromJsonToArrayOfVisit
     * @param array $result
     * @return array
     */
    private static function resultTwo(array $result):array
    {
        $result_2=array();
        foreach($result as $key=>$value)
        {
            if($value['number_of_visits']<=0){}   //if the visit's number is 0 (Or if time or duration is wrong)
            else
            {
                $start_in_minutes=EAccommodation::stringInMinutes($value['start']); //Change time in minutes until midnight
                $ap=array(); #array di appoggio
                for($i=0;$i<$value['number_of_visits'];$i++)
                {
                    $y=$start_in_minutes+ $i*$value['duration']; //add the visits intervals
                    $ap[]=EAccommodation::mitutesToString($y); #
                }
                $app=array(); 
                $app['day']=$value['day']; 
                $app['times']=$ap;
                $result_2[$key]=$app; 
            }    
        }
        return $result_2;
    }
    
    /**
     * Method DurationOfVisit
     *
     * return the duration of a visit from JSON file created by JS in visitAvailabilities
     * @param $json 
     *
     * @return ?int
     */
    public static function DurationOfVisit($json):?int
    {
        $myarray=json_decode($json,true);
        $result=array();
        
        foreach($myarray as $key=>$value)
        {
            $result[]=(int)$value['duration'];
        }
        if(count($result)>0) 
        {
            return $result[0];
        }
        else
        {
            return null;
        }
    }
    
    /**
     * Method stringInMinutes
     * 
     * trasforms a string in minutes
     * @param string $s
     * @return int
     */
    private static function stringInMinutes(string $s):int
    {
        list($hour,$minute)=explode(":",$s);
        $tot_minutes = $hour*60 + $minute;
        return (int)$tot_minutes;
    }

    /**
     * Method mitutesToString
     * 
     * trasforms minutes in string
     * @param int $min
     * @return string
     */
    private static function mitutesToString(int $min):string
    {
        $hours = intdiv($min, 60); // Ottiene le ore
        $minutes = $min % 60; // Ottiene i minuti residui
        // Format string like hh:mm
        return sprintf("%02d:%02d", $hours, $minutes);
    }

    /**
     * Method getRating
     * 
     * this method is used to return the rating of the owner and the accommodation
     *
     * @return array
     */
    public function getRating():array
    {
        $PM=FPersistentManager::getInstance();
        $result=array();
        $result['owner']=$PM->getOwnerRating($this->idOwner);
        $result['accommodation']=$PM->getAccommodationRating($this->idAccommodation);
        return $result;
    }
    
    /**
     * Method getData
     * 
     * this method is used to return this object as an array
     *
     * @return array
     */
    public function getData():array
    {
        $accommodationData = [
            'title' => $this->getTitle(),
            'price' => $this->getPrice(),
            'deposit' =>$this->getDeposit(),
            'date' => $this->getStart(),
            'startDate' => $this->getStart()->format('d'),
            'month' => $this->getStart()->format('M') == 9 ? 'september' : 'october',
            'address' => $this->getAddress()->getAddressLine1(),
            'city' => $this->getAddress()->getLocality(),
            'postalCode' => $this->getAddress()->getPostalCode(),
            'description' => $this->getDescription(),
            'men' => $this->getMan(),
            'women' => $this->getWoman(),
            'animals' => $this->getPets(),
            'smokers' => $this->getSmokers(),
            'places' => $this->getPlaces()
        ];
        return $accommodationData;
    }
    
    /**
     * Method getVisitAvailabilityData
     *
     * this method return an arrya of accommodation'visit availabilities
     * @return array
     */
    public function getVisitAvailabilityData():array
    {
        $visitAvailabilityData=array();
        foreach ($this->getVisit() as $day =>$times) 
        {
            if ($times !== []) 
            {
                $endTime= new DateTime($times[count($times)-1]);
                $endTime->modify('+' . $this->getVisitDuration() . ' minutes');
                $visitAvailabilityData[$day] = [
                    'start' => $times[0],
                    'end' => $endTime->format('H:i'),
                    'duration' => $this->getVisitDuration()
                ];
            }
        }
        return $visitAvailabilityData;
    }
}