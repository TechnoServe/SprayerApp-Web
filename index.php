<?php
ob_start();

session_start();

require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/helpers/Helper.php";

use sprint\sroute\SRoute;
use sprint\http\controllers\Dashboards;
use sprint\http\controllers\Users;
use sprint\http\controllers\{Profiles, Farmers, Plots, Applications, Equipments, Acquisitions, Incomes, Logs, Payments, Agreements, Dropdowns, Requests, Faqs, Campaigns};
use sprint\http\controllers\Auth;


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

SRoute::$root = "/";

SRoute::get("/", [Dashboards::class, "index"])->middleware([Auth::class, "session"]);

SRoute::get("/login", [Auth::class, "index"]);
SRoute::get("/forgot-password", [Auth::class, "forgotPassword"]);
SRoute::post("/send-password-link", [Users::class, "sendPasswordLink"]);
SRoute::get("/password-reset/{token}", [Users::class, "passwordResetForm"]);
SRoute::post("/update-user-password", [Users::class, "updateUserPassword"]);
SRoute::post("/authanticate", [Auth::class, "authanticate"]);
SRoute::get("/logout", [Auth::class, "logout"]);
SRoute::get("/load-filter", [Dashboards::class, "loadFilter"]);
SRoute::post("/request/seller/profile", [Requests::class, "requestProfile"]);


