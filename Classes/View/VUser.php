<?php

namespace Classes\View;
require __DIR__.'/../../vendor/autoload.php';

use StartSmarty;

class VUser{
    private $smarty;

    public function __construct(){

        $this->smarty = StartSmarty::configuration();
    }

    public function home(array $accommodations, ?string $modalSuccess){
        $this->smarty->assign('accommodations', json_encode($accommodations));
        $this->smarty->assign('modalSuccess', $modalSuccess);
        $this->smarty->display('User/home.tpl');
    }

    public function login(){
        $this->smarty->assign('usernameError', false);
        $this->smarty->assign('passwordError', false);
        $this->smarty->assign('usernameRight', '');
        $this->smarty->display('User/login.tpl');
    }
    public function about(){

        $this->smarty->display('User/about.tpl');
    }
    public function contact(?string $modalSuccess){
        $this->smarty->assign('modalSuccess', $modalSuccess);
        $this->smarty->display('User/contact.tpl');
    }
    public function findAccommodation(string $selectedCity, string $selectedUni, array $searchResult, string $date, int $ratingOwner, int $ratingAccommodation, int $minPrice, int $maxPrice, int $year):void{
        $this->smarty->assign('selectedCity', $selectedCity);
        $this->smarty->assign('selectedUni', $selectedUni);
        $this->smarty->assign('selectedDate', $date);
        $this->smarty->assign('searchResult', json_encode($searchResult));
        $this->smarty->assign('ratingOwner', $ratingOwner);
        $this->smarty->assign('ratingAccommodation', $ratingAccommodation);
        $this->smarty->assign('minPrice', $minPrice);
        $this->smarty->assign('maxPrice', $maxPrice);
        $this->smarty->assign('year',$year);
        $this->smarty->display('User/search.tpl');
    }

    public function register(?string $modalSuccess){
        $this->smarty->assign('userDuplicateError', false);
        $this->smarty->assign('studentMailError', false);
        $this->smarty->assign('passwordFormatError', false);
        $this->smarty->assign('mailDuplicateError', false);
        $this->smarty->assign('username', '');
        $this->smarty->assign('email', '');
        $this->smarty->assign('name', '');
        $this->smarty->assign('surname', '');
        $this->smarty->assign('type', '');
        $this->smarty->assign('modalSuccess', $modalSuccess);
        $this->smarty->display('User/register.tpl');
    }

    //Deve rimandare alla home dicendo che c'è stato un errore
    //L'errore potrebbe essere dovuto a username o email già presenti nel database
    public function registrationError(bool $mailDuplicateError, bool $userDuplicateError, bool $studentMailError, bool $passwordFormatError, string $username, string $email, string $name, string $surname, string $type, ?string $modalSuccess){
        $this->smarty->assign('userDuplicateError', $userDuplicateError);
        $this->smarty->assign('studentMailError', $studentMailError);
        $this->smarty->assign('passwordFormatError', $passwordFormatError);
        $this->smarty->assign('mailDuplicateError', $mailDuplicateError);
        $this->smarty->assign('username', $username);
        $this->smarty->assign('email', $email);
        $this->smarty->assign('name', $name);
        $this->smarty->assign('surname', $surname);
        $this->smarty->assign('type', $type);
        $this->smarty->assign('modalSuccess', $modalSuccess);
        $this->smarty->display('User/register.tpl');
    }

    //Deve rimandare alla home dicendo che c'è stato un errore nel login
    //"Username or password not correct"
    public function loginError(bool $passwordError, bool $usernameError, string $usernameRight, string $type){
        $this->smarty->assign('usernameError', $usernameError);
        $this->smarty->assign('passwordError', $passwordError);
        $this->smarty->assign('usernameRight', $usernameRight);
        $this->smarty->assign('type', $type);
        $this->smarty->display('User/login.tpl');
    }
    public function loginUsernameError(bool $passwordError, bool $usernameError, string $type){
        $this->smarty->assign('usernameError', $usernameError);
        $this->smarty->assign('passwordError', $passwordError);
        $this->smarty->assign('type', $type);
        $this->smarty->display('User/login.tpl');
    }
    public function guidelines(){
        $this->smarty->display('User/guidelines.tpl');
    }
}