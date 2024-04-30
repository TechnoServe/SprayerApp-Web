<?php

/**
 * Created by PhpStorm.
 * User: TNS Programmer
 * Date: 2022-02-16
 * Time: 4:39 PM
 */

namespace sprint\http\controllers;

use \sprint\http\core\Controller;

class Auth extends Controller
{
    private $auth;
    /**
     *
     */
    public function __construct()
    {
        $this->auth = new \sprint\models\Auth();
        $this->viewsPath = "views/layouts/";
    }

    /**
     *
     */
    public function index()
    {
        $this->view("Auth/Login", []);
    }

    
    /**
     *
     */
    public function forgotPassword()
    {
        $this->view("Auth/password-reset", []);
    }

    public function session()
    {
        if (empty($_SESSION['user'])) {
            header("Location: " . route("/login"));
        }
    }

    public function authanticate()
    {
        return $this->auth->authanticate();
    }

    public function logout()
    {
        unset($_SESSION["user"]);
        header("Location: " . route("/login"));
        exit();
    }
}
