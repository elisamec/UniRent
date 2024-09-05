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

/**
 * Class VStudent
 * 
 * This class represents the view for the Student entity.
 * It is responsible for rendering the Student-related views.
 * 
 * @package Classes\View
 */
class VStudent{
    private $smarty;

    /**
     * VStudent constructor.
     * 
     * It initializes the Smarty object.
     */
    public function __construct(){

        $this->smarty = StartSmarty::configuration();
    }

    /**
     * Show the student's home page
     * 
     * @param array $accommodations An array of accommodations
     * @param string|null $modalSuccess The success message for the modal (optional)
     * @return void
     */
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

    /**
     * Displays the student registration form.
     *
     * @return void
     */
    public function showStudentRegistration():void{
        $this->smarty->display('Student/register.tpl');
    }


    /**
     * Method to handle the contact functionality in the VStudent class.
     *
     * @param string|null $modalSuccess The success message to be displayed in the modal.
     * @return void
     */
    public function contact(?string $modalSuccess):void{
        $this->smarty->assign('modalSuccess', $modalSuccess);
        $this->smarty->display('Student/contact.tpl');
    }


    /**
     * Displays information about the student.
     *
     * @return void
     */
    public function about():void{
        $this->smarty->display('Student/about.tpl');
    }


    /**
     * Display finded accommodation based on search criteria.
     *
     * @param string $selectedCity The selected city for the accommodation search.
     * @param string $selectedUni The selected university for the accommodation search.
     * @param array $searchResult The search result array containing the available accommodations.
     * @param string $date The date for the accommodation search.
     * @param int $ratingOwner The minimum rating for the accommodation owner.
     * @param int $ratingAccommodation The minimum rating for the accommodation.
     * @param int $minPrice The minimum price for the accommodation.
     * @param int $maxPrice The maximum price for the accommodation.
     * @param int $year The year for the accommodation search.
     * @return void
     */
    public function findAccommodation(string $selectedCity, string $selectedUni, array $searchResult, string $date, int $ratingOwner, int $ratingAccommodation, int $minPrice, int $maxPrice, int $year):void{
        $this->smarty->assign('selectedCity', $selectedCity);
        $this->smarty->assign('selectedUni', $selectedUni);
        $this->smarty->assign('selectedDate', $date);
        $this->smarty->assign('searchResult', json_encode($searchResult));
        $this->smarty->assign('ratingOwner', $ratingOwner);
        $this->smarty->assign('ratingAccommodation', $ratingAccommodation);
        $this->smarty->assign('minPrice', $minPrice);
        $this->smarty->assign('maxPrice', $maxPrice);
        $this->smarty->assign('selectedYear',$year);
        $this->smarty->display('Student/search.tpl');
    }


    /**
     * Renders the accommodation view for a student.
     *
     * @param EAccommodation $accomm The accommodation object.
     * @param EOwner $owner The owner object.
     * @param array $reviewsData An array of review data.
     * @param string $period The rental period.
     * @param array $pictures An array of pictures.
     * @param array $timeSlots An array of time slots.
     * @param int $duration The duration of the rental.
     * @param array $tenantsJson An array of tenant data in JSON format.
     * @param int $num_places The number of available places.
     * @param bool $booked Indicates if the accommodation is booked.
     * @param string $day The day of the week.
     * @param string $time The time of the visit.
     * @param bool $disabled Indicates if the accommodation is disabled.
     * @param string $successReserve The success message for reserving the accommodation.
     * @param string $successVisit The success message for visiting the accommodation.
     * @param int $leavebleReviews The number of leaveble reviews.
     * @param int $year The year of the accommodation.
     *
     * @return void
     */
    public function accommodation(EAccommodation $accomm, EOwner $owner, array $reviewsData, string $period, array $pictures, array $timeSlots, int $duration, array $tenantsJson, int $num_places, bool $booked, string $day, string $time, bool $disabled, string $successReserve, string $successVisit, int $leavebleReviews, int $year):void{
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
        $this->smarty->assign('selectedYear',$year);
        $this->smarty->display('Student/accommodation.tpl');
    }


    /**
     * Renders the reviews section of the student view.
     *
     * @param array $reviewsData The data for the reviews.
     * @param string|null $modalSuccess The success message for the modal.
     * @return void
     */
    public function reviews(array $reviewsData, ?string $modalSuccess):void{
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->assign('modalSuccess', $modalSuccess);
        $this->smarty->display('Student/reviews.tpl');
    }


    /**
     * Renders the public profile of a student.
     *
     * @param EStudent $student The student object.
     * @param array $reviewsData The array of review data.
     * @param bool $self Indicates if the profile is for the logged-in student.
     * @param int $leavebleReviews The number of reviews that can be left.
     * @param string|null $modalSuccess The success message for the modal.
     * @return void
     */
    public function publicProfileFromStudent(EStudent $student, array $reviewsData, bool $self, int $leavebleReviews, ?string $modalSuccess):void{
        $this->smarty->assign('student', $student);
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->assign('self', $self);
        $this->smarty->assign('leavebleReviews', $leavebleReviews);
        $this->smarty->assign('modalSuccess', $modalSuccess);
        $this->smarty->display('Student/publicProfileFromStudent.tpl');
    }

