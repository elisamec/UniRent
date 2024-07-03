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

    public function home(){

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
    public function editProfile(EStudent $student, ?string $photo, bool $passwordError, bool $usernameDuplicate, bool $emailDuplicate, bool $universityMailError) :void {
        $this->smarty->assign('photo', $photo);
        $this->smarty->assign('student', $student);
        $this->smarty->assign('passwordError', $passwordError);
        $this->smarty->assign('usernameDuplicate', $usernameDuplicate);
        $this->smarty->assign('emailDuplicate', $emailDuplicate);
        $this->smarty->assign('universityMailError', $universityMailError);
        $this->smarty->display('Student/editPersonalProfile.tpl');
    }

    //Mostra la seconda parte della registrazione studente
    public function showStudentRegistration(){
        $this->smarty->display('Student/register.tpl');
    }
    public function contact(){
        $this->smarty->display('Student/contact.tpl');
    }
    public function about(){
        $this->smarty->display('Student/about.tpl');
    }
    public function search(){
        $this->smarty->display('Student/search.tpl');
    }
    public function accommodation(EAccommodation $accomm, EOwner $owner, array $reviewsData){
        $photos=json_encode($accomm->getPhoto());
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->assign('imagesJson', $photos);
        $this->smarty->assign('accommodation', $accomm);
        $this->smarty->assign('owner', $owner);
        $this->smarty->display('Student/accommodation.tpl');
    }
    public function reviews(array $reviewsData){
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->display('Student/reviews.tpl');
    }
    public function publicProfileStudent(EStudent $student, array $reviewsData){
        $this->smarty->assign('student', $student);
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->display('Student/publicProfileStudent.tpl');
    }
    public function findAccommodation(){
        $this->smarty->display('Student/search.tpl');
    }
}
