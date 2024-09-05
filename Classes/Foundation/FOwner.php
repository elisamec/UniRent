<?php 
namespace Classes\Foundation;
require __DIR__.'../../../vendor/autoload.php';
use Classes\Foundation\FConnection;
use Classes\Entity\EOwner;
use Classes\Entity\EPhoto;
use Classes\Foundation\FPhoto;
use Classes\Tools\TError;
use Classes\Tools\TStatusUser;
use PDO;
use PDOException;
use PDORow;
/**
 * This class provide to make query to EOwner class
 * @author Matteo Maloni ('UniRent')
 * @package Foundation
 */

 class FOwner
 {
    /**static attribute that contains the instance of the class */
    private static $instance=null;
    /**Constructor */
    private function __construct()
    {}
    /**This static method gives the istance of this singleton class
     * @return  
    */
    public static function getInstance()
    {
        if(is_null(self::$instance))
        {
            self::$instance = new FOwner();
        }
        return self::$instance;
    }

    public function exist(int $id):bool 
    {
        $db=FConnection::getInstance()->getConnection();
        try
        {
            $db->exec('LOCK TABLES owner READ');
            $db->beginTransaction();
            $q='SELECT * FROM owner WHERE id=:id';
            $stm=$db->prepare($q);
            $stm->bindparam(':id', $id, PDO::PARAM_INT);
            $stm->execute();
            $db->commit();
            
        }
        catch (PDOException $e) 
        {
            $db->rollBack();
        }
        $db->exec('UNLOCK TABLES');
        $result=$stm->rowCount();

        if ($result >0)
        {
            return true;
        }
        return false;

    }
    
    public function load(int $id): EOwner {
        $db=FConnection::getInstance()->getConnection();
        try
        {
            $db->exec('LOCK TABLES owner READ');
            $db->beginTransaction();
            $q='SELECT * FROM owner WHERE id=:id';
            $stm=$db->prepare($q);
            $stm->bindparam(':id', $id, PDO::PARAM_INT);
            $stm->execute();
            $db->commit();
            $db->exec('UNLOCK TABLES');
        }
        catch (PDOException $e) 
        {
            $db->rollBack();
        }
        $row=$stm->fetch(PDO::FETCH_ASSOC);
        $photoID=$row['picture'];
        $photo= ($photoID!==null) ? FPhoto::getInstance()->loadAvatar($photoID) : null;
        $result=new EOwner($row['id'],$row['username'], $row['password'], $row['name'], $row['surname'], $photo, $row['email'], $row['phoneNumber'], $row['iban'], $row['status']);
        return $result;
    }

    public function store(EOwner $owner):bool {
        $db=FConnection::getInstance()->getConnection();
        try
        {   
            if ($owner->getPhoto()!== null) {
                $storePicture = FPhoto::getInstance()->storeAvatar($owner->getPhoto());
                if ($storePicture===false){
                    return false;
                }
            }
            $db->exec('LOCK TABLES owner WRITE');
            $db->beginTransaction();
            $q='INSERT INTO owner (username, password, name, surname, picture, email, phonenumber, iban, status)';
            $q=$q.'VALUES (:user, :pass, :name, :surname, :picture, :email, :phone, :iban, :status)';
            $stm = $db->prepare($q);
            $stm->bindValue(':user', $owner->getUsername(), PDO::PARAM_STR);
            $stm->bindValue(':pass', $owner->getPassword(), PDO::PARAM_STR);
            $stm->bindValue(':name', $owner->getName(), PDO::PARAM_STR);
            $stm->bindValue(':surname', $owner->getSurname(), PDO::PARAM_STR);
            if ($owner->getPhoto()!== null) {
                $stm->bindValue(':picture', $owner->getPhoto()->getId(), PDO::PARAM_INT);
            } else {
                $stm->bindValue(':picture', null, PDO::PARAM_NULL);
            }
            $stm->bindValue(':email', $owner->getMail(), PDO::PARAM_STR);
            $stm->bindValue(':phone', $owner->getPhoneNumber(), PDO::PARAM_STR);
            $stm->bindValue(':iban', $owner->getIBAN(), PDO::PARAM_STR);    
            $stm->bindValue(':status', $owner->getStatus()->value, PDO::PARAM_STR);
            $stm->execute();
            $id=$db->lastInsertId();
            $db->commit();
            $db->exec('UNLOCK TABLES');
            $owner->setID($id);
            return true;
            
        }
        catch (PDOException $e) {
            $db->rollBack();
            $errorType = TError::getInstance()->handleDuplicateError($e);
            if ($errorType) {
                echo "Error: " . $errorType . "\n"; //quando faremo view leghiamolo a view
            } else {
                echo "An unexpected error occurred: " . $e->getMessage() . "\n";
            }
            return false;
        }
    }
    public static function update(EOwner $owner):bool {
        $db=FConnection::getInstance()->getConnection();
        //if (FOwner::getInstance()->exist($owner->getId())) {
        try
        { 
            $currentPhotoID= FOwner::currentPhoto($owner->getId());
            #potrebbe esserci un modo migliore per farlo
            $FPh=FPhoto::getInstance();
            if ($owner->getPhoto()!==null) {
                $update=$FPh->update($owner->getPhoto());
            } elseif ($currentPhotoID!==null and $owner->getPhoto()!==null) {
                $update = $FPh->delete($owner->getPhoto()->getId());
            }

            else
            {
                $update=true;
            }

            if ($update===false) {
                return false;
            }
            $db->exec('LOCK TABLES owner WRITE');
            //$db->beginTransaction();
            $q='UPDATE owner SET username = :user, password = :pass, name = :name, surname = :surname, picture = :picture, email = :email, phonenumber = :phone, iban = :iban, status = :status WHERE id = :id';
            $stm = $db->prepare($q);
            $stm->bindValue(':id', $owner->getId(), PDO::PARAM_INT);
            $stm->bindValue(':user', $owner->getUsername(), PDO::PARAM_STR);
            $stm->bindValue(':pass', $owner->getPassword(), PDO::PARAM_STR);
            $stm->bindValue(':name', $owner->getName(), PDO::PARAM_STR);
            $stm->bindValue(':surname', $owner->getSurname(), PDO::PARAM_STR);
            if ($owner->getPhoto()!==null) {
                $stm->bindValue(':picture', $owner->getPhoto()->getId(), PDO::PARAM_INT);
            } else {
                $stm->bindValue(':picture', null, PDO::PARAM_NULL);;
            }
            $stm->bindValue(':email', $owner->getMail(), PDO::PARAM_STR);
            $stm->bindValue(':phone', $owner->getPhoneNumber(), PDO::PARAM_STR);
            $stm->bindValue(':iban', $owner->getIBAN(), PDO::PARAM_STR);
            $stm->bindValue(':status', $owner->getStatus()->value, PDO::PARAM_STR);
            $stm->execute();
            $db->exec('UNLOCK TABLES');
            return true;
            
        }
        catch (PDOException $e) {
            $db->rollBack();
            $errorType = TError::getInstance()->handleDuplicateError($e);
            if ($errorType) {
                echo "Error: " . $errorType . "\n";
                 //quando faremo view leghiamolo a view
            } else {
                echo "An unexpected error occurred: " . $e->getMessage() . "\n";
            }
            return false;
        }
    //} else return false;
    }
    private static function currentPhoto(int $id): ?int {
        $db=FConnection::getInstance()->getConnection();
        try
        {
            $db->exec('LOCK TABLES owner READ');
            $db->beginTransaction();
            $q='SELECT picture FROM owner WHERE id=:id';
            $stm=$db->prepare($q);
            $stm->bindparam(':id', $id, PDO::PARAM_INT);
            $stm->execute();
            $db->commit();
            $db->exec('UNLOCK TABLES');
        }
        catch (PDOException $e) 
        {
            $db->rollBack();
        }
        $photoID=$stm->fetch(PDO::FETCH_ASSOC)['picture'];
        return $photoID;
    }
    public function delete(EOwner $owner): bool {
        $db=FConnection::getInstance()->getConnection();
        //$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
        try
        {   
            $db->exec('LOCK TABLES owner WRITE');
            $db->beginTransaction();
            $q='DELETE FROM owner WHERE id= :id';
            $stm=$db->prepare($q);
            $stm->bindValue(':id',$owner->getId(), PDO::PARAM_INT);
            $stm->execute();    
            $db->commit();
            $db->exec('UNLOCK TABLES');

            return true;
        }
        catch(PDOException $e)
        {
            $db->rollBack();
            return false;
        }
    }

    public function verifyEmail(string $email):bool
    {
        $q='SELECT * FROM owner WHERE email=:email';
        $connection= FConnection::getInstance();
        $db=$connection->getConnection();
        $db->exec('LOCK TABLES owner READ');
        $db->beginTransaction();
        $stm=$db->prepare($q);
        $stm->bindParam(':email',$email,PDO::PARAM_STR);
        $stm->execute();
        $db->commit();
        $db->exec('UNLOCK TABLES');
        $result=$stm->rowCount();

        if ($result >0)
        {
            return true;
        }
        return false;
    } 

    public function verifyUsername(string $username):bool|int
    {
        $q='SELECT * FROM owner WHERE username=:username';
        $connection= FConnection::getInstance();
        $db=$connection->getConnection();
        $db->exec('LOCK TABLES owner READ');
        $db->beginTransaction();
        $stm=$db->prepare($q);
        $stm->bindParam(':username',$username,PDO::PARAM_STR);
        $stm->execute();
        $db->commit();
        $db->exec('UNLOCK TABLES');
        $result=$stm->rowCount();
        $row=$stm->fetch(PDO::FETCH_ASSOC);

        if ($result >0)
        {
            return $row['id'];
        }
        return false;
    }
    
    /**
     * Method getOwnerByUsername
     * This method return the EOwner class from the db by the username
     * @param $user $user [owner's username]
     *
     * @return ?EOwner
     */
    public function getOwnerByUsername($user):?EOwner
    {
        $db=FConnection::getInstance()->getConnection();
        try
        {
            $q='SELECT * FROM owner WHERE username= :user';
            $db->beginTransaction();
            $stm=$db->prepare($q);
            $stm->bindParam(':user',$user,PDO::PARAM_STR);
            $stm->execute();
            $db->commit();
        }
        catch(PDOException $e)
        {
            $db->rollBack();
            return null;
        }
        $result_array=$stm->fetch(PDO::FETCH_ASSOC);
        $username=$result_array['username'];
        $password=$result_array['password'];
        $name=$result_array['name'];
        $surname=$result_array['surname'];
        $email=$result_array['email'];
        $phone=$result_array['phoneNumber'];
        $IBAN=$result_array['iban'];
        $status=$result_array['status'];
        if(is_null($result_array['picture']))
        {
            $photo=null;
        }
        else
        {
            $photo=FPhoto::getInstance()->loadAvatar($result_array['picture']);
        }
        $owner= new EOwner(null,$username,$password,$name,$surname,$photo,$email,$phone,$IBAN,$status);
        $owner->setID($result_array['id']);
        return $owner;
    }
    
    /**
     * Method deleteOwnerByUsername
     *
     * this method delete from db an owner using given username
     * @param $user $user [owner's username]
     *
     * @return bool
     */
    public function deleteOwnerByUsername($user):bool
    {
        $db=FConnection::getInstance()->getConnection();
        try
        {
            $q="DELETE FROM owner WHERE username = :user";
            $db->beginTransaction();
            $stm=$db->prepare($q);
            $stm->bindParam(':user',$user,PDO::PARAM_STR);
            $stm->execute();
            $db->commit();
        }
        catch(PDOException $e)
        {
            $db->rollBack();
            return false;
        }
        return true;
    }
    
    
    /**
     * Method getOwnerIdByUsername
     *
     * this method return the owner's id in the db ,if present, using the owner's username
     * @param $user $user [owner's username]
     *
     * @return int
     */
    public function getOwnerIdByUsername($user):?int
    {
        $db=FConnection::getInstance()->getConnection();
        try
        {
            $q='SELECT id FROM owner WHERE username= :user';
            $db->beginTransaction();
            $stm=$db->prepare($q);
            $stm->bindParam(':user',$user,PDO::PARAM_STR);
            $stm->execute();
            $db->commit();
        }
        catch(PDOException $e)
        {
            $db->rollBack();
            return null;
        }
        $result_array=$stm->fetch(PDO::FETCH_ASSOC);
        return $result_array['id'];
    }

    public function getUsernameByOwnerId(int $id):?string
    {
        $db=FConnection::getInstance()->getConnection();
        try
        {
            $q='SELECT username FROM owner WHERE id= :id';
            $db->beginTransaction();
            $stm=$db->prepare($q);
            $stm->bindParam(':id',$id,PDO::PARAM_STR);
            $stm->execute();
            $db->commit();
        }
        catch(PDOException $e)
        {
            $db->rollBack();
            return null;
        }
        $result_array=$stm->fetch(PDO::FETCH_ASSOC);
        return $result_array['username'];
    }
    
    /**
     * Method verifyPhoneNumber
     * 
     * this method return true if the given phone number is already in use, false viceversa
     * @param $phone $phone [owner's phone number]
     *
     * @return bool
     */
    public function verifyPhoneNumber($phone):bool
    {
        $db=FConnection::getInstance()->getConnection();
        try
        {
            $q='SELECT * FROM owner WHERE phoneNumber = :phone';
            $db->beginTransaction();
            $stm=$db->prepare($q);
            $stm->bindParam(':phone',$phone,PDO::PARAM_INT);
            $stm->execute();
            $db->commit();
        }
        catch(PDOException $e)
        {
            $db->rollBack();
            return true;
        }
        $result=$stm->rowCount();
        if($result>0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Method verifyIBAN
     *
     * this method verify if the iban is already in use
     * @param $iban $iban [owner's iban]
     *
     * @return bool
     */
    public function verifyIBAN($iban):bool
    {
        $db=FConnection::getInstance()->getConnection();
        try
        {
            $q='SELECT * FROM owner WHERE iban = :iban';
            $db->beginTransaction();
            $stm=$db->prepare($q);
            $stm->bindParam(':iban',$phone,PDO::PARAM_STR);
            $stm->execute();
            $db->commit();
        }
        catch(PDOException $e)
        {
            $db->rollBack();
            return true;
        }
        $result=$stm->rowCount();
        if($result>0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Method getPhotolByUsername
     *
     * This method return the owners's photo
     * @param $user username
     *
     * @return int|bool
     */
    public function getPhotoIdByUsername($user):int|bool|null
    {
        $db=FConnection::getInstance()->getConnection();
        try
        {
            $q='SELECT picture FROM owner WHERE username = :user';
            $db->beginTransaction();
            $stm=$db->prepare($q);
            $stm->bindParam(':user',$user);
            $stm->execute();
            $db->commit();
        }
        catch(PDOException $e)
        {
            $db->rollBack();
            return false;
        }

        $result_array=$stm->fetch(PDO::FETCH_ASSOC);        
        return $result_array['picture'];
    }
    
    /**
     * Method findOwnerRating
     *
     * this method return the owner's reating
     * @param $id $id [owner'sID]
     *
     * @return int
     */
    public static function findOwnerRating($id):int
    {
        $db=FConnection::getInstance()->getConnection();
        try
        {
            $q='SELECT AVG(r.valutation) AS rateO
                FROM accommodation a INNER JOIN owner o ON o.id=a.idOwner
                INNER JOIN ownerreview orw ON orw.idOwner=o.id
                INNER JOIN review r ON r.id=orw.idReview
                WHERE o.id=:id';
            $db->beginTransaction();
            $stm=$db->prepare($q);
            $stm->bindParam(':id',$id,PDO::PARAM_INT);
            $stm->execute();
            $db->commit();
        }
        catch(PDOException $e)
        {
            $db->rollBack();
            return 0;
        }
        $row=$stm->fetch(PDO::FETCH_ASSOC);
        if(is_null($row['rateO']))
        {
            return 0;
        }
        else
        {
            return (int)$row['rateO'];
        }
    }
    
    /**
     * Method getTenans
     *
     * this method return an array of EStudent who are tenants of an Owner
     * @param string $type [past, present or future tenants]
     * @param int $idOwner [Owner id]
     *
     * @return array
     */
    public function getTenans(string $type, int $idOwner):array
    {
        $db=FConnection::getInstance()->getConnection();
        FPersistentManager::getInstance()->updateDataBase();
        try
        {
            $db->exec('LOCK TABLES owner READ, accommodation READ, reservation READ, student READ, contract READ');
            $q="SELECT a.id AS idAccommodation , s.id AS idStudent, r.toDate AS expiryDate 
                FROM accommodation a INNER JOIN reservation r ON a.id=r.idAccommodation
                INNER JOIN student s ON s.id=r.idStudent
                INNER JOIN contract c ON c.idReservation=r.id
                INNER JOIN owner o ON o.id=a.idOwner
                WHERE c.`status`= :type
                AND o.id= :idOwner";
            $db->beginTransaction();
            $stm=$db->prepare($q);
            $stm->bindParam(':type',$type,PDO::PARAM_STR);
            $stm->bindParam(':idOwner',$idOwner,PDO::PARAM_INT);
            $stm->execute();
            $db->commit();
            $db->exec('UNLOCK TABLES');
        }
        catch(PDOException $e)
        {
            $db->rollBack();
            return array();
        }
        $rows=$stm->fetchAll(PDO::FETCH_ASSOC);
        $result = $this->fromRowsToTenantsArrayByRateT($rows,0); #we do not have the rateT yet, so we can ignore it putting it to 0
        return $result;
    }

    
    /**
     * Method getFilterTenants
     *
     * this method return an array which contains the accommodation id as key an as value an array of EStudent as first element and the expiry date as second element
     * @param $type $type [current/future/past contract]
     * @param $accommodation_name $accommodation_name [accommodation title]
     * @param $t_username $t_username [student's username]
     * @param $t_age $t_age [student'age]
     * @param $rateT $rateT [student'rating]
     * @param $date $date [september/october]
     * @param $men 
     * @param $women
     * @param $idOwner
     * @param $year 
     *
     * @return array
     */
    public function getFilterTenants($type,$accommodation_name,$t_username,$t_age,$rateT,$date,$men,$women,$idOwner,$year):array
    {
        $db=FConnection::getInstance()->getConnection();
        $date = $date=='september'? 9:10;
        if ($type=='current'){
            $type='onGoing';
        } else if ($type=='past') {
            $type='finished';
        }
        try
        {
            $q="SELECT a.id AS idAccommodation , s.id AS idStudent, r.toDate AS expiryDate
                FROM accommodation a INNER JOIN owner o ON o.id=a.idOwner
                INNER JOIN reservation r ON r.idAccommodation=a.id
                INNER JOIN contract c ON c.idReservation=r.id
                INNER JOIN student s ON s.id=r.idStudent
                WHERE c.`status`= :type
                AND o.id= :id";
            $params=array();
            $params[':type']=$type;
            $params[':id']=$idOwner;
            if($t_age!=0 and !is_null($t_age)){
                $q.=" AND TIMESTAMPDIFF(YEAR,s.birthDate,CURDATE())= :age";
                $params[':age']=$t_age;}
            if(!is_null($accommodation_name)){
                $q.=" AND a.title= :accommodation_name";
                $params[':accommodation_name']=$accommodation_name;}
            if($t_username!=''){
                $q.=" AND s.username= :t_username";
                $params[':t_username']=$t_username;}
            if(!is_null($year)){
                $q.=" AND YEAR(c.paymentDate)= :year";
                $params[':year']=$year;}
            if($men==true and $women==true){}
            elseif($men==false and $women==true){$q.=" AND s.sex='F'";}
            elseif($men==true and $women==false){$q.=" AND s.sex='M'";}
            else{}
            FPersistentManager::getInstance()->updateDataBase();
            $db->exec('LOCK TABLES accommodation READ, reservation READ, contract READ, owner READ, student READ');
            $db->beginTransaction();
            $stm=$db->prepare($q);
            $stm->execute($params);
            $db->commit();
            $db->exec('UNLOCK TABLES');
        }
        catch(PDOException $e)
        {
            $db->rollBack();
            return array();
        }
        $rows=$stm->fetchAll(PDO::FETCH_ASSOC);
        $result=$this->fromRowsToTenantsArrayByRateT($rows,(int)$rateT);
        return $result;   
    }
    
    /**
     * Method fromRowsToTenantsArrayByRateT
     * 
     * this method take only student with given rateT (tenants rate) and transforms them in an array to format
     * if you are not interest to rateT use rateT=0
     * @param $rows result of query
     * @param int $rateT tenants rate
     *
     * @return array
     */
    private function fromRowsToTenantsArrayByRateT($rows, int $rateT):array
    {
        $result=array();
        foreach($rows as $row)
        {
            $student=FPersistentManager::getInstance()->load('EStudent',$row['idStudent']);

            if(($student->getRating()>=$rateT) or ($student->getRating()==0))
            {
                $p_student=$student->getPhoto();
                if(!is_null($p_student))
                {
                    $p_student=(EPhoto::toBase64(array($p_student)))[0];
                    $student->setPhoto($p_student);
                }
                if(in_array($row['idAccommodation'],array_keys($result)))
                {
                    $result[$row['idAccommodation']][]=[$student, $row['expiryDate']];
                }
                else
                {
                    $result[$row['idAccommodation']]=array([$student, $row['expiryDate']]);
                }
            }
            else{}
        }
        return $result;
    }
    
    
    /**
     * Method getBannedOwners
     *
     * this method return an array of banned owners from Data Base
     * @return array
     */
    public function getBannedOwners():array
    {
        $result=array();
        $db=FConnection::getInstance()->getConnection();
        try
        {
            $q="SELECT o.id AS ID
                FROM owner o
                WHERE o.`status`='banned'";
            $db->beginTransaction();
            $stm=$db->prepare($q);
            $stm->execute();
            $db->commit();
        }
        catch(PDOException $e)
        {
            $db->rollBack();
            return $result;
        }
        $rows=$stm->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row)
        {
            $owner=$this->load($row['ID']);
            $result[]=$owner;
        }
        return $result;
    }
    
    /**
     * Method getSupportReply
     * 
     * this method return the owner's support replies
     *
     * @param int $id [explicite description]
     *
     * @return array
     */
    public function getSupportReply(int $id):array
    {
        $db=FConnection::getInstance()->getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
        try
        {
            if(!$db->inTransaction())
            {
                $db->beginTransaction();
            }
            $q="SELECT s.id as id, s.statusRead as statusRead, s.supportReply as supportReply
                FROM supportrequest s
                WHERE idStudent IS NULL 
                AND s.idOwner=:id
                AND s.supportReply IS NOT NULL
                LOCK IN SHARE MODE";
            #$db->exec('LOCK TABLES supportrequest READ');
            
            $stm=$db->prepare($q);
            $stm->bindParam(':id',$id,PDO::PARAM_INT);
            $stm->execute();
            if($db->inTransaction())
            {
                $db->commit();
            }
            #$db->exec('UNLOCK TABLES');
        }
        catch(PDOException $e)
        {
            if($db->inTransaction())
            {
                $db->rollBack();
            }
            return array();
        }
        $rows=$stm->fetchAll(PDO::FETCH_ASSOC);
        $result=array();
        foreach($rows as $row)
        {
            $supportreply= FPersistentManager::getInstance()->load('ESupportRequest',$row['id']);
            $supportreply->setStatusRead($row['statusRead']);
            $supportreply->setSupportReply($row['supportReply']);
            $result[]=$supportreply;
        }
        return $result;
    }
    public function getUsernameById(int $id): string | bool {
        $db=FConnection::getInstance()->getConnection();
        try
        {
            $db->exec('LOCK TABLES owner READ');
            $db->beginTransaction();
            $q='SELECT username FROM owner WHERE id=:id';
            $stm=$db->prepare($q);
            $stm->bindparam(':id', $id, PDO::PARAM_INT);
            $stm->execute();
            $db->commit();
            $db->exec('UNLOCK TABLES');
        }
        catch (PDOException $e) 
        {
            $db->rollBack();
            return false;
        }
        $row=$stm->fetch(PDO::FETCH_ASSOC);
        return $row['username'];
    }
    
 }