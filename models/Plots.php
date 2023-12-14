<?php

namespace sprint\models;

use \sprint\database\Model;
use \sprint\helpers\Alias;
use \sprint\helpers\DataTable;
use \sprint\models\Dashboards;
class Plots extends Model
{
    private $table = "plots";


    
    public function create()
    {
        $date = date("Y-m-d H:i:s");
        $post = array("first_name" => null, "plot_uid" => 1, 
                        "user_uid" =>  $_SESSION["user"]["user_uid"], "created_at" => $date, "updated_at" => $date, "last_sync_at" => $date);
        $this->insert($this->table)->values($post);

        $insertId = $this->insert_id();

         if($insertId){
            $auditData = [
                "description" => htmlspecialchars("Creating a plot with Id: {$insertId}"),
                "subject_id"  => htmlspecialchars($id),
                "subject_type"  => htmlspecialchars($this->table),
                "properties"  => json_encode($post),
                "platform"  => htmlspecialchars("web"),
            ];
            $auditResult = (new AuditLogs())->audit($auditData);
        }
        echo json_encode(array(
            "status" => $insertId > 0 ? "success" : "failed",
            "id" => $insertId
        ));
    }

    public function save($id, $post)
    {   
        $date = date("Y-m-d H:i:s");
        $post = array(
            "name"                  => trim($post["name"]),
            "number_of_trees"       => trim($post["number_of_trees"]),
            "province"              => trim($post["province"]),
            "district"              => trim($post["district"]),
            "administrative_post"   => trim($post["administrative_post"]),
            "updated_at"            => $date
        );
        $result = $this->update($this->table)->values($post)->where("id = {$id}");

        $this->removeEmptyEntries();    

         if($result){
            $auditData = [
                "description" => htmlspecialchars("Updating a plot data with Id: {$id}"),
                "subject_id"  => htmlspecialchars($id),
                "subject_type"  => htmlspecialchars($this->table),
                "properties"  => json_encode($post),
                "platform"  => htmlspecialchars("web"),
            ];
            $auditResult = (new AuditLogs())->audit($auditData);
        }
        echo json_encode(array(
            "status" => "success",
            "id" => $id
        ));
    }

    public function removeEmptyEntries(){
        $this->delete($this->table)->where("name IS NULL OR name = ''")->run();
    }

    /**
     * Returnes the ownerofthe plantation through is UIID
     * 
     * 
     */
    public function owner($plot)
    {
        return $this->select("farmers")->where("farmer_uid='{$plot['farmer_uid']}'")->limit(1)->result();
    }

    public function single($id)
    {
        return $this->find($this->table, $id);
    }

    public function show(){
        return $this->select($this->table)->results();
    }

