<?php

namespace sprint\http\controllers;

use \sprint\http\core\Controller;

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

class Plots extends Controller
{
    private $plot;
    /**
     *
     */
    public function __construct()
    {
        $this->plot = new \sprint\models\Plots();
        $this->viewsPath = "views/layouts/";
        $_SESSION["activeGroup"] = "data"; 
        $_SESSION["activeLink"] = "plots"; 
    }

    /**
     *
     */
    public function index()
    {
        $data["title"] = "Plots";
        $data["chartValue"] = $this->plot->plotsMapData();
        // var_dump("<pre>",$data["chartValue"]); exit();
        $this->view("Plots/Index", $data);
    }

    public function show()
    {
        return $this->plot->getListOfRegisteredPlots();
    }

    public function create()
    {
        return $this->plot->create();

    }

    public function save($id)
    {
        try{
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $data = $_POST;

                $validation = v::key('farmer_uid', v::notEmpty()->setName("Farmer name"))
                                ->key('plot_uid', v::notEmpty()->setName("Plot Id"))
                                ->key('name', v::notEmpty()->setName("Plot Name"))                
                                ->key('number_of_trees', v::notEmpty()->setName("Number of trees"))                
                                ->key('province', v::stringType()->notEmpty()->setName("Province"))                
                                ->key('district', v::stringType()->notEmpty()->setName("District"))
                                ->key('administrative_post', v::stringType()->setName("Administrative post"))
                                ->assert($data); 

                if($validation == null){
                    return $this->plot->save($id, $data);
                }
            }
            $plot = $this->plot->single($id);
            $data["plot"] = $plot;
            $data["farmer"] = $this->plot->owner($plot);

            if(empty($data)) return $this->view("Plots/Index");
            $data["title"] = "Plots";
            // var_dump("<pre>",$data); die();
            return $this->view("Plots/Save", $data);
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
            $result = $this->plot->deletePlot($id);
            $status = ($result ? "success" : "error");
            $message = ($result ? "Plot disabled successfully" : "Error during the disablement of the plot");
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
