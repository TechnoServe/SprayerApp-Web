<?php

/**
 * Created by PhpStorm.
 * Request: TNS Programmer
 * Date: 2022-02-16
 * Time: 4:39 PM
 */

namespace sprint\http\controllers;

use \sprint\http\core\Controller;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

class Requests extends Controller
{
    private $request;
    private $user;
    private $profile;
    /**
     *
     */
    public function __construct()
    {
        $this->request = new \sprint\models\Requests();
        $this->profile = new \sprint\models\Profiles();
        $this->user = new \sprint\models\Users();
        $this->viewsPath = "views/layouts/";
        $_SESSION["activeGroup"] = "admin";
        $_SESSION["activeLink"] = "requests";
    }

    /**
     *
     */
    public function index()
    {
        $this->view("Requests/Index", []);
    }

    public function show()
    {
        return $this->request->getListOfRegisteredRequests();
    }

    public function create()
    {
        return $this->request->create();
    }

    public function save($id)
    {
        try {
            // return $id;
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $data = $_POST;
                if(!("false" ==strtolower($data['deleted_at']))){
                    unset($data["deleted_at"]);
                }
                $validation = v::key('first_name', v::stringType()->notEmpty()->setName("First Name"))
                                ->key('last_name', v::stringType()->notEmpty()->setName("Last Name"))
                                ->key('birth_date', v::date()->notEmpty()->setName("Birthday"))                
                                ->key('email', v::optional(v::email())->setName("Email address"))                
                                ->key('mobile_number', v::digit()->notEmpty()->setName("Mobile Number"))                
                                ->key('province', v::stringType()->notEmpty()->setName("Province"))                
                                ->key('district', v::stringType()->notEmpty()->setName("District"))
                                                
                                // ->key('password', v::stringType()->notEmpty()->setName("Password"))                
                                ->assert($data); 
                if($validation == null){
                    $request = $this->request->single($id);
                    return $this->request->save($id, $data, $request["password"] ?? "");
                }
            }

            $data["request"] = $this->request->single($id);
            $data["profiles"] = $this->profile->show();
            if(empty($data["request"])) return $this->view("Requests/Index");

            return $this->view("Requests/Save", $data);
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

    public function requestProfile(){
        try {
            $result = json_encode(array(
                "status" => "success",
                "message" => "Creating a request."
            ));
            // return $id;
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $data = $_POST;
                if(!("false" ==strtolower($data['deleted_at']))){
                    unset($data["deleted_at"]);
                }
                $validation = v::key('first_name', v::stringType()->notEmpty()->setName("First Name"))
                                ->key('last_name', v::stringType()->notEmpty()->setName("Last Name"))
                                ->key('password', v::stringType()->length(6, null)->setName("Password"))
                                ->key('email', v::optional(v::email())->setName("Email address"))
                                ->key('mobile_number', v::digit()->notEmpty()->setName("Mobile Number"))                
                                ->key('province', v::stringType()->notEmpty()->setName("Province"))              
                                ->key('district', v::stringType()->notEmpty()->setName("District"))          
                                ->assert($data); 
                if($validation == null){
                    $existsRequest = $this->request->findByMobileNumber($data["mobile_number"]);
                    $existsUser = $this->user->findByMobileNumber($data["mobile_number"]);
                    // var_dump($exists); exit();
                    if($existsUser > 0 &&existsRequest ){
                        $result = json_encode(array(
                                        "status" => "info",
                                        "message" => "The mobile number was used by a user or by a old request. Please, type a new one!"
                                    )); 
                    }else{
                        $result =  $this->request->create();
                    }
                }                
            }
            echo $result;

        } catch(NestedValidationException | Exception $exception) {
            $messages = $exception->getMessages() ?? [$exception->getMessage()];
            $message = implode(".<br />", $messages);
            echo json_encode(array(
                "status" => "error",
                "message" => $message
            ));
        }
    }


    public function approve($id){
        $status = $_POST['status'];
        try { 
            #find the request
            $sellerProfile = $this->profile->findByName("Seller");
            if(!$sellerProfile){
                 echo json_encode(array(
                    "status" => "info",
                    "message" => "There is no Seller profile!"
                )); exit();
            }
            $request = $this->request->single($id);
            if(!$request){
                 echo json_encode(array(
                    "status" => "info",
                    "message" => "Request not found!"
                )); exit();
            }
            $user = $this->user->findByUserName($request["mobile_number"]);
            if(!$user){
                return json_encode(array(
                    "status" => "info",
                    "message" => "User not found!"
                ));exit();
            }
            echo $this->request->updateApproved($request["id"], $user["id"], $status, $sellerProfile["id"]);

        } catch(NestedValidationException | Exception $exception) {
            $messages = $exception->getMessages() ?? [$exception->getMessage()];
            $message = implode(".<br />", $messages);
            echo json_encode(array(
                "status" => "error",
                "message" => $message
            ));
        }
    }

}
