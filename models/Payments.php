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
class Payments extends Model
{
    private $table = "payments";


    
    public function create()
    {
        $date = date("Y-m-d H:i:s");
        $post = array(
            "paid" => 0, 
            "discount" => 0, 
            "description" => "", 
            "payment_type" => "", 
            "payment_uid" => time(),
            "user_uid" =>  $_SESSION["user"]["user_uid"],
            "created_at" => $date, 
            "updated_at" => $date, 
            "last_sync_at" => $date
        );
        $this->insert($this->table)->values($post);

        $insertId = $this->insert_id();

         if($insertId){
            $auditData = [
                "description" => htmlspecialchars("Creating a payment with Id: {$insertId}"),
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
            "paid"           => trim($post["paid"]),
            "discount"       => trim($post["discount"]),
            "description"    => trim($post["description"]),
            "payment_type"   => trim($post["payment_type"]),
            "updated_at"     => $date
        );
        $result = $this->update($this->table)->values($post)->where("id = {$id}");

        $this->removeEmptyEntries();    

         if($result){
            $auditData = [
                "description" => htmlspecialchars("Updating a payment data with Id: {$id}"),
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
        $this->delete($this->table)->where("(paid IS NULL OR paid = '') AND (discount IS NULL OR discount = '') AND (payment_type IS NULL OR payment_type = '')")->run();
    }

    /**
     * Returnes the ownerofthe plantation through is UIID
     * 
     * 
     */
    public function owner($payment)
    {
        return $this->select("farmers")->where("farmer_uid='{$payment['farmer_uid']}'")->limit(1)->result();
    }

    public function single($id)
    {
        return $this->find($this->table, $id);
    }

    public function show(){
        return $this->select($this->table)->results();
    }

    private function baseSelectPayments($filterCondition)
    {
        return $this->select($this->table . " " . Alias::payments)
            ->columns("
                        SQL_CALC_FOUND_ROWS
                        " . Alias::payments . ".id,
                        CONCAT(" . Alias::farmers . ".first_name, ' '," . Alias::farmers . ".last_name) as farmer,
                        " . Alias::payments . ".payment_uid,
                        " . Alias::payments . ".farmer_uid,
                        " . Alias::payments . ".paid,
                        " . Alias::payments . ".discount,
                        " . Alias::payments . ".payment_type,
                        " . Alias::payments . ".last_sync_at,
                        " . Alias::farmers . ".id,
                        " . Alias::farmers . ".province,
                        " . Alias::farmers . ".district,
                        " . Alias::farmers . ".mobile_number,
                        " . Alias::farmers . ".gender,
                        CONCAT_WS(' '," .Alias::sprayers. ".first_name," .Alias::sprayers. ".last_name) as sprayer


                ")
            ->join("(SELECT id, farmer_uid, first_name, last_name, gender, mobile_number, province, district, deleted_at, created_at,updated_at, last_sync_at FROM farmers) as ".Alias::farmers." ON ".Alias::farmers.".farmer_uid = ".Alias::payments.".farmer_uid")
            ->join("users as " . Alias::sprayers . " ON " . Alias::sprayers . ".user_uid = " . Alias::payments . ".user_uid ")

            ->where("               
                (".DataTable::filter(Alias::farmers).")
                {$filterCondition}
            ");
    }

    public function getListOfRegisteredPayments()
    {
        $this->removeEmptyEntries();
        $post = $_POST;
        $columnsFilter = array(
            array('db' => "CONCAT_WS(' '," .Alias::sprayers. ".first_name," .Alias::sprayers. ".last_name)", 'dt' => 0),
            array('db' => "CONCAT(" . Alias::farmers . ".first_name, ' '," . Alias::farmers . ".last_name)", 'dt' => 1),
            array('db' => Alias::farmers . ".mobile_number", 'dt' => 2),
            array('db' => Alias::payments . ".payment_uid", 'dt' => 3),
            array('db' => Alias::payments . ".payment_type", 'dt' => 4),
            array('db' => Alias::payments . ".paid", 'dt' => 5),
            array('db' => Alias::payments . ".discount", 'dt' => 6),
            array('db' => Alias::farmers . ".province", 'dt' => 7),
            array('db' => Alias::farmers . ".district", 'dt' => 8),
            array('db' => Alias::payments . ".last_sync_at", 'dt' => 9),
        );

        $columnsOrder = array(
            array('db' => "13", 'dt' => 0),
            array('db' => "2", 'dt' => 1),
            array('db' => Alias::farmers . ".mobile_number", 'dt' => 2),
            array('db' => Alias::payments . ".payment_uid", 'dt' => 3),
            array('db' => Alias::payments . ".payment_type", 'dt' => 4),
            array('db' => Alias::payments . ".paid", 'dt' => 5),
            array('db' => Alias::payments . ".discount", 'dt' => 6),
            array('db' => Alias::farmers . ".province", 'dt' => 7),
            array('db' => Alias::farmers . ".district", 'dt' => 8),
            array('db' => Alias::payments . ".last_sync_at", 'dt' => 9),
        );

        $filterCondition = DataTable::filterDt($post, $columnsFilter);

        $order = ($post["order"][0]["column"] == -1) ? Alias::payments . ".id DESC" : DataTable::orderDt($post, $columnsOrder);

        $resultset = $this->baseSelectPayments($filterCondition)
            ->order($order)
            ->limit($post["length"])
            ->offset($post["start"])
            ->results();
        $this->query = "SELECT FOUND_ROWS() AS totalRecords";

        $totalRecords = $this->result("totalRecords");

        $data = array();

        foreach ($resultset as $value) :

            $output = array();
            $output[] = $value["sprayer"];
            $output[] = $value["farmer"];
            $output[] = $value["mobile_number"];
            $output[] = $value["payment_uid"];
            $output[] = $value["payment_type"];
            $output[] = $value["paid"];
            $output[] = $value["discount"];
            $output[] = $value["province"];
            $output[] = $value["district"];
            $output[] = $value["last_sync_at"];
            $output[] = '<a href="' . route("/payments/save/" . $value["id"]) . '" class="btn btn-sm btn-secondary"><i class="fa fa-pencil"></i></a><button  onclick="deletePayment(this)" type="button" id="' . $value["id"] . '"  data-fullname="' . $value["farmer"]. '"  data-uid="' . $value["payment_uid"]. '" class="btn btn-sm btn-danger btnDeleteFarmer"><i class="fa fa-trash"></i></button>';
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


    public function deletePayment($id){
        $date = date("Y-m-d H:i:s");
        $result = $this->update($this->table)->values(array(
            "deleted_at" => $date,
            "updated_at" => $date
        ))->where("id = {$id}");

        if($result){
            $auditData = [
                "description" => htmlspecialchars("Deleting payment {$id}"),
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
