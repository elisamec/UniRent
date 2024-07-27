<?php 

    namespace Classes\Foundation;
    require __DIR__ . '/../../vendor/autoload.php';

    use Classes\Foundation\FConnection;
    use Classes\Foundation\FPhoto;
    use Classes\Entity\EAccommodation;
    use Classes\Entity\EPhoto;
use Classes\Entity\EStudent;
use PDO;
    use DateTime;
    use PDOException;

    use CommerceGuys\Addressing\Address;
    
    /**
     * FAccommodation
     * 
     */
    class FAccommodation{

        private static $instance=null;
        
        /**
         * __construct
         *
         * @return self
         */
        public function __construct(){}

        /**
         * getInstance
         *
         * @return FAccommodation
         */
        public static function getInstance():FAccommodation
        {
            if(is_null(self::$instance))
            {
                self::$instance= new FAccommodation();
            }
            return self::$instance;
        }

        /**
        * exist
        *
        * @param  int $accommodationId
        * @return bool
        */
        public function exist(int $accommodatioId):bool 
        {
            $q="SELECT * FROM accommodation WHERE id=:id";
            $db=FConnection::getInstance()->getConnection();
            $db->exec('LOCK TABLES accommodation READ');
            $db->beginTransaction();
            $stm=$db->prepare($q);
            $stm->bindParam(':id',$accommodatioId,PDO::PARAM_INT);
            $stm->execute();
            $db->commit();
            $db->exec('UNLOCK TABLES');
            $result=$stm->rowCount();

            if ($result >0) return true;
            return false;
        }

        /**
         * load Accommodation from db
         *  
         *  
         * @param  int $idAccommodation
         * @return EAccommodation
         */
        public function load(int $idAccommodation): ?EAccommodation{
            
            $FP=FPhoto::getInstance();
            $FA=FAccommodation::getInstance();
            $db=FConnection::getInstance()->getConnection();

            try{
                $db->exec('LOCK TABLES accommodation READ');
                $db->beginTransaction();
                $q="SELECT * FROM accommodation WHERE id=:id";    
                $stm=$db->prepare($q);
                $stm->bindParam(':id',$idAccommodation,PDO::PARAM_INT);
                $stm->execute();
                $db->commit();
                $db->exec('UNLOCK TABLES');

            }catch (PDOException $e){
                $db->rollBack();        
                return null;
            }

            $photos = $FP->loadAccommodation($idAccommodation);
            $row=$stm->fetch(PDO::FETCH_ASSOC);
            //print "Ho recuperato le seguenti info per l'accomodation con id $idAccommodation <br>";
            //var_dump($row); //row è false per qualche motivo
            $address = new Address();
            $address = $FA->loadAddress($row['address']);
            $start = new DateTime($row['start']);
            $visit = $FA->loadDays($idAccommodation);

            $result=new EAccommodation(
                $row['id'],
                $photos,
                $row['title'],
                $address,
                $row['price'],
                $start,
                $row['description'],
                $row['places'],
                $row['deposit'],
                $visit, 
                $row['visitDuration'],
                $row['man'],
                $row['woman'],
                $row['pets'],
                $row['smokers'],
                $row['status'],
                $row['idOwner']
            );
            return $result;

        }

        /**
         * loadAddress
         * private class that loads the address of an accommodation from db 
         * 
         * @param  int $idAddress
         * @return Address
         */
        private function loadAddress(int $idAddress):Address{

            $db=FConnection::getInstance()->getConnection();
        
            try{
                $db->exec('LOCK TABLES address READ');
                $db->beginTransaction();
                $q="SELECT * FROM address WHERE id=:id";    
                $stm=$db->prepare($q);
                $stm->bindParam(':id',$idAddress,PDO::PARAM_INT);
                $stm->execute();
                $db->commit();
                $db->exec('UNLOCK TABLES');

            }catch (PDOException $e){
                $db->rollBack();
            }

            $row=$stm->fetch(PDO::FETCH_ASSOC);
            $address=new Address();
            $address = $address->withSortingCode($row['id'])->withAddressLine1($row['addressLine'])->withPostalCode($row['postalCode'])->withLocality($row['city']);
            return $address;
        }

        /**
         * loadDays
         * private class that loads the days of visit of an accommodation from db 
         * 
         * @param  int $idAccommodation
         * @return array
         */
        private function loadDays(int $idAccommodation):array{

            $FA=FAccommodation::getInstance();
            $db=FConnection::getInstance()->getConnection();
        
            try{
                $db->exec('LOCK TABLES day READ');
                $db->beginTransaction();
                $q="SELECT * FROM day WHERE idAccommodation=:id";    
                $stm=$db->prepare($q);
                $stm->bindParam(':id',$idAccommodation,PDO::PARAM_INT);
                $stm->execute();
                $db->commit();
                $db->exec('UNLOCK TABLES');

            }catch (PDOException $e){
                $db->rollBack();
            }

            $days = [];
            $rows = $stm->fetchAll(PDO::FETCH_ASSOC);

            foreach ($rows as $row) {
                $times = $FA->loadTime($row['id']);
                $days[$row['day']] = $times;
            }

            return $days;
            
        }

        /**
         * loadTime
         * private class that loads the times of visit of an accommodation from db 
         * 
         * @param  int $idDay
         * @return array
         */
        private function loadTime(int $idDay):array{
            
            $db=FConnection::getInstance()->getConnection();
        
            try{
                $db->exec('LOCK TABLES time READ');
                $db->beginTransaction();
                $q="SELECT * FROM time WHERE idDay=:id";    
                $stm=$db->prepare($q);
                $stm->bindParam(':id',$idDay,PDO::PARAM_INT);
                $stm->execute();
                $db->commit();
                $db->exec('UNLOCK TABLES');

            }catch (PDOException $e){
                $db->rollBack();
            }

            $times = [];
            $rows = $stm->fetchAll(PDO::FETCH_ASSOC);

            foreach ($rows as $row) {
                $time = new DateTime($row['hour']);
                $times[] = $time->format('H:i');
            }

            return $times;
            
        }

        /**
         * store
         *
         * @param  EAccommodation $accommodation
         * @return bool
         */
        public function store(EAccommodation $accommodation):bool 
        {
            $FP=FPhoto::getInstance();
            $FA=FAccommodation::getInstance();
            $db=FConnection::getInstance()->getConnection();
            $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
            $db->beginTransaction();
            
            try{ 
                $db->exec('LOCK TABLES accommodation WRITE');
                

                $q='INSERT INTO accommodation (title, address, price, start, description, places, deposit, visitDuration, man, woman, pets, smokers, status, idOwner)';
                $q=$q.' VALUES (:title, :address, :price, :start, :description, :places, :deposit, :visitDuration, :man, :woman, :pets, :smokers, :status, :idOwner)';

                $stm=$db->prepare($q);

                $address = $FA->storeAddress($accommodation->getAddress());

                $stm->bindValue(':title',$accommodation->getTitle(),PDO::PARAM_STR);
                $stm->bindValue(':address',$address,PDO::PARAM_INT);
                $stm->bindValue(':price',$accommodation->getPrice(),PDO::PARAM_INT);
                $stm->bindValue(':start',$accommodation->getStart()->format('Y-m-d H:i:s'),PDO::PARAM_STR);
                $stm->bindValue(':description',$accommodation->getDescription(),PDO::PARAM_STR);
                $stm->bindValue(':places',$accommodation->getPlaces(),PDO::PARAM_INT);
                $stm->bindValue(':deposit',$accommodation->getDeposit(),PDO::PARAM_INT);
                $stm->bindValue(':visitDuration',$accommodation->getVisitDuration(),PDO::PARAM_INT);
                $stm->bindValue(':man',$accommodation->getMan(),PDO::PARAM_BOOL);
                $stm->bindValue(':woman',$accommodation->getWoman(),PDO::PARAM_BOOL);
                $stm->bindValue(':pets',$accommodation->getPets(),PDO::PARAM_BOOL);
                $stm->bindValue(':smokers',$accommodation->getSmokers(),PDO::PARAM_BOOL);
                $stm->bindValue(':status',$accommodation->getStatus(),PDO::PARAM_BOOL);
                $stm->bindValue(':idOwner',$accommodation->getIdOwner(),PDO::PARAM_INT);
                
                $stm->execute();
                $id=$db->lastInsertId();
                $db->commit();
                $db->exec('UNLOCK TABLES');
                $accommodation->setIdAccommodation($id);

                $photos = $accommodation->getPhoto();
                foreach($photos as $photo){
                    $photo = $photo->setIdAccommodation($id);
                }

                $visit = $accommodation->getVisit();
                $days = array_keys($visit);

                foreach($days as $day){

                    $tmp = $FA -> storeDay($id, $day);

                    foreach ($visit[$day] as $time){
                       $result = $FA -> storeTime($tmp, $time);
                       print $time;
                    }
                }

                $FP->store($photos);
                return true;
            }      
            catch(PDOException $e)
            {
                $db->rollBack();
                return false;
            }

        }

        /**
         * storeAddress
         * private class that stores the address of an accommodation in db
         * 
         * @param  Address $address
         * @return int
         */
        private function storeAddress(Address $address):?int {

            $db=FConnection::getInstance()->getConnection();
            $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
            
            try { 
                $db->exec('LOCK TABLES address WRITE');
                $db->beginTransaction();

                $addressLine = $address->getAddressLine1();
                $postalCode = $address->getPostalCode();
                $city = $address->getLocality();

                $q='INSERT INTO address (addressLine, postalCode, city)';
                $q=$q.' VALUES (:addressLine, :postalCode, :city)';

                $stm=$db->prepare($q);
                $stm->bindValue(':addressLine',$addressLine,PDO::PARAM_STR);
                $stm->bindValue(':postalCode',$postalCode,PDO::PARAM_STR);
                $stm->bindValue(':city',$city,PDO::PARAM_STR);
                
                $stm->execute();
                $id=$db->lastInsertId();
                $db->exec('UNLOCK TABLES');
                $address->withSortingCode($id);
                return $id;
            }      
            catch(PDOException $e){
                $db->rollBack();
                return null;
            }
            
        }


        /**
         * storeDay
         * private class that stores days of visit of an accommodation in db
         * 
         * @param  int $idAccommodation
         * @param  string $day
         * @return int
         */
        private function storeDay(int $idAccommodation, String $day):?int {

            $db=FConnection::getInstance()->getConnection();
            $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
            
            try { 

                $db->exec('LOCK TABLES day WRITE');
                $db->beginTransaction();

                $q='INSERT INTO day (day, idAccommodation)';
                $q=$q.' VALUES (:day, :idAccommodation)';

                $stm=$db->prepare($q);
                $stm->bindValue(':day',$day,PDO::PARAM_STR);
                $stm->bindValue(':idAccommodation',$idAccommodation,PDO::PARAM_INT);
                
                $stm->execute();
                $id=$db->lastInsertId();
                //$db->commit();
                $db->exec('UNLOCK TABLES');
                
                return $id;
            }      
            catch(PDOException $e){
                $db->rollBack();
                return null;
            }
            
        }

        /**
         * storeDay
         * private class that stores days of visit of an accommodation in db
         * 
         * @param  int $idAccommodation
         * @param  string $day
         * @return int
         */
        private function storeTime(int $idDay, String $time):?bool {

            $db=FConnection::getInstance()->getConnection();
            $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
            
            try { 

                $db->exec('LOCK TABLES time WRITE');
                $db->beginTransaction();

                $q='INSERT INTO time (hour, idDay)';
                $q=$q.' VALUES (:hour, :idDay)';

                $stm=$db->prepare($q);
                $stm->bindValue(':hour',$time,PDO::PARAM_STR);
                $stm->bindValue(':idDay',$idDay,PDO::PARAM_INT);
                
                $stm->execute();
                //$db->commit();
                $db->exec('UNLOCK TABLES');
                
                return true;
            }      
            catch(PDOException $e){
                $db->rollBack();
                return null;
            }
            
        }

        /**
        * update
        *
        * @param  EAccommodation $accommodation
        * @return bool
        */
        /*public function update(EAccommodation $accommodation):bool {

            $FA=FAccommodation::getInstance();

            $idAccommodation = $accommodation->getIdAccommodation();

            $delete = $FA->delete($idAccommodation);

            $delete ? $store = $FA->store($accommodation) : $store = false;

            return $store;
        }*/

        public function update(EAccommodation $accommodation):bool {
           
            $FA=FAccommodation::getInstance();
            $db=FConnection::getInstance()->getConnection();
            $accommodationId = $accommodation->getIdAccommodation();
            try{
                $db->exec('LOCK TABLES accommodation WRITE');
                $db->beginTransaction();

                $resAddress = $FA->updateAddress($accommodation->getAddress());
                $resVisit = $FA -> updateDay($accommodation);
                $resPhoto = $FA -> updatePhoto($accommodation);
                
                if($resAddress && $resVisit && $resPhoto){

                    $q='UPDATE accommodation SET title = :title, price = :price,
                                    start = :start, description = :description, places = :places, deposit = :deposit,
                                    visitDuration = :visitDuration, man = :man, woman = :woman,
                                    pets = :pets, smokers = :smokers, status = :status, idOwner = :idOwner  WHERE id=:id';
                
                    $stm=$db->prepare($q);
                    
                    $stm->bindValue(':title',$accommodation->getTitle(),PDO::PARAM_STR);
                    $stm->bindValue(':price',$accommodation->getPrice(),PDO::PARAM_INT);
                    $stm->bindValue(':start',$accommodation->getStart()->format('Y-m-d H:i:s'),PDO::PARAM_STR);
                    $stm->bindValue(':description',$accommodation->getDescription(),PDO::PARAM_STR);
                    $stm->bindValue(':places',$accommodation->getPlaces(),PDO::PARAM_INT);
                    $stm->bindValue(':deposit',$accommodation->getDeposit(),PDO::PARAM_INT);
                    $stm->bindValue(':visitDuration',$accommodation->getVisitDuration(),PDO::PARAM_INT);
                    $stm->bindValue(':man',$accommodation->getMan(),PDO::PARAM_BOOL);
                    $stm->bindValue(':woman',$accommodation->getWoman(),PDO::PARAM_BOOL);
                    $stm->bindValue(':pets',$accommodation->getPets(),PDO::PARAM_BOOL);
                    $stm->bindValue(':smokers',$accommodation->getSmokers(),PDO::PARAM_BOOL);
                    $stm->bindValue(':status',$accommodation->getStatus(),PDO::PARAM_BOOL);
                    $stm->bindValue(':idOwner',$accommodation->getIdOwner(),PDO::PARAM_INT);
                    $stm->bindValue(':id',$accommodationId,PDO::PARAM_INT);

                    $stm->execute();           
                    $db->commit();
                    $db->exec('UNLOCK TABLES');
                    return true;

                } else return false;
                
            }
            catch(PDOException $e){
                $db->rollBack();
                print "Error: " . $e->getMessage() . "\n"; // Add this line to see the error message
                return false;
            }
            
        }


        /**
         * updateAddress
         * private class that updates the address of an accommodation in db
         * 
         * @param  Address $address
         * @return bool
         */
        private function updateAddress(Address $address): bool {
            $db=FConnection::getInstance()->getConnection();
            try{
                $db->exec('LOCK TABLES address WRITE');
                $db->beginTransaction();
                $q='UPDATE address SET addressLine = :addressLine, postalCode = :postalCode, city = :city  WHERE id=:id';
                $stm=$db->prepare($q);
                $stm->bindValue(':addressLine',$address->getAddressLine1(),PDO::PARAM_STR);
                $stm->bindValue(':postalCode',$address->getPostalCode(),PDO::PARAM_STR);
                $stm->bindValue(':city',$address->getLocality(),PDO::PARAM_STR);
                $stm->bindValue(':id',$address->getSortingCode(),PDO::PARAM_INT);
                $stm->execute();          
                $db->exec('UNLOCK TABLES');
                return true;
            }
            catch(PDOException $e){
                $db->rollBack();
                return false;
            }
        }


        /**
         * updateDay
         * private class that updates the days and times of visit of an accommodation in db
         * 
         * @param  int $idAccommodation
         * @param  string $day
         * @return bool
         */
        private function updateDay(EAccommodation $accommodation):bool {

            $FA = FAccommodation::getInstance();

            $idAccommodation = $accommodation->getIdAccommodation();
            
            //Delete the days and times of visit
            $delete = $FA -> deleteDay($idAccommodation);

            //If the days and times of visit are deleted, store the new ones
            if ($delete) {
                
                //Get new days and times of visit
                $visit = $accommodation->getVisit();
                $days = array_keys($visit);

                if($days == []) return true;  #If for visits days there aren't, true you can return,said master Yoda

                //Store new days and times
                foreach($days as $day){

                    $tmp = $FA -> storeDay($idAccommodation, $day);

                    foreach ($visit[$day] as $time){
                        $result = $FA -> storeTime($tmp, $time);
                    }
                }
            
            } else $result = false;

            return $result;   
        }

        /**
         * update photo
         * private class that updates photos of an accommodation
         * 
         * 
         */
        
        private function updatePhoto(EAccommodation $accommodation):bool{

            $FP = FPhoto::getInstance();

            $idAccommodation = $accommodation->getIdAccommodation();
            $delete = $FP -> deleteAccommodation($idAccommodation);

            if($delete){
                $photos = $accommodation->getPhoto();

                foreach($photos as $photo){
                    $photo = $photo->setIdAccommodation($idAccommodation);
                }

                $FP -> store($photos);
                return true;

            } else return false;

        }
        
        /**
         * retriveDayId
         * private class that retrives the days of visit of an accommodation from db
         * 
         * @param  int $idAccommodation
         * @return array
         */
        private function retriveDayId(int $idAccommodation):array 
        {
            $db=FConnection::getInstance()->getConnection();

            try{
                $db->exec('LOCK TABLES day WRITE');
                $db->beginTransaction();
                
                $q='SELECT id FROM day WHERE idAccommodation=:idAccommodation';
                $stm=$db->prepare($q);
                $stm->bindValue(':idAccommodation',$idAccommodation,PDO::PARAM_INT);
                $stm->execute();           


                $result = $stm->fetchAll(PDO::FETCH_ASSOC);
                $days = [];
                foreach($result as $row){
                    $days[] = $row['id'];
                }

                return $days;
            }
            catch(PDOException $e){
                $db->rollBack();
                return [];
            }
            
        }

        /**
         * retriveAddressId
         * 
         * @param int $idAccommodation
         * @return int
         */
        private function retriveAddressId(int $idAccommodation):int 
        {
            $db=FConnection::getInstance()->getConnection();

            try{
                $db->exec('LOCK TABLES accommodation WRITE');
                $db->beginTransaction();
                
                $q='SELECT address FROM accommodation WHERE id=:id';
                $stm=$db->prepare($q);
                $stm->bindValue(':id',$idAccommodation,PDO::PARAM_INT);
                $stm->execute();           

                $result = $stm->fetch(PDO::FETCH_ASSOC);
                return $result['address'];
            }
            catch(PDOException $e){
                $db->rollBack();
                return 0;
            }
            
        }

        /**
         * delete
         * 
         * @param  int $idAccommodation
         * @return bool
         */
        public function delete(int $idAccommodation):bool{
            $db=FConnection::getInstance()->getConnection();
            $FA=FAccommodation::getInstance();
            
           if($FA->exist($idAccommodation)){

                $FA->deleteDay($idAccommodation);
                $addressId = $FA->retriveAddressId($idAccommodation);

                try{  

                    $db->exec('LOCK TABLES accommodation WRITE');
                    $db->beginTransaction();
                    $q='DELETE FROM accommodation WHERE id=:id';
                    $stm=$db->prepare($q);
                    $stm->bindValue(':id',$idAccommodation, PDO::PARAM_INT);
                    $stm->execute();    
                    $db->commit();
                    $db->exec('UNLOCK TABLES');

                    $FA->deleteAddress($addressId);

                    return true;
                }
                catch(PDOException $e)
                {
                    $db->rollBack();
                    return false;
                }

            } else return false;
        }

        /**
         * deleteAddress
         * private class that deletes the address of an accommodation in db
         * 
         * @param  int $idAddress
         * @return bool
         */
        private function deleteAddress(int $idAddress):bool {
            $db=FConnection::getInstance()->getConnection();

            try{
                $db->exec('LOCK TABLES address WRITE');
                $db->beginTransaction();
                $q='DELETE FROM address WHERE id=:id';
                $stm=$db->prepare($q);
                $stm->bindValue(':id',$idAddress, PDO::PARAM_INT);
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

        /**
         * deleteDay
         * private class that deletes the days of visit of an accommodation in db
         * 
         * @param  int $idAccommodation
         * @return bool
         */
        private function deleteDay(int $idAccommodation):bool {

            $db=FConnection::getInstance()->getConnection();
            $FA = FAccommodation::getInstance();

            try{
                $db->exec('LOCK TABLES day WRITE');
                $db->beginTransaction();

                $days = $FA->retriveDayId($idAccommodation);
                foreach($days as $day){
                    $FA->deleteTime($day);
                }
                
                $q='DELETE FROM day WHERE idAccommodation=:idAccommodation';
                $stm=$db->prepare($q);
                $stm->bindValue(':idAccommodation',$idAccommodation,PDO::PARAM_INT);
                $stm->execute();           
                $db->commit();
                $db->exec('UNLOCK TABLES');

                return true;
            }
            catch(PDOException $e){
                $db->rollBack();
                return false;
            }
            
        }


        /**
         * deleteTime   
         * private class that deletes the times of visit of an accommodation in db
         * 
         * @param  int $idDay
         * @return bool
         * 
         */
        private function deleteTime(int $idDay):bool 
        {
            $db=FConnection::getInstance()->getConnection();

            try{
                $db->exec('LOCK TABLES time WRITE');
                $db->beginTransaction();
                
                $q='DELETE FROM time WHERE idDay=:idDay';
                $stm=$db->prepare($q);
                $stm->bindValue(':idDay',$idDay,PDO::PARAM_INT);
                $stm->execute();           

                return true;
            }
            catch(PDOException $e){
                $db->rollBack();
                return false;
            }     
        }
        
        /**
         * Method findAccommodationsUser
         * 
         * this method return an array of EAccommodations with give specifics
         *
         * @param $city $city [city]
         * @param $date $date [month's of start]
         * @param $rateA $rateA [accommodation's rating]
         * @param $rateO $rateO [owner's rating]
         * @param $minPrice $minPrice [min Price]
         * @param $maxPrice $maxPrice [max Price]
         *
         * @return array
         */
        public function findAccommodationsUser($city, $date,$rateA,$rateO,$minPrice,$maxPrice):array
        {
            $result=array();
            $db=FConnection::getInstance()->getConnection();
            $date==='september' ? $date=9 : $date=10 ;
            try
            {
                $q ="SELECT a.id AS id
                     FROM accommodation a INNER JOIN address ad ON a.address=ad.id
                     WHERE ad.city= :city
                     AND MONTH(a.`start`)= :m
                     AND a.status=TRUE
                     AND((a.price>= :min)AND(a.price<= :max))";
                $db->exec('LOCK TABLES accommodation READ , address READ');
                $db->beginTransaction();
                $stm=$db->prepare($q);
                $stm->bindParam(':min',$minPrice,PDO::PARAM_INT);
                $stm->bindParam('max',$maxPrice,PDO::PARAM_INT);
                $stm->bindParam(':city',$city,PDO::PARAM_STR);
                $stm->bindParam(':m',$date,PDO::PARAM_INT);
                $stm->execute();
                $db->commit();
                $db->exec('UNLOCK TABLES');
            }
            catch(PDOException $e)
            {
                $db->rollBack();
                return $result;
            }
            $rows=$stm->fetchAll(PDO::FETCH_ASSOC);
            
            foreach($rows as $row)
            {
                $r=$this->load($row['id']);
                $rating=$r->getRating();
                if(($rating['owner']>=$rateO) and ($rating['accommodation']>=$rateA) or($rateO=0 and $rateA=0)or($rateO=0 and $rating['accommodation']>=$rateO)or($rateA=0 and $rating['owner']>=$rateO))
                {
                    $ap=array();
                    $ap['title'] = $r->getTitle();
                    $ap['id'] = $r->getIdAccommodation();
                    $ap['price'] = $r->getPrice();
                    $ap['address'] = $r->getAddress()->getAddressLine1().' , '.$r->getAddress()->getLocality();
                    if(count($r->getPhoto())==0)
                    {
                        $ap['photo']=null;
                    }
                    else
                    {
                        $ap['photo']=(($r->getPhoto())[0])->getPhoto();
                        $base64 = base64_encode($ap['photo']);
                        $photo = "data:" . 'image/jpeg' . ";base64," . $base64;
                        $ap['photo'] = $photo;
                    }
                    $result[]=$ap;
                }
                else{}
            }
            return $result;
        }
        
        /**
         * Method findAccommodationsStudent
         * 
         * this method return an array of EAccommodation with the given specifics
         *
         * @param $city $city [city]
         * @param $date $date [month's of start]
         * @param $rateA $rateA [Accommodation's rating]
         * @param $rateO $rateO [Owner's rating]
         * @param $minPrice $minPrice [min Pirice]
         * @param $maxPrice $maxPrice [max Price]
         * @param $student $student [object EStudent]
         *
         * @return array
         */
        public function findAccommodationsStudent($city,$date,$rateA,$rateO,$minPrice,$maxPrice,$student):array
        {
            $result=array();
            $db=FConnection::getInstance()->getConnection();
            $date==='september' ? $date=9 : $date=10 ;
            $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);

            //Student informations
            $smoke=$student->getSmoker();
            $animals=$student->getAnimals();
            $sex=$student->getSex();

            try
            {
                $q="SELECT a.id
                    FROM accommodation a INNER JOIN address ad ON ad.id=a.address
                    WHERE ad.city= :city 
                    AND a.status= TRUE
                    AND MONTH(a.`start`)= :mon
                    AND((a.price>= :min)AND(a.price<= :max)) ";
                
                if($smoke)
                {
                    $q.=" AND a.smokers=TRUE ";
                }
                if($animals)
                {
                    $q.=" AND a.pets=TRUE ";
                }
                
                if($sex=='M')
                {
                    $q.=" AND ((a.man=TRUE AND a.woman=TRUE)OR(a.man=FALSE AND a.woman=FALSE)OR(a.man=TRUE AND a.woman=FALSE)) ";
                }
                else
                {
                    $q.="AND ((a.man=TRUE AND a.woman=TRUE)OR(a.man=FALSE AND a.woman=FALSE)OR(a.man=FALSE AND a.woman=TRUE))";
                }
                
                $db->beginTransaction();
                $stm=$db->prepare($q);
                $stm->bindParam(':city',$city,PDO::PARAM_STR);
                $stm->bindParam(':mon',$date,PDO::PARAM_INT);
                $stm->bindParam(':min',$minPrice,PDO::PARAM_INT);
                $stm->bindParam(':max',$maxPrice,PDO::PARAM_INT);
                $stm->execute();
                $db->commit();
            }
            catch(PDOException $e)
            {
                $db->rollBack();
                #return $result;
            }
            $rows=$stm->fetchAll(PDO::FETCH_ASSOC);

            foreach($rows as $row)
            {
                $r=$this->load($row['id']);
                $rating=$r->getRating();
                if(($rating['owner']>=$rateO) and ($rating['accommodation']>=$rateA) or($rateO=0 and $rateA=0)or($rateO=0 and $rating['accommodation']>=$rateO)or($rateA=0 and $rating['owner']>=$rateO))
                {
                    $ap=array();
                    $ap['title'] = $r->getTitle();
                    $ap['id'] = $r->getIdAccommodation();
                    $ap['price'] = $r->getPrice();
                    $ap['address'] = $r->getAddress()->getAddressLine1().' , '.$r->getAddress()->getLocality();
                    if(count($r->getPhoto())==0)
                    {
                        $ap['photo']=null;
                    }
                    else
                    {
                        $ap['photo']=(($r->getPhoto())[0])->getPhoto();
                        $base64 = base64_encode($ap['photo']);
                        $photo = "data:" . 'image/jpeg' . ";base64," . $base64;
                        $ap['photo'] = $photo;
                    }
                    $result[]=$ap;
                }
                else{}
            }
            return $result;
        }

        public function lastAccommodationsUser():array
        {
            $result=array();
            $db=FConnection::getInstance()->getConnection();
            $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
            try
            {
                $q="SELECT a.id AS id
                    FROM accommodation a INNER JOIN owner o ON o.id=a.idOwner
                    WHERE o.status != 'banned'
                    ORDER BY a.`start` DESC LIMIT 6";
                $db->exec('LOCK TABLES accommodation READ');
                $db->beginTransaction();
                $stm=$db->prepare($q);
                $stm->execute();
                $db->commit();
                $db->exec('UNLOCK TABLES');
            }
            catch(PDOException $e)
            {
                $db->rollBack();
                return $result;
            }
            $rows=$stm->fetchAll(PDO::FETCH_ASSOC);
            foreach($rows as $row)
            {
                $r=$this->load($row['id']);
                $ap=array();
                $ap['title'] = $r->getTitle();
                $ap['id'] = $r->getIdAccommodation();
                $ap['price'] = $r->getPrice();
                $ap['address'] = $r->getAddress()->getAddressLine1().' , '.$r->getAddress()->getLocality();
                if(count($r->getPhoto())==0)
                {
                    $ap['photo']=null;
                }
                else
                {
                    $ap['photo']=(($r->getPhoto())[0])->getPhoto();
                    $base64 = base64_encode($ap['photo']);
                    $photo = "data:" . 'image/jpeg' . ";base64," . $base64;
                    $ap['photo'] = $photo;
                }
                $result[]=$ap;
            }
            return $result;
        }

        public function lastAccommodationsStudent(EStudent $student):array
        {
            $result=array();
            $db=FConnection::getInstance()->getConnection();
            $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);

            //Student informations
            $smoke=$student->getSmoker();
            $animals=$student->getAnimals();
            $sex=$student->getSex();

            try
            {
                $q="SELECT a.id AS id
                    FROM accommodation a INNER JOIN owner o ON o.id=a.idOwner
                    WHERE o.status != 'banned' "; 

                if($sex=='M')
                {
                    $q.=" AND ((a.man=TRUE AND a.woman=TRUE)OR(a.man=FALSE AND a.woman=FALSE)OR(a.man=TRUE AND a.woman=FALSE)) ";
                }
                else
                {
                    $q.=" AND ((a.man=TRUE AND a.woman=TRUE)OR(a.man=FALSE AND a.woman=FALSE)OR(a.man=FALSE AND a.woman=TRUE))";
                }

                if($smoke)
                {
                    $q.=" AND a.smokers=TRUE ";
                }
                if($animals)
                {
                    $q.=" AND a.pets=TRUE ";
                }
                
                $q.=" ORDER BY a.`start` DESC LIMIT 6 ";
                
                $db->exec('LOCK TABLES accommodation READ');
                $db->beginTransaction();
                $stm=$db->prepare($q);
                $stm->execute();
                $db->commit();
                $db->exec('UNLOCK TABLES');
            }
            catch(PDOException $e)
            {
                $db->rollBack();
                return $result;
            }
            $rows=$stm->fetchAll(PDO::FETCH_ASSOC);

            foreach($rows as $row)
            {
                $r=$this->load($row['id']);
                $ap=array();
                $ap['title'] = $r->getTitle();
                $ap['id'] = $r->getIdAccommodation();
                $ap['price'] = $r->getPrice();
                $ap['address'] = $r->getAddress()->getAddressLine1().' , '.$r->getAddress()->getLocality();
                if(count($r->getPhoto())==0)
                {
                    $ap['photo']=null;
                }
                else
                {
                    $ap['photo']=(($r->getPhoto())[0])->getPhoto();
                    $base64 = base64_encode($ap['photo']);
                    $photo = "data:" . 'image/jpeg' . ";base64," . $base64;
                    $ap['photo'] = $photo;
                }
                $result[]=$ap;
            }
            return $result;

        }


        public function loadByOwner(int $idOwner): ?array
        {
            $result=array();
            $db=FConnection::getInstance()->getConnection();
            try
            {
                $db->exec('LOCK TABLES accommodation READ');
                $q='SELECT id FROM accommodation WHERE idOwner=:id';
                $db->beginTransaction();
                $stm=$db->prepare($q);
                $stm->bindParam(':id',$idOwner,PDO::PARAM_INT);
                $stm->execute();
                $db->commit();
                $db->exec('UNLOCK TABLES');
                
            }
            catch(PDOException $e)
            {
                $db->rollBack();
                return null;
            }
            $rows=$stm->fetchAll(PDO::FETCH_ASSOC);
            foreach($rows as $row)
            {
                $accom=self::load($row['id']);
                $result[]=$accom;
            }
            return $result;
        }

        public function findAccommodationRating($id):int
        {
            $db=FConnection::getInstance()->getConnection();
            try
            {
                $q='SELECT AVG(r.valutation) AS rateA
                    FROM accommodation a INNER JOIN accommodationreview ar ON a.id=ar.idAccommodation
                    INNER JOIN review r ON r.id=ar.idReview
                    WHERE a.id=:id';
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
            if(is_null($row['rateA']))
            {
                return 0;
            }
            else
            {
                return (int)$row['rateA'];
            }
        }
        
        /**
         * Method areThereFreePlaces
         * 
         * this method return true if there are free places in the accommodation
         *
         * @param int $idAccommodation [accommodation's id]
         * @param int $year [year]
         *
         * @return bool
         */
        public function areThereFreePlaces(int $idAccommodation, int $year):bool
        {
            $db=FConnection::getInstance()->getConnection();
            $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
            FPersistentManager::getInstance()->updateDataBase();
            try
            {
                $q="SELECT DISTINCT COUNT(*) AS places_used , a.places AS total_places
                    FROM accommodation a INNER JOIN reservation r ON r.idAccommodation=a.id
                    INNER JOIN contract c ON c.idReservation=r.id
                    WHERE a.id= :idA
                    AND YEAR(r.fromDate)=:year
                    GROUP BY a.places";
                $db->exec('LOCK TABLES accommodation READ, reservation READ, contract READ');
                $db->beginTransaction();
                $stm=$db->prepare($q);
                $stm->bindParam(':year',$year,PDO::PARAM_INT);
                $stm->bindParam(':idA',$idAccommodation,PDO::PARAM_INT);
                $stm->execute();
                $db->commit();
                $db->exec('UNLOCK TABLES');
            }
            catch(PDOException $e)
            {
                $db->rollBack();
                print $e->getMessage();
            }
            $row=$stm->fetch(PDO::FETCH_ASSOC);

            if($row==false)# nel caso non ci sono contratti , no reservation per l' anno selezionato
            {
                return true;
            }
            else
            {
                if($row['total_places']>$row['places_used'])
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
        }
        public function getTitleById(int $id) {
            $db=FConnection::getInstance()->getConnection();
            try
            {
                $db->exec('LOCK TABLES accommodation READ');
                $q='SELECT title FROM accommodation WHERE id=:id';
                $db->beginTransaction();
                $stm=$db->prepare($q);
                $stm->bindParam(':id',$id,PDO::PARAM_INT);
                $stm->execute();
                $db->commit();
                $db->exec('UNLOCK TABLES');
            }
            catch(PDOException $e)
            {
                $db->rollBack();
                return null;
            }
            $row=$stm->fetch(PDO::FETCH_ASSOC);
            return $row['title'];
        }
    }

