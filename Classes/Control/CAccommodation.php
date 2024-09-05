<?php 

namespace Classes\Control;

use Classes\Foundation\FPersistentManager;
use Classes\Utilities\USuperGlobalAccess;
use Classes\View\VOwner;
use Classes\Entity\EPhoto;
use Classes\Entity\EAccommodation;
use Classes\Utilities\USession;
use CommerceGuys\Addressing\Address;
use Classes\View\VError;
use DateTime;

require __DIR__.'/../../vendor/autoload.php';

/** * 
 * This class is responsible for managing accommodations.
 * 
 * @package Classes\Control
 */
class CAccommodation {

    /**
     * Method addAccommodation
     * This method shows the add accommodation page
     * 
     * @return void
     */
    public static function addAccommodation() :void
    {   
        COwner::checkIfOwner();
        $view=new VOwner();
        $view->addAccommodation();
    }
        
    /**
     * Method addAccommodationOperations
     *
     * This method is used to add the accommodation to the database
     * 
     * @return void
     */
    public static function addAccommodationOperations()
    {
        $afs = USuperGlobalAccess::getAllPost(['title', 'price', 'deposit', 'startDate', 'month', 'visitAvailabilityData', 'places',
            'address', 'city', 'postalCode', 'description', 'men', 'women', 'smokers', 'animals', 'uploadedImagesData']);
        $myarray = json_decode($afs['uploadedImagesData'], true) ?? [];
        $array_photos = EPhoto::fromJsonToPhotos($myarray);
        $duration = EAccommodation::DurationOfVisit($afs["visitAvailabilityData"]);

        if (!is_null($duration) && $duration > 0) {
            $array_visit = EAccommodation::fromJsonToArrayOfVisit($afs['visitAvailabilityData']);
        } else {
            $array_visit = array();
            $duration = 0;
        }
        $afs['month'] = $afs['month'] == 'Sep' ? 8 : 9;
        $men = USession::getInstance()->booleanSolver($afs['men']);
        $women = USession::getInstance()->booleanSolver($afs['women']);
        $smokers = USession::getInstance()->booleanSolver($afs['smokers']);
        $animals = USession::getInstance()->booleanSolver($afs['animals']);
        $date = new DateTime('now');
        $date = $date->setDate((int)$date->format('Y'), $afs['month'], (int)$afs['startDate']);
        $idOwner = USession::getInstance()->getSessionElement('id');
        $addressObj = new Address();
        $addressObj = $addressObj->withAddressLine1($afs['address'])->withPostalcode($afs['postalCode'])->withLocality($afs['city']);
        $accomodation = new EAccommodation(null, $array_photos, $afs['title'], $addressObj, $afs['price'], $date, $afs['description'], (int)$afs['places'], (float)$afs['deposit'], $array_visit, $duration, $men, $women, $animals, $smokers, true, $idOwner);
        $result = FPersistentManager::getInstance()->store($accomodation);
        $result ? header('Location:/UniRent/Owner/home/success') : header('Location:/UniRent/Owner/home/error');
    }

    /**
     * Deactivate Accommodation
     * @param int $idAccommodation
     * @return void
     */
    public static function deactivate(int $idAccommodation):void {
        $PM= FPersistentManager::getInstance();
        $accommodation=$PM->load('EAccommodation', $idAccommodation);
        $accommodation->setStatus(false);
        $res=$PM->update($accommodation);
        $modalSuccess = $res ? 'success' : 'error';
        header('Location:'.USuperGlobalAccess::getCookie('current_page').'/'.$modalSuccess);
    }
    
    /**
     * Activate Accommodation
     * @param int $idAccommodation
     * @return void
     */
    public static function activate(int $idAccommodation):void {
        $PM= FPersistentManager::getInstance();
        $accommodation=$PM->load('EAccommodation', $idAccommodation);
        $accommodation->setStatus(true);
        $res=$PM->update($accommodation);
        $modalSuccess = $res ? 'success' : 'error';
        header('Location:'.USuperGlobalAccess::getCookie('current_page').'/'.$modalSuccess);
    }

