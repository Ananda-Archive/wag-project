<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserModel extends CI_Model {
    CONST TABLE_NAME = "user";

    public function create_user($id, $username, $password) {
        $this->db->insert(
            $this::TABLE_NAME,
            array(
                "id" => $id,
                "username" => $username,
                "password" => $password,
            )
        );
        // Displays the number of affected rows
        return $this->db->affected_rows();
    }

    public function get_by_id($id) {
        return $this->db
                    ->select("*")
                    ->where("id",$id)
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

    public function auth($username, $password) {
        $result = $this->db
                    ->select("*")
                    ->where("username",$username)
                    ->get($this::TABLE_NAME);
        if($result->num_rows()>0) {
            $passwordDecrypt = $result->row()->password;
            if(password_verify($password, $passwordDecrypt)) {
                return $result->row()->id;
            } else {
                return -1; // wrong password
            }
        } else {
            return -2; // User not found
        }
    }
}