<?php  
namespace Classes\Entity;
require __DIR__.'/../../vendor/autoload.php';
require_once('../Tools/TStatus.php');
use Classes\Entity\EReservation;
use DateTime;

/**
 * EContract
 * @author Matteo Maloni (UNiRent) <matteo.maloni@student.univaq.it>
 * @package Entity
 */
class EContract extends EReservation
{    
    /**
     * status
     *
     * @var TStatusContract $status
     */
    private TStatusContract $status;    
    /**
     * paymentDate
     *
     * @var DateTime $paymentDate
     */
    private DateTime $paymentDate;    
    /**
     * cardNumber
     *
     * @var int $cardNumber
     */
    private int $cardNumber;
    
    /**
     * __construct
     *
     * @param  TStatusContract $status
     * @param  string $cardNumber
     * @param  EReservation $reserv
     * @param  DateTime $payment
     * @return self
     */
    public function __construct(TStatusContract $status, string $cardNumber, EReservation $reserv, DateTime $payment)
    {
        parent::__construct($reserv->getFromDate(),$reserv->getToDate(),$reserv->getAccomodationId(),$reserv->getIdStudent());
        $this->status=$status;
        $this->cardNumber=$cardNumber;
        $this->paymentDate=$payment;
    }
    
    /**
     * getStatus
     *
     * @return TStatusContract
     */
    public function getStatus():TStatusContract
    {
        return $this->status;
    }    
    /**
     * getPaymentDate
     *
     * @return DateTime
     */
    public function getPaymentDate():DateTime
    {
        return $this->paymentDate;
    }    
    /**
     * getCardNumber
     *
     * @return string
     */
    public function getCardNumber():string
    {
        return $this->cardNumber;
    }
    
    /**
     * setContractStatus
     *
     * @param  TStatusContract $status
     * @return void
     */
    public function setContractStatus(TStatusContract $status):void
    {
        $this->status=$status;
    }    
    /**
     * setCardNumber
     *
     * @param  int $number
     * @return void
     */
    public function setCardNumber(string $number):void
    {
        $this->cardNumber=$number;
    }
}