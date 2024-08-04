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
    private function assignReplies(array $replies, int $countReply) {
        $this->smarty->assign('replies', json_encode($replies));
        $this->smarty->assign('countReply', $countReply);
    }

    //Mostra la home del proprietario
    public function home(array $accommodationsActive, array $accommodationsInactive, array $replies, int $countReply) {
        $this->assignReplies($replies, $countReply);
        $this->smarty->assign('accommodationsActive', json_encode($accommodationsActive));
        $this->smarty->assign('accommodationsInactive', json_encode($accommodationsInactive));
        $this->smarty->display('Owner/home.tpl');
    }
    public function accommodationManagement(EAccommodation $accomm, EOwner $owner, array $reviewsData, array $pictures, array $tenants, int $num_places, bool $disabled, bool $deletable, array $replies, int $countReply):void{
        $photos=json_encode($pictures);
        $this->assignReplies($replies, $countReply);
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
    public function profile(EOwner $owner, ?string $photo, array $replies, int $countReply){
        $this->assignReplies($replies, $countReply);
        $this->smarty->assign('photo', $photo);
        $this->smarty->assign('owner', $owner);
        $this->smarty->display('Owner/personalProfile.tpl');
    }

    public function editProfile(EOwner $owner, ?string $photo, bool $usernameDuplicate, bool $emailDuplicate, bool $phoneError, bool $ibanError, bool $oldPasswordError, bool $passwordError, array $replies, int $countReply){
        $this->assignReplies($replies, $countReply);
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
    public function contact(array $replies, int $countReply){
        $this->assignReplies($replies, $countReply);
        $this->smarty->display('Owner/contact.tpl');
    }
    public function about(array $replies, int $countReply){
        $this->assignReplies($replies, $countReply);
        $this->smarty->display('Owner/about.tpl');
    }
    public function reviews(array $reviewsData, array $replies, int $countReply){
        $this->assignReplies($replies, $countReply);
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->display('Owner/reviews.tpl');
    }

    public function addAccommodation(array $replies, int $countReply)
    {
        $this->assignReplies($replies, $countReply);
        $this->smarty->display('Owner/addAccommodation.tpl');
    }
    public function publicProfileFromOwner(EOwner $owner, array $reviewsData, ?string $kind="#", bool $self, array $replies, int $countReply){
        $this->assignReplies($replies, $countReply);
        $this->smarty->assign('owner', $owner);
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->assign('kind', $kind);
        $this->smarty->assign('self', $self);
        $this->smarty->display('Owner/publicProfileFromOwner.tpl');
    }
    public function publicProfileFromStudent(EOwner $owner, array $reviewsData, ?string $kind="#", array $replies, int $countReply){
        $this->assignReplies($replies, $countReply);
        $this->smarty->assign('owner', $owner);
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->assign('kind', $kind);
        $this->smarty->display('Owner/publicProfileFromStudent.tpl');
    }
    public function postedReview(array $reviewsData, array $replies, int $countReply){
        $this->assignReplies($replies, $countReply);
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->display('Owner/postedReviews.tpl');
    }
    public function viewOwnerAds(array $accommodations, string $username, array $replies, int $countReply){
        $this->assignReplies($replies, $countReply);
        $this->smarty->assign('accommodations', json_encode($accommodations));
        $this->smarty->assign('username', $username);
        $this->smarty->display('Owner/viewOwnerAds.tpl');
    }
    public function editAccommodation(array $accomm, array $pictures, array $visitAvailabilityData, int $id, array $replies, int $countReply){
        $this->assignReplies($replies, $countReply);
        $photos=json_encode($pictures);
        $this->smarty->assign('accommodationId', $id);
        $this->smarty->assign('uploadedImagesData', $photos);
        $this->smarty->assign('accommodationData', json_encode($accomm));
        $this->smarty->assign('visitAvailabilityData', json_encode($visitAvailabilityData));
        $this->smarty->display('Owner/editAccommodation.tpl');
    }
    public function tenants(array $tenants, string $kind, array $accommodations, int $rating=0, array $replies, int $countReply) {
        $this->assignReplies($replies, $countReply);
        $this->smarty->assign('tenants', json_encode($tenants));
        $this->smarty->assign('kind', $kind);
        $this->smarty->assign('accommodationTitles', json_encode($accommodations));
        $this->smarty->assign('rating', $rating);
        $this->smarty->display('Owner/tenants.tpl');
    }
    public function visits(array $visitsData, array $replies, int $countReply) {
        $this->assignReplies($replies, $countReply);
        $this->smarty->assign('eventsData', json_encode($visitsData));
        $this->smarty->display('Owner/visits.tpl');
    }
    public function viewVisit(EVisit $visit, EStudent $student, EAccommodation $accommodation, string $accommodationPhoto, array $timeSlots, string $successEdit, string $successDelete, array $replies, int $countReply) {
        $this->assignReplies($replies, $countReply);
        $this->smarty->assign('visit', $visit);
        $this->smarty->assign('student', $student);
        $this->smarty->assign('accommodation', $accommodation);
        $this->smarty->assign('timeSlots', json_encode($timeSlots));
        $this->smarty->assign('accommodationImage', $accommodationPhoto);
        $this->smarty->assign('successEdit', $successEdit);
        $this->smarty->assign('successDelete', $successDelete);
        $this->smarty->display('Owner/visitDetails.tpl');
    }
    public function showReservations(array $reservationsData, array $replies, int $countReply):void {
        $this->assignReplies($replies, $countReply);
        $json =  json_encode($reservationsData);
        $this->smarty->assign('reservationsData', $json);
        $this->smarty->display('Owner/reservations.tpl');
    }
    public function reservationDetails(EReservation $reservation, EStudent $student, string $timeLeft, array $reviewsData, array $replies, int $countReply):void {
        $this->assignReplies($replies, $countReply);
        $this->smarty->assign('reservation', $reservation);
        $this->smarty->assign('student', $student);
        $this->smarty->assign('timeLeft', $timeLeft);
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->display('Owner/reservationDetails.tpl');
    }
    public function showContracts(array $contractsData, string $kind, array $replies, int $countReply):void {
        $this->assignReplies($replies, $countReply);
        $this->smarty->assign('contractsData', json_encode($contractsData));
        $this->smarty->assign('kind', $kind);
        $this->smarty->display('Owner/contracts.tpl');
    }
    public function contractDetails(EContract $contract, EStudent $student, array $reviewsData, array $replies, int $countReply):void {
        $this->assignReplies($replies, $countReply);
        $this->smarty->assign('contract', $contract);
        $this->smarty->assign('student', $student);
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->display('Owner/contractDetails.tpl');
    }
}
