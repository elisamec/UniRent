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
            
            try{
                $db->exec('LOCK TABLES visit READ');
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
                $db->exec('LOCK TABLES visit READ');
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
                $db->exec('LOCK TABLES visit READ');
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
                $db->exec('LOCK TABLES visit READ');
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

    }