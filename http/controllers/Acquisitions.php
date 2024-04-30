<?php

/**
 * Created by SublimeText.
 * User: AgritechMoz
 * Date: 2023-05-23
 * Time: 8:02 PM
 */

namespace sprint\http\controllers;

use \sprint\http\core\Controller;
use \sprint\models\Dropdowns as Drops;

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

class Acquisitions extends Controller
{
    private $acquisitions;
    private $chemicals, $modes, $suppliers;
    /**
     *
     */
    public function __construct()
    {
        $this->acquisitions = new \sprint\models\Acquisitions();
        $this->viewsPath = "views/layouts/";
        $drops = new Drops();
        $this->suppliers = $drops->options("chemical_supplier");
        $this->chemicals = $drops->options("chemical_name");
        $this->modes = $drops->options("chemical_mode");
        $_SESSION["activeGroup"] = "data"; 
        $_SESSION["activeLink"] = "acquisitions"; 
    }
    /**
     *
     */
    public function index()
    {
        $data["title"] = "Acquisitions";
        $this->view("Acquisitions/Index", $data);
    }

    public function show()
    {
        return $this->acquisitions->getListOfRegisteredAcquisitions();
    }

    public function create()
    {
        return $this->acquisitions->create();

    }

    public function save($id)
    {
        try{
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $data = $_POST;
                $validation = v::key('user_uid', v::notEmpty()->setName("Sprayer name"))
                    ->key('chemical_acquisition_uid', v::notEmpty()->setName("Acquisition Id"))
                    ->key('chemical_acquisition_mode', v::notEmpty()->setName("Acquisition Mode"))   
                    ->key('chemical_supplier', v::notEmpty()->setName("Chemical Supplier"))   
                    ->key('chemical_name', v::notEmpty()->setName("Chemical Name"))   
                    ->key('chemical_quantity', v::notEmpty()->setName("Chemical Quantity"))   
                    ->key('chemical_price', v::notEmpty()->setName("Acquisition price"))   
                    ->key('acquired_at', v::notEmpty()->setName("Acquisition date"))   
                    ->assert($data); 

                if($validation == null){
                    return $this->acquisitions->save($id, $data);
                }
            }
            $acquisitions = $this->acquisitions->single($id);
            $data["acquisitions"] = $acquisitions;
            $data["user"] = $this->acquisitions->owner($acquisitions);
            $data["suppliers"] = $this->suppliers;
            $data["modes"] = $this->modes;
            $data["chemicals"] = $this->chemicals;

            if(empty($data)) return $this->view("Acquisitions/Index");
            $data["title"] = "Acquisitions";
            // var_dump("<pre>",$data); die();
            return $this->view("Acquisitions/Save", $data);
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
            $result = $this->acquisitions->deleteAcquisitions($id);
            $status = ($result ? "success" : "error");
            $message = ($result ? "Acquisitions disabled successfully" : "Error during the disablement of the acquisitions");
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
