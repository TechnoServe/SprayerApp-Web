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

class Agreements extends Controller
{
    private $agreement;
    /**
     *
     */
    public function __construct()
    {
        $this->agreement = new \sprint\models\Agreements();
        $this->viewsPath = "views/layouts/";
        
        $_SESSION["activeGroup"] = "finances";
        $_SESSION["activeLink"] = "agreements";
       
    }

    /**
     *
     */
    public function index()
    {
        $data["title"] = "Agreements";
        $this->view("Agreements/Index", $data);
    }

    public function show()
    {
        return $this->agreement->getListOfRegisteredAgreements();
    }

    public function create()
    {
        return $this->agreement->create();

    }

    public function save($id)
    {
        try{
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $data = $_POST;

                $validation = v::key('farmer_uid', v::notEmpty()->setName("Farmer name"))
                                ->key('payment_aggreement_uid', v::notEmpty()->setName("Agreement Id"))
                                ->key('aggreed_payment', v::notEmpty()->setName("Payment"))            
                                ->key('number_of_applications', v::notEmpty()->setName("Payment applications"))            
                                ->key('aggreed_trees_to_spray', v::notEmpty()->setName("Number of trees"))              
                                ->assert($data); 

                if($validation == null){
                    return $this->agreement->save($id, $data);
                }
            }
            $agreement = $this->agreement->single($id);
            $data["agreement"] = $agreement;
            $data["farmer"] = $this->agreement->owner($agreement);

            if(empty($data)) return $this->view("Agreements/Index");
            $data["title"] = "Agreements";
            return $this->view("Agreements/Save", $data);
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
            $result = $this->agreement->deleteAgreement($id);
            $status = ($result ? "success" : "error");
            $message = ($result ? "Agreement disabled successfully" : "Error during the disablement of the agreement");
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
