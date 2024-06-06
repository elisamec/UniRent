<?php  

require_once('EReservation.php');
require_once('../Tools/TStatus.php');
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
     * @param  int $cardNumber
     * @param  EReservation $reserv
     * @param  DateTime $payment
     * @return self
     */
    public function __construct(TStatusContract $status, int $cardNumber, EReservation $reserv, DateTime $payment)
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
     * @return int
     */
    public function getCardNumber():int
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
    public function setCardNumber(int $number):void
    {
        $this->cardNumber=$number;
    }
}