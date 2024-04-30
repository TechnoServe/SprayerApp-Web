<?php

/**
 * Created by PhpStorm.
 * Request: TNS Programmer
 * Date: 2022-02-16
 * Time: 4:39 PM
 */

namespace sprint\models;

use \sprint\database\Model;
use \sprint\helpers\Alias;
use \sprint\helpers\DataTable;

class Requests extends Model
{
    private $table = "requests";

    public function create()
    {
        $date = date("Y-m-d H:i:s");
        $post = $_POST;
        $data = array(
            "first_name"       => trim($post["first_name"]),
            "last_name"        => trim($post["last_name"]),
            "email"            => trim($post["email"]),
            "mobile_number"    => trim($post["mobile_number"]),
            "email"            => trim($post["email"]),
            "password"         => trim($post["password"]),
            "approved_by"      => null,
            "province"         => trim($post["province"]),
            "district"         => trim($post["district"]),
            "created_at"       => $date,
            "updated_at"       => $date,
        );
        $this->beginTransaction();
        $this->insert($this->table)->values($data);
        $insertId = $this->insert_id();

        #Lets create a user
        $postUser = array(
            "user_uid"              => time(),
            "first_name"            => trim($post["first_name"]),
            "last_name"             => trim($post["last_name"]),
            "birth_date"            => $date,
            "email"                 => trim($post["email"]),
            "mobile_number"         => trim($post["mobile_number"]),
            "province"              => trim($post["province"]),
            "district"              => trim($post["district"]),
            "password"              => trim($post["password"]),
            "administrative_post"   => trim($post["administrative_post"]),
            "created_at"            => $date,
            "updated_at"            => $date,
            "last_sync_at"          => $date,
        );

        $this->insert("users")->values($postUser);
        $insertIdUser = $this->insert_id();

        if($insertId && $insertIdUser){
            $auditData = [
                "description" => htmlspecialchars("Request created with Id {$insertId}"),
                "subject_id"  => htmlspecialchars($insertId),
                "subject_type"  => htmlspecialchars($this->table),
                "properties"  => json_encode($data),
                "platform"  => htmlspecialchars("web"),
            ];
            $auditResult = (new AuditLogs())->audit($auditData);
            
            $auditDataUser = [
                "description" => htmlspecialchars("Seller created with Id {$insertIdUser}"),
                "subject_id"  => htmlspecialchars($insertIdUser),
                "subject_type"  => htmlspecialchars("users"),
                "properties"  => json_encode($postUser),
                "platform"  => htmlspecialchars("web"),
            ];
            $auditResult = (new AuditLogs())->audit($auditDataUser);
            $this->commit();    

            echo json_encode(array(
                "status" => "success",
                "message" => "Request was updated successfully",
                "id" => $id
            ));
        }
    }

    public function save()
    {

    }

    public function resetPassword($id, $password)
    {
        $date = date("Y-m-d H:i:s");
        $result = $this->update($this->table)->values(array(
            "password"   => $password,
            "updated_at" => $date
        ))->where("id = {$id}");
    
        #Audit 
        if($result){
            $auditData = [
                "description" => htmlspecialchars("Password reset"),
                "subject_id"  => htmlspecialchars($id),
                "subject_type"  => htmlspecialchars($this->table),
                "properties"  => json_encode(["password" => $password, "update_at" => $date]),
                "platform"  => htmlspecialchars("web"),
            ];

            $auditResult = (new AuditLogs())->audit($auditData);
        }  

        return $result;
    }

    public function removeEmptyEntries()
    {
        $this->delete($this->table)->where("(first_name IS NULL OR first_name = '') AND (last_name IS NULL OR last_name = '') AND (email IS NULL OR email = '') AND (mobile_number IS NULL OR mobile_number = '' OR mobile_number = 0)  AND (password IS NULL OR password = '')")->run();
    }

    public function single($id)
    {
        return $this->find($this->table, $id);
    }

    public function findByMobileNumber($mobile)
    {
        return $this->select($this->table)->where("mobile_number='$mobile'")->num_rows();
    }
    /**
     * Get the encrypted password geneated by the mysql db using SHA2 aalgoritm
     * 
     */
    public function getEncryptedPassword($plainText)
    {
        return $this->select("requests u")->columns("SHA2('{$plainText}', 512) AS pwd")->result();
    }

