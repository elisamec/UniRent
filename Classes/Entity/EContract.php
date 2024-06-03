<?php  

require_once('EReservation.php');
require_once('../Tools/TStatus.php');
class EContract extends EReservation
{
    private TStatusContract $status;
    private DateTime $paymentDate;
    private int $cardNumber;

    public function __construct(TStatusContract $status, int $cardNumber, EReservation $reserv)
    {
        parent::__construct($reserv->getFromDate(),$reserv->getToDate(),$reserv->getAccomodationId(),$reserv->getIdStudent());
        $this->status=$status;
        $this->cardNumber=$cardNumber;
        $this->paymentDate=new DateTime('now');
    }

    public function getStatus():TStatusContract
    {
        return $this->status;
    }
    public function getPaymentDate():DateTime
    {
        return $this->paymentDate;
    }
    public function getCardNumber():int
    {
        return $this->cardNumber;
    }

    public function setContractStatus(TStatusContract $status):void
    {
        $this->status=$status;
    }
    public function setCardNumber(int $number):void
    {
        $this->cardNumber=$number;
    }
}