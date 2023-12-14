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
class Logs extends Model
{
    private $table = "auditlogs";

    public function single($id)
    {
        return $this->find($this->table, $id);
    }

    public function show(){
        return $this->select($this->table)->results();
    }

    private function baseSelectLogs($filterCondition)
    {
        return $this->select($this->table . " " . Alias::logs)
            ->columns("
                    SQL_CALC_FOUND_ROWS
                    CONCAT(" . Alias::sprayers . ".first_name, ' '," . Alias::sprayers . ".last_name) as sprayer,
                    " . Alias::sprayers . ".first_name,
                    " . Alias::logs . ".*
                ")
            ->join("(SELECT id,user_uid, first_name, last_name FROM users) as ".Alias::sprayers." ON ".Alias::sprayers.".id = ".Alias::logs.".user_id")
            ->where( Alias::logs .".id > 0  {$filterCondition} ");
    }

    public function getListOfRegisteredLogs()
    {
        $post = $_POST;
        $columnsFilter = array(
            array('db' => "CONCAT(" . Alias::sprayers . ".first_name, ' '," . Alias::sprayers . ".last_name)", 'dt' => 0),
            array('db' => Alias::logs . ".description", 'dt' => 1),
            array('db' => Alias::logs . ".subject_type", 'dt' => 2),
            array('db' => Alias::logs . ".platform", 'dt' => 3),
            array('db' => Alias::logs . ".created_at", 'dt' => 4),
        );

        $columnsOrder = array(
            array('db' => "CONCAT(" . Alias::sprayers . ".first_name, ' '," . Alias::sprayers . ".last_name)", 'dt' => 0),
            array('db' => Alias::logs . ".description", 'dt' => 1),
            array('db' => Alias::logs . ".subject_type", 'dt' => 2),
            array('db' => Alias::logs . ".platform", 'dt' => 3),
            array('db' => Alias::logs . ".created_at", 'dt' => 4),
        );

        $filterCondition = DataTable::filterDt($post, $columnsFilter);

        $order = ($post["order"][0]["column"] == 0) ? Alias::logs . ".id DESC" : DataTable::orderDt($post, $columnsOrder);

        $resultset = $this->baseSelectLogs($filterCondition)
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
            $output[] = $value["description"];
            $output[] = $value["subject_type"];
            $output[] = $value["platform"];
            $output[] = $value["created_at"];
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


    public function deleteLogs($id){
        $date = date("Y-m-d H:i:s");
        $result = $this->update($this->table)->values(array(
            "deleted_at" => $date,
            "updated_at" => $date
        ))->where("id = {$id}");

        if($result){
            $auditData = [
                "description" => htmlspecialchars("Deleting logs {$id}"),
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
