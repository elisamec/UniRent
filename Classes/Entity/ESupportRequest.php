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
    private ?int $id;
    private string $message;
    private TRequestType $topic;
    private ?int $idAuthor;
    private ?TType $authorType;
    private ?string $supportReply=null;
    private bool $statusRead=false;
    private TStatusSupport $status=TStatusSupport::WAITING;
    private static $entity = ESupportRequest::class;

    public function getEntity():string {
        return $this->entity;
    }

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
    public function getId(): int {
        return $this->id;
    }
    public function getMessage(): string
    {
        return $this->message;
    }
    public function getTopic():TRequestType 
    {
        return $this->topic;
    }
    public function getAuthorID(): int
    {
        return $this->idAuthor;
    }
    public function getAuthorType(): TType
    {
        return $this->authorType;
    }
    public function getStatus(): TStatusSupport
    {
        return $this->status;
    }
    public function getSupportReply(): ?string
    {
        return $this->supportReply;
    }
    public function getStatusRead(): bool
    {
        return $this->statusRead;
    }
    public function setId(int $id): void {
        $this->id=$id;
    }
    public function setMessage(string $message): void
    {
        $this->message=$message;
    }
    public function setTopic(TRequestType $topic):void 
    {
        $this->topic=$topic;
    }
    public function setAuthorID(int $idAuthor): void
    {
        $this->idAuthor=$idAuthor;
    }
    public function setAuthorType(TType $authorType): void
    {
        $this->authorType=$authorType;
    }
    public function setStatus(TStatusSupport $status): void
    {
        $this->status=$status;
    }
    public function setSupportReply(string $supportReply): void
    {
        $this->supportReply=$supportReply;
    }
    public function setStatusRead(bool $statusRead): void
    {
        $this->statusRead=$statusRead;
    }
}