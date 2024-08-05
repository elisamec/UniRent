<?php

namespace Classes\View;
require __DIR__.'/../../vendor/autoload.php';

use Classes\Entity\EAccommodation;
use Classes\Entity\EContract;
use Classes\Entity\EOwner;
use Classes\Entity\EReservation;
use Classes\Entity\EStudent;
use Classes\Entity\EVisit;
use StartSmarty;

class VOwner {
    private $smarty;

    public function __construct() {
        $this->smarty = StartSmarty::configuration();
    }

    //Mostra la home del proprietario
    public function home(array $accommodationsActive, array $accommodationsInactive) {
        $this->smarty->assign('accommodationsActive', json_encode($accommodationsActive));
        $this->smarty->assign('accommodationsInactive', json_encode($accommodationsInactive));
        $this->smarty->display('Owner/home.tpl');
    }
    public function accommodationManagement(EAccommodation $accomm, EOwner $owner, array $reviewsData, array $pictures, array $tenants, int $num_places, bool $disabled, bool $deletable):void{
        $photos=json_encode($pictures);
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->assign('imagesJson', $photos);
        $this->smarty->assign('accommodation', $accomm);
        $this->smarty->assign('owner', $owner);
        $this->smarty->assign('tenantsJson', json_encode($tenants));
        $this->smarty->assign('num_places', $num_places);
        $this->smarty->assign('disabled', $disabled);
        $this->smarty->assign('deletable', $deletable);
        $this->smarty->display('Owner/accommodationManagement.tpl');
    }

    //Mostra la seconda parte della registrazione proprietario
    public function showOwnerRegistration(){
        $this->smarty->assign('phoneError', false);
        $this->smarty->assign('ibanError', false);
        $this->smarty->assign('phone', '');
        $this->smarty->assign('iban', '');
        $this->smarty->display('Owner/register.tpl');
    }
    
    public function registrationError(bool $phoneError, bool $ibanError, string $phone, string $iban){
        $this->smarty->assign('phoneError', $phoneError);
        $this->smarty->assign('ibanError', $ibanError);
        $this->smarty->assign('phone', $phone);
        $this->smarty->assign('iban', $iban);
        $this->smarty->display('Owner/register.tpl');
    }

    /**
     * Show the owner's profile
     * 
     * @param EOwner $owner The owner's profile to display
     * @param string|null $photo The owner's profile photo
     */
    public function profile(EOwner $owner, ?string $photo){
        $this->smarty->assign('photo', $photo);
        $this->smarty->assign('owner', $owner);
        $this->smarty->display('Owner/personalProfile.tpl');
    }

    public function editProfile(EOwner $owner, ?string $photo, bool $usernameDuplicate, bool $emailDuplicate, bool $phoneError, bool $ibanError, bool $oldPasswordError, bool $passwordError){
        $this->smarty->assign('photo', $photo);
        $this->smarty->assign('owner', $owner);
        $this->smarty->assign('usernameDuplicate', $usernameDuplicate);
        $this->smarty->assign('emailDuplicate', $emailDuplicate);
        $this->smarty->assign('phoneError', $phoneError);
        $this->smarty->assign('ibanError', $ibanError);
        $this->smarty->assign('oldPasswordError', $oldPasswordError);
        $this->smarty->assign('passwordError', $passwordError);
        $this->smarty->display('Owner/editPersonalProfile.tpl');
    }
    public function contact(){
        $this->smarty->display('Owner/contact.tpl');
    }
    public function about(){
        $this->smarty->display('Owner/about.tpl');
    }
    public function reviews(array $reviewsData){
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->display('Owner/reviews.tpl');
    }

    public function addAccommodation()
    {
        $this->smarty->display('Owner/addAccommodation.tpl');
    }
    public function publicProfileFromOwner(EOwner $owner, array $reviewsData, ?string $kind="#", bool $self){
        $this->smarty->assign('owner', $owner);
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->assign('kind', $kind);
        $this->smarty->assign('self', $self);
        $this->smarty->display('Owner/publicProfileFromOwner.tpl');
    }
    public function publicProfileFromStudent(EOwner $owner, array $reviewsData, ?string $kind="#"){
        $this->smarty->assign('owner', $owner);
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->assign('kind', $kind);
        $this->smarty->display('Owner/publicProfileFromStudent.tpl');
    }
    public function postedReview(array $reviewsData){
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->display('Owner/postedReviews.tpl');
    }
    public function viewOwnerAds(array $accommodations, string $username){
        $this->smarty->assign('accommodations', json_encode($accommodations));
        $this->smarty->assign('username', $username);
        $this->smarty->display('Owner/viewOwnerAds.tpl');
    }
    public function editAccommodation(array $accomm, array $pictures, array $visitAvailabilityData, int $id){
        $photos=json_encode($pictures);
        $this->smarty->assign('accommodationId', $id);
        $this->smarty->assign('uploadedImagesData', $photos);
        $this->smarty->assign('accommodationData', json_encode($accomm));
        $this->smarty->assign('visitAvailabilityData', json_encode($visitAvailabilityData));
        $this->smarty->display('Owner/editAccommodation.tpl');
    }
    public function tenants(array $tenants, string $kind, array $accommodations, int $rating=0) {
        $this->smarty->assign('tenants', json_encode($tenants));
        $this->smarty->assign('kind', $kind);
        $this->smarty->assign('accommodationTitles', json_encode($accommodations));
        $this->smarty->assign('rating', $rating);
        $this->smarty->display('Owner/tenants.tpl');
    }
    public function visits(array $visitsData) {
        
        $this->smarty->assign('eventsData', json_encode($visitsData));
        $this->smarty->display('Owner/visits.tpl');
    }
    public function viewVisit(EVisit $visit, EStudent $student, EAccommodation $accommodation, string $accommodationPhoto, array $timeSlots, string $successEdit, string $successDelete) {
        
        $this->smarty->assign('visit', $visit);
        $this->smarty->assign('student', $student);
        $this->smarty->assign('accommodation', $accommodation);
        $this->smarty->assign('timeSlots', json_encode($timeSlots));
        $this->smarty->assign('accommodationImage', $accommodationPhoto);
        $this->smarty->assign('successEdit', $successEdit);
        $this->smarty->assign('successDelete', $successDelete);
        $this->smarty->display('Owner/visitDetails.tpl');
    }
    public function showReservations(array $reservationsData):void {
        
        $json =  json_encode($reservationsData);
        $this->smarty->assign('reservationsData', $json);
        $this->smarty->display('Owner/reservations.tpl');
    }
    public function reservationDetails(EReservation $reservation, EStudent $student, string $timeLeft, array $reviewsData):void {
        
        $this->smarty->assign('reservation', $reservation);
        $this->smarty->assign('student', $student);
        $this->smarty->assign('timeLeft', $timeLeft);
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->display('Owner/reservationDetails.tpl');
    }
    public function showContracts(array $contractsData, string $kind):void {
        
        $this->smarty->assign('contractsData', json_encode($contractsData));
        $this->smarty->assign('kind', $kind);
        $this->smarty->display('Owner/contracts.tpl');
    }
    public function contractDetails(EContract $contract, EStudent $student, array $reviewsData):void {
        
        $this->smarty->assign('contract', $contract);
        $this->smarty->assign('student', $student);
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->display('Owner/contractDetails.tpl');
    }
}
