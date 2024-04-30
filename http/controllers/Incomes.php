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

class Incomes extends Controller
{
    private $incomes;
    private $categories, $types, $payments;
    /**
     *
     */
    public function __construct()
    {
        $this->incomes = new \sprint\models\Incomes();
        $this->viewsPath = "views/layouts/";
        $this->categories = [
            "Employees",
            "Equipment acquisition",
            "Equipment maintenance",
            "Trabalhadores",
            "Combustivel",
            "Compra de Equipamento",
            "Limpeza e Poda",
            "Manutenção de Equipamento",
            "Cleaning and Pruning",
        ];
        
        $_SESSION["activeGroup"] = "finances";
        $_SESSION["activeLink"] = "incomes";
        
        $this->types = [
            'Despesa',
            'Receita',
        ];

        $this->$payments = [
            'Mzn',
            'Kg',
        ];
    }

    /**
     *
     */
    public function index()
    {
        $data["title"] = "Incomes";
        $this->view("Incomes/Index", $data);
    }

    public function show()
    {
        return $this->incomes->getListOfRegisteredIncomes();
    }

    public function create()
    {
        return $this->incomes->create();

    }

    public function save($id)
    {
        try{
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $data = $_POST;
                $validation = v::key('user_uid', v::notEmpty()->setName("Sprayer name"))
                    ->key('expenses_income_uid', v::notEmpty()->setName("Id"))
                    ->key('category', v::notEmpty()->setName("Category"))   
                    ->key('price', v::notEmpty()->setName("Price"))   
                    ->key('expenses_income_type', v::notEmpty()->setName("Type"))   
                    ->key('expenses_income_payment_type', v::notEmpty()->setName("Payment Type"))    
                    ->assert($data); 

                if($validation == null){
                    return $this->incomes->save($id, $data);
                }
            }
            $incomes = $this->incomes->single($id);
            $data["incomes"] = $incomes;
            $data["user"] = $this->incomes->owner($incomes);
            $data["payments"] = $this->$payments;
            $data["types"] = $this->types;
            $data["categories"] = $this->categories;

            if(empty($data)) return $this->view("Incomes/Index");
            $data["title"] = "Incomes";
            // var_dump("<pre>",$data); die();
            return $this->view("Incomes/Save", $data);
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
            $result = $this->incomes->deleteIncomes($id);
            $status = ($result ? "success" : "error");
            $message = ($result ? "Incomes disabled successfully" : "Error during the disablement of the incomes");
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
