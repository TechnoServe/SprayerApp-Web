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

class Faqs extends Controller
{
    private $faq;
    /**
     *
     */
    public function __construct()
    {
        $this->faq = new \sprint\models\Faqs();
        $this->viewsPath = "views/layouts/";
        $_SESSION["activeGroup"] = "admin";
        $_SESSION["activeLink"] = "faqs";
    }

    /**
     *
     */
    public function index()
    {
        $this->view("Faqs/Index", []);
    }

    public function show()
    {
        return $this->faq->getListOfRegisteredFaqs();
    }

    public function create()
    {
        return $this->faq->create();
    }

    public function save($id)
    {
        try {
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $data = $_POST;
                if(!("false" ==strtolower($data['deleted_at']))){
                    unset($data["deleted_at"]);
                }
                $validation = v::key("title", v::stringType()->notEmpty()->setName("Title"))
                    ->key("description", v::stringType()->notEmpty()->setName("Description"))
                    ->assert($data); 
                if($validation == null){
                    return $this->faq->save($id, $data);
                }
            }

            $data["faq"] = $this->faq->single($id);

            if(empty($data)) return $this->view("Faqs/Index");

            return $this->view("Faqs/Save", $data);
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
        //     return $this->faq->save($id);
        // }

        // $data = $this->faq->single($id);

        // if(empty($data)) return $this->view("Faqs/Index");

        // return $this->view("Faqs/Save", $data);
    }
}