    /**
     * Deletes the accommodation
     * @param int $id of the accommodation
     * @return void
     */
    public static function delete(int $id):void {
        $PM=FPersistentManager::getInstance();
        $result=$PM->delete('EAccommodation', $id);
        $result? header('Location:/UniRent/Owner/home/success') : header('Location:/UniRent/Owner/home/error');
    }

          
     /**
      * Method editAccommodation

      * this method is used to edit the accommodation, is used to show the template for editing
      * @param string $id [accommodation ID]
      *
      * @return void
      */
     public static function editAccommodation(string $id) {
        COwner::checkIfOwner();
        $session = USession::getInstance();
        $view = new VOwner();
        $PM=FPersistentManager::getInstance();
        $accommodation = $PM->load('EAccommodation', (int)$id);
        if ($accommodation->getIdOwner() !== $session->getSessionElement('id')) {
            $viewError= new VError();
            $viewError->error(403);
            exit();
        }
        $photos_acc=$accommodation->getPhoto();
        $uploadedPhotos=EPhoto::toBase64($photos_acc);

        $img=array();
        foreach($uploadedPhotos as $p)
        {
            $img[]=$p->getPhoto();
        }
        $accommodationData=$accommodation->getData();
        
        $visitAvailabilityData= $accommodation->getVisitAvailabilityData();
        $view->editAccommodation($accommodationData, $img , $visitAvailabilityData, $id);
    }

    
    /**
     * Method editAccommodationOperations
     *
     * this is the method used by the contoller to update the accommodation, this call the PersistantManager to the operations
     * @param int $id [accommodation ID]
     *
     * @return void
     */
    public static function editAccommodationOperations(int $id) :void
    {
            COwner::checkIfOwner();
            $afs=USuperGlobalAccess::getAllPost(['uploadedImagesData','title','price','deposit','startDate','month','visitAvailabilityData',
                                                 'places','address','city','postalCode','description','men','women','smokers','animals']);
            $myarray=json_decode($afs['uploadedImagesData'],true) ?? [];
            $array_photos=EPhoto::fromJsonToPhotos($myarray);
            $duration=EAccommodation::DurationOfVisit($afs['visitAvailabilityData']);
            if(!is_null($duration) and $duration>0)  #se la durata delle visite è zero non ci saranno visite
            {
                $array_visit=EAccommodation::fromJsonToArrayOfVisit($afs['visitAvailabilityData']);
            }
            else
            {
                $array_visit=array();
                $duration=0;
            }
            $afs['month'] = $afs['month']=='september' ? 8 : 9;
            $men=USession::getInstance()->booleanSolver($afs['men']);
            $women=USession::getInstance()->booleanSolver($afs['women']);
            $smokers=USession::getInstance()->booleanSolver($afs['smokers']);
            $animals=USession::getInstance()->booleanSolver($afs['animals']);
            $date= new DateTime('now');
            $year=(int)$date->format('Y');
            $date=$date->setDate($year,$afs['month'],(int)$afs['startDate']);
            $status=FPersistentManager::getInstance()->load('EAccommodation',$id)->getStatus();
            $idOwner=USession::getInstance()->getSessionElement('id');
            $addressId = FPersistentManager::getInstance()->load('EAccommodation', $id)->getAddress()->getSortingCode();
            $addressObj= new Address();
            $addressObj=$addressObj->withAddressLine1($afs['address'])->withPostalcode($afs['postalCode'])->withLocality($afs['city'])->withSortingCode($addressId);
            $accomodation = new EAccommodation($id,$array_photos,$afs['title'],$addressObj,(float)$afs['price'],$date,$afs['description'],(int)$afs['places'],(float)$afs['deposit'],$array_visit,$duration,$men,$women,$animals,$smokers,$status,$idOwner);
            $result=FPersistentManager::getInstance()->update($accomodation);
            $id = $accomodation->getIdAccommodation();
            $result ? header('Location:/UniRent/Owner/accommodationManagement/'.$id.'/success') : header('Location:/UniRent/Owner/accommodationManagement/'.$id.'/error');
    }
    
}