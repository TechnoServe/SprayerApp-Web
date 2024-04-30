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
use Twilio\Rest\Client;

class Farmers extends Controller
{
    private $farmer;
    /**
     *
     */
    public function __construct()
    {
        $this->farmer = new \sprint\models\Farmers();
        $this->viewsPath = "views/layouts/";
        $_SESSION["activeGroup"] = "data";
        $_SESSION["activeLink"] = "farmers";
    }

    /**
     *
     */
    public function index()
    {
        $this->view("Farmers/Index", []);
    }

    public function show()
    {
        return $this->farmer->getListOfRegisteredFarmers();
    }

    public function create()
    {
        return $this->farmer->create();
        // return $this->view("Farmers/Save", $data);

    }

    public function save($id)
    {
        try {
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $data = $_POST;

                $validation = v::key('first_name', v::stringType()->notEmpty()->setName("First Name"))
                    ->key('last_name', v::stringType()->notEmpty()->setName("Last Name"))
                    ->key('gender', v::notEmpty()->setName("Gender"))
                    ->key('birth_date', v::date()->notEmpty()->setName("Birthday"))
                    ->key('email', v::optional(v::email())->setName("Email address"))
                    ->key('mobile_number', v::optional(v::digit())->setName("Mobile Number"))
                    ->key('province', v::stringType()->notEmpty()->setName("Province"))
                    ->key('district', v::stringType()->notEmpty()->setName("District"))
                    ->key('administrative_post', v::stringType()->setName("Administrative post"))
                    ->assert($data);

                if ($validation == null) {
                    return $this->farmer->save($id, $data);
                }
            }

            $data["farmer"] = $this->farmer->single($id);

            if (empty($data)) return $this->view("Farmers/Index");

            return $this->view("Farmers/Save", $data);
        } catch (NestedValidationException  $exception) {
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
        try {
            $result = $this->farmer->deleteFarmer($id);
            $status = ($result ? "success" : "error");
            $message = ($result ? "Farmer disabled successfully" : "Error during the disablement of the farmer");
            echo json_encode(array(
                "status" => $status,
                "code" => 400,
                "message" => $message
            ));
        } catch (NestedValidationException $exception) {
            $messages = $exception->getMessages() ?? [$exception->getMessage()];
            $message = implode(".<br />", $messages);
            echo json_encode(array(
                "status" => "error",
                "code" => 400,
                "message" => $message
            ));
        }
    }

    function sms()
    {

        return $this->view("Farmers/sms", []);
    }

    function sendSMS()
    {

        try {
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $data = $_POST;
                $validation = v::key('message', v::stringType()->notEmpty()->setName("Message"))
                    ->assert($data);

                if ($validation == null) {
                    // echo json_encode($data); exit();
                    // return $this->farmer->sendSMS($data);
                    $nrs = $data["nrs"];
                    foreach ($nrs as $nr) {
                        // Your Account SID and Auth Token from console.twilio.com
                        $sid = $_ENV["TWILIO_SID"]; 
                        $token = $_ENV["TWILIO_TOKEN"];
                        $client = new  Client($sid, $token);
                        // $client->setLogLevel('debug');

                        // Use the Client to make requests to the Twilio REST API
                        $message = $client->messages->create(
                            // The number you'd like to send the message to
                            "+258{$nr}",
                            [
                                "messagingServiceSid" => $_ENV["TWILIO_MSSID"],
                                'body' => $data["message"] ?? "Caju+ SMS Alert!"
                            ]
                        );
                    }
                    // echo json_encode(["message" => $message]);
                    echo json_encode(array(
                        "status" => "success",
                        "code" => 200,
                        "message" => "Messages were sent."
                    ));
                }
            }
        } catch (NestedValidationException  $exception) {
            $messages = $exception->getMessages() ?? [$exception->getMessage()];
            $message = implode(".<br />", $messages);
            echo json_encode(array(
                "status" => "error",
                "code" => 400,
                "message" => $message
            ));
        }
    }


    function getGeoFarmers()
    {

        try {
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $data = $_POST;
                $provinces = $data["province"] ?? [];
                $districts = $data["district"] ?? [];
                $gender = $data["gender"]  ?? "";
                $farmers = (!empty($provinces) ? $this->farmer->geoFarmers($provinces, $districts, $gender) : []) ?? [];
                echo json_encode(array(
                    "status" => "success",
                    "code" => 200,
                    "message" => "Found: " . count($farmers),
                    "data" => $farmers
                ));
            }
        } catch (\Throwable  $exception) {
            echo json_encode(array(
                "status" => "error",
                "code" => 400,
                "message" => "Error while getting the farmers to send the sms: {$exception->getMessage()} in line {$exception->getLine()}",
                "data" => []
            ));
        }
    }
}
