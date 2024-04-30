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
class Applications extends Model
{
    private $table = "chemical_application";


    
    public function create()
    {
        $date = date("Y-m-d H:i:s");
        $post = array(  "chemical_application_uid"   => 1,
                        "number_of_trees_sprayed"  => 1,
                        "application_number"       => 1,
                        "sprayed_at"               => $date,
                        "updated_at"               => $date, 
                        "last_sync_at" => $date);
        $this->insert($this->table)->values($post);

        $insertId = $this->insert_id();

         if($insertId){
            $auditData = [
                "description" => htmlspecialchars("Creating a application with Id: {$insertId}"),
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
            "chemical_application_uid" => trim($post["chemical_application_uid"]),
            "number_of_trees_sprayed"  => trim($post["number_of_trees_sprayed"]),
            "application_number"       => trim($post["application_number"]),
            "sprayed_at"               => trim($post["sprayed_at"]),
            "updated_at"               => $date
        );
        $result = $this->update($this->table)->values($post)->where("id = {$id}");

        $this->removeEmptyEntries();    

         if($result){
            $auditData = [
                "description" => htmlspecialchars("Updating a application data with Id: {$id}"),
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
    public function owner($application)
    {
        return $this->select("farmers")->where("farmer_uid='{$application['farmer_uid']}'")->limit(1)->result();
    }

    public function single($id)
    {
        return $this->find($this->table, $id);
    }

    public function show(){
        return $this->select($this->table)->results();
    }

    private function baseSelectApplications($filterCondition)
    {
        return $this->select($this->table . " " . Alias::applications)
            ->columns("

                        SQL_CALC_FOUND_ROWS

                        " . Alias::applications . ".id,
                        CONCAT(" . Alias::farmers . ".first_name, ' '," . Alias::farmers . ".last_name) as farmer,
                        " . Alias::farmers . ".first_name,
                        " . Alias::applications . ".chemical_application_uid,
                        " . Alias::applications . ".number_of_trees_sprayed,
                        " . Alias::applications . ".application_number,
                        " . Alias::applications . ".sprayed_at,
                        " . Alias::farmers . ".province,
                        " . Alias::farmers . ".district,
                        " . Alias::farmers . ".administrative_post,
                        " . Alias::applications . ".last_sync_at

                ")
            ->join("(SELECT farmer_uid, first_name, last_name, province, district, administrative_post FROM farmers) as ".Alias::farmers." ON ".Alias::farmers.".farmer_uid = ".Alias::applications.".farmer_uid")
            ->where("               
                (".DataTable::filter(Alias::applications).")
                {$filterCondition}
            ");
    }

    public function getListOfRegisteredApplications()
    {
        $this->removeEmptyEntries();
        $post = $_POST;
        $columnsFilter = array(
            array('db' => "CONCAT(" . Alias::farmers . ".first_name, ' '," . Alias::farmers . ".last_name)", 'dt' => 0),
            array('db' => Alias::applications . ".chemical_application_uid", 'dt' => 1),
            array('db' => Alias::applications . ".number_of_trees_sprayed", 'dt' => 2),
            array('db' => Alias::applications . ".application_number", 'dt' => 3),
            array('db' => Alias::applications . ".sprayed_at", 'dt' => 4),
            array('db' => Alias::farmers . ".province", 'dt' => 5),
            array('db' => Alias::farmers . ".district", 'dt' => 6),
            // array('db' => Alias::applications . ".administrative_post", 'dt' => 7),
            array('db' => Alias::applications . ".last_sync_at", 'dt' => 7),
        );

        $columnsOrder = array(
            array('db' => "2", 'dt' => 0),
            array('db' => Alias::applications . ".chemical_application_uid", 'dt' => 1),
            array('db' => Alias::applications . ".number_of_trees_sprayed", 'dt' => 2),
            array('db' => Alias::applications . ".application_number", 'dt' => 3),
            array('db' => Alias::applications . ".sprayed_at", 'dt' => 4),
            array('db' => Alias::farmers . ".province", 'dt' => 5),
            array('db' => Alias::farmers . ".district", 'dt' => 6),
            // array('db' => Alias::applications . ".administrative_post", 'dt' => 7),
            array('db' => Alias::applications . ".last_sync_at", 'dt' => 7),
        );

        $filterCondition = DataTable::filterDt($post, $columnsFilter);

        $order = ($post["order"][0]["column"] == -1) ? Alias::applications . ".id DESC" : DataTable::orderDt($post, $columnsOrder);

        $resultset = $this->baseSelectApplications($filterCondition)
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

            $output[] = $value["farmer"];
            $output[] = $value["chemical_application_uid"];
            $output[] = $value["number_of_trees_sprayed"];
            $output[] = $value["application_number"];
            $output[] = substr($value["sprayed_at"], 0, 10);
            $output[] = $value["province"];
            $output[] = $value["district"];
            // $output[] = $value["administrative_post"];
            $output[] = $value["last_sync_at"];
            $output[] = '<a href="' . route("/applications/save/" . $value["id"]) . '" class="btn btn-sm btn-secondary"><i class="fa fa-pencil"></i></a><button  onclick="deleteApplication(this)" type="button" id="' . $value["id"] . '"  data-fullname="' . $value["farmer"]. '"  data-uid="' . $value["chemical_application_uid"]. '" class="btn btn-sm btn-danger btnDeleteApplication"><i class="fa fa-trash"></i></button>';
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


    public function deleteApplication($id){
        $date = date("Y-m-d H:i:s");
        $result = $this->update($this->table)->values(array(
            "deleted_at" => $date,
            "updated_at" => $date
        ))->where("id = {$id}");

        if($result){
            $auditData = [
                "description" => htmlspecialchars("Deleting application {$id}"),
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
