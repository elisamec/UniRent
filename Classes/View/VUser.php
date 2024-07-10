<?php

namespace Classes\View;
require __DIR__.'/../../vendor/autoload.php';

use StartSmarty;

class VUser{
    private $smarty;

    public function __construct(){

        $this->smarty = StartSmarty::configuration();
    }

    public function home(){

        $this->smarty->display('User/home.tpl');
    }

    public function login(){
        $this->smarty->assign('usernameError', false);
        $this->smarty->assign('passwordError', false);
        $this->smarty->assign('usernameBanned', false);
        $this->smarty->assign('usernameRight', '');
        $this->smarty->display('User/login.tpl');
    }
    public function about(){

        $this->smarty->display('User/about.tpl');
    }
    public function contact(){

        $this->smarty->display('User/contact.tpl');
    }
    public function findAccommodation($selectedCity, $selectedUni, array $searchResult, $date){
        $this->smarty->assign('selectedCity', $selectedCity);
        $this->smarty->assign('selectedUni', $selectedUni);
        $this->smarty->assign('searchResult', json_encode($searchResult));
        $this->smarty->assign('selectedDate', $date);
        $this->smarty->display('User/search.tpl');
    }

    public function register(){
        $this->smarty->assign('userDuplicateError', false);
        $this->smarty->assign('studentMailError', false);
        $this->smarty->assign('passwordFormatError', false);
        $this->smarty->assign('mailDuplicateError', false);
        $this->smarty->assign('username', '');
        $this->smarty->assign('email', '');
        $this->smarty->assign('name', '');
        $this->smarty->assign('surname', '');
        $this->smarty->assign('type', '');
        $this->smarty->display('User/register.tpl');
    }

    //Deve rimandare alla home dicendo che c'è stato un errore
    //L'errore potrebbe essere dovuto a username o email già presenti nel database
    public function registrationError(bool $mailDuplicateError, bool $userDuplicateError, bool $studentMailError, bool $passwordFormatError, string $username, string $email, string $name, string $surname, string $type){
        $this->smarty->assign('userDuplicateError', $userDuplicateError);
        $this->smarty->assign('studentMailError', $studentMailError);
        $this->smarty->assign('passwordFormatError', $passwordFormatError);
        $this->smarty->assign('mailDuplicateError', $mailDuplicateError);
        $this->smarty->assign('username', $username);
        $this->smarty->assign('email', $email);
        $this->smarty->assign('name', $name);
        $this->smarty->assign('surname', $surname);
        $this->smarty->assign('type', $type);
        $this->smarty->display('User/register.tpl');
    }

    //Deve rimandare alla home dicendo che c'è stato un errore nel login
    //"Username or password not correct"
    public function loginError(bool $passwordError, bool $usernameError, bool $usernameBanned, string $usernameRight, string $type){
        $this->smarty->assign('usernameError', $usernameError);
        $this->smarty->assign('passwordError', $passwordError);
        $this->smarty->assign('usernameBanned', $usernameBanned);
        $this->smarty->assign('usernameRight', $usernameRight);
        $this->smarty->assign('type', $type);
        $this->smarty->display('User/login.tpl');
    }
    public function loginUsernameError(bool $passwordError, bool $usernameError, bool $usernameBanned, string $type){
        $this->smarty->assign('usernameError', $usernameError);
        $this->smarty->assign('passwordError', $passwordError);
        $this->smarty->assign('usernameBanned', $usernameBanned);
        $this->smarty->assign('type', $type);
        $this->smarty->display('User/login.tpl');
    }
}