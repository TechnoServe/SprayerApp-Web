<?php

/**
 * Created by PhpStorm.
 * User: TNS Programmer
 * Date: 2022-02-16
 * Time: 4:39 PM
 */

namespace sprint\models;

use \sprint\database\Model;

class Auth extends Model
{
    public function authanticate()
    {
        try {
            $post = $_POST;
            $status = "error";
            $message = "Incorrect username or password.";
            $type = "error";

            $result = $this->select("users u")
                ->columns("
                u.*, 
                p.name AS profile, 
                p.visibility, 
                p.web_access, 
                p.mobile_access
            ")->join("profiles p ON p.id = u.profile_id")
                // ->where("(email = '" . htmlspecialchars($post['username']) . "' OR mobile_number = '" . htmlspecialchars($post['username'] ). "') AND password = SHA2('" . htmlspecialchars($post["password"] ). "', 512) AND deleted_at IS NULL")
                ->where("(email = '" . htmlspecialchars($post['username']) . "' OR mobile_number = '" . htmlspecialchars($post['username'] ). "') AND password = '" . htmlspecialchars($post["password"] ). "'")
                ->result();
            $numRows = $this->num_rows();
            
            if($numRows > 0){
                if($result["deleted_at"] == null && $result["web_access"] == 1){
                    #Chech his profile
                    $user = new \sprint\models\Users();
                    $_SESSION["user"] = $result;
                    $_SESSION["user"]["islogged"] = true;
                    $_SESSION["user"]["isSeller"] = $user->isSeller($result["id"]) > 0 ? true : false;
                    #Update the status
                    $status = "success";
                    $message = "Login successfuly.";
                    $type = "success";
                }elseif($result["deleted_at"] == null && $result["web_access"] != 1){
                    #Update the status
                    $status = "error";
                    $message = "You don't have permission to access web reports. If this continues, please, contact the administrator to activate.";
                    $type = "info";
                }else{
                    #Update the status
                    $status = "error";
                    $message = "Your account is inactive. If this continues, please, contact the administrator to activate.";
                    $type = "info";
                }
            }
            echo json_encode(array(
                "status" => $status,
                "message" => $message,
                "result" => $result,
                "type" => $type
            ));
        } catch (Exception $ex) {
            echo json_encode(array(
                "status" => "error",
                "message" => $message,
                "result" => $result,
                "type" => $type
            ));
        }
    }
}
