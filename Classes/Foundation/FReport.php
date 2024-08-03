<?php
namespace Classes\Foundation;
require __DIR__.'../../../vendor/autoload.php';
use Classes\Foundation\FConnection;
use Classes\Entity\EReport;
use Classes\Tools\TType;
use DateTime;
use PDO;
use PDOException;

class FReport {
    private static $instance=null;

    /**Constructor */
    private function __construct(){}

    /**This static method gives the istance of this singleton class
     * @return  
    */
    public static function getInstance()
    {
        if(is_null(self::$instance))
        {
            self::$instance = new FReport();
        }
        return self::$instance;
    }

    public function exist(int $idReport):bool 
    {
        $q="SELECT * FROM report WHERE id=:idReport";
        $db=FConnection::getInstance()->getConnection();
        $db->beginTransaction();
        $stm=$db->prepare($q);
        $stm->bindParam(':idReport',$idReport,PDO::PARAM_INT);
        $stm->execute();
        $db->commit();
        $result=$stm->rowCount();

        if ($result >0) return true;
        return false;
    }
    public function load(int $idReport):EReport | bool {
        if ($this->exist($idReport))
        {
            try {
                $q="SELECT * FROM report WHERE id=:idReport";
                $db=FConnection::getInstance()->getConnection();
                $db->exec('LOCK TABLE report READ');
                $db->beginTransaction();
                $stm=$db->prepare($q);
                $stm->bindParam(':idReport',$idReport,PDO::PARAM_INT);
                $stm->execute();
                $db->commit();
                $db->exec('UNLOCK TABLES');
            } catch (PDOException $e) {
                $db->rollBack();
                return false;
            }
            $result=$stm->fetch(PDO::FETCH_ASSOC);
            if ($result['idStudent']!=null) {
                $idSubject=$result['idStudent'];
                $type=TType::STUDENT;
            } else if ($result['idOwner']!=null) {
                $idSubject=$result['idOwner'];
                $type=TType::OWNER;
            } else if ($result['idReview']!=null) {
                $idSubject=$result['idReview'];
                $type=TType::REVIEW;
            }
            if ($result['banDate']!=null) {
                $banDate=new DateTime($result['banDate']);
            } else {
                $banDate=null;
            }
            $report=new EReport($result['id'],$result['description'],new DateTime($result['made']),$banDate,$idSubject,$type);
            return $report;
        } else {
            return false;
        }
    }
    public function store(EReport $report):bool {
        try {
            $q="INSERT INTO report (description,banDate,idStudent,idOwner,idReview) VALUES (:description,:banDate,:idStudent,:idOwner,:idReview)";
            $db=FConnection::getInstance()->getConnection();
            $db->exec('LOCK TABLE report WRITE');
            $db->beginTransaction();
            $stm=$db->prepare($q);
            $description=$report->getDescription();
            $banDate=$report->getBanDate()==null? null : $report->getBanDate()->format('Y-m-d');
            $idSubject=$report->getIdSubject();
            $type = $report->getSubjectType();
            $null=null;
            if ($type === TType::OWNER) {
                $stm->bindParam(':idOwner',$idSubject,PDO::PARAM_INT);
                $stm->bindParam(':idStudent',$null,PDO::PARAM_NULL);
                $stm->bindParam(':idReview',$null,PDO::PARAM_NULL);

            } else if ($type === TType::STUDENT) {
                $stm->bindParam(':idStudent',$idSubject,PDO::PARAM_INT);
                $stm->bindParam(':idOwner',$null,PDO::PARAM_NULL);
                $stm->bindParam(':idReview',$null,PDO::PARAM_NULL);
            } else if ($type === TType::REVIEW) {
                $stm->bindParam(':idReview',$idSubject,PDO::PARAM_INT);
                $stm->bindParam(':idStudent',$null,PDO::PARAM_NULL);
                $stm->bindParam(':idOwner',$null,PDO::PARAM_NULL);
            }
            $stm->bindParam(':description',$description,PDO::PARAM_STR);
            $stm->bindParam(':banDate', $banDate,PDO::PARAM_STR);
            $stm->execute();
            $id=$db->lastInsertId();
            $db->commit();
            $db->exec('UNLOCK TABLES');
            $report->setId($id);
            return true;
        } catch (PDOException $e) {
            $db->rollBack();
            return false;
        }
    }
    public function update(EReport $report):bool {
        try {
            $q="UPDATE report SET description=:description,banDate=:banDate WHERE id=:idReport";
            $db=FConnection::getInstance()->getConnection();
            $db->exec('LOCK TABLE report WRITE');
            $db->beginTransaction();
            $stm=$db->prepare($q);
            $description=$report->getDescription();
            $banDate=$report->getBanDate()->format('Y-m-d');
            $id=$report->getId();
            $stm->bindParam(':description',$description,PDO::PARAM_STR);
            $stm->bindParam(':banDate', $banDate,PDO::PARAM_STR);
            $stm->bindParam(':idReport',$id,PDO::PARAM_INT);
            $stm->execute();
            $db->commit();
            $db->exec('UNLOCK TABLES');
            return true;
        } catch (PDOException $e) {
            $db->rollBack();
            return false;
        }
    }
    public function delete(EReport $report):bool {
        try {
            $q="DELETE FROM report WHERE id=:idReport";
            $db=FConnection::getInstance()->getConnection();
            $db->exec('LOCK TABLE report WRITE');
            $db->beginTransaction();
            $stm=$db->prepare($q);
            $id=$report->getId();
            $stm->bindParam(':idReport',$id,PDO::PARAM_INT);
            $stm->execute();
            $db->commit();
            $db->exec('UNLOCK TABLES');
            return true;
        } catch (PDOException $e) {
            $db->rollBack();
            return false;
        }
    }
    public function getLastBanReportBySubject(int $subjectId, TType | string $type):EReport | bool {
        if (!is_string($type)) {
            $type=$type->value;
        }
        $db=FConnection::getInstance()->getConnection();
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $q="SELECT * FROM report WHERE id". ucfirst($type)."=:idSubject AND banDate IS NOT NULL ORDER BY banDate DESC LIMIT 1";
            $db->exec('LOCK TABLE report READ');
            $db->beginTransaction();
            $stm=$db->prepare($q);
            $stm->bindParam(':idSubject',$subjectId,PDO::PARAM_INT);
            $stm->execute();
            $db->commit();
            $db->exec('UNLOCK TABLES');
        } catch (PDOException $e) {
            $db->rollBack();
            return false;
        }
        $result=$stm->fetch(PDO::FETCH_ASSOC);
        if ($result['idStudent']!=null) {
            $idSubject=$result['idStudent'];
            $type=TType::STUDENT;
        } else if ($result['idOwner']!=null) {
            $idSubject=$result['idOwner'];
            $type=TType::OWNER;
        } else if ($result['idReview']!=null) {
            $idSubject=$result['idReview'];
            $type=TType::REVIEW;
        }
        if ($result['banDate']!=null) {
            $banDate=new DateTime($result['banDate']);
        } else {
            $banDate=null;
        }
        $report=new EReport($result['id'],$result['description'],new DateTime($result['made']),$banDate,$idSubject,$type);
        return $report;
    }
    
    /**
     * Method getAllReport
     *
     * this method return all the reports
     * @return array
     */
    public function getAllReport():array
    {
        $db=FConnection::getInstance()->getConnection();
        try
        {
            $q="SELECT id
                FROM report
                ORDER BY 
                    bandate IS NULL DESC, 
                    CASE 
                        WHEN bandate IS NULL THEN made 
                        ELSE NULL 
                    END DESC,
                    CASE 
                        WHEN bandate IS NOT NULL THEN bandate 
                        ELSE NULL 
                    END ASC
                LOCK IN SHARE MODE";
            $db->beginTransaction();
            $stm=$db->prepare($q);
            $stm->execute();
            $db->commit();
        }
        catch(PDOException $e)
        {
            $db->rollBack();
            return [];
        }
        $result=$stm->fetchAll(PDO::FETCH_ASSOC);
        foreach($result as $report)
        {
            $reports[]=$this->load($report['id']);
        }
        return $reports;
    }
}

