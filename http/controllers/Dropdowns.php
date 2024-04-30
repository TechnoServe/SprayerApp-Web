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

class Dropdowns extends Controller
{
    private $dropdown;
    /**
     *
     */
    public function __construct()
    {
        $this->dropdown = new \sprint\models\Dropdowns();
        $this->viewsPath = "views/layouts/";
        $_SESSION["activeGroup"] = "admin";
        $_SESSION["activeLink"] = "dropdowns";
    }

    /**
     *
     */
    public function index()
    {
        $this->view("Dropdowns/Index", []);
    }

    public function show()
    {
        return $this->dropdown->getListOfRegisteredDropdowns();
    }

    public function create()
    {
        return $this->dropdown->create();
    }

    public function save($id)
    {
        try {
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $data = $_POST;
                if(!("false" ==strtolower($data['deleted_at']))){
                    unset($data["deleted_at"]);
                }
                $validation = v::key("name", v::stringType()->notEmpty()->setName("Name/Key"))
                    ->key("value", v::stringType()->notEmpty()->setName("Values"))
                    ->key("type", v::stringType()->notEmpty()->setName("Type"))     
                    ->assert($data); 
                if($validation == null){
                    return $this->dropdown->save($id, $data);
                }
            }

            $data["dropdown"] = $this->dropdown->single($id);

            if(empty($data)) return $this->view("Dropdowns/Index");

            return $this->view("Dropdowns/Save", $data);
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
        //     return $this->dropdown->save($id);
        // }

        // $data = $this->dropdown->single($id);

        // if(empty($data)) return $this->view("Dropdowns/Index");

        // return $this->view("Dropdowns/Save", $data);
    }
}
