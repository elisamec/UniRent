<?php

namespace Classes\View;
require __DIR__.'/../../vendor/autoload.php';

use Classes\Entity\EAccommodation;
use Classes\Entity\EContract;
use Classes\Entity\ECreditCard;
use Classes\Entity\EOwner;
use Classes\Entity\EPhoto;
use Classes\Entity\EReservation;
use StartSmarty;
use Classes\Entity\EStudent;
use Classes\Entity\EVisit;

class VStudent{
    private $smarty;

    public function __construct(){

        $this->smarty = StartSmarty::configuration();
    }

    public function home(array $accommodations, ?string $modalSuccess):void{
        $this->smarty->assign('accommodations', json_encode($accommodations));
        $this->smarty->assign('modalSuccess', $modalSuccess);
        $this->smarty->display('Student/home.tpl');
    }

    /**
     * Show the student's profile
     * 
     * @param EStudent $student The student's profile to display
     * @param string|null $photo The student's profile photo
     * @return void
     */
    public function profile(EStudent $student, ?string $photo, ?string $modalSuccess) :void {

        $this->smarty->assign('photo', $photo);
        $this->smarty->assign('modalSuccess', $modalSuccess);
        $this->smarty->assign('student', $student);
        $this->smarty->display('Student/personalProfile.tpl');
    }

    /**
     * Show form for editing the student's profile
     * 
     * @param EStudent $student The student's profile to edit
     * @param string|null $photo The student's profile photo
     * @return void
     */
    public function editProfile(EStudent $student, ?string $photo, bool $passwordError, bool $usernameDuplicate, bool $emailError, bool $oldPasswordError, ?string $modalSuccess) :void {
        $this->smarty->assign('photo', $photo);
        $this->smarty->assign('student', $student);
        $this->smarty->assign('passwordError', $passwordError);
        $this->smarty->assign('usernameDuplicate', $usernameDuplicate);
        $this->smarty->assign('emailError', $emailError);
        $this->smarty->assign('oldPasswordError', $oldPasswordError);
        $this->smarty->assign('modalSuccess', $modalSuccess);
        $this->smarty->display('Student/editPersonalProfile.tpl');
    }

