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
class Incomes extends Model
{
    private $table = "expenses_incomes";
    
    public function create()
    {
        $date = date("Y-m-d H:i:s");
        $post = array(  "expenses_income_uid" => "",
                        "user_uid" =>  $_SESSION["user"]["user_uid"],
                        "category"                  => 1,
                        "description"               => "",
                        "expenses_income_type"      => 0,
                        "price"                     => 0,
                        "updated_at"                => $date, 
                        "last_sync_at"              => $date);
        $this->insert($this->table)->values($post);

        $insertId = $this->insert_id();

         if($insertId){
            $auditData = [
                "description" => htmlspecialchars("Creating a expense or incomes with Id: {$insertId}"),
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
            "expenses_income_uid"              => trim($post["expenses_income_uid"]),
            "user_uid"                      => trim($post["user_uid"]),
            "category"                      => trim($post["category"]),
            "price"                         => trim($post["price"]),
            "description"                   => trim($post["description"]),
            "expenses_income_type"          => trim($post["expenses_income_type"]),
            "expenses_income_payment_type"  => trim($post["expenses_income_payment_type"]),
            "updated_at"                    => $date
        );
        $result = $this->update($this->table)->values($post)->where("id = {$id}");

        $this->removeEmptyEntries();    

         if($result){
            $auditData = [
                "description" => htmlspecialchars("Updating a expense or incomes data with Id: {$id}"),
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
        $this->delete($this->table)->where("expenses_income_uid IS NULL OR expenses_income_uid = '' AND (description IS NULL OR description = '')  AND (expenses_income_type IS NULL OR expenses_income_type = 0) AND (price IS NULL OR price = 0)")->run();
    }

    /**
     * Returnes the ownerofthe plantation through is UIID
     * 
     * 
     */
    public function owner($incomes)
    {
        return $this->select("users u")->where("user_uid='{$incomes['user_uid']}'")->limit(1)->result();
    }

    public function single($id)
    {
        return $this->find($this->table, $id);
    }

    public function show(){
        return $this->select($this->table)->results();
    }

    private function baseSelectIncomes($filterCondition)
    {
        return $this->select($this->table . " " . Alias::incomes)
            ->columns("

                        SQL_CALC_FOUND_ROWS

                        " . Alias::incomes . ".id,
                        CONCAT(" . Alias::sprayers . ".first_name, ' '," . Alias::sprayers . ".last_name) as sprayer,
                        " . Alias::sprayers . ".first_name,
                        " . Alias::incomes . ".category,
                        " . Alias::incomes . ".expenses_income_uid,
                        " . Alias::incomes . ".description,
                        " . Alias::incomes . ".expenses_income_type,
                        " . Alias::incomes . ".expenses_income_payment_type,
                        " . Alias::incomes . ".price,
                        " . Alias::incomes . ".last_sync_at

                ")
            ->join("(SELECT user_uid, first_name, last_name FROM users) as ".Alias::sprayers." ON ".Alias::sprayers.".user_uid = ".Alias::incomes.".user_uid")
            ->where("               
                (".DataTable::filter(Alias::incomes).")
                {$filterCondition}
            ");
    }

    public function getListOfRegisteredIncomes()
    {
        $this->removeEmptyEntries();
        $post = $_POST;
        $columnsFilter = array(
            array('db' => "CONCAT(" . Alias::sprayers . ".first_name, ' '," . Alias::sprayers . ".last_name)", 'dt' => 0),
            array('db' => Alias::incomes . ".expenses_income_uid", 'dt' => 1),
            array('db' => Alias::incomes . ".category", 'dt' => 2),
            array('db' => Alias::incomes . ".price", 'dt' => 3),
            array('db' => Alias::incomes . ".expenses_income_type", 'dt' => 4),
            array('db' => Alias::incomes . ".expenses_income_payment_type", 'dt' => 5),
            array('db' => Alias::incomes . ".description", 'dt' => 6),
            array('db' => Alias::incomes . ".last_sync_at", 'dt' => 7),
        );

        $columnsOrder = array(
            array('db' => "2", 'dt' => 0),
            array('db' => Alias::incomes . ".expenses_income_uid", 'dt' => 1),
            array('db' => Alias::incomes . ".category", 'dt' => 2),
            array('db' => Alias::incomes . ".price", 'dt' => 3),
            array('db' => Alias::incomes . ".expenses_income_type", 'dt' => 4),
            array('db' => Alias::incomes . ".expenses_income_payment_type", 'dt' => 5),
            array('db' => Alias::incomes . ".description", 'dt' => 6),
            array('db' => Alias::incomes . ".last_sync_at", 'dt' => 7),
        );

        $filterCondition = DataTable::filterDt($post, $columnsFilter);

        $order = ($post["order"][0]["column"] == -1) ? Alias::incomes . ".id DESC" : DataTable::orderDt($post, $columnsOrder);

        $resultset = $this->baseSelectIncomes($filterCondition)
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
            $output[] = $value["expenses_income_uid"];
            $output[] = $value["category"];
            $output[] = $value["price"];
            $output[] = $value["expenses_income_type"];
            $output[] = $value["expenses_income_payment_type"];
            $output[] = $value["description"];
            $output[] = $value["last_sync_at"];
            $output[] = '<a href="' . route("/incomes/save/" . $value["id"]) . '" class="btn btn-sm btn-secondary"><i class="fa fa-pencil"></i></a><button  onclick="deleteIncomes(this)" type="button" id="' . $value["id"] . '"  data-fullname="' . $value["sprayer"]. '"  data-uid="' . $value["chemical_incomes_uid"]. '" class="btn btn-sm btn-danger btnDeleteIncomes"><i class="fa fa-trash"></i></button>';
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


    public function deleteIncomes($id){
        $date = date("Y-m-d H:i:s");
        $result = $this->update($this->table)->values(array(
            "deleted_at" => $date,
            "updated_at" => $date
        ))->where("id = {$id}");

        if($result){
            $auditData = [
                "description" => htmlspecialchars("Deleting incomes {$id}"),
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