    /**
     * Renders the public profile of a student from the owner's perspective.
     *
     * @param EStudent $student The student object to display the profile for.
     * @param array $reviewsData An array containing the review data for the student.
     * @param string|null $modalSuccess The success message to display in a modal (optional).
     * @param int $leavebleReviews The number of reviews that can be left for the student.
     * @return void
     */
    public function publicProfileFromOwner(EStudent $student, array $reviewsData,?string $modalSuccess, int $leavebleReviews):void{
        $this->smarty->assign('student', $student);
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->assign('leavebleReviews', $leavebleReviews);
        $this->smarty->assign('modalSuccess', $modalSuccess);
        $this->smarty->display('Student/publicProfileFromOwner.tpl');
    }

    /**
     * Displays the payment methods for a student.
     *
     * @param array $cardsData The array containing the card data.
     * @param string|null $modalSuccess The success message to display in the modal.
     * @return void
     */
    public function paymentMethods(array $cardsData, ?string $modalSuccess):void{
        $this->smarty->assign('cardsData', json_encode($cardsData));
        $this->smarty->assign('modalSuccess', $modalSuccess);
        $this->smarty->display('Student/paymentMethods.tpl');
    }

    /**
     * Assigns the given reviews data to the 'reviewsData' variable in the Smarty template.
     *
     * @param mixed $reviewsData The reviews data to be assigned.
     * @return void
     */
    public function postedReview(array $reviewsData, ?string $modalSuccess):void{
        
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->assign('modalSuccess', $modalSuccess);
        $this->smarty->display('Student/postedReviews.tpl');
    }

    /**
     * Displays the visits for a student.
     *
     * @param array $visitsData The array containing the visit data.
     * @return void
     */
    public function visits(array $visitsData):void{
        $this->smarty->assign('eventsData', json_encode($visitsData));
        $this->smarty->display('Student/visits.tpl');
    }

    /**
     * Displays the visit details for a student.
     *
     * @param EVisit $visit The visit object.
     * @param EOwner $owner The owner object.
     * @param EAccommodation $accommodation The accommodation object.
     * @param string $accommodationPhoto The accommodation photo.
     * @param array $timeSlots The time slots array.
     * @param string $successEdit The success message for editing the visit.
     * @param string $successDelete The success message for deleting the visit.
     * @return void
     */
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

    /**
     * Displays the reservations for a student.
     *
     * @param array $reservationsData The array containing the reservation data.
     * @param string $kind The kind of reservation.
     * @param string|null $modalSuccess The success message for the modal.
     * @return void
     */
    public function showReservations(array $reservationsData, string $kind, ?string $modalSuccess):void {
        $this->smarty->assign('reservations', json_encode($reservationsData));
        $this->smarty->assign('kind', $kind);
        $this->smarty->assign('modalSuccess', $modalSuccess);
        $this->smarty->display('Student/reservations.tpl');
    }

    /**
     * Displays the reservation details for a student.
     *
     * @param EReservation $reservation The reservation object.
     * @param EAccommodation $accommodation The accommodation object.
     * @param EOwner $owner The owner object.
     * @param string $timeLeft The time left for the reservation.
     * @param array $pictures The array containing the pictures.
     * @param array $reviewsData The array containing the review data.
     * @param array $creditCardData The array containing the credit card data.
     * @param string|null $modalSuccess The success message for the modal.
     * @return void
     */
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

    /**
     * Displays the contracts for a student.
     *
     * @param array $contractsData The array containing the contract data.
     * @param string $kind The kind of contract.
     * @param string|null $modalSuccess The success message for the modal.
     * @return void
     */
    public function showContracts(array $contractsData, string $kind, ?string $modalSuccess):void {
        $this->smarty->assign('contracts', json_encode($contractsData));
        $this->smarty->assign('kind', $kind);
        $this->smarty->assign('modalSuccess', $modalSuccess);
        $this->smarty->display('Student/contracts.tpl');
    }

    /**
     * Displays the contract details for a student.
     *
     * @param EContract $contract The contract object.
     * @param EAccommodation $accommodation The accommodation object.
     * @param EOwner $owner The owner object.
     * @param string $cardNumber The card number.
     * @param string $cardHolder The card holder.
     * @param array $pictures The array containing the pictures.
     * @param array $reviewsData The array containing the review data.
     * @param string|null $modalSuccess The success message for the modal.
     * @param int $leaveble The number of leaveble reviews.
     * @return void
     */
    public function contractDetails(EContract $contract, EAccommodation $accommodation, EOwner $owner, string $cardNumber, string $cardHolder, array $pictures, array $reviewsData, ?string $modalSuccess, int $leaveble):void {
        $this->smarty->assign('accommodation', $accommodation);
        $this->smarty->assign('contract', $contract);
        $this->smarty->assign('owner', $owner);
        $this->smarty->assign('cardNumber', $cardNumber);
        $this->smarty->assign('cardHolder', $cardHolder);
        $this->smarty->assign('imagesJson', json_encode($pictures));
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->assign('modalSuccess', $modalSuccess);
        $this->smarty->assign('leaveble', $leaveble);
        $this->smarty->display('Student/contractDetails.tpl');
    }

    /**
     * Displays the support form for a student.
     *
     * @param string|null $modalSuccess The success message for the modal.
     * @return void
     */
    public function supportReplies(array $replies, int $count):void {
        $this->smarty->assign('replies', json_encode($replies));
        $this->smarty->assign('count', $count);
        $this->smarty->display('Student/supportReplies.tpl');
    }


    /**
     * This method is responsible for displaying the guidelines for students.
     * 
     * @return void
     */
    public function guidelines():void {
        $this->smarty->display('Student/guidelines.tpl');
    }
}
