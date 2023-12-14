<?php

/**
 * Created by PhpStorm.
 * User: TNS Programmer
 * Date: 2022-02-16
 * Time: 4:39 PM
 */

namespace sprint\http\controllers;

use \sprint\http\core\Controller;

class Dashboards extends Controller
{
    private $dash;
    private $campaign;
    private $data;
    /**
     *
     */
    public function __construct()
    {
        $this->dash = new \sprint\models\Dashboards();
        $this->campaign = new \sprint\models\Campaigns();
        $this->viewsPath = "views/layouts/";
        $this->data["campaigns"] = $this->campaign->all();
        $_SESSION["activeGroup"] = "dashboard";
        $_SESSION["activeLink"] = "dashboard";
    }

    /**
     *
     */
    public function index()
    {
        return $this->view("Dashboards/Index", []);
    }

    public function loadFilter()
    {
        try {
            echo json_encode(
                array(
                    "output" =>  $this->cView("Widgets/Filter", $this->data, "json")
                )
            );
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function loadDatatable()
    {
        echo json_encode(
            array(
                "output" =>  $this->cView("Widgets/Datatable", [], "json")
            )
        );
    }

    public function activeSprayersUsers()
    {
        $providers = $this->dash->activeSprayersUsersByProfile("Provedor");
        $acticeSprayers = $this->dash->activeSprayersUsers();
        $percentage = number_format($providers / $acticeSprayers * 100, 2);
        $color = ($percentage < 50) ? "bg-danger" : (($percentage < 75) ? 'bg-primary' : 'bg-success');
        $data = array(
            "value" => "{$providers}/{$acticeSprayers}",
            "title" => "Number of Active Sprayers",
            "tooltipTitle" => "This is the total number of sprayers that have access to the system",
            "tooltipPosition" => "top",
            "progress" => [true, (int)$percentage, $color]

        );

        echo json_encode(
            array(
                "output" =>  $this->cView("Widgets/Kpi", $data, "json")
            )
        );
    }

    public function registeredFarmers()
    {
        $data = array(
            "value" => $this->dash->registeredFarmers(),
            "title" => "Number of Registered Farmers",
            "tooltipTitle" => "This is the total number of farmers that have been registered by the sprayers in the system",
            "tooltipPosition" => "top"
        );

        echo json_encode(
            array(
                "output" =>  $this->cView("Widgets/Kpi", $data, "json")
            )
        );
    }

    public function assistedFarmers()
    {
        $data = array(
            "value" => $this->dash->assistedFarmers(),
            "title" => "Number of Assisted Farmers",
            "tooltipTitle" => "This is the total number of farmers that have had their trees sprayed",
            "tooltipPosition" => "top"
        );

        echo json_encode(
            array(
                "output" =>  $this->cView("Widgets/Kpi", $data, "json")
            )
        );
    }


    public function rcnCollected()
    {
        $data = array(
            "value" => number_format(floatval($this->dash->rcnCollected()), 0),
            "title" => "Total RCN Collected (KGs)",
            "tooltipTitle" => "This is the total quantity of cashew collected by the sprayers",
            "tooltipPosition" => "top"
        );

        echo json_encode(
            array(
                "output" =>  $this->cView("Widgets/Kpi", $data, "json")
            )
        );
    }


    public function rcnCollectedPerSprayers()
    {
        $data = array(
            "value" => number_format(floatval($this->dash->rcnCollectedPerSprayers()), 0),
            "title" => "RCN Collected Per Sprayer (KGs)",
            "tooltipTitle" => "This is the average quantity of cashew collected by each sprayer",
            "tooltipPosition" => "top"
        );

        echo json_encode(
            array(
                "output" =>  $this->cView("Widgets/Kpi", $data, "json")
            )
        );
    }


    public function netIncomePerSprayers()
    {
        $data = array(
            "value" => number_format(floatval($this->dash->netIncomePerSprayers()), 0),
            "title" => "Net Income per Sprayer (MZN)",
            "tooltipTitle" => "This is the net income the sprayer makes, calculated by the total monetary costs of the cashew collected minus the chemical, fuel & worker costs",
            "tooltipPosition" => "top"
        );

        echo json_encode(
            array(
                "output" =>  $this->cView("Widgets/Kpi", $data, "json")
            )
        );
    }

    public function numberOfTreesSprayedPerApplication()
    {
        $data = array(
            "trace1" => $this->dash->numberOfTreesSprayedPerApplication(),
            "title" => "Number of Trees Sprayed per Application",
            "id" => "number-of-trees-sprayed-per-application"
        );

        echo json_encode(
            array(
                "output" =>  $this->cView("Widgets/Barchart", $data, "json")
            )
        );
    }

    public function chemicalProvenance()
    {
        $data = array(
            "title" => "Source of Chemicals",
            "id" => "chemical-provenance",
            "source" => $this->dash->chemicalProvenance()
        );

        echo json_encode(
            array(
                "output" =>  $this->cView("Widgets/Stackedbar", $data, "json")
            )
        );
    }
    public function genderDistribution()
    {
        $data = array(
            "labels" => [ "Female", "Male",],
            "values" =>  $this->dash->genderDistribution(),
            "id" => "number-of-farmers-by-gender",
            "title" => "Farmers by Gender"
        );

        echo json_encode(
            array(
                "output" =>  $this->cView("Widgets/Pie", $data, "json")
            )
        );
    }
    public function listAll()
    {
        return $this->dash->getListOfRegisteredSprayers();
    }
}
