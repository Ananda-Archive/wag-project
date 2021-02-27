<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ItemOrderModel extends CI_Model {
    CONST TABLE_NAME = "item_order";

   public function create_item_order($oh_id, $item_orders) {
        foreach($item_orders as $item_order) {
            $this->db->insert(
                $this::TABLE_NAME,
                array(
                    "price" => $item_order["price"],
                    "amount" => $item_order["amount"],
                    "oh_id" => $oh_id,
                    "item_id" => $item_order["itemId"]
                )
            );
        }; return $this->db->affected_rows();
    }

    public function get_by_id($id) {
        return $this->db
                    ->select("id, item_id as itemId, price, amount, oh_id as ohId, review, review_message as reviewMessage")
                    ->where("id",$id)
                    ->get($this::TABLE_NAME)
                    ->result_array();
    }

    public function getAll() {
        return $this->db
                    ->select("*")
                    ->where("review IS NOT NULL", NULL, FALSE)
                    ->where("review >=",4)
                    ->get($this::TABLE_NAME)
                    ->result_array();
    }

    public function getReview($id) {
        return $this->db
                    ->select("id, item_id as itemId, price, amount, oh_id as ohId, review, review_message as reviewMessage")
                    ->where("item_id",$id)
                    ->where("review IS NOT NULL", NULL, FALSE)
                    ->get($this::TABLE_NAME)
                    ->result_array();
    }

    public function get_by_order_id($id) {
        return $this->db
                    ->select("id, item_id as itemId, price, amount, oh_id as ohId, review, review_message as reviewMessage")
                    ->where("oh_id",$id)
                    ->get($this::TABLE_NAME)
                    ->result_array();
    }

    public function update($id, $datas) {
        $result = $this->db->get_where($this::TABLE_NAME, $datas);
        if($result->num_rows() > 0) return true;

        $this->db->update($this::TABLE_NAME, $datas, "id='{$id}'");
        
        return $this->db->affected_rows();
    }

    public function id_exist($id) {
        return $this->db
                    ->select("id")
                    ->where("id",$id)
                    ->get($this::TABLE_NAME)
                    ->num_rows();
    }
}