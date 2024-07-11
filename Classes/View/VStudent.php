<?php

namespace Classes\View;
require __DIR__.'/../../vendor/autoload.php';

use Classes\Entity\EAccommodation;
use Classes\Entity\EOwner;
use Classes\Entity\EPhoto;
use StartSmarty;
use Classes\Entity\EStudent;

class VStudent{
    private $smarty;

    public function __construct(){

        $this->smarty = StartSmarty::configuration();
    }

    public function home():void{

        $this->smarty->display('Student/home.tpl');
    }

    /**
     * Show the student's profile
     * 
     * @param EStudent $student The student's profile to display
     * @param string|null $photo The student's profile photo
     * @return void
     */
    public function profile(EStudent $student, ?string $photo) :void {

        $this->smarty->assign('photo', $photo);
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
    public function editProfile(EStudent $student, ?string $photo, bool $passwordError, bool $usernameDuplicate, bool $emailError, bool $oldPasswordError) :void {
        $this->smarty->assign('photo', $photo);
        $this->smarty->assign('student', $student);
        $this->smarty->assign('passwordError', $passwordError);
        $this->smarty->assign('usernameDuplicate', $usernameDuplicate);
        $this->smarty->assign('emailError', $emailError);
        $this->smarty->assign('oldPasswordError', $oldPasswordError);
        $this->smarty->display('Student/editPersonalProfile.tpl');
    }

    //Mostra la seconda parte della registrazione studente
    public function showStudentRegistration():void{
        $this->smarty->display('Student/register.tpl');
    }
    public function contact():void{
        $this->smarty->display('Student/contact.tpl');
    }
    public function about():void{
        $this->smarty->display('Student/about.tpl');
    }
    public function findAccommodation(string $selectedCity, string $selectedUni, array $searchResult, string $date, int $ratingOwner, int $ratingStudent, int $ratingAccommodation, int $minPrice, int $maxPrice):void{
        $this->smarty->assign('selectedCity', $selectedCity);
        $this->smarty->assign('selectedUni', $selectedUni);
        $this->smarty->assign('selectedDate', $date);
        $this->smarty->assign('searchResult', json_encode($searchResult));
        $this->smarty->assign('ratingOwner', $ratingOwner);
        $this->smarty->assign('ratingStudent', $ratingStudent);
        $this->smarty->assign('ratingAccommodation', $ratingAccommodation);
        $this->smarty->assign('minPrice', $minPrice);
        $this->smarty->assign('maxPrice', $maxPrice);
        $this->smarty->display('Student/search.tpl');
    }
    public function accommodation(EAccommodation $accomm, EOwner $owner, array $reviewsData, string $period, array $pictures):void{
        $photos=json_encode($pictures);
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->assign('imagesJson', $photos);
        $this->smarty->assign('accommodation', $accomm);
        $this->smarty->assign('owner', $owner);
        $this->smarty->assign('period', $period);
        $this->smarty->display('Student/accommodation.tpl');
    }
    public function reviews(array $reviewsData):void{
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->display('Student/reviews.tpl');
    }
    public function publicProfileFromStudent(EStudent $student, array $reviewsData):void{
        $this->smarty->assign('student', $student);
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->display('Student/publicProfileFromStudent.tpl');
    }
    public function publicProfileFromOwner(EStudent $student, array $reviewsData):void{
        $this->smarty->assign('student', $student);
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->display('Student/publicProfileFromOwner.tpl');
    }
    public function paymentMethods(array $cardsData):void{
        $this->smarty->assign('cardsData', json_encode($cardsData));
        $this->smarty->display('Student/paymentMethods.tpl');
    }
    public function postedReview(array $reviewsData):void{
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->display('Student/postedReviews.tpl');
    }
}
