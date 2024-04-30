<?php

/**
 * Created by SublimeText.
 * User: AgritechMoz
 * Date: 2023-05-23
 * Time: 8:02 PM
 */

namespace sprint\http\controllers;

use \sprint\http\core\Controller;

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

class Equipments extends Controller
{
    private $equipments;
    /**
     *
     */
    public function __construct()
    {
        $this->equipments = new \sprint\models\Equipments();
        $this->viewsPath = "views/layouts/";
        $_SESSION["activeGroup"] = "data"; 
        $_SESSION["activeLink"] = "equipments"; 
    }

    /**
     *
     */
    public function index()
    {
        $data["title"] = "Equipments";
        $this->view("Equipments/Index", $data);
    }

    public function show()
    {
        return $this->equipments->getListOfRegisteredEquipments();
    }

    public function create()
    {
        return $this->equipments->create();

    }

    public function save($id)
    {
        try{
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $data = $_POST;
                $validation = v::key('user_uid', v::notEmpty()->setName("Sprayer name"))
                                ->key('equipments_uid', v::notEmpty()->setName("Equipment Id"))
                                ->key('name', v::notEmpty()->setName("Equipment Name"))   
                                ->key('brand', v::notEmpty()->setName("Equipment Bramd"))   
                                // ->key('model', v::notEmpty()->setName("Equipment Model"))   
                                ->key('status', v::notEmpty()->setName("Equipment Status"))   
                                ->assert($data); 

                if($validation == null){
                    return $this->equipments->save($id, $data);
                }
            }
            $equipments = $this->equipments->single($id);
            $data["equipments"] = $equipments;
            $data["user"] = $this->equipments->owner($equipments);

            if(empty($data)) return $this->view("Equipments/Index");
            $data["title"] = "Equipments";
            // var_dump("<pre>",$data); die();
            return $this->view("Equipments/Save", $data);
        } catch(NestedValidationException | Exception $exception) {
            $messages = $exception->getMessages() ?? [$exception->getMessage()];
            $message = implode(".<br />", $messages);
            echo json_encode(array(
                "status" => "error",
                "code" => 400,
                "message" => $message
            ));
        }
    }

     public function delete($id)
    {
        try{
            $result = $this->equipments->deleteEquipments($id);
            $status = ($result ? "success" : "error");
            $message = ($result ? "Equipments disabled successfully" : "Error during the disablement of the equipments");
            echo json_encode(array(
                "status" => $status,
                "code" => 400,
                "message" => $message
            ));
        } catch(NestedValidationException | Exception $exception) {
            $messages = $exception->getMessages() ?? [$exception->getMessage()];
            $message = implode(".<br />", $messages);
            echo json_encode(array(
                "status" => "error",
                "code" => 400,
                "message" => $message
            ));
        }
    }
}