SRoute::group(
	array(
		"middleware" => array(
			[Auth::class, "session"]
		)
	),
	function () {
		//Group of routes for dashboards
		SRoute::group(array(
			"prefix" => "/dashboards",
			"controller" => Dashboards::class,
		), function () {
			//homepage
			SRoute::get("/")->method("index");

			//Load filter
			SRoute::post("/loadFilter")->method("loadFilter");

			//Load datatable
			SRoute::post("/loadDatatable")->method("loadDatatable");

			//Load datatable
			SRoute::post("/sprayers/list-all")->method("listAll");


			//KPI's
			SRoute::post("/activeSprayersUsers")->method("activeSprayersUsers");
			SRoute::post("/registeredFarmers")->method("registeredFarmers");
			SRoute::post("/assistedFarmers")->method("assistedFarmers");
			SRoute::post("/rcnCollected")->method("rcnCollected");
			SRoute::post("/rcnCollectedPerSprayers")->method("rcnCollectedPerSprayers");
			SRoute::post("/netIncomePerSprayers")->method("netIncomePerSprayers");

			//Chart's
			SRoute::post("/numberOfTreesSprayedPerApplication")->method("numberOfTreesSprayedPerApplication");
			SRoute::post("/chemicalProvenance")->method("chemicalProvenance");
		});

		SRoute::group(array(
			"prefix" => "/users",
			"controller" => Users::class
		), function () {
			SRoute::get("/")->method("index");
			SRoute::post("/show")->method("show");
			SRoute::get("/create")->method("create");
			SRoute::get("/save/{id}")->method("save");
			SRoute::post("/save/{id}")->method("save");
			SRoute::delete("/remove/{id}")->method("remove");
			SRoute::post("/reset-password/{id}")->method("resetPassword");
		});

		SRoute::group(array(
			"prefix" => "/profiles",
			"controller" => Profiles::class
		), function () {
			SRoute::get("/")->method("index");
			SRoute::get("/create")->method("create");
			SRoute::get("/save/{id}?")->method("save");
			SRoute::post("/save/{id}?")->method("save");
			SRoute::post("/privilages/{id}?")->method("privilages");
			SRoute::post("/show")->method("show");
		});
		SRoute::group(array(
			"prefix" => "/farmers",
			"controller" => Farmers::class
		), function () {
			SRoute::get("/")->method("index");
			SRoute::get("/create")->method("create");
			SRoute::get("/save/{id}?")->method("save");
			SRoute::post("/save/{id}?")->method("save");
			SRoute::post("/show")->method("show");
			SRoute::get("/delete/{id}")->method("delete");
			SRoute::get("/sms")->method("sms");
			SRoute::post("/sms/send")->method("sendSMS");
			SRoute::post("/sms/get-geo-farmers")->method("getGeoFarmers");
		});

		SRoute::group(array(
			"prefix" => "/plots",
			"controller" => Plots::class
		), function () {
			SRoute::get("/")->method("index");
			SRoute::get("/create")->method("create");
			SRoute::get("/save/{id}?")->method("save");
			SRoute::post("/save/{id}?")->method("save");
			SRoute::post("/show")->method("show");
			SRoute::get("/delete/{id}")->method("delete");
		});

		SRoute::group(array(
			"prefix" => "/applications",
			"controller" => Applications::class
		), function () {
			SRoute::get("/")->method("index");
			SRoute::get("/create")->method("create");
			SRoute::get("/save/{id}?")->method("save");
			SRoute::post("/save/{id}?")->method("save");
			SRoute::post("/show")->method("show");
			SRoute::get("/delete/{id}")->method("delete");
		});

		SRoute::group(array(
			"prefix" => "/equipments",
			"controller" => Equipments::class
		), function () {
			SRoute::get("/")->method("index");
			SRoute::get("/create")->method("create");
			SRoute::get("/save/{id}?")->method("save");
			SRoute::post("/save/{id}?")->method("save");
			SRoute::post("/show")->method("show");
			SRoute::get("/delete/{id}")->method("delete");
		});

		SRoute::group(array(
			"prefix" => "/acquisitions",
			"controller" => Acquisitions::class
		), function () {
			SRoute::get("/")->method("index");
			SRoute::get("/create")->method("create");
			SRoute::get("/save/{id}?")->method("save");
			SRoute::post("/save/{id}?")->method("save");
			SRoute::post("/show")->method("show");
			SRoute::get("/delete/{id}")->method("delete");
		});

		SRoute::group(array(
			"prefix" => "/incomes",
			"controller" => Incomes::class
		), function () {
			SRoute::get("/")->method("index");
			SRoute::get("/create")->method("create");
			SRoute::get("/save/{id}?")->method("save");
			SRoute::post("/save/{id}?")->method("save");
			SRoute::post("/show")->method("show");
			SRoute::get("/delete/{id}")->method("delete");
		});

		SRoute::group(array(
			"prefix" => "/payments",
			"controller" => Payments::class
		), function () {
			SRoute::get("/")->method("index");
			SRoute::get("/create")->method("create");
			SRoute::get("/save/{id}?")->method("save");
			SRoute::post("/save/{id}?")->method("save");
			SRoute::post("/show")->method("show");
			SRoute::get("/delete/{id}")->method("delete");
		});

		SRoute::group(array(
			"prefix" => "/logs",
			"controller" => Logs::class
		), function () {
			SRoute::get("/")->method("index");
			SRoute::get("/create")->method("create");
			SRoute::get("/save/{id}?")->method("save");
			SRoute::post("/save/{id}?")->method("save");
			SRoute::post("/show")->method("show");
			SRoute::get("/delete/{id}")->method("delete");
		});

		SRoute::group(array(
			"prefix" => "/agreements",
			"controller" => Agreements::class
		), function () {
			SRoute::get("/")->method("index");
			SRoute::get("/create")->method("create");
			SRoute::get("/save/{id}?")->method("save");
			SRoute::post("/save/{id}?")->method("save");
			SRoute::post("/show")->method("show");
			SRoute::get("/delete/{id}")->method("delete");
		});

		SRoute::group(array(
			"prefix" => "/dropdowns",
			"controller" => Dropdowns::class
		), function () {
			SRoute::get("/")->method("index");
			SRoute::get("/create")->method("create");
			SRoute::get("/save/{id}?")->method("save");
			SRoute::post("/save/{id}?")->method("save");
			SRoute::post("/show")->method("show");
			SRoute::get("/delete/{id}")->method("delete");
		});

		SRoute::group(array(
			"prefix" => "/faqs",
			"controller" => Faqs::class
		), function () {
			SRoute::get("/")->method("index");
			SRoute::get("/create")->method("create");
			SRoute::get("/save/{id}?")->method("save");
			SRoute::post("/save/{id}?")->method("save");
			SRoute::post("/show")->method("show");
			SRoute::get("/delete/{id}")->method("delete");
		});

		SRoute::group(array(
			"prefix" => "/requests",
			"controller" => Requests::class
		), function () {
			SRoute::get("/")->method("index");
			SRoute::post("/show")->method("show");
			SRoute::get("/create")->method("create");
			SRoute::get("/save/{id}")->method("save");
			SRoute::post("/save/{id}")->method("save");
			SRoute::delete("/remove/{id}")->method("remove");
			SRoute::post("/{id}/approve/")->method("approve");
			// SRoute::get("/{id}/repprove")->method("repprove");
			SRoute::post("/reset-password/{id}")->method("resetPassword");
		});


		SRoute::group(array(
			"prefix" => "/campaigns",
			"controller" => Campaigns::class
		), function () {
			SRoute::get("/")->method("index");
			SRoute::get("/create")->method("create");
			SRoute::get("/save/{id}?")->method("save");
			SRoute::post("/save/{id}?")->method("save");
			SRoute::post("/show")->method("show");
			SRoute::get("/delete/{id}")->method("delete");
			SRoute::post("/toggle-status/{id}")->method("toggleStatus");
		});
	}
);

SRoute::run();

ob_end_flush();
