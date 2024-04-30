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
use \sprint\traits\Auditable;

class Profiles extends Model
{
    use Auditable;

    private $table = "profiles";

    public function create()
    {
        $date = date("Y-m-d H:i:s");
        $post = array("name" => null);
        $this->insert($this->table)->values($post);

        $insertId = $this->insert_id();

        if($insertId){
            $auditData = [
                "description" => htmlspecialchars("Creating a profile with id {$insertId}"),
                "subject_id"  => htmlspecialchars($insertId),
                "subject_type"  => htmlspecialchars($this->table),
                "properties"  => json_encode($post),
                "platform"  => htmlspecialchars("web"),
            ];
            $auditResult = (new AuditLogs())->audit($auditData);
        }

        $this->audit("create", $this);
        
        echo json_encode(array(
            "status" => $insertId > 0 ? "success" : "failed",
            "id" => $insertId
        ));
    }

    public function save($id)
    {
        $post = $_POST;
        $date = date("Y-m-d H:i:s");
        $delete = ($post["deleted_at"] == 0 ? $date : NULL);
        $post = array(
            "name"          => trim($post["name"]),
            "visibility"    => $post["visibility"],
            "web_access"    => $post["web_access"],
            "mobile_access" => $post["mobile_access"],
            "deleted_at"    => $delete,
        );
        $result = $this->update($this->table)->values($post)->where("id = {$id}");
        $resultUsers = $this->update("users")->values(["deleted_at" => $delete])->where("id > 0 AND profile_id={$id}");
       
        if($result){
            $auditData = [
                "description" => htmlspecialchars("Updating a profile data with id {$id}"),
                "subject_id"  => htmlspecialchars($id),
                "subject_type"  => htmlspecialchars($this->table),
                "properties"  => json_encode($post),
                "platform"  => htmlspecialchars("web"),
            ];
            $auditResult = (new AuditLogs())->audit($auditData);
        }

        $this->removeEmptyEntries();

        echo json_encode(array(
            "status" => "success",
            "message" => "Profile data updated successfully!",
            "id" => $id
        ));
    }

    public function removeEmptyEntries(){
        $this->delete($this->table)->where("name IS NULL OR name = ''")->run();
    }

    public function single($id)
    {
        return $this->find($this->table, $id);
    }

    public function findByName($profileName)
    {
        return $this->select($this->table)->where("name='{$profileName}'")->result();
    }

    public function show(){
        return $this->select($this->table)->where("deleted_at is null")->results();
    }

    private function baseSelectUsers($filterCondition)
    {
        return $this->select($this->table . " " . Alias::profiles)
            ->columns("

                        SQL_CALC_FOUND_ROWS

                        " . Alias::profiles . ".id,
                        " . Alias::profiles . ".name,
                        " . Alias::profiles . ".visibility,
                        " . Alias::profiles . ".web_access,
                        " . Alias::profiles . ".mobile_access,
                        IF(`deleted_at` IS NULL , 'Active', 'Inactive') AS status

                ")
            ->where("
                (id > 0)
                {$filterCondition}

            ");
    }

    public function getListOfRegisteredProfiles()
    {
        $this->removeEmptyEntries();
        $post = $_POST;

        $columnsFilter = array(
            array('db' => Alias::profiles . ".name", 'dt' => 0),
            array('db' => Alias::profiles . ".visibility", 'dt' => 1),
            array('db' => "IF(`deleted_at` IS NULL , 'Active', 'Inactive')", 'dt' => 4),

        );

        $columnsOrder = array(
            array('db' => Alias::profiles . ".name", 'dt' => 0),
            array('db' => Alias::profiles . ".visibility", 'dt' => 1),
            array('db' => "6", 'dt' => 4),
        );

        $filterCondition = DataTable::filterDt($post, $columnsFilter);

        $order = ($post["order"][0]["column"] == 0) ? Alias::profiles . ".id DESC" : DataTable::orderDt($post, $columnsOrder);

        $resultset = $this->baseSelectUsers($filterCondition)
            ->order($order)
            ->limit($post["length"])
            ->offset($post["start"])
            ->results();

        $this->query = "SELECT FOUND_ROWS() AS totalRecords";

        $totalRecords = $this->result("totalRecords");

        $data = array();

        foreach ($resultset as $value) :

            $output = array();

            $output[] = $value["name"];
            $output[] = $value["visibility"];
            $output[] = $value["web_access"] == 1 ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>';
            $output[] = $value["mobile_access"] == 1 ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>';
            $output[] = $value["status"];
            $output[] = '<a href="' . route("/profiles/save/" . $value["id"]) . '" class="btn btn-sm btn-secondary"><i class="fa fa-pencil"></i></a>';

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
}