    //Mostra la seconda parte della registrazione studente
    public function showStudentRegistration():void{
        $this->smarty->display('Student/register.tpl');
    }
    public function contact(?string $modalSuccess):void{
        $this->smarty->assign('modalSuccess', $modalSuccess);
        $this->smarty->display('Student/contact.tpl');
    }
    public function about():void{
        $this->smarty->display('Student/about.tpl');
    }
    public function findAccommodation(string $selectedCity, string $selectedUni, array $searchResult, string $date, int $ratingOwner, int $ratingAccommodation, int $minPrice, int $maxPrice):void{
        $this->smarty->assign('selectedCity', $selectedCity);
        $this->smarty->assign('selectedUni', $selectedUni);
        $this->smarty->assign('selectedDate', $date);
        $this->smarty->assign('searchResult', json_encode($searchResult));
        $this->smarty->assign('ratingOwner', $ratingOwner);
        $this->smarty->assign('ratingAccommodation', $ratingAccommodation);
        $this->smarty->assign('minPrice', $minPrice);
        $this->smarty->assign('maxPrice', $maxPrice);
        $this->smarty->display('Student/search.tpl');
    }
    public function accommodation(EAccommodation $accomm, EOwner $owner, array $reviewsData, string $period, array $pictures, array $timeSlots, int $duration, array $tenantsJson, int $num_places, bool $booked, string $day, string $time, bool $disabled, string $successReserve, string $successVisit, int $leavebleReviews):void{
        $photos=json_encode($pictures);
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->assign('imagesJson', $photos);
        $this->smarty->assign('accommodation', $accomm);
        $this->smarty->assign('owner', $owner);
        $this->smarty->assign('period', $period);
        $this->smarty->assign('timeSlots', json_encode($timeSlots));
        $this->smarty->assign('duration', $duration);
        $this->smarty->assign('tenantsJson', json_encode($tenantsJson));
        $this->smarty->assign('num_places', $num_places);
        $this->smarty->assign('booked', $booked);
        $this->smarty->assign('day', $day);
        $this->smarty->assign('time', $time);
        $this->smarty->assign('disabled', $disabled);
        $this->smarty->assign('successReserve', $successReserve);
        $this->smarty->assign('successVisit', $successVisit);
        $this->smarty->assign('leavebleReviews', $leavebleReviews);
        $this->smarty->display('Student/accommodation.tpl');
    }
    public function reviews(array $reviewsData, ?string $modalSuccess):void{
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->assign('modalSuccess', $modalSuccess);
        $this->smarty->display('Student/reviews.tpl');
    }
    public function publicProfileFromStudent(EStudent $student, array $reviewsData, ?string $kind="#", bool $self, int $roomate, ?string $modalSuccess):void{
        $this->smarty->assign('student', $student);
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->assign('kind', $kind);
        $this->smarty->assign('self', $self);
        $this->smarty->assign('roomate', $roomate);
        $this->smarty->assign('modalSuccess', $modalSuccess);
        $this->smarty->display('Student/publicProfileFromStudent.tpl');
    }
    public function publicProfileFromOwner(EStudent $student, array $reviewsData, ?string $kind="#", ?string $modalSuccess):void{
        $this->smarty->assign('student', $student);
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->assign('kind', $kind);
        $this->smarty->assign('modalSuccess', $modalSuccess);
        $this->smarty->display('Student/publicProfileFromOwner.tpl');
    }
    public function paymentMethods(array $cardsData, ?string $modalSuccess):void{
        $this->smarty->assign('cardsData', json_encode($cardsData));
        $this->smarty->assign('modalSuccess', $modalSuccess);
        $this->smarty->display('Student/paymentMethods.tpl');
    }
    public function postedReview(array $reviewsData, ?string $modalSuccess):void{
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->assign('modalSuccess', $modalSuccess);
        $this->smarty->display('Student/postedReviews.tpl');
    }
    public function visits(array $visitsData):void{
        $this->smarty->assign('eventsData', json_encode($visitsData));
        $this->smarty->display('Student/visits.tpl');
    }
    public function viewVisit(EVisit $visit, EOwner $owner, EAccommodation $accommodation, string $accommodationPhoto, array $timeSlots, string $successEdit, string $successDelete):void {
        $this->smarty->assign('visit', $visit);
        $this->smarty->assign('owner', $owner);
        $this->smarty->assign('accommodation', $accommodation);
        $this->smarty->assign('timeSlots', json_encode($timeSlots));
        $this->smarty->assign('accommodationImage', $accommodationPhoto);
        $this->smarty->assign('successEdit', $successEdit);
        $this->smarty->assign('successDelete', $successDelete);
        $this->smarty->display('Student/visitDetails.tpl');
    }
    public function showReservations(array $reservationsData, string $kind, ?string $modalSuccess):void {
        $this->smarty->assign('reservations', json_encode($reservationsData));
        $this->smarty->assign('kind', $kind);
        $this->smarty->assign('modalSuccess', $modalSuccess);
        $this->smarty->display('Student/reservations.tpl');
    }
    public function reservationDetails(EReservation $reservation, EAccommodation $accommodation, EOwner $owner, string $timeLeft, array $pictures, array $reviewsData, array $creditCardData, ?string $modalSuccess):void {
        $this->smarty->assign('accommodation', $accommodation);
        $this->smarty->assign('reservation', $reservation);
        $this->smarty->assign('owner', $owner);
        $this->smarty->assign('timeLeft', $timeLeft);
        $this->smarty->assign('imagesJson', json_encode($pictures));
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->assign('creditCardData', json_encode($creditCardData));
        $this->smarty->assign('modalSuccess', $modalSuccess);
        $this->smarty->display('Student/reservationDetails.tpl');
    }
    public function showContracts(array $contractsData, string $kind, ?string $modalSuccess):void {
        $this->smarty->assign('contracts', json_encode($contractsData));
        $this->smarty->assign('kind', $kind);
        $this->smarty->assign('modalSuccess', $modalSuccess);
        $this->smarty->display('Student/contracts.tpl');
    }
    public function contractDetails(EContract $contract, EAccommodation $accommodation, EOwner $owner, string $cardNumber, string $cardHolder, array $pictures, array $reviewsData, ?string $modalSuccess):void {
        $this->smarty->assign('accommodation', $accommodation);
        $this->smarty->assign('contract', $contract);
        $this->smarty->assign('owner', $owner);
        $this->smarty->assign('cardNumber', $cardNumber);
        $this->smarty->assign('cardHolder', $cardHolder);
        $this->smarty->assign('imagesJson', json_encode($pictures));
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->assign('modalSuccess', $modalSuccess);
        $this->smarty->display('Student/contractDetails.tpl');
    }
}
