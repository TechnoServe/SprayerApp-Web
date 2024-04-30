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
class Acquisitions extends Model
{
    private $table = "chemical_acquisition";


    
    public function create()
    {
        $date = date("Y-m-d H:i:s");
        $post = array(  "chemical_acquisition_mode" => "",
                        "user_uid" =>  $_SESSION["user"]["user_uid"], 
                        "chemical_acquisition_uid"  => 1,
                        "chemical_name"             => "",
                        "chemical_quantity"         => 0,
                        "chemical_price"            => 0,
                        "updated_at"                => $date, 
                        "last_sync_at"              => $date);
        $this->insert($this->table)->values($post);

        $insertId = $this->insert_id();

         if($insertId){
            $auditData = [
                "description" => htmlspecialchars("Creating a acquisitions with Id: {$insertId}"),
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
            "chemical_acquisition_uid"  => trim($post["chemical_acquisition_uid"]),
            "user_uid"                  => trim($post["user_uid"]),
            "chemical_acquisition_mode" => trim($post["chemical_acquisition_mode"]),
            "chemical_supplier"         => trim($post["chemical_supplier"]),
            "chemical_name"             => trim($post["chemical_name"]),
            "chemical_quantity"         => trim($post["chemical_quantity"]),
            "chemical_price"            => trim($post["chemical_price"]),
            "acquired_at"               => trim($post["acquired_at"]),
            "updated_at"                => $date
        );
        $result = $this->update($this->table)->values($post)->where("id = {$id}");

        $this->removeEmptyEntries();    

         if($result){
            $auditData = [
                "description" => htmlspecialchars("Updating a acquisitions data with Id: {$id}"),
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
        $this->delete($this->table)->where("chemical_acquisition_mode IS NULL OR chemical_acquisition_mode = '' AND (chemical_name IS NULL OR chemical_name = '')  AND (chemical_quantity IS NULL OR chemical_quantity = 0) AND (chemical_price IS NULL OR chemical_price = 0)")->run();
    }

    /**
     * Returnes the ownerofthe plantation through is UIID
     * 
     * 
     */
    public function owner($acquisitions)
    {
        return $this->select("users u")->where("user_uid='{$acquisitions['user_uid']}'")->limit(1)->result();
    }

    public function single($id)
    {
        return $this->find($this->table, $id);
    }

    public function show(){
        return $this->select($this->table)->results();
    }

    private function baseSelectAcquisitions($filterCondition)
    {
        return $this->select($this->table . " " . Alias::acquisitions)
            ->columns("

                        SQL_CALC_FOUND_ROWS

                        " . Alias::acquisitions . ".id,
                        CONCAT(" . Alias::sprayers . ".first_name, ' '," . Alias::sprayers . ".last_name) as sprayer,
                        " . Alias::sprayers . ".first_name,
                        " . Alias::acquisitions . ".chemical_acquisition_uid,
                        " . Alias::acquisitions . ".chemical_acquisition_mode,
                        " . Alias::acquisitions . ".chemical_supplier,
                        " . Alias::acquisitions . ".chemical_name,
                        " . Alias::acquisitions . ".chemical_quantity,
                        " . Alias::acquisitions . ".chemical_price,
                        " . Alias::acquisitions . ".acquired_at,
                        " . Alias::acquisitions . ".last_sync_at

                ")
            ->join("(SELECT user_uid, first_name, last_name FROM users) as ".Alias::sprayers." ON ".Alias::sprayers.".user_uid = ".Alias::acquisitions.".user_uid")
            ->where("               
                (".DataTable::filter(Alias::acquisitions).")
                {$filterCondition}
            ");
    }

    public function getListOfRegisteredAcquisitions()
    {
        $this->removeEmptyEntries();
        $post = $_POST;
        $columnsFilter = array(
            array('db' => "CONCAT(" . Alias::sprayers . ".first_name, ' '," . Alias::sprayers . ".last_name)", 'dt' => 0),
            array('db' => Alias::acquisitions . ".chemical_acquisition_uid", 'dt' => 1),
            array('db' => Alias::acquisitions . ".chemical_acquisition_mode", 'dt' => 2),
            array('db' => Alias::acquisitions . ".chemical_supplier", 'dt' => 3),
            array('db' => Alias::acquisitions . ".chemical_name", 'dt' => 4),
            array('db' => Alias::acquisitions . ".chemical_quantity", 'dt' => 5),
            array('db' => Alias::acquisitions . ".chemical_price", 'dt' => 6),
            array('db' => Alias::acquisitions . ".acquired_at", 'dt' => 7),
            array('db' => Alias::acquisitions . ".last_sync_at", 'dt' => 8),
        );

        $columnsOrder = array(
            array('db' => "2", 'dt' => 0),
            array('db' => Alias::acquisitions . ".chemical_acquisition_uid", 'dt' => 1),
            array('db' => Alias::acquisitions . ".chemical_acquisition_mode", 'dt' => 2),
            array('db' => Alias::acquisitions . ".chemical_supplier", 'dt' => 3),
            array('db' => Alias::acquisitions . ".chemical_name", 'dt' => 4),
            array('db' => Alias::acquisitions . ".chemical_quantity", 'dt' => 5),
            array('db' => Alias::acquisitions . ".chemical_price", 'dt' => 6),
            array('db' => Alias::acquisitions . ".acquired_at", 'dt' => 7),
            array('db' => Alias::acquisitions . ".last_sync_at", 'dt' => 8),
        );

        $filterCondition = DataTable::filterDt($post, $columnsFilter);

        $order = ($post["order"][0]["column"] == -1) ? Alias::acquisitions . ".id DESC" : DataTable::orderDt($post, $columnsOrder);

        $resultset = $this->baseSelectAcquisitions($filterCondition)
            ->order($order)
            ->limit($post["length"])
            ->offset($post["start"])
            // ->write(true); var_dump($resultset); die();
            ->results();
        $this->query = "SELECT FOUND_ROWS() AS totalRecords";

        $totalRecords = $this->result("totalRecords");

        $data = array();

        foreach ($resultset as $value) :

            $output = array();

            $output[] = $value["sprayer"];
            $output[] = $value["chemical_acquisition_uid"];
            $output[] = $value["chemical_acquisition_mode"];
            $output[] = $value["chemical_supplier"];
            $output[] = $value["chemical_name"];
            $output[] = $value["chemical_quantity"];
            $output[] = $value["chemical_price"];
            $output[] = $value["acquired_at"];
            $output[] = $value["last_sync_at"];
            $output[] = '<a href="' . route("/acquisitions/save/" . $value["id"]) . '" class="btn btn-sm btn-secondary"><i class="fa fa-pencil"></i></a><button  onclick="deleteAcquisitions(this)" type="button" id="' . $value["id"] . '"  data-fullname="' . $value["sprayer"]. '"  data-uid="' . $value["chemical_acquisitions_uid"]. '" class="btn btn-sm btn-danger btnDeleteAcquisitions"><i class="fa fa-trash"></i></button>';
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


    public function deleteAcquisitions($id){
        $date = date("Y-m-d H:i:s");
        $result = $this->update($this->table)->values(array(
            "deleted_at" => $date,
            "updated_at" => $date
        ))->where("id = {$id}");

        if($result){
            $auditData = [
                "description" => htmlspecialchars("Deleting acquisitions {$id}"),
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
