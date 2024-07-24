<?php 

namespace Classes\Foundation;

use PDOException;
use Updater\Updater;
use PDO;

class FUpdater
{
    private static $instance;
    private function __construct(){}

    public static function getInstance()
    {
        if (!self::$instance) 
        {
            self::$instance = new FUpdater();
        }
        return self::$instance;
    }

    public function updateDB()
    {
        $db=FConnection::getInstance()->getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
        try
        {
            $db->exec('LOCK TABLES contract WRITE, reservation WRITE');
            include __DIR__.'/../../Updater/day.php';
            $d=$day;
            if($cont==0)
            {
                $db->beginTransaction();
// CONTRACTS UPDATE
                #seleziona gli id dei contratti che dovrebbero essere messi come onGoing
                $q="SELECT 	r.id 
                    FROM reservation r 
                    INNER JOIN contract c ON r.id = c.idReservation             
                    WHERE DateDiff(fromDate, NOW())<0
                    AND DateDiff(toDate, NOW())>0";
                $stm=$db->prepare($q);
                $stm->execute();
                $res=$stm->fetchAll(PDO::FETCH_COLUMN,0);

                //update contract to onGoing
                if(!empty($res))
                {
                    $ids = implode(',', array_map('intval', $res));
                    $updateQuery = "UPDATE contract SET status = 'onGoing' WHERE idReservation IN ($ids)";
                    $updateStm = $db->prepare($updateQuery);
                    $updateStm->execute();
                }

                //select contract to finished
                $q1="SELECT r.id 
                     FROM reservation r 
                     INNER JOIN contract c ON r.id = c.idReservation           #seleziona gli id dei contratti che dovrebbero essere messi come finished
                     WHERE DateDiff(toDate, NOW())<0";

                $stm1=$db->prepare($q1);
                $stm1->execute();
                $res1=$stm1->fetchAll(PDO::FETCH_COLUMN,0);

                //update contract to finished
                if(!empty($res1))
                {
                    $ids1 = implode(',', array_map('intval', $res1));
                    $updateQuery1 = "UPDATE contract SET status='finished'
                                     WHERE idReservation IN ($ids1)";
                    $updateStm1 = $db->prepare($updateQuery1);
                    $updateStm1->execute();
                }

// RESERVATION UPDATE

                #select the reservation to be accepted
                $q2="WITH freeTable AS (
                                        SELECT DISTINCT 
                                        a1.id AS idAccommodation, 
                                        a1.places - (
                                        SELECT COUNT(*)
                                        FROM contract c2
                                        INNER JOIN reservation r2 ON c2.idReservation = r2.id
                                        WHERE r2.idAccommodation = a1.id AND EXTRACT(YEAR FROM r2.fromDate) = EXTRACT(YEAR FROM r1.fromDate)
                                        ) AS freePlaces, 
                                        EXTRACT(YEAR FROM r1.fromDate) AS year
                                        FROM accommodation a1
                                        INNER JOIN reservation r1 ON a1.id = r1.idAccommodation
                                        )
                                        
                    SELECT DISTINCT r.id
                    FROM reservation r 
                    INNER JOIN accommodation a ON a.id = r.idAccommodation
                    WHERE r.id NOT IN (SELECT r3.id
				    FROM reservation r3
				    INNER JOIN contract c3 ON c3.idReservation = r3.id)
                    AND DATEDIFF(r.made, NOW())<-2
                    AND r.statusAccept = 0
                    AND EXISTS (
                                SELECT 1
                                FROM freeTable ft
                                WHERE ft.idAccommodation = a.id
                                AND ft.year = EXTRACT(YEAR FROM r.fromDate)
                                )
                    ORDER BY r.made ASC";
                $stm2=$db->prepare($q2);
                $stm2->execute();
                $res2=$stm2->fetchAll(PDO::FETCH_COLUMN,0);

                
                //update reservation to accepted after 2 days
                if(!empty($res2))
                {
                    $ids2 = implode(',', array_map('intval', $res2));
                    $updateQuery2 = "UPDATE reservation SET status = 1, made=CURRENT_TIMESTAMP
                                     WHERE id IN ($ids2)";
                    $updateStm2 = $db->prepare($updateQuery2);
                    $updateStm2->execute();
                }

                

                //select reservation's ids to delete
                $q3="SELECT id
                     FROM reservation
                     WHERE DateDiff(made, NOW())<-2
                     AND id NOT IN (SELECT r1.id
			                        FROM contract c
			                        INNER JOIN reservation r1 ON r1.id = c.idReservation)";

                $stm3=$db->prepare($q3);
                $stm3->execute();
                $res3=$stm3->fetchAll(PDO::FETCH_COLUMN,0);

                //delete reservation after 2 days 
                if(!empty($res3))
                {
                    $ids3 = implode(',', array_map('intval', $res3));
                    $updateQuery3 = "DELETE FROM reservation WHERE id IN ($ids3)";
                    $updateStm3 = $db->prepare($updateQuery3);
                    $updateStm3->execute();
                }


                //delete reservation if there is no more free places
                $q4="DELETE FROM reservation
                     WHERE id IN (
                                  SELECT r2.id
                                  FROM (
                                        SELECT a1.id AS idAccommodation,
                                        (SELECT COUNT(*)
                                         FROM contract c2
                                         INNER JOIN reservation r2 ON c2.idReservation = r2.id
                                         WHERE r2.idAccommodation = a1.id AND EXTRACT(YEAR FROM r2.fromDate) = EXTRACT(YEAR FROM r1.fromDate)
                                        ) AS freePlaces,
                                        EXTRACT(YEAR FROM r1.fromDate) AS year
                                        FROM accommodation a1
                                        INNER JOIN reservation r1 ON a1.id = r1.idAccommodation
                                        ) AS freeTable
                                        INNER JOIN reservation r2 ON freeTable.idAccommodation = r2.idAccommodation
                                        AND freeTable.year = EXTRACT(YEAR FROM r2.fromDate)
                                        WHERE freeTable.freePlaces = 0
                                        )";
                        
                $stm4 = $db->prepare($q4);
                $stm4->execute();

                //END UPDATE OPERATIONS

                $result=$db->commit();
                if($result)
                {
                    Updater::getInstance()->updateDayFile($d,1);
                    #print 'ok';
                }
                $db->exec('UNLOCK TABLES');
                return;
            }
            else
            {
                $db->exec('UNLOCK TABLES');
                return;
            }
        }
        catch(PDOException $e)
        {
            $db->rollBack();
            $db->exec('UNLOCK TABLES');
            http_response_code(500);
        }
    }
}