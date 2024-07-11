<?php

namespace Classes\View;
require __DIR__.'/../../vendor/autoload.php';

use Classes\Entity\EAccommodation;
use Classes\Entity\EOwner;
use StartSmarty;

class VOwner {
    private $smarty;

    public function __construct() {
        $this->smarty = StartSmarty::configuration();
    }

    //Mostra la home del proprietario
    public function home(array $accommodations) {
        $this->smarty->assign('accommodations', json_encode($accommodations));
        $this->smarty->display('Owner/home.tpl');
    }
    public function accommodation(EAccommodation $accomm, EOwner $owner, array $reviewsData, array $pictures):void{
        $photos=json_encode($pictures);
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->assign('imagesJson', $photos);
        $this->smarty->assign('accommodation', $accomm);
        $this->smarty->assign('owner', $owner);
        $this->smarty->display('Owner/accommodation.tpl');
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
    public function publicProfileFromOwner(EOwner $owner, array $reviewsData){
        $this->smarty->assign('owner', $owner);
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->display('Owner/publicProfileFromOwner.tpl');
    }
    public function publicProfileFromStudent(EOwner $owner, array $reviewsData){
        $this->smarty->assign('owner', $owner);
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->display('Owner/publicProfileFromStudent.tpl');
    }
    public function postedReview(array $reviewsData){
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->display('Student/postedReviews.tpl');
    }
}
