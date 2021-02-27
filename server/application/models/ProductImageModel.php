<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProductImageModel extends CI_Model {
    CONST TABLE_NAME = "product_image";

    public function create($id, $images) {
        foreach ($images as $image) {
            $this->db->insert($this::TABLE_NAME, array(
                'p_id' => $id,
                'image' => $image,
                'cover' => 0,
            ));
        }
        return $this->db->affected_rows();
    }

    public function create_lead($id, $image) {
        $this->db->insert($this::TABLE_NAME, array(
            'p_id' => $id,
            'image' => $image,
            'cover' => 1,
        ));
        return $this->db->affected_rows();
    }

    public function get_by_id($id, $cover) {
        $this->db->select('*');
        $this->db->from($this::TABLE_NAME);
        $this->db->where("p_id='{$id}'");
        $this->db->where("cover='{$cover}'");
        return $this->db->get()->result_array();
    }

    public function update($id, $images) {
        $this->db->where('p_id', $id);
        $this->db->where('cover', 0);
        $this->db->delete($this::TABLE_NAME);

        foreach ($images as $image) {
            $this->db->insert($this::TABLE_NAME, array(
                'p_id' => $id,
                'image' => $image,
                'cover' => 0,
            ));
        }

        return $this->db->affected_rows();
    }

    public function updateLead($id, $image) {
        $this->db->where('p_id', $id);
        $this->db->where('cover', 1);
        $this->db->delete($this::TABLE_NAME);

        $this->db->insert($this::TABLE_NAME, array(
            'p_id' => $id,
            'image' => $image,
            'cover' => 1,
        ));
        return $this->db->affected_rows();
    }
}