<?php 
    require_once ('FConnection.php');
    require_once ('../Entity/EAccommodation.php');
    require_once ('FPhoto.php');
    require_once ('../Entity/EPhoto.php');
    require __DIR__ . '/../../vendor/autoload.php';

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
            $q="SELECT * FROM visit WHERE id=$accommodatioId";
            $db=FConnection::getInstance()->getConnection();
            $db->beginTransaction();
            $stm=$db->prepare($q);
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
        public function load(int $idAccommodation): EAccommodation{
            
            $FP=FPhoto::getInstance();
            $FA=FAccommodation::getInstance();
            $db=FConnection::getInstance()->getConnection();

            if($FA->exist($idAccommodation)){
              
                try{
                    $db->exec('LOCK TABLES accommodation READ');
                    $db->beginTransaction();
                    $q="SELECT * FROM accommodation WHERE id=$idAccommodation";    
                    $stm=$db->prepare($q);
                    $stm->execute();
                    $db->commit();
                    $db->exec('UNLOCK TABLES');

                }catch (PDOException $e){
                    $db->rollBack();
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
            }else{
                return null;
            }
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
                $q="SELECT * FROM address WHERE id=$idAddress";    
                $stm=$db->prepare($q);
                $stm->execute();
                $db->commit();
                $db->exec('UNLOCK TABLES');

            }catch (PDOException $e){
                $db->rollBack();
            }

            $row=$stm->fetch(PDO::FETCH_ASSOC);
            $address=new Address();
            $address = $address->withAddressLine1($row['addressLine'])->withPostalCode($row['postalCode'])->withLocality($row['city']);
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
                $q="SELECT * FROM day WHERE idAccommodation=$idAccommodation";    
                $stm=$db->prepare($q);
                $stm->execute();
                $db->commit();
                $db->exec('UNLOCK TABLES');

            }catch (PDOException $e){
                $db->rollBack();
            }

            $rows = $stm->fetchAll(PDO::FETCH_ASSOC);

            foreach ($rows as $row) {
                $times = $FA->loadTime($row['id']);
                $days = [$row['day'] => $times];
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
                $q="SELECT * FROM time WHERE idDay=$idDay";    
                $stm=$db->prepare($q);
                $stm->execute();
                $db->commit();
                $db->exec('UNLOCK TABLES');

            }catch (PDOException $e){
                $db->rollBack();
            }

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
            $db=FConnection::getInstance()->getConnection();
            $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
            
            try{ 
                $db->exec('LOCK TABLES accommodation WRITE');
                $db->beginTransaction();

                $photos = $accommodation->getPhoto();
                $title = $accommodation->getTitle();
                $start = $accommodation->getStart()->format('Y-m-d H:i:s');


                $q='INSERT INTO accommodation ( title, address, idAccommodation)';
                $q=$q.' VALUES (:title, :address, :idAccommodation)';

                $stm=$db->prepare($q);

                foreach($photos as $photo){
                    $result = $FP->store($photo);
                    if (!$result) {
                        throw new PDOException("Database operation failed.");
                        return false;
                    }
                }

                $stm->bindValue(':title',$title,PDO::PARAM_STR);
                //Indirizzo (chiave in acc. e tutto il restio in address)

                
                $stm->execute();
                $id=$db->lastInsertId();
                $db->commit();
                $db->exec('UNLOCK TABLES');
                $accommodation->setIdAccommodation($id);
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
        private function storeAddress(Address $address):int {

            $db=FConnection::getInstance()->getConnection();
            $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
            
            try
            { 
                $db->exec('LOCK TABLES address WRITE');
                $db->beginTransaction();

                $addressLine = $address->getAddressLine1();
                $postalCode = $address->getPostalCode();
                $city = $address->getLocality();

                $q='INSERT INTO address ( addressLine, postalCode, city)';
                $q=$q.' VALUES (:addressLine, :postalCode, :city)';

                $stm=$db->prepare($q);
                $stm->bindValue(':addressLine',$addressLine,PDO::PARAM_STR);
                $stm->bindValue(':postalCode',$postalCode,PDO::PARAM_STR);
                $stm->bindValue(':city',$city,PDO::PARAM_STR);
                
                $stm->execute();
                $id=$db->lastInsertId();
                $db->commit();
                $db->exec('UNLOCK TABLES');
                $address->withSortingCode($id);
                return $id;
            }      
            catch(PDOException $e)
            {
                $db->rollBack();
                return null;
            }
            
        }

    }