    private function baseSelectRequests($filterCondition)
    {
        return $this->select($this->table . " " . Alias::requests)
            ->columns("
                       SQL_CALC_FOUND_ROWS
                        " . Alias::requests . ".id,
                CONCAT( " . Alias::requests . ".first_name, ' ',". Alias::requests . ".last_name) as fullname, 
                        " . Alias::requests . ".first_name,
                        " . Alias::requests . ".province,
                        " . Alias::requests . ".district,
                        " . Alias::requests . ".mobile_number,
                        " . Alias::requests . ".email,
                        " . Alias::requests . ".approved_by,
                        " . Alias::requests . ".approved,
                        " . Alias::requests . ".deleted_at,
						IF(" . Alias::requests . ".`approved` > 0 , 'Approved', 'Not approved') AS status

                ")
            ->where(" (" . Alias::requests . ".id > 0)
                    {$filterCondition}
            ");
    }

    public function getListOfRegisteredRequests()
    {
        $this->removeEmptyEntries();
        $post = $_POST;

        $columnsFilter = array(
            // array('db' =>"fullname", 'dt' => 0),
            array('db' => "CONCAT( " . Alias::requests . ".first_name, ' ',". Alias::requests . ".last_name)", 'dt' => 0),
            array('db' => Alias::requests . ".email", 'dt' => 1),
            array('db' => Alias::requests . ".mobile_number", 'dt' => 2),
            array('db' => Alias::requests . ".district", 'dt' => 3),
            array('db' => Alias::requests . ".province", 'dt' => 4),
            array('db' => "IF(`deleted_at` > 0 , 'Approved', 'Not approved')", 'dt' => 5),
            array('db' => Alias::requests . ".approved_by", 'dt' => 6),
        );

        $columnsOrder = array(
            array('db' => "2", 'dt' => 0),
            array('db' => Alias::requests . ".email", 'dt' => 1),
            array('db' => Alias::requests . ".mobile_number", 'dt' => 2),
            array('db' => Alias::requests . ".district", 'dt' => 3),
            array('db' => Alias::requests . ".province", 'dt' => 4),
            array('db' => "11", 'dt' => 5),
            array('db' => Alias::requests . ".approved_by", 'dt' => 6),
        );

        $filterCondition = DataTable::filterDt($post, $columnsFilter);

        $order = ($post["order"][0]["column"] == -1) ? Alias::requests . ".id DESC" : DataTable::orderDt($post, $columnsOrder);
        $resultset = $this->baseSelectRequests($filterCondition)
            ->order($order)
            ->limit($post["length"])
            ->offset($post["start"])
            ->results();
        $this->query = "SELECT FOUND_ROWS() AS totalRecords";

        $totalRecords = $this->result("totalRecords");

        $data = array();

        foreach ($resultset as $value) :

            $output = array();

            $output[] = $value["fullname"];
            $output[] = $value["email"];
            $output[] = $value["mobile_number"];
            $output[] = $value["district"];
            $output[] = $value["province"];
            $output[] = $value["status"];
            $output[] = $value["approved_by"];
            $output[] = '<button type="button" onclick="updateApproved('.$value['id'].', 1)" class="btn btn-sm btn-success" title="Approve request"><i class="fa fa-check"></i></button>&nbsp;<button type="button" onclick="updateApproved('.$value['id'].', 0)" class="btn btn-sm btn-danger" title="Repprove request"><i class="fa fa-times"></i></button>';

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

    public function updateApproved($request, $user, $status, $profile)
    {
        $date = date("Y-m-d H:i:s");
        $post = $_POST;
        $approvalStatus = $status == 0 ? "Approved " : "Repproved";
        $u = $_SESSION["user"];
        $dataRequest = array(
            "approved"         => $status,
            "approved_by"      => $u["first_name"].' '.$u["last_name"],
            "updated_at"       => $date,
        );
        $dataUser = array(
            "deleted_at"       => $status== 1 ? null : $date,
            "updated_at"       => $date,
            "profile_id"       => $profile,

        );
        $this->beginTransaction();
        $resultRequest = $this->update($this->table)->values($dataRequest)->where("id = {$request}");
        $resultUser = $this->update("users")->values($dataUser)->where("id = {$user}");
        
        if($resultRequest && $resultUser){
            $auditData = [
                "description" => htmlspecialchars("{$approvalStatus} $request with Id {$request}"),
                "subject_id"  => htmlspecialchars($request),
                "subject_type"  => htmlspecialchars($this->table),
                "properties"  => json_encode($dataRequest),
                "platform"  => htmlspecialchars("web"),
            ];
            $auditResult = (new AuditLogs())->audit($auditData);
            
            $auditDataUser = [
                "description" => htmlspecialchars(" {$approvalStatus} request for User Seller Id {$user}"),
                "subject_id"  => htmlspecialchars($user),
                "subject_type"  => htmlspecialchars("users"),
                "properties"  => json_encode($dataUser),
                "platform"  => htmlspecialchars("web"),
            ];
            $auditResult = (new AuditLogs())->audit($auditDataUser);
            $this->commit();    

            echo json_encode(array(
                "status" => "success",
                "message" => "Request was updated successfully",
                "id" => $id
            ));
        }else{

        echo json_encode(array(
            "status" => "info",
            "message" => "Something went wrong!",
            "id" => $id
        ));  
        }

    }
}
