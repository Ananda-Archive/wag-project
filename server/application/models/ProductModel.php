<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProductModel extends CI_Model {
    CONST TABLE_NAME = "product";

    public function id_exist($id) {
        return $this->db
                    ->select("id")
                    ->where("id",$id)
                    ->get($this::TABLE_NAME)
                    ->num_rows();
    }

    public function create($id, $name, $ingredients, $skin_type, $price, $discount, $stock, $discount_status, $weight) {
        $this->db->insert(
            $this::TABLE_NAME,
            array(
                "id" => $id,
                "name" => $name,
                "ingredients" => $ingredients,
                "skin_type" => $skin_type,
                "price" => $price,
                "discount" => $discount,
                "stock" => $stock,
                "discount_status" => $discount_status,
                "weight" => $weight
            )
        ); return $this->db->affected_rows();
    }

    public function getAll() {
        return $this->db
                    ->select("id,name,ingredients,skin_type as skinType,price,discount,stock,discount_status as discountStatus, weight")
                    ->get($this::TABLE_NAME)
                    ->result_array();
    }

    public function get_by_id($id) {
        return $this->db
                    ->select("id,name,ingredients,skin_type as skinType,price,discount,stock,discount_status as discountStatus, weight")
                    ->where("id",$id)
                    ->get($this::TABLE_NAME)
                    ->result_array();
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