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
class Equipments extends Model
{
    private $table = "equipments";


    
    public function create()
    {
        $date = date("Y-m-d H:i:s");
        $post = array(  "name"         => 1,
                        "brand"        => 1,
                        "model"        => 1,
                        "status"       => $date,
                        "updated_at"   => $date, 
                        "last_sync_at" => $date);
        $this->insert($this->table)->values($post);

        $insertId = $this->insert_id();

         if($insertId){
            $auditData = [
                "description" => htmlspecialchars("Creating a equipments with Id: {$insertId}"),
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
            "name"          => trim($post["name"]),
            "brand"       => trim($post["brand"]),
            "status"      => trim($post["status"]),
            "model"       => trim($post["model"]),
            "updated_at"  => $date
        );
        $result = $this->update($this->table)->values($post)->where("id = {$id}");

        $this->removeEmptyEntries();    

         if($result){
            $auditData = [
                "description" => htmlspecialchars("Updating a equipments data with Id: {$id}"),
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
        // $this->delete($this->table)->where("name IS NULL OR name = ''")->run();
    }

    /**
     * Returnes the ownerofthe plantation through is UIID
     * 
     * 
     */
    public function owner($equipmentss)
    {
        return $this->select("users u")->where("user_uid='{$equipmentss['user_uid']}'")->limit(1)->result();
    }

    public function single($id)
    {
        return $this->find($this->table, $id);
    }

    public function show(){
        return $this->select($this->table)->results();
    }

    private function baseSelectEquipments($filterCondition)
    {
        return $this->select($this->table . " " . Alias::equipments)
            ->columns("

                        SQL_CALC_FOUND_ROWS

                        " . Alias::equipments . ".id,
                        CONCAT(" . Alias::sprayers . ".first_name, ' '," . Alias::sprayers . ".last_name) as sprayer,
                        " . Alias::sprayers . ".first_name,
                        " . Alias::equipments . ".equipments_uid,
                        " . Alias::equipments . ".name,
                        " . Alias::equipments . ".model,
                        " . Alias::equipments . ".brand,
                        " . Alias::equipments . ".status,
                        " . Alias::equipments . ".last_sync_at

                ")
            ->join("(SELECT user_uid, first_name, last_name FROM users) as ".Alias::sprayers." ON ".Alias::sprayers.".user_uid = ".Alias::equipments.".user_uid")
            ->where("               
                (".DataTable::filter(Alias::equipments).")
                {$filterCondition}
            ");
    }

    public function getListOfRegisteredEquipments()
    {
        $this->removeEmptyEntries();
        $post = $_POST;
        $columnsFilter = array(
            array('db' => "CONCAT(" . Alias::sprayers . ".first_name, ' '," . Alias::sprayers . ".last_name)", 'dt' => 0),
            array('db' => Alias::equipments . ".equipments_uid", 'dt' => 1),
            array('db' => Alias::equipments . ".name", 'dt' => 2),
            array('db' => Alias::equipments . ".model", 'dt' => 3),
            array('db' => Alias::equipments . ".brand", 'dt' => 4),
            array('db' => Alias::equipments . ".status", 'dt' => 5),
            array('db' => Alias::equipments . ".last_sync_at", 'dt' => 6),
        );

        $columnsOrder = array(
            array('db' => "2", 'dt' => 0),
            array('db' => Alias::equipments . ".equipments_uid", 'dt' => 1),
            array('db' => Alias::equipments . ".name", 'dt' => 2),
            array('db' => Alias::equipments . ".model", 'dt' => 3),
            array('db' => Alias::equipments . ".brand", 'dt' => 4),
            array('db' => Alias::equipments . ".status", 'dt' => 5),
            array('db' => Alias::equipments . ".last_sync_at", 'dt' => 6),
        );

        $filterCondition = DataTable::filterDt($post, $columnsFilter);

        $order = ($post["order"][0]["column"] == -1) ? Alias::equipments . ".id DESC" : DataTable::orderDt($post, $columnsOrder);

        $resultset = $this->baseSelectEquipments($filterCondition)
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
            $output[] = $value["equipments_uid"];
            $output[] = $value["name"];
            $output[] = $value["model"];
            $output[] = $value["brand"];
            $output[] = $value["status"];
            $output[] = $value["last_sync_at"];
            $output[] = '<a href="' . route("/equipments/save/" . $value["id"]) . '" class="btn btn-sm btn-secondary"><i class="fa fa-pencil"></i></a><button  onclick="deleteEquipments(this)" type="button" id="' . $value["id"] . '"  data-fullname="' . $value["sprayer"]. '"  data-uid="' . $value["chemical_equipments_uid"]. '" class="btn btn-sm btn-danger btnDeleteEquipments"><i class="fa fa-trash"></i></button>';
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


    public function deleteEquipments($id){
        $date = date("Y-m-d H:i:s");
        $result = $this->update($this->table)->values(array(
            "deleted_at" => $date,
            "updated_at" => $date
        ))->where("id = {$id}");

        if($result){
            $auditData = [
                "description" => htmlspecialchars("Deleting equipments {$id}"),
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
