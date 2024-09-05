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
    /**
     * @var Smarty $smarty
     */
    private $smarty;

    /**
     * __construct
     * 
     * This method is used to initialize the smarty object
     */
    public function __construct() {
        $this->smarty = StartSmarty::configuration();
    }

    /**
     * home
     * 
     * This method is used to show the owner's dashboard
     * @param array $accommodationsActive
     * @param array $accommodationsInactive
     * @param ?string $modalSuccess
     */
    public function home(array $accommodationsActive, array $accommodationsInactive, ?string $modalSuccess):void {
        $this->smarty->assign('accommodationsActive', json_encode($accommodationsActive));
        $this->smarty->assign('modalSuccess', $modalSuccess);
        $this->smarty->assign('accommodationsInactive', json_encode($accommodationsInactive));
        $this->smarty->display('Owner/home.tpl');
    }

    /**
     * Manages the accommodation for a specific owner.
     *
     * @param EAccommodation $accomm The accommodation object.
     * @param EOwner $owner The owner object.
     * @param array $reviewsData An array of review data.
     * @param array $pictures An array of pictures.
     * @param array $tenants An array of tenants.
     * @param int $num_places The number of places.
     * @param bool $disabled Indicates if the accommodation is disabled.
     * @param bool $deletable Indicates if the accommodation is deletable.
     * @param string|null $modalSuccess The success message for the modal.
     * @return void
     */
    public function accommodationManagement(EAccommodation $accomm, EOwner $owner, array $reviewsData, array $pictures, array $tenants, int $num_places, bool $disabled, bool $deletable, ?string $modalSuccess):void{
        $photos=json_encode($pictures);
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->assign('imagesJson', $photos);
        $this->smarty->assign('accommodation', $accomm);
        $this->smarty->assign('owner', $owner);
        $this->smarty->assign('tenantsJson', json_encode($tenants));
        $this->smarty->assign('num_places', $num_places);
        $this->smarty->assign('disabled', $disabled);
        $this->smarty->assign('deletable', $deletable);
        $this->smarty->assign('modalSuccess', $modalSuccess);
        $this->smarty->display('Owner/accommodationManagement.tpl');
    }

    /**
     * Displays the owner registration form.
     *
     * @return void
     */
    public function showOwnerRegistration() :void{
        $this->smarty->assign('phoneError', false);
        $this->smarty->assign('ibanError', false);
        $this->smarty->assign('phone', '');
        $this->smarty->assign('iban', '');
        $this->smarty->display('Owner/register.tpl');
    }
    
    /**
     * Displays the owner registration form with errors.
     *
     * @param bool $phoneError Indicates if there is a phone error.
     * @param bool $ibanError Indicates if there is an IBAN error.
     * @param string $phone The phone number.
     * @param string $iban The IBAN.
     * @return void
     */
    public function registrationError(bool $phoneError, bool $ibanError, string $phone, string $iban) :void{
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
     * 
     * @return void
     */
    public function profile(EOwner $owner, ?string $photo, ?string $modalSuccess) :void{
        $this->smarty->assign('photo', $photo);
        $this->smarty->assign('owner', $owner);
        $this->smarty->assign('modalSuccess', $modalSuccess);
        $this->smarty->display('Owner/personalProfile.tpl');
    }

    /**
     * Edit the profile of an owner.
     *
     * @param EOwner $owner The owner object to be edited.
     * @param string|null $photo The photo of the owner (optional).
     * @param bool $usernameDuplicate Indicates if there is a duplicate username.
     * @param bool $emailDuplicate Indicates if there is a duplicate email.
     * @param bool $phoneError Indicates if there is a phone error.
     * @param bool $ibanError Indicates if there is an IBAN error.
     * @param bool $oldPasswordError Indicates if there is an old password error.
     * @param bool $passwordError Indicates if there is a password error.
     * @return void
     */
    public function editProfile(EOwner $owner, ?string $photo, bool $usernameDuplicate, bool $emailDuplicate, bool $phoneError, bool $ibanError, bool $oldPasswordError, bool $passwordError):void {
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

    /**
     * Contact the owner.
     *
     * @param string|null $modalSuccess The success message for the modal.
     * @return void
     */
    public function contact(?string $modalSuccess):void{
        $this->smarty->assign('modalSuccess', $modalSuccess);
        $this->smarty->display('Owner/contact.tpl');
    }

    /**
     * Represents a method to retrieve information about the owner.
     *
     * @return void
     */
    public function about():void{
        $this->smarty->display('Owner/about.tpl');
    }

    /**
     * Method to handle reviews for the VOwner class.
     *
     * @param array $reviewsData The array containing the reviews data.
     * @param string|null $modalSuccess The success message for the modal.
     * @return void
     */
    public function reviews(array $reviewsData, ?string $modalSuccess):void{
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->assign('modalSuccess', $modalSuccess);
        $this->smarty->display('Owner/reviews.tpl');
    }

    /**
     * Method to handle the addition of an accommodation.
     *
     * @return void
     */
    public function addAccommodation():void
    {
        $this->smarty->display('Owner/addAccommodation.tpl');
    }


    /**
     * Generates the public profile of an owner.
     *
     * @param EOwner $owner The owner object.
     * @param array $reviewsData The array of reviews data.
     * @param bool $self Indicates if the profile is for the owner themselves.
     * @param string|null $modalSuccess The success message for the modal.
     * @return void
     */
    public function publicProfileFromOwner(EOwner $owner, array $reviewsData, bool $self, ?string $modalSuccess):void{
        $this->smarty->assign('owner', $owner);
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->assign('self', $self);
        $this->smarty->assign('modalSuccess', $modalSuccess);
        $this->smarty->display('Owner/publicProfileFromOwner.tpl');
    }

    /**
     * Generates the public profile of a student.
     *
     * @param EOwner $owner The owner object.
     * @param array $reviewsData The array of reviews data.
     * @param string|null $modalSuccess The success message for the modal.
     * @param int $leavebleReviews The number of reviews that can be left.
     * @return void
     */
    public function publicProfileFromStudent(EOwner $owner, array $reviewsData, ?string $modalSuccess, int $leavebleReviews):void{
        $this->smarty->assign('owner', $owner);
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->assign('modalSuccess', $modalSuccess);
        $this->smarty->assign('leavebleReviews', $leavebleReviews);
        $this->smarty->display('Owner/publicProfileFromStudent.tpl');
    }

    
    /**
     * Show a posted review.
     *
     * @param array $reviewsData The data of the reviews.
     * @param string|null $modalSuccess The success message for the modal.
     * @return void
     */
    public function postedReview(array $reviewsData, ?string $modalSuccess):void{
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->assign('modalSuccess', $modalSuccess);
        $this->smarty->display('Owner/postedReviews.tpl');
    }

    /**
     * Show the owner's ads.
     *
     * @param array $accommodations The accommodations to display.
     * @param string $username The username of the owner.
     * @return void
     */
    public function viewOwnerAds(array $accommodations, string $username):void{
        $this->smarty->assign('accommodations', json_encode($accommodations));
        $this->smarty->assign('username', $username);
        $this->smarty->display('Owner/viewOwnerAds.tpl');
    }


    /**
     * Edit an accommodation.
     *
     * @param array $accomm The accommodation data.
     * @param array $pictures The pictures of the accommodation.
     * @param array $visitAvailabilityData The availability data for visits.
     * @param int $id The ID of the accommodation.
     * @return void
     */
    public function editAccommodation(array $accomm, array $pictures, array $visitAvailabilityData, int $id):void{
        $photos=json_encode($pictures);
        $this->smarty->assign('accommodationId', $id);
        $this->smarty->assign('uploadedImagesData', $photos);
        $this->smarty->assign('accommodationData', json_encode($accomm));
        $this->smarty->assign('visitAvailabilityData', json_encode($visitAvailabilityData));
        $this->smarty->display('Owner/editAccommodation.tpl');
    }

    /**
     * Show the owner's tenants.
     *
     * @param array $tenants The tenants to display.
     * @param string $kind The kind of tenant.
     * @param array $accommodations The accommodations to display.
     * @param int $rating The rating of the tenant.
     * @param string|null $accommodation The accommodation to display.
     * @param string|null $username The username of the tenant.
     * @param string|null $period The period of the tenant.
     * @param string|null $age The age of the tenant.
     * @param bool|null $men Indicates if the tenant
     * @param bool|null $women Indicates if
     * @param string|null $year The year of the tenant.
     * 
     * @return void
     */
    public function tenants(array $tenants, string $kind, array $accommodations, int $rating=0, ?string $accommodation=null, ?string $username=null, ?string $period=null, ?string $age=null, ?bool $men=null, ?bool $women=null, ?string $year=null) :void {
        $this->smarty->assign('tenants', json_encode($tenants));
        $this->smarty->assign('kind', $kind);
        $this->smarty->assign('accommodationTitles', json_encode($accommodations));
        $this->smarty->assign('rating', $rating);
        $this->smarty->assign('accommodation', $accommodation);
        $this->smarty->assign('username', $username);
        $this->smarty->assign('period', $period);
        $this->smarty->assign('age', $age);
        $this->smarty->assign('men', $men);
        $this->smarty->assign('women', $women);
        $this->smarty->assign('year', $year);
        $this->smarty->display('Owner/tenants.tpl');
    }

    /**
     * Show the owner's visits.
     *
     * @param array $visitsData The data of the visits.
     * @return void
     */
    public function visits(array $visitsData) :void{
        
        $this->smarty->assign('eventsData', json_encode($visitsData));
        $this->smarty->display('Owner/visits.tpl');
    }


    /**
     * View the owner of a visit.
     *
     * @param EVisit $visit The visit object.
     * @param EStudent $student The student object.
     * @param EAccommodation $accommodation The accommodation object.
     * @param string $accommodationPhoto The photo of the accommodation.
     * @param string $successEdit The success message for editing.
     * @param string $successDelete The success message for deletion.
     * @return void
     */
    public function viewVisitOwner(EVisit $visit, EStudent $student, EAccommodation $accommodation, string $accommodationPhoto, string $successEdit, string $successDelete) :void {
        
        $this->smarty->assign('visit', $visit);
        $this->smarty->assign('student', $student);
        $this->smarty->assign('accommodation', $accommodation);
        $this->smarty->assign('accommodationImage', $accommodationPhoto);
        $this->smarty->assign('successEdit', $successEdit);
        $this->smarty->assign('successDelete', $successDelete);
        $this->smarty->display('Owner/visitDetails.tpl');
    }

    /**
     * Displays the reservations for the owner.
     *
     * @param array $reservationsData The data of the reservations.
     * @param string|null $modalSuccess The success message for the modal.
     * @return void
     */
    public function showReservations(array $reservationsData, ?string $modalSuccess):void {
        
        $json =  json_encode($reservationsData);
        $this->smarty->assign('modalSuccess', $modalSuccess);
        $this->smarty->assign('reservationsData', $json);
        $this->smarty->display('Owner/reservations.tpl');
    }


    /**
     * Displays the reservation details for a specific owner.
     *
     * @param EReservation $reservation The reservation object.
     * @param EStudent $student The student object.
     * @param string $timeLeft The time left for the reservation.
     * @param array $reviewsData The reviews data for the owner.
     * @param string|null $modalSuccess The success message for the modal.
     * @return void
     */
    public function reservationDetails(EReservation $reservation, EStudent $student, string $timeLeft, array $reviewsData, ?string $modalSuccess):void {
        
        $this->smarty->assign('reservation', $reservation);
        $this->smarty->assign('student', $student);
        $this->smarty->assign('timeLeft', $timeLeft);
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->assign('modalSuccess', $modalSuccess);
        $this->smarty->display('Owner/reservationDetails.tpl');
    }


    /**
     * Displays the contracts for the owner.
     *
     * @param array $contractsData The data of the contracts.
     * @param string $kind The kind of contracts to display.
     * @param string|null $modalSuccess The success message for the modal.
     * @return void
     */
    public function showContracts(array $contractsData, string $kind, ?string $modalSuccess):void {
        
        $this->smarty->assign('contractsData', json_encode($contractsData));
        $this->smarty->assign('kind', $kind);
        $this->smarty->assign('modalSuccess', $modalSuccess);
        $this->smarty->display('Owner/contracts.tpl');
    }


    /**
     * Retrieves the contract details for a given contract, student, reviews data, modal success message, and leaveble value.
     *
     * @param EContract $contract The contract object.
     * @param EStudent $student The student object.
     * @param array $reviewsData The array of reviews data.
     * @param string|null $modalSuccess The modal success message (nullable).
     * @param int $leaveble The leaveble value.
     * @return void
     */
    public function contractDetails(EContract $contract, EStudent $student, array $reviewsData, ?string $modalSuccess, int $leaveble):void {
        
        $this->smarty->assign('contract', $contract);
        $this->smarty->assign('student', $student);
        $this->smarty->assign('modalSuccess', $modalSuccess);
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->assign('leaveble', $leaveble);
        $this->smarty->display('Owner/contractDetails.tpl');
    }


    /**
     * Method to handle support replies.
     *
     * @param array $replies The array of support replies.
     * @param int $count The count of support replies.
     * @return void
     */
    public function supportReplies(array $replies, int $count):void {
        $this->smarty->assign('replies', json_encode($replies));
        $this->smarty->assign('count', $count);
        $this->smarty->display('Owner/supportReplies.tpl');
    }


    /**
     * This method is used to retrieve the guidelines for the owner view.
     * 
     * @return void
     */
    public function guidelines():void{
        $this->smarty->display('Owner/guidelines.tpl');
    }
}
