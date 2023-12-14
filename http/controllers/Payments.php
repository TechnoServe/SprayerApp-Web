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

class Payments extends Controller
{
    private $payment, $types;
    /**
     *
     */
    public function __construct()
    {
        $this->payment = new \sprint\models\Payments();
        $this->viewsPath = "views/layouts/";
        $this->$types = [
            'Mzn',
            'Kg',
        ];
        $_SESSION["activeGroup"] = "finances";
        $_SESSION["activeLink"] = "payments";
    }

    /**
     *
     */
    public function index()
    {
        $data["title"] = "Payments";
        $this->view("Payments/Index", $data);
    }

    public function show()
    {
        return $this->payment->getListOfRegisteredPayments();
    }

    public function create()
    {
        return $this->payment->create();

    }

    public function save($id)
    {
        try{
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $data = $_POST;

                $validation = v::key('farmer_uid', v::notEmpty()->setName("Farmer name"))
                                ->key('payment_uid', v::notEmpty()->setName("Payment Id"))
                                ->key('payment_type', v::notEmpty()->setName("Payment Type"))            
                                ->key('paid', v::notEmpty()->setName("Paid"))              
                                ->assert($data); 

                if($validation == null){
                    return $this->payment->save($id, $data);
                }
            }
            $payment = $this->payment->single($id);
            $data["payment"] = $payment;
            $data["farmer"] = $this->payment->owner($payment);
            $data["types"] = $this->types;

            if(empty($data)) return $this->view("Payments/Index");
            $data["title"] = "Payments";
            return $this->view("Payments/Save", $data);
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
            $result = $this->payment->deletePayment($id);
            $status = ($result ? "success" : "error");
            $message = ($result ? "Payment disabled successfully" : "Error during the disablement of the payment");
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
