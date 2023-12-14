<?php

/**
 * Created by PhpStorm.
 * User: TNS Programmer
 * Date: 2022-02-16
 * Time: 4:39 PM
 */

namespace sprint\models;

use \sprint\database\Model;
use \sprint\helpers\Alias;
use \sprint\helpers\DataTable;
use \sprint\models\Dashboards;
class Agreements extends Model
{
    private $table = "payments_aggreement";

    public function create()
    {
        $date = date("Y-m-d H:i:s");
        $post = array(
            "aggreed_payment" => "", 
            "aggreed_trees_to_spray" => 0, 
            "number_of_applications" => 0, 
            "payment_type" => "", 
            "payment_aggreement_uid" => time(),
            "user_uid" =>  $_SESSION["user"]["user_uid"], 
            "created_at" => $date, 
            "updated_at" => $date, 
            "last_sync_at" => $date
        );
        $this->insert($this->table)->values($post);

        $insertId = $this->insert_id();

         if($insertId){
            $auditData = [
                "number_of_applications" => htmlspecialchars("Creating a agreement with Id: {$insertId}"),
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
            "aggreed_payment"           => trim($post["aggreed_payment"]),
            "aggreed_trees_to_spray"       => trim($post["aggreed_trees_to_spray"]),
            "number_of_applications"    => trim($post["number_of_applications"]),
            "payment_type"   => trim($post["payment_type"]),
            "updated_at"     => $date
        );
        $result = $this->update($this->table)->values($post)->where("id = {$id}");

        $this->removeEmptyEntries();    

         if($result){
            $auditData = [
                "number_of_applications" => htmlspecialchars("Updating a agreement data with Id: {$id}"),
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
        $this->delete($this->table)->where("(aggreed_payment IS NULL OR aggreed_payment = '') AND (aggreed_trees_to_spray IS NULL OR aggreed_trees_to_spray = 0) AND (payment_type IS NULL OR payment_type = '')")->run();
    }

    /**
     * Returnes the ownerofthe plantation through is UIID
     * 
     * 
     */
    public function owner($agreement)
    {
        return $this->select("farmers")->where("farmer_uid='{$agreement['farmer_uid']}'")->limit(1)->result();
    }

    public function single($id)
    {
        return $this->find($this->table, $id);
    }

    public function show(){
        return $this->select($this->table)->results();
    }

    private function baseSelectAgreements($filterCondition)
    {
        return $this->select($this->table . " " . Alias::agreements)
            ->columns("
                        SQL_CALC_FOUND_ROWS
                        " . Alias::agreements . ".id as agreeId,
                        CONCAT(" . Alias::farmers . ".first_name, ' '," . Alias::farmers . ".last_name) as farmer,
                        " . Alias::agreements . ".payment_aggreement_uid,
                        " . Alias::agreements . ".farmer_uid,
                        " . Alias::agreements . ".aggreed_payment,
                        " . Alias::agreements . ".aggreed_trees_to_spray,
                        " . Alias::agreements . ".payment_type,
                        " . Alias::agreements . ".last_sync_at,
                        " . Alias::agreements . ".number_of_applications,
                        " . Alias::farmers . ".id,
                        " . Alias::farmers . ".province,
                        " . Alias::farmers . ".district,
                        " . Alias::farmers . ".mobile_number,
                        " . Alias::farmers . ".gender

                ")
            ->join("(SELECT id, farmer_uid, first_name, last_name, gender, mobile_number, province, district, deleted_at, created_at,updated_at, last_sync_at FROM farmers) as ".Alias::farmers." ON ".Alias::farmers.".farmer_uid = ".Alias::agreements.".farmer_uid")
            ->where("               
                (".DataTable::filter(Alias::farmers).")
                {$filterCondition}
            ");
    }

    public function getListOfRegisteredAgreements()
    {
        $this->removeEmptyEntries();
        $post = $_POST;
        $columnsFilter = array(
            array('db' => "CONCAT(" . Alias::farmers . ".first_name, ' '," . Alias::farmers . ".last_name)", 'dt' => 0),
            array('db' => Alias::farmers . ".mobile_number", 'dt' => 1),
            array('db' => Alias::agreements . ".payment_aggreement_uid", 'dt' => 2),
            array('db' => Alias::agreements . ".payment_type", 'dt' => 3),
            array('db' => Alias::agreements . ".aggreed_payment", 'dt' => 4),
            array('db' => Alias::agreements . ".aggreed_trees_to_spray", 'dt' => 5),
            array('db' => Alias::agreements . ".number_of_applications", 'dt' => 6),
            array('db' => Alias::farmers . ".province", 'dt' => 7),
            array('db' => Alias::farmers . ".district", 'dt' => 8),
            array('db' => Alias::agreements . ".last_sync_at", 'dt' => 9),
        );

        $columnsOrder = array(
             array('db' => "2", 'dt' => 0),
            array('db' => Alias::farmers . ".mobile_number", 'dt' => 1),
            array('db' => Alias::agreements . ".payment_aggreement_uid", 'dt' => 2),
            array('db' => Alias::agreements . ".payment_type", 'dt' => 3),
            array('db' => Alias::agreements . ".aggreed_payment", 'dt' => 4),
            array('db' => Alias::agreements . ".aggreed_trees_to_spray", 'dt' => 5),
            array('db' => Alias::agreements . ".number_of_applications", 'dt' => 6),
            array('db' => Alias::farmers . ".province", 'dt' => 7),
            array('db' => Alias::farmers . ".district", 'dt' => 8),
            array('db' => Alias::agreements . ".last_sync_at", 'dt' => 9),
        );

        $filterCondition = DataTable::filterDt($post, $columnsFilter);

        $order = ($post["order"][0]["column"] == -1) ? Alias::agreements . ".id DESC" : DataTable::orderDt($post, $columnsOrder);

        $resultset = $this->baseSelectAgreements($filterCondition)
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
            $output[] = $value["mobile_number"];
            $output[] = $value["payment_aggreement_uid"];
            $output[] = $value["payment_type"];
            $output[] = $value["aggreed_payment"];
            $output[] = $value["aggreed_trees_to_spray"];
            $output[] = $value["number_of_applications"];
            $output[] = $value["province"];
            $output[] = $value["district"];
            $output[] = $value["last_sync_at"];
            $output[] = '<a href="' . route("/agreements/save/" . $value["agreeId"]) . '" class="btn btn-sm btn-secondary"><i class="fa fa-pencil"></i></a><button  onclick="deleteAgreement(this)" type="button" id="' . $value["agreeId"] . '"  data-fullname="' . $value["farmer"]. '"  data-uid="' . $value["payment_aggreement_uid"]. '" class="btn btn-sm btn-danger btnDeleteFarmer"><i class="fa fa-trash"></i></button>';
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


    public function deleteAgreement($id){
        $date = date("Y-m-d H:i:s");
        $result = $this->update($this->table)->values(array(
            "deleted_at" => $date,
            "updated_at" => $date
        ))->where("id = {$id}");

        if($result){
            $auditData = [
                "number_of_applications" => htmlspecialchars("Deleting agreement {$id}"),
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
