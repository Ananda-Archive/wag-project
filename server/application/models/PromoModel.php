<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PromoModel extends CI_Model {
    CONST TABLE_NAME = "promo";

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
    public function getAll() {
        return $this->db
                    ->select("*")
                    ->get($this::TABLE_NAME)
                    ->result_array();
    }

    public function create($id, $name, $code, $discount, $type) {
        $this->db->insert(
            $this::TABLE_NAME,
            array(
                "id" => $id,
                "name" => $name,
                "code" => $code,
                "discount" => $discount,
                "type" => $type,
            )
        ); return $this->db->affected_rows();
    }

    public function update($id, $datas) {
        $result = $this->db->get_where($this::TABLE_NAME, $datas);
        if($result->num_rows() > 0) return true;

        $this->db->update($this::TABLE_NAME, $datas, "id='{$id}'");
        
        return $this->db->affected_rows();
    }

    public function delete($id){
        $this->db->delete($this::TABLE_NAME, "id='{$id}'");
        return $this->db->affected_rows();
    }

}