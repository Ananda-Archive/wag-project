<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TimelineModel extends CI_Model {
    CONST TABLE_NAME = "timeline";

    public function create_timeline($id, $date_start, $date_end) {
        $this->db->insert(
            $this::TABLE_NAME,
            array(
                "id" => $id,
                "date_start" => $date_start,
                "date_end" => $date_end
            )
        );
        // Displays the number of affected rows
        return $this->db->affected_rows();
    }

    public function get_all() {
        return $this->db
                    ->select('id, date_start as dateStart, date_end as dateEnd')
                    ->get($this::TABLE_NAME)
                    ->result_array();
    }

    public function get_by_id($id) {
        return $this->db
                    ->select('id, date_start as dateStart, date_end as dateEnd')
                    ->where("id",$id)
                    ->get($this::TABLE_NAME)
                    ->result_array();
    }

    public function get_current() {
        return $this->db
                    ->limit(1)  
                    ->select('id, date_start as dateStart, date_end as dateEnd')
                    ->order_by("modified", "DESC")
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

    public function update_timeline($id, $datas) {
        // Check Jika merubah sesuatu
        // Jika update tidak ada perubahan maka affected_rows() return 0
        $result = $this->db->get_where($this::TABLE_NAME, $datas);
        if($result->num_rows() > 0) return true;

        // Update
        $this->db->update($this::TABLE_NAME, $datas, "id='{$id}'");
        return $this->db->affected_rows();
    }
}