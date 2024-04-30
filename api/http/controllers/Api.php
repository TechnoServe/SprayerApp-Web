<?php

/**
 * Created by PhpStorm.
 * User: TNS Programmer
 * Date: 2022-02-16
 * Time: 4:39 PM
 */

namespace sprint\http\controllers;

use \sprint\http\core\Controller;

class Api extends Controller
{
    private $data;
    private $json;
    private $api;
    
    public function __construct(){
        $this->api = new \sprint\models\Api();  
        
        $this->data = file_get_contents("php://input");
        
        if(empty($_SERVER["CONTENT_TYPE"]) || $_SERVER["CONTENT_TYPE"] != "application/json; charset=UTF-8")
        {
            echo json_encode(array(
                "status" => "Content type is not set to json"
            ));            
            exit;
        }
        
        if(strtolower($_SERVER["REQUEST_METHOD"]) == "post")
        {
            if(!$this->json = json_decode($this->data, true))
            {
                echo json_encode(array(
                    "status" => "Bad json format"
                ));
                exit;
            }

            if(empty($this->json))
            {
                echo json_encode(array(
                    "status" => "Empty body"
                ));
                exit;
            } 
        }
    }
    
    public function profiles()
    {
        echo $this->api->profiles();
    }
    
    public function campaigns()
    {
        echo $this->api->campaigns();
    }
    
    public function faqs()
    {
        echo $this->api->faqs();
    }
    
    public function save($table, $uidKey)
    {
        echo $this->api->save($table, $this->json, $uidKey);
    }
    
    public function fetch($table, $uidKey)
    {
        echo $this->api->fetch($table, $this->json, $uidKey);
    }
    
    public function authanticate(){
        echo $this->api->authanticate($this->json);
    }
}
