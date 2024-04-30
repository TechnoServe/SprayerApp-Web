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

class Campains extends Controller
{
    private $campain;
    /**
     *
     */
    public function __construct()
    {
        $this->campain = new \sprint\models\Campains();
        $this->viewsPath = "views/layouts/";
        $_SESSION["activeGroup"] = "admin";
        $_SESSION["activeLink"] = "campains";
    }

    /**
     *
     */
    public function index()
    {
        $this->view("Campains/Index", []);
    }

    public function show()
    {
        return $this->campain->getListOfRegisteredCampains();
    }

    public function create()
    {
        return $this->campain->create();
    }

    public function save($id)
    {
        try {
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $data = $_POST;
                if(!("false" ==strtolower($data['deleted_at']))){
                    unset($data["deleted_at"]);
                }
                $validation = v::key("opening", v::stringType()->notEmpty()->setName("Start day"))
                ->key("clossing", v::stringType()->notEmpty()->setName("End day"))
                    ->key("description", v::stringType()->notEmpty()->setName("Description"))
                    ->assert($data); 
                if($validation == null){
                    return $this->campain->save($id, $data);
                }
            }

            $data["campain"] = $this->campain->single($id);

            if(empty($data)) return $this->view("Campains/Index");

            return $this->view("Campains/Save", $data);
        } catch(NestedValidationException | Exception $exception) {
            // echo $exception->getFullMessage();
            $messages = $exception->getMessages() ?? [$exception->getMessage()];
            $message = implode(".<br />", $messages);
            echo json_encode(array(
                "status" => "error",
                "message" => $message
            ));
        }
        // if ($_SERVER["REQUEST_METHOD"] === "POST") {
        //     return $this->campain->save($id);
        // }

        // $data = $this->campain->single($id);

        // if(empty($data)) return $this->view("Campains/Index");

        // return $this->view("Campains/Save", $data);
    }
}
