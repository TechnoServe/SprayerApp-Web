<?php

/**
 * Created by PhpStorm.
 * User: TNS Programmer
 * Date: 2022-02-16
 * Time: 4:39 PM
 */

namespace sprint\models;

use \sprint\database\Model;

class Api extends Model
{
    public function save($table, $data, $uidKey)
    {
        if(!empty($data))
        {            
            unset($data["sync_status"]);
            unset($data["id"]);
            
            $uid = $data[$uidKey];
            
            $this->select($table)->where("{$uidKey} = {$uid}")->result();
            
            if($this->num_rows() > 0){
                $this->update($table)->values($data)->where("{$uidKey} = {$uid}");
                
                return json_encode(
                    array(
                        "status"=> "success"                      
                    )
                );
            }
            
            $this->insert($table)->values($data);
            
            $lastInsertId = $this->insert_id();
            
            if($lastInsertId > 0){
                return json_encode(
                    array(
                        "status"=> "success"                      
                    )
                );
            }else{
                return json_encode(
                    array(
                        "status"=> "failed"                      
                    )
                );
            }
        }
    }
    
    public function profiles()
    {
        
        $results = $this->select("profiles")
            ->columns("id, name, visibility")
            ->where("mobile_access = 1")
            ->results();
            
        return json_encode(
            array(
                "status" => $this->num_rows() == 0 ? "failed" : "success",
                "data" => $results,
                )
            );
    }
    
    public function faqs()
    {
        
        $results = $this->select("faqs")->where("deleted_at IS NULL")->results();
            
        return json_encode(
            array(
                "status" => $this->num_rows() == 0 ? "failed" : "success",
                "data" => $results,
                )
            );
    }
    
    public function campaigns()
    {
        
        $results = $this->select("campains")->where("deleted_at IS NULL")->results();
            
        return json_encode(
            array(
                "status" => $this->num_rows() == 0 ? "failed" : "success",
                "data" => $results,
                )
            );
    }
    
    public function fetch($table, $data, $uidKey){
        $visibility = $data["visibility"];
        $district = $data["district"];
        $province = $data["province"];
        $where = ["u.user_uid > 0"];
        $filter = "";
        $result = array();
        
        $uid = array_column($data["uid"], $uidKey);        
        
        if(!empty($uid)){
            $joinUid    = implode(", ", $uid);
            $filter     = "$table.$uidKey NOT IN (".$joinUid.")";            
            array_push($where, $filter);
        }
 
        if($visibility == "district")
        {
            array_push($where, "u.district = '{$district}'");   
        }else if($visibility == "province")
        {
            array_push($where, "u.province = '{$province}'");  
        }
        
        $where = implode(" AND ", $where);
        
        $result = $this->select($table)->columns("{$table}.*")->join("users u ON u.user_uid = {$table}.user_uid")->where($where)->results(); 
        
        echo json_encode($result);
    }
    
    public function authanticate($data)
    {
        $username = $data["username"];
        $password = $data["password"];
        
        $result = [];
        
        $result = $this->select("users")->columns("
                users.*,
                profiles.name as profile_name, 
                profiles.mobile_access as profile_mobile_access,
                profiles.visibility as profile_visibility
            ")
            ->join("profiles ON profiles.id = users.profile_id")
            ->where("(users.mobile_number = '$username' OR users.email = '$username') AND users.password = '$password'")
            ->result();
        
        return json_encode(
            array(
                "status" => $this->num_rows() == 0 ? "failed" : "success",
                "data" => $result,
                )
            );
    }
}
