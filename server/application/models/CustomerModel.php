<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CustomerModel extends CI_Model {
    CONST TABLE_NAME = "customer";

    public function create_user($id, $name, $email, $password, $alamat, $kecamatan, $kota, $provinsi, $is_member, $kode_pos, $phone) {
        $this->db->insert(
            $this::TABLE_NAME,
            array(
                "id" => $id,
                "name" => $name,
                "email" => $email,
                "password" => $password,
                "phone" => $phone,
                "alamat" => $alamat,
                "kecamatan" => $kecamatan,
                "kota" => $kota,
                "provinsi" => $provinsi,
                "kode_pos" => $kode_pos,
                "is_member" => $is_member
            )
        );
        // Displays the number of affected rows
        return $this->db->affected_rows();
    }

    public function id_exist($id) {
        return $this->db
                    ->select("id")
                    ->where("id",$id)
                    ->get($this::TABLE_NAME)
                    ->num_rows();
    }

    public function alreadyRegistered($email) {
        return $this->db
                    ->select("id")
                    ->where("email",$email)
                    ->where("is_member",1)
                    ->get($this::TABLE_NAME)
                    ->num_rows();
    }

    public function auth($email, $password) {
        $result = $this->db
                    ->select("*")
                    ->where("email",$email)
                    ->where("is_member",1)
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

    public function get_by_id($id) {
        return $this->db
                    ->select("*")
                    ->where("id",$id)
                    ->get($this::TABLE_NAME)
                    ->result_array();
    }
}