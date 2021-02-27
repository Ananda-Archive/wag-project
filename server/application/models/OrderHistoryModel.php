<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class OrderHistoryModel extends CI_Model {
    CONST TABLE_NAME = "order_history";

    public function id_exist($id) {
        return $this->db
                    ->select("id")
                    ->where("id",$id)
                    ->get($this::TABLE_NAME)
                    ->num_rows();
    }

    public function create($id, $name, $user_id, $courier, $courier_type, $shipping_cost, $promo_id, $note, $status, $bukti) {
        $this->db->insert(
            $this::TABLE_NAME,
            array(
                "id" => $id,
                "name" => $name,
                "user_id" => $user_id,
                "courier" => $courier,
                "courier_type" => $courier_type,
                "shipping_cost" => $shipping_cost,
                "promo_id" => $promo_id,
                "note" => $note,
                "status" => $status,
                "bukti" => $bukti
            )
        ); return $this->db->affected_rows();
    }

    public function uploadBukti($id, $link) {
        $datas = array(
            'bukti' => $link,
            'status' => 1
        );

        $this->db->update($this::TABLE_NAME, $datas, "id='{$id}'");
        
        return $this->db->affected_rows();
    }

    public function get_by_id($id) {
        return $this->db
                    ->select("id, name, user_id as userId, courier, courier_type as courierType, shipping_cost as shippingCost, promo_id as promoId, note, status, bukti, resi")
                    ->where("id",$id)
                    ->get($this::TABLE_NAME)
                    ->result_array();
    }
    
    public function getAll() {
        return $this->db
                    ->select("id, name, user_id as userId, courier, courier_type as courierType, shipping_cost as shippingCost, promo_id as promoId, note, status, bukti, resi")
                    ->order_by("created", "ASC")
                    ->get($this::TABLE_NAME)
                    ->result_array();
    }

    public function update($id, $datas) {
        $result = $this->db->get_where($this::TABLE_NAME, $datas);
        if($result->num_rows() > 0) return true;

        $this->db->update($this::TABLE_NAME, $datas, "id='{$id}'");
        
        return $this->db->affected_rows();
    }

    public function remind() {
        return $this->db
                    ->select("*")
                    ->where("DATE(`created`) + INTERVAL 2 DAY < NOW() ")
                    ->where("status",0)
                    ->get($this::TABLE_NAME)
                    ->result_array();
    }
}