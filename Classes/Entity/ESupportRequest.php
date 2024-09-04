<?php
namespace Classes\Entity;
require __DIR__ . '../../../vendor/autoload.php';
use Classes\Tools\TType;
use Classes\Tools\TStatusSupport;
use Classes\Tools\TRequestType;

/**
 * ESupportRequest
 * 
 * This class depicts a Support Request
 * 
 * @package Entity
 */

class ESupportRequest {
    /**
     * 
     * @var ?int $id the id of the support request
     * @var string $message the message of the support request
     * @var TRequestType $topic the topic of the support request
     * @var ?int $idAuthor the id of the author of the support request
     * @var TType $authorType the type of the author of the support request
     * @var ?string $supportReply the reply of the support request
     * @var bool $statusRead if the support request has been read
     * @var TStatusSupport $status the status of the support request
     * @var $entity the class
     */
    private ?int $id;
    private string $message;
    private TRequestType $topic;
    private ?int $idAuthor;
    private ?TType $authorType;
    private ?string $supportReply=null;
    private bool $statusRead=false;
    private TStatusSupport $status=TStatusSupport::WAITING;
    private static $entity = ESupportRequest::class;

    /**
     * getEntity
     * 
     * This method is used to get the entity of the support request
     * @return string
     */
    public function getEntity():string {
        return $this->entity;
    }
    /**
     * __construct
     * 
     * @param ?int $id
     * @param string $message
     * @param TRequestType|string $topic
     * @param ?int $idAuthor
     * @param string|null|TType $authorType
     */
    public function __construct(?int $id, string $message, string | TRequestType $topic, ?int $idAuthor, string | null| TType $authorType) 
    {
        $this->id=$id;
        $this->message=$message;
        if (is_string($topic)) {
            $topic = TRequestType::from($topic);
        }
        $this->topic=$topic;
        $this->idAuthor=$idAuthor;
        if (is_string($authorType)) {
            $authorType = TType::from(strtolower($authorType));
        }
        $this->authorType=$authorType;
    }
    /**
     * getId
     * 
     * This method is used to get the id of the support request
     * @return int
     */
    public function getId(): ?int {
        return $this->id;
    }
    /**
     * getMessage
     * 
     * This method is used to get the message of the support request
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
    /**
     * getTopic
     * 
     * This method is used to get the topic of the support request
     * @return TRequestType
     */
    public function getTopic():TRequestType 
    {
        return $this->topic;
    }
    /**
     * getAuthorID
     * 
     * This method is used to get the id of the author of the support request
     * @return int
     */
    public function getAuthorID(): ?int
    {
        return $this->idAuthor;
    }
    /**
     * getAuthorType
     * 
     * This method is used to get the type of the author of the support request
     * @return TType
     */
    public function getAuthorType(): ?TType
    {
        return $this->authorType;
    }
    /**
     * getStatus
     * 
     * This method is used to get the status of the support request
     * @return TStatusSupport
     */
    public function getStatus(): TStatusSupport
    {
        return $this->status;
    }
    /**
     * getSupportReply
     * 
     * This method is used to get the reply of the support request
     * @return string|null
     */
    public function getSupportReply(): ?string
    {
        return $this->supportReply;
    }
    /**
     * getStatusRead
     * 
     * This method is used to get if the support request has been read
     * @return bool
     */
    public function getStatusRead(): bool
    {
        return $this->statusRead;
    }
    /**
     * setId
     * 
     * This method is used to set the id of the support request
     * @param int $id
     */
    public function setId(int $id): void {
        $this->id=$id;
    }
    /**
     * setMessage
     * 
     * This method is used to set the message of the support request
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message=$message;
    }
    /**
     * setTopic
     * 
     * This method is used to set the topic of the support request
     * @param TRequestType $topic
     */
    public function setTopic(TRequestType $topic):void 
    {
        $this->topic=$topic;
    }
    /**
     * setAuthorID
     * 
     * This method is used to set the id of the author of the support request
     * @param int $idAuthor
     */
    public function setAuthorID(int $idAuthor): void
    {
        $this->idAuthor=$idAuthor;
    }
    /**
     * setAuthorType
     * 
     * This method is used to set the type of the author of the support request
     * @param TType $authorType
     */
    public function setAuthorType(TType $authorType): void
    {
        $this->authorType=$authorType;
    }
    /**
     * setStatus
     * 
     * This method is used to set the status of the support request
     * @param TStatusSupport $status
     */
    public function setStatus(TStatusSupport $status): void
    {
        $this->status=$status;
    }
    /**
     * setSupportReply
     * 
     * This method is used to set the reply of the support request
     * @param string $supportReply
     */
    public function setSupportReply(?string $supportReply): void
    {
        $this->supportReply=$supportReply;
    }
    /**
     * setStatusRead
     * 
     * This method is used to set if the support request has been read
     * @param bool $statusRead
     */
    public function setStatusRead(bool $statusRead): void
    {
        $this->statusRead=$statusRead;
    }
}