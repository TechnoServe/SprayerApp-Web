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

class Farmers extends Model
{
    use Auditable;

    private $table = "farmers";


    public function create()
    {
        $date = date("Y-m-d H:i:s");
        $post = array(
            "first_name" => null,
            "farmer_uid" => 1,
            "user_uid" =>  $_SESSION["user"]["user_uid"],
            "created_at" => $date,
            "updated_at" => $date,
            "last_sync_at" => $date
        );
        $this->insert($this->table)->values($post);

        $insertId = $this->insert_id();

        $this->audit("create", $this);

        if ($insertId) {
            $auditData = [
                "description" => htmlspecialchars("Creating a farmer with id: {$insertId}"),
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
        $result = $this->update($this->table)->values(array(
            "first_name"            => trim($post["first_name"]),
            "last_name"             => trim($post["last_name"]),
            "birth_date"            => trim($post["birth_date"]),
            "email"                 => trim($post["email"]),
            "mobile_number"         => trim($post["mobile_number"]),
            "province"              => trim($post["province"]),
            "district"              => trim($post["district"]),
            "administrative_post"   => trim($post["administrative_post"]),
            "gender"              => trim($post["gender"]),
            "updated_at"            => $date
        ))->where("id = {$id}");

        if ($result) {
            $auditData = [
                "description" => htmlspecialchars("Updating a farmer data with id: {$id}"),
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
            "id" => $id
        ));
    }

    public function removeEmptyEntries()
    {
        $this->delete($this->table)->where("first_name IS NULL OR first_name = ''")->run();
    }

    public function single($id)
    {
        return $this->find($this->table, $id);
    }

    public function show()
    {
        return $this->select($this->table . " " . Alias::farmers)
            ->columns("ifnull( " . Alias::campaigns . ".description, 'N/A') as campaignName,  " . Alias::farmers . ".*")
            ->left("campaings as " . Alias::campaigns . " ON DATE(" . Alias::farmers . ".created_at) BETWEEN " . Alias::campaigns . ".opening AND  " . Alias::campaigns . ".closing")
            ->results();
    }
    public function geoFarmers($prov, $distr, $gender)
    {
        $provinceArray =  $prov ?? [];
        $districtArray =  $distr ?? [];
        $provinces = array_map(function ($v) {
            return "'{$v}'";
        }, $provinceArray);

        $districts = array_map(function ($v) {
            return "'{$v}'";
        }, $districtArray);
        $filter[] = !empty($provinceArray) ? " province IN(" . implode(", ", $provinces) . ") " : null;
        $filter[] = !empty($districtArray) ? " district IN(" . implode(", ", $districts) . ") " : null;
        $filter[] = $gender != "" ? " gender = '{$gender}' " : null;
        $filter = array_filter($filter);
        $where = !empty($filter) ? implode(" AND ", $filter) : "";

        return $this->select("farmers")
            ->columns("CONCAT_WS(' ', first_name, last_name) as fullname, gender, province, district, mobile_number")
            ->where(" {$where} ")
            ->order("1")
            // ->write(true);
            ->results();
    }


    private function baseSelectFarmers($filterCondition)
    {
        return $this->select($this->table . " " . Alias::farmers)
            ->columns("

                        SQL_CALC_FOUND_ROWS
                        " . Alias::campaigns . ".description,
                        " . Alias::farmers . ".id,
                        CONCAT(" . Alias::farmers . ".first_name, ' '," . Alias::farmers . ".last_name) as name,
                        " . Alias::farmers . ".province,
                        " . Alias::farmers . ".district,
                        " . Alias::farmers . ".administrative_post,
                        " . Alias::farmers . ".mobile_number,
                        " . Alias::farmers . ".last_sync_at,
                        " . Alias::farmers . ".gender

                ")
            ->left("campaigns as " . Alias::campaigns . " ON DATE(" . Alias::farmers . ".created_at) BETWEEN " . Alias::campaigns . ".opening AND  " . Alias::campaigns . ".closing")
            ->where("             
                (" . DataTable::filter(Alias::farmers) . ")
                {$filterCondition}
            ");
    }

    public function getListOfRegisteredFarmers()
    {
        $this->removeEmptyEntries();
        $post = $_POST;
        $columnsFilter = array(
            array('db' => " CONCAT(" . Alias::farmers . ".first_name, ' '," . Alias::farmers . ".last_name) ", 'dt' => 0),
            array('db' => Alias::farmers . ".province", 'dt' => 1),
            array('db' => Alias::farmers . ".district", 'dt' => 2),
            array('db' => Alias::farmers . ".administrative_post", 'dt' => 3),
            array('db' => Alias::farmers . ".mobile_number", 'dt' => 4),
            array('db' => Alias::farmers . ".gender", 'dt' => 5),
            array('db' => Alias::farmers . ".last_sync_at", 'dt' => 6),
            array('db' => Alias::campaigns . ".description", 'dt' => 7),
        );

        $columnsOrder = array(
            array('db' => "2", 'dt' => 0),
            array('db' => Alias::farmers . ".province", 'dt' => 1),
            array('db' => Alias::farmers . ".district", 'dt' => 2),
            array('db' => Alias::farmers . ".administrative_post", 'dt' => 3),
            array('db' => Alias::farmers . ".mobile_number", 'dt' => 4),
            array('db' => Alias::farmers . ".gender", 'dt' => 5),
            array('db' => Alias::farmers . ".last_sync_at", 'dt' => 6),
            array('db' => Alias::campaigns . ".description", 'dt' => 7),
        );

        $filterCondition = DataTable::filterDt($post, $columnsFilter);

        $order = ($post["order"][0]["column"] == -1) ? Alias::farmers . ".id DESC" : DataTable::orderDt($post, $columnsOrder);

        $resultset = $this->baseSelectFarmers($filterCondition)
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
            $output[] = $value["province"];
            $output[] = $value["district"];
            $output[] = $value["administrative_post"];
            $output[] = $value["mobile_number"];
            $output[] = $value["gender"];
            $output[] = $value["last_sync_at"];
            $output[] = $value["description"];
            // $output[] = $value["gender"] == 'Male' ? '<i class="fa fa-person"></i>' : '<i class="fa fa-person-dress"></i>';
            $output[] = '<a href="' . route("/farmers/save/" . $value["id"]) . '" class="btn btn-sm btn-secondary"><i class="fa fa-pencil"></i></a> <button  onclick="deleteFarmer(this)" type="button" id="' . $value["id"] . '"  data-fullname="' . $value["name"] . '" class="btn btn-sm btn-danger btnDeleteFarmer"><i class="fa fa-trash"></i></button>';
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

    public function deleteFarmer($id)
    {
        $date = date("Y-m-d H:i:s");
        $result = $this->update($this->table)->values(array(
            "deleted_at" => $date,
            "updated_at" => $date
        ))->where("id = {$id}");

        if ($result) {
            $auditData = [
                "description" => htmlspecialchars("Deleting farmer {$id}"),
                "subject_id"  => htmlspecialchars($id),
                "subject_type"  => htmlspecialchars($this->table),
                "properties"  => json_encode($post),
                "platform"  => htmlspecialchars("web"),
            ];
            $auditResult = (new AuditLogs())->audit($auditData);
        }

        return $result;
    }

    function sendSMS()
    {
        echo json_encode(array(
            "status" => "success",
            "message" => "Functionality not working yet..."
        ));
    }
}
