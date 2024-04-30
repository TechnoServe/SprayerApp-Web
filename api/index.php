<?php
ob_start();

session_start();

require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/helpers/Helper.php";

use sprint\sroute\SRoute;
use sprint\http\controllers\Api;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();

SRoute::$root = "api/";

SRoute::get("/", function(){
    echo "API HOME";
});

//post data to the server
SRoute::post("/save/{table}/{uidKey}", [Api::class, "save"]);    
SRoute::post("/fetch/{table}/{uidKey}", [Api::class, "fetch"]);

//get data to from the server
SRoute::get("/profiles", [Api::class, "profiles"]);    
SRoute::get("/faqs", [Api::class, "faqs"]);    
SRoute::get("/campaigns", [Api::class, "campaigns"]);
SRoute::post("/login/users", [Api::class, "authanticate"]);

SRoute::run();

ob_end_flush();
