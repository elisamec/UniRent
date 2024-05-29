<?php
require_once('../Tools/TType.php');
require_once('../Tools/TRequestType.php');
require_once('../Tools/TStatus.php');

class ESupportRequest {
    private ?int $id;
    private string $message;
    private TRequestType $topic;
    private int $idAuthor;
    private TType $authorType;
    private TStatusSupport $status=TStatusSupport::WAITING;
    private static $entity = ESupportRequest::class;

    public function getEntity():string {
        return $this->entity;
    }
    public function __construct(?int $id, string $message, TRequestType $topic, int $idAuthor, TType $authorType) 
    {
        $this->id=$id;
        $this->message=$message;
        $this->topic=$topic;
        $this->idAuthor=$idAuthor;
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
}