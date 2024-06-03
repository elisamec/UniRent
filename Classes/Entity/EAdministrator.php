<?php 

namespace Classes\Entity;

/**
 * EAdministrator
 * @author Matteo Maloni (UniRent) <matteo.maloni.00@gmail.com>
 * @package Entity
 */
class EAdministrator
{
    private ?int $id=null;
    private string $username;
    private string $password;
    private string $email;
    
    /**
     * __construct
     *
     * @param  string $username
     * @param  string $password
     * @param  string $email
     * @return void
     */
    public function __construct(string $username,string $password,string $email)
    {
        $this->username=$username;
        $this->password=password_get_info($password)['algoName'] !== PASSWORD_DEFAULT ? password_hash($password, PASSWORD_DEFAULT) : $password;
        $this->email=$email;
    }    
    /**
     * getID
     *
     * @return int
     */
    public function getID():?int
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
     * getEmail
     *
     * @return string
     */
    public function getEmail():string
    {
        return $this->email;
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
     * setID
     *
     * @param  int $ID
     * @return void
     */
    public function setID(int $ID):void
    {
        $this->id=$ID;
    }    
    /**
     * setPassword
     *
     * @param  string $psw
     * @return void
     */
    public function setPassword(string $password):void
    {
        $this->password=password_hash($password, PASSWORD_DEFAULT);
    }    
    /**
     * setUsername
     *
     * @param  string $user
     * @return void
     */
    public function setUsername(string $user):void
    {
        $this->username=$user;
    }    
    /**
     * setEmail
     *
     * @param  string $mail
     * @return void
     */
    public function setEmail(string $mail):void
    {
        $this->email=$mail;
    }

    public function __toString():string
    {
        return 'USERNAME:'.$this->username.' EMAIL:'.$this->email;
    }
}

