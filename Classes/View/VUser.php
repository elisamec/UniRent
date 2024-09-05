<?php

namespace Classes\View;
require __DIR__.'/../../vendor/autoload.php';

use StartSmarty;

/**
 * Class VUser
 *
 * This class represents the view for the User entity.
 * It is responsible for rendering the User-related views.
 * 
 * @package Classes\View
 */
class VUser{

    /**
     * @var \Smarty $smarty The Smarty object used to render the views.
     */
    private $smarty;

    /**
     * VUser constructor.
     * 
     * It initializes the Smarty object.
     */
    public function __construct() {

        $this->smarty = StartSmarty::configuration();
    }

    /**
     * Renders the home view.
     *
     * @param array $accommodations An array of accommodations.
     * @param string|null $modalSuccess The success message for the modal (optional).
     * @return void
     */
    public function home(array $accommodations, ?string $modalSuccess) :void {
        $this->smarty->assign('accommodations', json_encode($accommodations));
        $this->smarty->assign('modalSuccess', $modalSuccess);
        $this->smarty->display('User/home.tpl');
    }

    /**
     * Renders the login view.
     *
     * @param string $modalSuccess The success message for the modal .
     * @return void
     */
    public function login(string $modalSuccess):void {
        $this->smarty->assign('usernameError', false);
        $this->smarty->assign('passwordError', false);
        $this->smarty->assign('usernameRight', '');
        $this->smarty->assign('modalSuccess', $modalSuccess);
        $this->smarty->display('User/login.tpl');
    }

    /**
     * Renders the about view.
     *
     * @return void
     */
    public function about():void {

        $this->smarty->display('User/about.tpl');
    }

    /**
     * Renders the contact view.
     *
     * @param string|null $modalSuccess The success message for the modal (optional).
     * @return void
     */
    public function contact(?string $modalSuccess):void {
        $this->smarty->assign('modalSuccess', $modalSuccess);
        $this->smarty->display('User/contact.tpl');
    }

    /**
     * Renders the accommodation view.
     *
     * @param array $accommodation The accommodation to render.
     * @param string|null $modalSuccess The success message for the modal (optional).
     * @return void
     */
    public function findAccommodation(string $selectedCity, string $selectedUni, array $searchResult, string $date, int $ratingOwner, int $ratingAccommodation, int $minPrice, int $maxPrice, int $year):void{
        $this->smarty->assign('selectedCity', $selectedCity);
        $this->smarty->assign('selectedUni', $selectedUni);
        $this->smarty->assign('selectedDate', $date);
        $this->smarty->assign('selectedYear', $year);
        $this->smarty->assign('searchResult', json_encode($searchResult));
        $this->smarty->assign('ratingOwner', $ratingOwner);
        $this->smarty->assign('ratingAccommodation', $ratingAccommodation);
        $this->smarty->assign('minPrice', $minPrice);
        $this->smarty->assign('maxPrice', $maxPrice);
        $this->smarty->display('User/search.tpl');
    }


    /**
     * Registers a user.
     *
     * @return void
     */
    public function register():void {
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

    /**
     * Registers a user and handles any registration errors.
     *
     * @param bool $mailDuplicateError Indicates if there is a duplicate email error.
     * @param bool $userDuplicateError Indicates if there is a duplicate username error.
     * @param bool $studentMailError Indicates if there is a student email error.
     * @param bool $passwordFormatError Indicates if there is a password format error.
     * @param string $username The username of the user.
     * @param string $email The email of the user.
     * @param string $name The name of the user.
     * @param string $surname The surname of the user.
     * @param string $type The type of the user.
     * @param string|null $modalSuccess The success message to display in a modal.
     * 
     * @return void
     */
    public function registrationError(bool $mailDuplicateError, bool $userDuplicateError, bool $studentMailError, bool $passwordFormatError, string $username, string $email, string $name, string $surname, string $type, ?string $modalSuccess):void  {
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


    /**
     * Display the login error page.
     *
     * @param bool $passwordError Indicates if there is a password error.
     * @param bool $usernameError Indicates if there is a username error.
     * @param string $usernameRight The correct username.
     * @param string $type The type of login error.
     * @return void
     */
    public function loginError(bool $passwordError, bool $usernameError, string $usernameRight, string $type):void {
        $this->smarty->assign('usernameError', $usernameError);
        $this->smarty->assign('passwordError', $passwordError);
        $this->smarty->assign('usernameRight', $usernameRight);
        $this->smarty->assign('type', $type);
        $this->smarty->display('User/login.tpl');
    }

    /**
     * Display the login error page.
     *
     * @param bool $passwordError Indicates if there is a password error.
     * @param bool $usernameError Indicates if there is a username error.
     * @param string $type The type of login error.
     * @return void
     */
    public function loginUsernameError(bool $passwordError, bool $usernameError, string $type):void {
        $this->smarty->assign('usernameError', $usernameError);
        $this->smarty->assign('passwordError', $passwordError);
        $this->smarty->assign('type', $type);
        $this->smarty->display('User/login.tpl');
    }


    /**
     * Method: guidelines
     * Description: This method is responsible for displaying the guidelines for the user.
     * 
     * @return void
     */
    public function guidelines():void {
        $this->smarty->display('User/guidelines.tpl');
    }
}