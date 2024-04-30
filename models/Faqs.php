<?php

/**
 * Created by PhpStorm.
 * Faq: TNS Programmer
 * Date: 2022-02-16
 * Time: 4:39 PM
 */

namespace sprint\models;

use \sprint\database\Model;
use \sprint\helpers\Alias;
use \sprint\helpers\DataTable;
use \sprint\traits\Auditable;

class Faqs extends Model
{
    use Auditable;

    private $table = "faqs";

    public function create()
    {
        $date = date("Y-m-d H:i:s");
        $post = array("title" => "", "description" => "", "created_at" =>$date, "updated_at" => $date);
        $this->insert($this->table)->values($post);

        $insertId = $this->insert_id();

        if($insertId){
            $auditData = [
                "description" => htmlspecialchars("Creating a faq with id {$insertId}"),
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
            "title"        => htmlspecialchars(trim($post["title"])),
            "description"     => htmlspecialchars(trim($post["description"])),
            "deleted_at" => ($post["deleted_at"] ? $date : NULL),
        );
        $result = $this->update($this->table)->values($post)->where("id = {$id}");

        if($result){
            $auditData = [
                "description" => htmlspecialchars("Updating a faq data with id {$id}"),
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
            "message" => "Faq was updated successfully",

        ));
    }

    public function removeEmptyEntries(){
        $this->delete($this->table)->where("(`title` IS NULL OR `title` = '') AND (`description` IS NULL OR `description` = '')")->run();
    }

    public function single($id)
    {
        return $this->find($this->table, $id);
    }

    public function options($name)
    {
        $values = $this->select($this->table)->columns("*")->where("name='{$name}' AND deleted_at IS NULL")->result()["value"] ?? [];
        return array_map(function($each) { return trim(ucfirst($each)); }, explode(",", $values));
    }

    public function show(){
        return $this->select($this->table)->results();
    }

    private function baseSelectFaqs($filterCondition)
    {
        return $this->select($this->table . " " . Alias::faqs)
            ->columns("
                        SQL_CALC_FOUND_ROWS
                        " . Alias::faqs . ".id,
                        " . Alias::faqs . ".title,
                        " . Alias::faqs . ".description,
                        " . Alias::faqs . ".updated_at,
                        IF(`deleted_at` IS NULL , 'Active', 'Inactive') AS status

                ")
            ->where("(id>0) {$filterCondition}

            ");
    }

    public function getListOfRegisteredFaqs()
    {
        $this->removeEmptyEntries();
        $post = $_POST;

        $columnsFilter = array(
            array('db' => Alias::faqs . ".title", 'dt' => 0),
            array('db' => Alias::faqs . ".description", 'dt' => 1),
            array('db' => "IF(`deleted_at` IS NULL , 'Active', 'Inactive')", 'dt' => 2),
        );

        $columnsOrder = array(
            array('db' => Alias::faqs . ".title", 'dt' => 0),
            array('db' => Alias::faqs . ".description", 'dt' => 1),
            array('db' => "6", 'dt' => 2),
        );

        $filterCondition = DataTable::filterDt($post, $columnsFilter);

        $order = ($post["order"][0]["column"] == -1) ? Alias::faqs . ".id DESC" : DataTable::orderDt($post, $columnsOrder);

        $resultset = $this->baseSelectFaqs($filterCondition)
            ->order($order)
            ->limit($post["length"])
            ->offset($post["start"])
            ->results();

        $this->query = "SELECT FOUND_ROWS() AS totalRecords";

        $totalRecords = $this->result("totalRecords");

        $data = array();

        foreach ($resultset as $value) :

            $output = array();

            $output[] = $value["title"];
            $output[] = $value["description"];
            $output[] = $value["status"];
            $output[] = '<a href="' . route("/faqs/save/" . $value["id"]) . '" class="btn btn-sm btn-secondary"><i class="fa fa-pencil"></i></a>';

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
