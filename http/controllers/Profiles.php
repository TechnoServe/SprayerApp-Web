<?php

/**
 * Created by PhpStorm.
 * User: TNS Programmer
 * Date: 2022-02-16
 * Time: 4:39 PM
 */

namespace sprint\http\controllers;

use \sprint\http\core\Controller;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

class Profiles extends Controller
{
    private $profile;
    /**
     *
     */
    public function __construct()
    {
        $this->profile = new \sprint\models\Profiles();
        $this->viewsPath = "views/layouts/";
        $_SESSION["activeGroup"] = "admin";
        $_SESSION["activeLink"] = "profiles";
    }

    /**
     *
     */
    public function index()
    {
        $this->view("Profiles/Index", []);
    }

    public function show()
    {
        return $this->profile->getListOfRegisteredProfiles();
    }

    public function create()
    {
        return $this->profile->create();
    }

    public function save($id)
    {
        try {
             if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $data = $_POST;
                $validation = v::key('name', v::stringType()->notEmpty()->setName("Profile name"))             
                                ->assert($data); 
                if($validation == null){
                    $profile = $this->profile->single($id);
                    return $this->profile->save($id);
                }
            }

            $data["profile"]= $this->profile->single($id);

            if(empty($data["profile"])) return $this->view("Profiles/Index");

            return $this->view("Profiles/Save", $data);
        } catch(NestedValidationException | Exception $exception) {
            // echo $exception->getFullMessage();
            $messages = $exception->getMessages() ?? [$exception->getMessage()];
            $message = implode(".<br />", $messages);
            echo json_encode(array(
                "status" => "error",
                "message" => $message
            ));
        }
    }
}
