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

class Applications extends Controller
{
    private $application;
    /**
     *
     */
    public function __construct()
    {
        $this->application = new \sprint\models\Applications();
        $this->viewsPath = "views/layouts/";
        $_SESSION["activeGroup"] = "data"; 
        $_SESSION["activeLink"] = "applications"; 
    }

    /**
     *
     */
    public function index()
    {
        $data["title"] = "Applications";
        $this->view("Applications/Index", $data);
    }

    public function show()
    {
        return $this->application->getListOfRegisteredApplications();
    }

    public function create()
    {
        return $this->application->create();

    }

    public function save($id)
    {
        try{
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $data = $_POST;
                $validation = v::key('farmer_uid', v::notEmpty()->setName("Farmer name"))
                                ->key('chemical_application_uid', v::notEmpty()->setName("Application Id"))
                                ->key('application_number', v::notEmpty()->setName("Application Number"))                
                                ->key('number_of_trees_sprayed', v::notEmpty()->setName("Number of trees"))                
                                ->key('sprayed_at', v::dateTime()->notEmpty()->setName("Sprayed Date"))   
                                ->assert($data); 

                if($validation == null){
                    return $this->application->save($id, $data);
                }
            }
            $application = $this->application->single($id);
            $data["application"] = $application;
            $data["farmer"] = $this->application->owner($application);

            if(empty($data)) return $this->view("Applications/Index");
            $data["title"] = "Applications";
            // var_dump("<pre>",$data); die();
            return $this->view("Applications/Save", $data);
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
            $result = $this->application->deleteApplication($id);
            $status = ($result ? "success" : "error");
            $message = ($result ? "Application disabled successfully" : "Error during the disablement of the application");
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
