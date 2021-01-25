<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserModel extends CI_Model {
    CONST TABLE_NAME = "user";

    public function create_user($id, $name, $email, $phone, $username, $password, $verified, $role, $privilege) {
        $this->db->insert(
            $this::TABLE_NAME,
            array(
                "id" => $id,
                "name" => $name,
                "email" => $email,
                "phone" => $phone,
                "username" => $username,
                "password" => $password,
                "verified" => $verified,
                "role" => $role,
                "privilege" => $privilege
            )
        );
        // Displays the number of affected rows
        return $this->db->affected_rows();
    }

    public function get_all() {
        return $this->db
                    ->select("id,name,email,phone,username,verified,role,privilege,modified")
                    ->where("verified",1)
                    ->order_by("role", "DESC")
                    ->order_by("name", "ASC")
                    ->get($this::TABLE_NAME)
                    ->result_array();
    }

    public function get_by_id($id) {
        return $this->db
                    ->select("id,name,email,phone,username,verified,role,privilege,modified")
                    ->where("id",$id)
                    ->order_by("modified", "DESC")
                    ->get($this::TABLE_NAME)
                    ->result_array();
    }

    public function get_unverified() {
        return $this->db
                    ->select("id,name,email,phone,username,verified,role,privilege,modified")
                    ->where("verified",0)
                    ->order_by("modified", "DESC")
                    ->get($this::TABLE_NAME)
                    ->result_array();
    }

    public function get_dosen() {
        return $this->db
                    ->select("id,name")
                    ->where("role",2)
                    ->get($this::TABLE_NAME)
                    ->result_array();
    }

    public function id_exist($id) {
        return $this->db
                    ->select("id")
                    ->where("id",$id)
                    ->get($this::TABLE_NAME)
                    ->num_rows();
    }

    public function username_exist($username) {
        return $this->db
                    ->select("username")
                    ->where("username",$username)
                    ->get($this::TABLE_NAME)
                    ->num_rows();
    }

    public function auth($username,$password) {
        // Check if username exist
        $result = $this->db
                    ->select("id,username,password,verified")
                    ->where("username",$username)
                    ->get($this::TABLE_NAME);
        if($result->num_rows()>0) {
            $passwordDecrypt = $result->row()->password;
            if(password_verify($password, $passwordDecrypt)) {
                // Check if the input password match the encrypted password in db
                if($result->row()->verified) {
                    return $result->row()->id;
                } return -1; // User Not Verified Yet
            } return -2; // Wrong Password
        } return -3; // User Not Found
    }

    public function verify($users) {
        foreach($users as $user) {
            $this->db->where("id", $user["id"]);
            $this->db->update($this::TABLE_NAME, [
                "verified"=>1
            ]);
        }
        return $this->db->affected_rows();
    }

    public function reject($users) {
        foreach($users as $user) {
            $this->db->delete($this::TABLE_NAME, "id='{$user["id"]}'");
        } return $this->db->affected_rows();
    }

    public function update_user($id, $datas) {
        // Check Jika merubah sesuatu
        // Jika update tidak ada perubahan maka affected_rows() return 0
        $result = $this->db->get_where($this::TABLE_NAME, $datas);
        if($result->num_rows() > 0) return true;

        // Update
        $this->db->update($this::TABLE_NAME, $datas, "id='{$id}'");
        return $this->db->affected_rows();
    }

    public function delete_user($id) {
        $this->db->delete($this::TABLE_NAME, "id='{$id}'");
        return $this->db->affected_rows();
    }
}