    public function plotsMapData()
    {
        return $this->select($this->table . " " . Alias::plots)
            ->columns("
                    " . Alias::plots . ".administrative_post,
                    " . Alias::locations . ".latitude,
                    " . Alias::locations . ".longitude
                ")
            ->join("(SELECT post, latitude,longitude FROM locations) AS ".Alias::locations." ON ".Alias::locations.".post = ".Alias::plots.".administrative_post")
            ->where("(".DataTable::filter(Alias::plots).")")
            // ->group(Alias::plots.".administrative_post")
            // ->write(true);
            ->results();
    }

    private function baseSelectPlots($filterCondition)
    {
        return $this->select($this->table . " " . Alias::plots)
            ->columns("

                        SQL_CALC_FOUND_ROWS
                        " . Alias::campaigns . ".description,
                        " . Alias::plots . ".id,
                        CONCAT(" . Alias::farmers . ".first_name, ' '," . Alias::farmers . ".last_name) as farmer,
                        " . Alias::plots . ".name,
                        " . Alias::plots . ".plot_uid,
                        " . Alias::farmers . ".province,
                        " . Alias::farmers . ".district,
                        " . Alias::plots . ".administrative_post,
                        " . Alias::farmers . ".mobile_number,
                        " . Alias::plots . ".last_sync_at,
                        " . Alias::farmers . ".gender

                ")
            ->join("(SELECT farmer_uid, first_name, last_name, gender, mobile_number, province, district FROM farmers) as ".Alias::farmers." ON ".Alias::farmers.".farmer_uid = ".Alias::plots.".farmer_uid")
            ->left("campaigns as " . Alias::campaigns . " ON DATE(" . Alias::plots . ".created_at) BETWEEN " . Alias::campaigns . ".opening AND  " . Alias::campaigns . ".closing")

            ->where("               
                (".DataTable::filter(Alias::plots).")
                {$filterCondition}
            ");
    }

    public function getListOfRegisteredPlots()
    {
        $this->removeEmptyEntries();
        $post = $_POST;
        $columnsFilter = array(
            array('db' => "CONCAT(" . Alias::farmers . ".first_name, ' '," . Alias::farmers . ".last_name)", 'dt' => 0),
            array('db' => Alias::plots . ".plot_uid", 'dt' => 1),
            array('db' => Alias::plots . ".name", 'dt' => 2),
            array('db' => Alias::farmers . ".province", 'dt' => 3),
            array('db' => Alias::farmers . ".district", 'dt' => 4),
            array('db' => Alias::plots . ".administrative_post", 'dt' => 5),
            array('db' => Alias::farmers . ".mobile_number", 'dt' => 6),
            // array('db' => Alias::farmers . ".gender", 'dt' => 7),
            array('db' => Alias::plots . ".last_sync_at", 'dt' => 7),
            array('db' => Alias::campaigns . ".description", 'dt' => 8),
        );

        $columnsOrder = array(
            array('db' => "2", 'dt' => 0),
            array('db' => Alias::plots . ".plot_uid", 'dt' => 1),
            array('db' => Alias::plots . ".name", 'dt' => 2),
            array('db' => Alias::farmers . ".province", 'dt' => 3),
            array('db' => Alias::farmers . ".district", 'dt' => 4),
            array('db' => Alias::plots . ".administrative_post", 'dt' => 5),
            array('db' => Alias::farmers . ".mobile_number", 'dt' => 6),
            // array('db' => Alias::farmers . ".gender", 'dt' => 7),
            array('db' => Alias::plots . ".last_sync_at", 'dt' => 7),
            array('db' => Alias::campaigns . ".description", 'dt' => 8),
        );

        $filterCondition = DataTable::filterDt($post, $columnsFilter);

        $order = ($post["order"][0]["column"] == -1) ? Alias::plots . ".id DESC" : DataTable::orderDt($post, $columnsOrder);

        $resultset = $this->baseSelectPlots($filterCondition)
            ->order($order)
            ->limit($post["length"])
            ->offset($post["start"])
            ->results();
        $this->query = "SELECT FOUND_ROWS() AS totalRecords";

        $totalRecords = $this->result("totalRecords");

        $data = array();

        foreach ($resultset as $value) :

            $output = array();

            $output[] = $value["farmer"];
            $output[] = $value["plot_uid"];
            $output[] = $value["name"];
            $output[] = $value["province"];
            $output[] = $value["district"];
            $output[] = $value["administrative_post"];
            $output[] = $value["mobile_number"];
            // $output[] = $value["gender"];
            $output[] = $value["last_sync_at"];
            $output[] = $value["description"];
            $output[] = '<a href="' . route("/plots/save/" . $value["id"]) . '" class="btn btn-sm btn-secondary"><i class="fa fa-pencil"></i></a><button  onclick="deletePlot(this)" type="button" id="' . $value["id"] . '"  data-fullname="' . $value["farmer"]. '"  data-uid="' . $value["plot_uid"]. '" class="btn btn-sm btn-danger btnDeleteFarmer"><i class="fa fa-trash"></i></button>';
            $data[] = $output;

        endforeach;

        echo json_encode(
            array(
                "draw" => isset($post['draw']) ? intval($post["draw"]) : 0,
                "recordsTotal" => count($resultset),
                "recordsFiltered" => $totalRecords,
                "data" => $data,
            )
        );
    }


    public function deletePlot($id){
        $date = date("Y-m-d H:i:s");
        $result = $this->update($this->table)->values(array(
            "deleted_at" => $date,
            "updated_at" => $date
        ))->where("id = {$id}");

        if($result){
            $auditData = [
                "description" => htmlspecialchars("Deleting plot {$id}"),
                "subject_id"  => htmlspecialchars($id),
                "subject_type"  => htmlspecialchars($this->table),
                "properties"  => json_encode($post),
                "platform"  => htmlspecialchars("web"),
            ];
            $auditResult = (new AuditLogs())->audit($auditData);
        }

        return $result;
    }
}
