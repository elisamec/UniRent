<?php 

    namespace Classes\Foundation;
    require __DIR__ . '/../../vendor/autoload.php';

    use Classes\Foundation\FConnection;
    use Classes\Foundation\FPhoto;
    use Classes\Entity\EAccommodation;
    use Classes\Entity\EPhoto;
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
            $q="SELECT * FROM visit WHERE id=:id";
            $db=FConnection::getInstance()->getConnection();
            $db->beginTransaction();
            $stm=$db->prepare($q);
            $stm->bindParam(':id',$accommodatioId,PDO::PARAM_INT);
            $stm->execute();
            $db->commit();
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
                $row['deposit'],
                $visit, 
                $row['visitDuration'],
                $row['man'],
                $row['woman'],
                $row['pets'],
                $row['smokers'],
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
                

                $q='INSERT INTO accommodation (title, address, price, start, description, deposit, visitDuration, man, woman, pets, smokers, idOwner)';
                $q=$q.' VALUES (:title, :address, :price, :start, :description, :deposit, :visitDuration, :man, :woman, :pets, :smokers, :idOwner)';

                $stm=$db->prepare($q);

                $address = $FA->storeAddress($accommodation->getAddress());

                $stm->bindValue(':title',$accommodation->getTitle(),PDO::PARAM_STR);
                $stm->bindValue(':address',$address,PDO::PARAM_INT);
                $stm->bindValue(':price',$accommodation->getPrice(),PDO::PARAM_INT);
                $stm->bindValue(':start',$accommodation->getStart()->format('Y-m-d H:i:s'),PDO::PARAM_STR);
                $stm->bindValue(':description',$accommodation->getDescription(),PDO::PARAM_STR);
                $stm->bindValue(':deposit',$accommodation->getDeposit(),PDO::PARAM_INT);
                $stm->bindValue(':visitDuration',$accommodation->getVisitDuration(),PDO::PARAM_INT);
                $stm->bindValue(':man',$accommodation->getMan(),PDO::PARAM_BOOL);
                $stm->bindValue(':woman',$accommodation->getWoman(),PDO::PARAM_BOOL);
                $stm->bindValue(':pets',$accommodation->getPets(),PDO::PARAM_BOOL);
                $stm->bindValue(':smokers',$accommodation->getSmokers(),PDO::PARAM_BOOL);
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
                $db->commit();
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
                $db->commit();
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
        public function update(EAccommodation $accommodation):bool 
        {   
            $FA=FAccommodation::getInstance();
            $db=FConnection::getInstance()->getConnection();

            $accommodationId = $accommodation->getIdAccommodation();

            try{
                $db->exec('LOCK TABLES accommodation WRITE');
                $db->beginTransaction();
                
                $q='UPDATE accommodation SET title = :title, price = :price,
                                    start = :start, description = :description, deposit = :deposit,
                                    visitDuration = :visitDuration, man = :man, woman = :woman,
                                    pets = :pets, smokers = :smokers, idOwner = :idOwner  WHERE id=:id';
                
                $stm=$db->prepare($q);

                //Cambiare le photo
                //Cambiare i giorni di visita
                $res = $FA->updateAddress($accommodation->getAddress());
                $stm->bindValue(':title',$accommodation->getTitle(),PDO::PARAM_STR);
                $stm->bindValue(':price',$accommodation->getPrice(),PDO::PARAM_INT);
                $stm->bindValue(':start',$accommodation->getStart()->format('Y-m-d H:i:s'),PDO::PARAM_STR);
                $stm->bindValue(':description',$accommodation->getDescription(),PDO::PARAM_STR);
                $stm->bindValue(':deposit',$accommodation->getDeposit(),PDO::PARAM_INT);
                $stm->bindValue(':visitDuration',$accommodation->getVisitDuration(),PDO::PARAM_INT);
                $stm->bindValue(':man',$accommodation->getMan(),PDO::PARAM_BOOL);
                $stm->bindValue(':woman',$accommodation->getWoman(),PDO::PARAM_BOOL);
                $stm->bindValue(':pets',$accommodation->getPets(),PDO::PARAM_BOOL);
                $stm->bindValue(':smokers',$accommodation->getSmokers(),PDO::PARAM_BOOL);
                $stm->bindValue(':idOwner',$accommodation->getIdOwner(),PDO::PARAM_INT);
                $stm->bindValue(':id',$accommodationId,PDO::PARAM_INT);

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
         * private class that updates the days of visit of an accommodation in db
         * 
         * @param  int $idAccommodation
         * @param  string $day
         * @return bool
         */
        public function updateDay(int $idAccommodation, String $day):bool 
        {
            $db=FConnection::getInstance()->getConnection();
            $FA = FAccommodation::getInstance();

            try{
                $db->exec('LOCK TABLES day WRITE');
                $db->beginTransaction();
                
                $FA->deleteDay($idAccommodation);
                
                $db->exec('UNLOCK TABLES');

                return true;
            }
            catch(PDOException $e){
                $db->rollBack();
                return false;
            }
            
        }

        public function updateTime(int $idDay, array $times):bool 
        {
            $db=FConnection::getInstance()->getConnection();
            $FA = FAccommodation::getInstance();

            try{
                $db->exec('LOCK TABLES time WRITE');
                $db->beginTransaction();
                
                $FA->deleteTime($idDay);

                foreach($times as $time){
                    $FA->storeTime($idDay, $time);
                }

                $db->exec('UNLOCK TABLES');

                return true;
            }
            catch(PDOException $e){
                $db->rollBack();
                return false;
            }
            
        }

        public function deleteDay(int $idAccommodation):bool 
        {
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
                print "transazione iniziata";
                $db->commit();
                $db->exec('UNLOCK TABLES');

                return true;
            }
            catch(PDOException $e){
                $db->rollBack();
                return false;
            }
            
        }

        public function retriveDayId(int $idAccommodation):array 
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
         * deleteTime   
         * private class that deletes the times of visit of an accommodation in db
         * 
         * @param  int $idDay
         * @return bool
         * 
         */
        public function deleteTime(int $idDay):bool 
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

        public function findAccommodationsByCityAndDate($city, $date)
        {
            $result=array();
            $db=FConnection::getInstance()->getConnection();
            $date==='september' ? $date=10 : $date=9 ;
            try
            {
                $q ='SELECT a.id ';
                $q.=' FROM accommodation a INNER JOIN address adr ON a.address=adr.id';
                $q.=' WHERE MONTH(a.start)= :date  AND adr.city = :city';
                $db->exec('LOCK TABLES accommodation READ , address READ');
                $db->beginTransaction();
                $stm=$db->prepare($q);
                $stm->bindParam(':city',$city,PDO::PARAM_STR);
                $stm->bindParam(':date',$date,PDO::PARAM_INT);
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

    }