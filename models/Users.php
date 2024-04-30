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

class Users extends Model
{
    private $table = "users";

    public function create()
    {
        $date = date("Y-m-d H:i:s");
        $post = array(
            "user_uid" =>  time(),
            "first_name" => "",
            "password" => "",
            "birth_date" => $date,
            "created_at" => $date,
            "updated_at" => $date,
            "last_sync_at" => $date,
        );

        $this->insert($this->table)->values($post);
        $insertId = $this->insert_id();
        if ($insertId) {
            $auditData = [
                "description" => htmlspecialchars("Creating user with id {$insertId}"),
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

    public function save($id, $post, $password)
    {
        $date = date("Y-m-d H:i:s");
        $post = array(
            "first_name"            => trim($post["first_name"]),
            "last_name"             => trim($post["last_name"]),
            "birth_date"            => trim($post["birth_date"]),
            "email"                 => trim($post["email"]),
            "mobile_number"         => trim($post["mobile_number"]),
            "province"              => trim($post["province"]),
            "district"              => trim($post["district"]),
            "administrative_post"   => trim($post["administrative_post"]),
            "profile_id"            => trim($post["profile_id"]),
            "deleted_at"            => ($post["deleted_at"] ? $date : NULL),
            "updated_at"            => $date
        );
        //Set password if the user its not set yet
        if (empty($password) || '' == $password) {
            // We need to get the encrypted password ahead of update
            $post["password"] = htmlspecialchars($post["mobile_number"]);
        }
        $result = $this->update($this->table)->values($post)->where("id = {$id}");
        if ($result) {
            $auditData = [
                "description" => htmlspecialchars("Updating a user data with id {$id}"),
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
            "message" => "User was updated successfully",
            "id" => $id
        ));
    }

    public function resetPassword($id, $password)
    {
        $date = date("Y-m-d H:i:s");
        $result = $this->update($this->table)->values(array(
            "password"   => $password,
            "updated_at" => $date
        ))->where("id = {$id}");

        #Audit 
        if ($result) {
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


    public function findByMobileNumber($mobile)
    {
        return $this->select($this->table)->where("mobile_number='$mobile'")->num_rows();
    }
    
    public function findByToken($token)
    {
        return $this->select($this->table)->where("reset_token_hash='$token'")->result();
    }

    public function findByEmail($email)
    {
        return $this->select($this->table)->where("email='$email'")->result();
    }

    public function findByUserName($userName)
    {
        return $this->select($this->table)->where("mobile_number='$userName'")->result();
    }

    public function isSeller($id)
    {
        return $this->select($this->table . " as " . Alias::sprayers)
            ->columns(Alias::sprayers . ".id ")
            ->join("profiles as " . Alias::profiles . " ON " . Alias::profiles . ".id=" . Alias::sprayers . ".profile_id ")
            ->where(
                ""
                    . Alias::sprayers . ".id='$id' AND "
                    . Alias::sprayers . ".deleted_at IS NULL AND "
                    . Alias::profiles . ".deleted_at IS NULL AND "
                    . Alias::profiles . ".name='Seller'"

            )
            ->num_rows();
    }

    public function single($id)
    {
        return $this->find($this->table, $id);
    }
    /**
     * Get the encrypted password geneated by the mysql db using SHA2 aalgoritm
     * 
     */
    public function getEncryptedPassword($plainText)
    {
        return $this->select("users u")->columns("SHA2('{$plainText}', 512) AS pwd")->result();
    }

    private function baseSelectUsers($filterCondition)
    {
        return $this->select($this->table . " " . Alias::sprayers)
            ->columns("
                       SQL_CALC_FOUND_ROWS
                        " . Alias::sprayers . ".id,
                CONCAT( " . Alias::sprayers . ".first_name, ' '," . Alias::sprayers . ".last_name) as fullname, 
                        " . Alias::sprayers . ".first_name,
                        " . Alias::sprayers . ".province,
                        " . Alias::sprayers . ".district,
                        " . Alias::sprayers . ".administrative_post,
                        " . Alias::sprayers . ".mobile_number,
                        " . Alias::sprayers . ".last_sync_at,
                        " . Alias::sprayers . ".deleted_at,
						" . Alias::profiles . ".name,
						IF(" . Alias::sprayers . ".`deleted_at` IS NULL , 'Active', 'Inactive') AS status

                ")
            ->join("profiles " . Alias::profiles . " ON " . Alias::profiles . ".id = " . Alias::sprayers . ".profile_id")
            ->where(" (" . Alias::sprayers . ".id > 0)
                    {$filterCondition}
            ");
    }

    public function getListOfRegisteredUsers()
    {
        $this->removeEmptyEntries();
        $post = $_POST;

        $columnsFilter = array(
            // array('db' =>"fullname", 'dt' => 0),
            array('db' => "CONCAT( " . Alias::sprayers . ".first_name, ' '," . Alias::sprayers . ".last_name)", 'dt' => 0),
            array('db' => Alias::sprayers . ".administrative_post", 'dt' => 1),
            array('db' => Alias::sprayers . ".district", 'dt' => 2),
            array('db' => Alias::sprayers . ".province", 'dt' => 3),
            array('db' => Alias::sprayers . ".mobile_number", 'dt' => 4),
            array('db' => Alias::profiles . ".name", 'dt' => 5),
            array('db' => "IF(" . Alias::sprayers . ".`deleted_at` IS NULL , 'Active', 'Inactive')", 'dt' => 6),
            array('db' => Alias::sprayers . ".last_sync_at", 'dt' => 7),
        );

        $columnsOrder = array(
            array('db' => "2", 'dt' => 0),
            array('db' => Alias::sprayers . ".administrative_post", 'dt' => 1),
            array('db' => Alias::sprayers . ".district", 'dt' => 2),
            array('db' => Alias::sprayers . ".province", 'dt' => 3),
            array('db' => Alias::sprayers . ".mobile_number", 'dt' => 4),
            array('db' => Alias::profiles . ".name", 'dt' => 5),
            array('db' => Alias::sprayers . ".deleted_at", 'dt' => 6),
            array('db' => Alias::sprayers . ".last_sync_at", 'dt' => 7),
        );

        $filterCondition = DataTable::filterDt($post, $columnsFilter);

        $order = ($post["order"][0]["column"] == -1) ? Alias::sprayers . ".id DESC" : DataTable::orderDt($post, $columnsOrder);
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

            $output[] = $value["fullname"];
            $output[] = $value["administrative_post"];
            $output[] = $value["district"];
            $output[] = $value["province"];
            $output[] = $value["mobile_number"];
            $output[] = $value["name"];
            // $output[] = '<a href="#">' . $value["name"] . '</a>';
            $output[] = $value["status"];
            $output[] = $value["last_sync_at"];
            $output[] = '<a href="' . route("/users/save/" . $value["id"]) . '" class="btn btn-sm btn-secondary"><i class="fa fa-pencil"></i></a>';

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



    public function updateTokenForPasswordReset($token_hash, $expiry, $email, $id)
    {
        $date = date("Y-m-d H:i:s");
        $post = array(
            "reset_token_hash"            => trim($token_hash),
            "reset_token_expires_at"    => trim($expiry),
        );
        //Set password if the user its not set yet

        $result = $this->update($this->table)->values($post)->where("id = {$id}");
        if ($result) {
            $auditData = [
                "description" => htmlspecialchars("Password request code to:  {$id}"),
                "subject_id"  => htmlspecialchars($id),
                "subject_type"  => htmlspecialchars($this->table),
                "properties"  => json_encode($post),
                "platform"  => htmlspecialchars("web"),
            ];
            $auditResult = (new AuditLogs())->audit($auditData);
            echo json_encode(array(
                "status" => "success",
                "message" => "The password link/code was sent to your email address!",
            ));
        } else {

            echo json_encode(array(
                "status" => "error",
                "message" => "Its seems that the email address doens not exists!",
            ));
        }
    }
}
