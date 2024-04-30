<?php

/**
 * Created by PhpStorm.
 * Campaign: TNS Programmer
 * Date: 2022-02-16
 * Time: 4:39 PM
 */

namespace sprint\models;

use \sprint\database\Model;
use \sprint\helpers\Alias;
use \sprint\helpers\DataTable;
use \sprint\traits\Auditable;

class Campaigns extends Model
{
    use Auditable;

    private $table = "campains";

    public function create()
    {
        $date = date("Y-m-d H:i:s");
        $post = array("opening" => $date, "closing" => $date, "description" => "", "created_at" =>$date, "updated_at" => $date);
        $this->insert($this->table)->values($post);

        $insertId = $this->insert_id();

        if($insertId){
            $auditData = [
                "description" => htmlspecialchars("Creating a campaign with id {$insertId}"),
                "subject_id"  => htmlspecialchars($insertId),
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
            "opening"        => htmlspecialchars(trim($post["opening"])),
            "closing"        => htmlspecialchars(trim($post["closing"])),
            "description"     => htmlspecialchars(trim($post["description"])),
            "deleted_at" => ($post["deleted_at"] ? $date : NULL),
        );
        $result = $this->update($this->table)->values($post)->where("id = {$id}");

        if($result){
            $auditData = [
                "description" => htmlspecialchars("Updating a campaign data with id {$id}"),
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
            "id" => $id,
            "message" => "Campaign was updated sucessfully",

        ));
    }

    public function removeEmptyEntries(){
        $this->delete($this->table)->where("(`opening` IS NULL) AND (`closing` IS NULL ) AND(`description` IS NULL OR `description` = '')")->run();
    }

    public function single($id)
    {
        return $this->find($this->table, $id);
    }

    public function exists($from, $to, $id = null)
    {
        $sql = "(('{$from}' BETWEEN `opening` AND `closing`) OR ('{$to}' BETWEEN `opening` AND `closing`))";
        $sql .= ($id == null ? "" : " AND `id` !={$id}");
        return $this->select($this->table)->where($sql)->num_rows();
    }

    public function all()
    {
        return $this->select($this->table)->where("deleted_at IS NULL")->order("id DESC")->results();
    }

    public function options($name)
    {
        $values = $this->select($this->table)->columns("*")->where("name='{$name}' AND deleted_at IS NULL")->result()["value"] ?? [];
        return array_map(function($each) { return trim(ucfirst($each)); }, explode(",", $values));
    }

    public function show(){
        return $this->select($this->table)->results();
    }

    private function baseSelectCampaigns($filterCondition)
    {
        return $this->select($this->table . " " . Alias::campaigns)
            ->columns("
                        SQL_CALC_FOUND_ROWS
                        " . Alias::campaigns . ".id,
                        " . Alias::campaigns . ".opening,
                        " . Alias::campaigns . ".closing,
                        " . Alias::campaigns . ".description,
                        " . Alias::campaigns . ".updated_at,
                        " . Alias::campaigns . ".closed_date,
                        IF(`deleted_at` IS NULL , 'Active', 'Inactive') AS status

                ")
            ->where("(id>0) {$filterCondition}

            ");
    }

    public function getListOfRegisteredCampaigns()
    {
        $this->removeEmptyEntries();
        $post = $_POST;

        $columnsFilter = array(
            array('db' => Alias::campaigns . ".opening", 'dt' => 0),
            array('db' => Alias::campaigns . ".closing", 'dt' => 1),
            array('db' => Alias::campaigns . ".description", 'dt' => 2),
            // array('db' => "IF(`deleted_at` IS NULL , 'Active', 'Inactive')", 'dt' => 3),
        );

        $columnsOrder = array(
            array('db' => Alias::campaigns . ".opening", 'dt' => 0),
            array('db' => Alias::campaigns . ".closing", 'dt' => 1),
            array('db' => Alias::campaigns . ".description", 'dt' => 2),
            // array('db' => "6", 'dt' => 3),
        );

        $filterCondition = DataTable::filterDt($post, $columnsFilter);

        $order = ($post["order"][0]["column"] == -1) ? Alias::campaigns . ".id DESC" : DataTable::orderDt($post, $columnsOrder);

        $resultset = $this->baseSelectCampaigns($filterCondition)
            ->order($order)
            ->limit($post["length"])
            ->offset($post["start"])
            ->results();

        $this->query = "SELECT FOUND_ROWS() AS totalRecords";

        $totalRecords = $this->result("totalRecords");

        $data = array();

        foreach ($resultset as $value) :

            $output = array();

            $output[] = $value["description"];
            $output[] = $value["opening"];
            $output[] = $value["closing"];
            // $output[] = $value["status"];
            $output[] = $value["closed_date"] == null ? '<span class="text-success">Opened</span>' : '<span class="text-danger">Closed</span>' ;
            $output[] = '<a href="' . route("/campaigns/save/" . $value["id"]) . '" class="btn btn-sm btn-secondary"><i class="fa fa-pencil"></i></a>';

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
