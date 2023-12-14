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

class Campaigns extends Controller
{
    private $campaign;
    /**
     *
     */
    public function __construct()
    {
        $this->campaign = new \sprint\models\Campaigns();
        $this->viewsPath = "views/layouts/";
        $_SESSION["activeGroup"] = "admin";
        $_SESSION["activeLink"] = "campaigns";
    }

    /**
     *
     */
    public function index()
    {
        $this->view("Campaigns/Index", []);
    }

    public function show()
    {
        return $this->campaign->getListOfRegisteredCampaigns();
    }

    public function create()
    {
        return $this->campaign->create();
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
                ->key("closing", v::stringType()->notEmpty()->setName("End day"))
                    ->key("description", v::stringType()->notEmpty()->setName("Description"))
                    ->assert($data); 
                // Chequing if exits or not
                $exists = $this->campaign->exists($data["opening"], $data["closing"], $id);
                if($exists > 0){
                    // Chequing if exits or not
                    echo json_encode(array(
                        "status" => "error",
                        "message" =>  "The dates selected to this campaign are already allocated to other."
                    ));
                    exit();
                }
                
                if($validation == null){
                    return $this->campaign->save($id, $data);
                }
            }

            $data["campaign"] = $this->campaign->single($id);

            if(empty($data)) return $this->view("Campaigns/Index");

            return $this->view("Campaigns/Save", $data);
        } catch(NestedValidationException | \Exception $exception) {
            // echo $exception->getFullMessage();
            $messages = $exception->getMessages() ?? [$exception->getMessage()];
            $message = implode(".<br />", $messages);
            echo json_encode(array(
                "status" => "error",
                "message" => $message
            ));
        }
        // if ($_SERVER["REQUEST_METHOD"] === "POST") {
        //     return $this->campaign->save($id);
        // }

        // $data = $this->campaign->single($id);

        // if(empty($data)) return $this->view("Campaigns/Index");

        // return $this->view("Campaigns/Save", $data);
    }

    public function toggleStatus($id)
    {
        try {
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $data = $_POST;
                $campaign = $this->campaign->single($id);
                if($campaign != null){
                    $date = date("Y-m-d");
                    $toggle = $campaign["closed_date"] == null ? $date : null;
                    $result = $this->campaign->update("campaigns")->values(["closed_date" => $toggle])->where("id=$id");
                    if($result){
                        echo json_encode(array(
                            "status" => "success",
                            "message" => "Campaign status updated successfully!"
                        )); exit();
                    }else{
                        echo json_encode(array(
                            "status" => "info",
                            "message" => "Failed to toggle status of the campaign"
                        )); exit();
                    }

                }else{
                    echo json_encode(array(
                        "status" => "info",
                        "message" => "Failed to update the campaign"
                    )); exit();
                }
                
            }else{
                echo json_encode(array(
                    "status" => "info",
                    "message" => "Failed to update the campaign"
                )); 
            }
        } catch(NestedValidationException | Exception $exception) {
            // echo $exception->getFullMessage();
            $messages = $exception->getMessages() ?? [$exception->getMessage()];
            $message = implode(".<br />", $messages);
            echo json_encode(array(
                "status" => "error",
                "message" => $message
            ));
        }

        // $data = $this->campaign->single($id);

        // if(empty($data)) return $this->view("Campaigns/Index");

        // return $this->view("Campaigns/Save", $data);
    }
}
