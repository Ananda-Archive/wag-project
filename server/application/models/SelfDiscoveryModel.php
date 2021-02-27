<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SelfDiscoveryModel extends CI_Model {
    CONST TABLE_NAME = "self_discovery";

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

    public function create($title, $content, $image) {
        $this->db->insert(
            $this::TABLE_NAME,
            array(
                "title" => $title,
                "content" => $content,
                "image" => $image,
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