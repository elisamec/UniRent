<?php

namespace Classes\View;
require __DIR__.'/../../vendor/autoload.php';
use Classes\Entity\EOwner;
use StartSmarty;

class VOwner {
    private $smarty;

    public function __construct() {
        $this->smarty = StartSmarty::configuration();
    }

    //Mostra la home del proprietario
    public function home() {
        $this->smarty->display('Owner/home.tpl');
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

    public function editProfile(EOwner $owner, ?string $photo){
        $this->smarty->assign('photo', $photo);
        $this->smarty->assign('owner', $owner);
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
    public function publicProfileOwner(EOwner $owner, array $reviewsData){
        $this->smarty->assign('owner', $owner);
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->display('Owner/publicProfileOwner.tpl');
    }
}
