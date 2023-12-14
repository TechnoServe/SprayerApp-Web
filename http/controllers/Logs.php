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

class Logs extends Controller
{
    private $logs;
    private $chemicals, $modes, $suppliers;
    /**
     *
     */
    public function __construct()
    {
        $this->logs = new \sprint\models\Logs();
        $this->viewsPath = "views/layouts/";
        $this->chemicals = [
            'Flutriafol (Starback) (L)',
            'Oxicloreto de cobre (Snow cop) (KG)',
            'Trifloxystrobin (Snowstrobin) (KG)',
            'Oxicloreto de cobre  (Coprox Super) (KG)',
            'Beta-Ciflutrina (Bulldock) (KG)',
            'Trifloxystrobin  (Flint) (KG)',
            'Lambda Cyhalothrin  (Karate) (KG)',
            'Lambda Cyhalothrin  (Fortis K) (KG)',
            'Triadimenol  (Voltriad) (KG)',
            'Lambda Cyhalothrin (Ninja plus) (KG)',
            'Trifloxystrobin (Virgo) (KG)',
        ];
        
        $this->modes = [
            'Comprado',
            'Gratuito',
        ];

        $this->suppliers = [
            'AGRIFOCUS/IAM',
            'BAYER',
            'SNOW Internacional',
        ];

        $_SESSION["activeGroup"] = "admin";
        $_SESSION["activeLink"] = "logs";
    }

    /**
     *
     */
    public function index()
    {
        $data["title"] = "Logs";
        $this->view("Logs/Index", $data);
    }

    public function show()
    {
        return $this->logs->getListOfRegisteredLogs();
    }

    public function create()
    {
        return $this->logs->create();

    }

    public function save($id)
    {
        try{
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $data = $_POST;
                $validation = v::key('user_uid', v::notEmpty()->setName("Sprayer name"))
                    ->key('chemical_log_uid', v::notEmpty()->setName("Log Id"))
                    ->key('chemical_log_mode', v::notEmpty()->setName("Log Mode"))   
                    ->key('chemical_supplier', v::notEmpty()->setName("Chemical Supplier"))   
                    ->key('chemical_name', v::notEmpty()->setName("Chemical Name"))   
                    ->key('chemical_quantity', v::notEmpty()->setName("Chemical Quantity"))   
                    ->key('chemical_price', v::notEmpty()->setName("Log price"))   
                    ->key('acquired_at', v::notEmpty()->setName("Log date"))   
                    ->assert($data); 

                if($validation == null){
                    return $this->logs->save($id, $data);
                }
            }
            $logs = $this->logs->single($id);
            $data["logs"] = $logs;
            $data["user"] = $this->logs->owner($logs);
            $data["suppliers"] = $this->suppliers;
            $data["modes"] = $this->modes;
            $data["chemicals"] = $this->chemicals;

            if(empty($data)) return $this->view("Logs/Index");
            $data["title"] = "Logs";
            // var_dump("<pre>",$data); die();
            return $this->view("Logs/Save", $data);
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
            $result = $this->logs->deleteLogs($id);
            $status = ($result ? "success" : "error");
            $message = ($result ? "Logs disabled successfully" : "Error during the disablement of the logs");
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
