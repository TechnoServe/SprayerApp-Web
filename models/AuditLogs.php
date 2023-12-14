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
class AuditLogs extends Model
{
    private $table = "auditlogs";

    public function audit($post)
    {
        try{
            $date = date("Y-m-d H:i:s");
            #Get the authenticated user;
            $user = $_SESSION["user"];
            // var_dump("<pre>",$user); exit();
            $insertId = $this->insert($this->table)->values(array(
                "description" => htmlspecialchars($post["description"]),
                "subject_id"  => htmlspecialchars($post["subject_id"]),
                "subject_type"  => htmlspecialchars($post["subject_type"]),
                "user_id"  => htmlspecialchars($user["id"] ?? 0),
                "old_properties"  => htmlspecialchars($post["old_properties"] ?? null),
                "properties"  => htmlspecialchars($post["properties"]),
                "platform"  => htmlspecialchars($post["platform"]),
                "created_at"  => $date,
                "updated_at"  => $date
            ));
            //  echo json_encode(array(
            //     "status" => $insertId > 0 ? "success" : "failed",
            //     "id" => $insertId,
            //     "message" => $insertId > 0 ? "Entity audited." : "failed to audit entity."
            // ));
         }catch(\Exceptions $ex){
            echo json_encode(array(
                "status" => "failed",
                "message" => "Error while log entity data: {$ex->getMessage()} at line {$ex->getLine()}"
            ));
         }
    }
}
