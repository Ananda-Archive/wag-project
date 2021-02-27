<?php

use chriskacerguis\RestServer\RestController;

class Product extends RestController {

    function __construct() {
        parent::__construct();
    }

    function index_post() {
        $this->load->model("ProductModel");
        $this->load->model("ProductImageModel");
        switch($this->post("command")) {
            case "insertProduct":
                $name = $this->post("name");
                $ingredients = $this->post("ingredients");
                $skin_type = $this->post("skinType");
                $price = $this->post("price");
                $discount = $this->post("discount");
                $stock = $this->post("stock");
                $weight = $this->post("weight");
                $discount_status = 0;
                $image_lead = $this->post("imageLead");
                $images = $this->post("images");
                if(!isset($name) || !isset($ingredients) || !isset($skin_type) || !isset($price) || !isset($discount) || !isset($stock) || !isset($discount_status) || !isset($image_lead) || !isset($images)|| !isset($weight)) {
                    $required_parameters = [];
                    if(!isset($name)) array_push($required_parameters, "name");
                    if(!isset($ingredients)) array_push($required_parameters, "ingredients");
                    if(!isset($skin_type)) array_push($required_parameters, "skin_type");
                    if(!isset($price)) array_push($required_parameters, "price");
                    if(!isset($discount)) array_push($required_parameters, "discount");
                    if(!isset($stock)) array_push($required_parameters, "stock");
                    if(!isset($discount_status)) array_push($required_parameters, "discount_status");
                    if(!isset($weight)) array_push($required_parameters, "weight");
                    if(!isset($image_lead)) array_push($required_parameters, "image_lead");
                    if(!isset($images)) array_push($required_parameters, "images");
                    $this->response(
                        array(
                            "status" => FALSE,
                            "message" => $this::REQUIRED_PARAMETERS . implode(', ', $required_parameters)
                        ), $this::HTTP_BAD_REQUEST
                    );
                }
                do {
                    $id = bin2hex(random_bytes(8));
                } while($this->ProductModel->id_exist($id));
                if($this->ProductModel->create($id, $name, $ingredients, $skin_type, $price, $discount, $stock, $discount_status, $weight)) {
                    if($this->ProductImageModel->create_lead($id,$image_lead) && $this->ProductImageModel->create($id,$images)) {
                        $this->response(
                            array(
                                'status' => TRUE,
                                'message' => $this::INSERT_SUCCESS
                            ),
                            $this::HTTP_CREATED
                        );
                    } else {
                        $this->response(
                            array(
                                'status' => FALSE,
                                'message' => $this::INSERT_FAILED
                            ),
                            $this::HTTP_INTERNAL_ERROR
                        );
                    }
                }
                $this->response(
                    array(
                        'status' => FALSE,
                        'message' => $this::INSERT_FAILED
                    ),
                    $this::HTTP_INTERNAL_ERROR
                );
                break;
        }
    }

    function index_get() {
        $this->load->model("ProductImageModel");
        $this->load->model("ProductModel");
        $this->load->model("ItemOrderModel");
        // $this->load->model("ItemOrderModel");
        switch($this->get("command")) {
            case "delete":
                $id = $this->get("id");
                if(!isset($id)){
                    $this->response(
                        array(
                            'status' => FALSE,
                            'message' => $this::REQUIRED_PARAMETERS." id"
                        ),
                        $this::HTTP_BAD_REQUEST
                    );
                }
                if($this->ProductModel->id_exist($id) == 0) {
                    $this->response(
                        array(
                            'status' => FALSE,
                            'message' => $this::INVALID_ID_MESSAGE. " id does not exist"
                        ),
                        $this::HTTP_BAD_REQUEST
                    );
                }

                if($this->ProductModel->delete($id)) {
                    $this->response(
                        array(
                            'status' => TRUE,
                            'message' => $this::DELETE_SUCCESS
                        ),$this::HTTP_OK
                    );
                    $this->response(
                        array(
                            'status' => FALSE,
                            'message' => $this::DELETE_FAILED
                        ),$this::HTTP_INTERNAL_ERROR
                    );
                }
            case "getById":
                $id = $this->get("id");
                $result = $this->ProductModel->get_by_id($id);
                $img = $this->ProductImageModel->get_by_id($result[0]['id'], 0);
                $imgLead = $this->ProductImageModel->get_by_id($result[0]['id'], 1);
                $review = $this->ItemOrderModel->getReview($result[0]['id']);
                $result[0] = array_merge($result[0], array('images' => $img, 'imageLead' => $imgLead, 'review' => $review));
                $this->response($result,$this::HTTP_OK);
            default:
                $index = 0;
                $result = $this->ProductModel->getAll();
                foreach ($result as $row) {
                    $img = $this->ProductImageModel->get_by_id($row['id'], 0);
                    $imgLead = $this->ProductImageModel->get_by_id($row['id'], 1);
                    $temp = array_merge($result[$index], array('images' => $img, 'imageLead' => $imgLead));
                    $result[$index] = $temp;
                    $index++;
                }
                $this->response($result,$this::HTTP_OK);
        }
    }

    function index_put() {
        $this->load->model("ProductModel");
        $this->load->model("ProductImageModel");
        switch($this->put("command")) {
            case "updateProduct":
                $id = $this->put("id");
                $name = $this->put("name");
                $ingredients = $this->put("ingredients");
                $skin_type = $this->put("skinType");
                $price = $this->put("price");
                $discount = $this->put("discount");
                $stock = $this->put("stock");
                $discount_status = $this->put("discountStatus");
                $image_lead = $this->put("imageLead");
                $images = $this->put("images");
                $weight = $this->put("weight");
                
                $datas = array();

                if(!isset($id)){
                    $this->response(
                        array(
                            'status' => FALSE,
                            'message' => $this::REQUIRED_PARAMETERS." id"
                        ),
                        $this::HTTP_BAD_REQUEST
                    );
                }

                if($this->ProductModel->id_exist($id) == 0) {
                    $this->response(
                        array(
                            'status' => FALSE,
                            'message' => $this::INVALID_ID_MESSAGE. " id does not exist"
                        ),
                        $this::HTTP_BAD_REQUEST
                    );
                }

                $datas = array_merge($datas, array('id' => $id));
                if(isset($name)) {
                    $datas = array_merge($datas, array("name" => $name));
                }
                if(isset($ingredients)) {
                    $datas = array_merge($datas, array("ingredients" => $ingredients));
                }
                if(isset($skin_type)) {
                    $datas = array_merge($datas, array("skin_type" => $skin_type));
                }
                if(isset($price)) {
                    $datas = array_merge($datas, array("price" => $price));
                }
                if(isset($discount)) {
                    $datas = array_merge($datas, array("discount" => $discount));
                }
                if(isset($stock)) {
                    $datas = array_merge($datas, array("stock" => $stock));
                }
                if(isset($discount_status)) {
                    $datas = array_merge($datas, array("discount_status" => $discount_status));
                }
                if(isset($weight)) {
                    $datas = array_merge($datas, array("weight" => $weight));
                }

                if($this->ProductModel->update($id, $datas)) {
                    if(isset($images)) {
                        if($this->ProductImageModel->update($id, $images)) {
                            if(isset($image_lead)) {
                                if($this->ProductImageModel->updateLead($id, $image_lead)) {
                                    $this->response(
                                        array(
                                            'status' => TRUE,
                                            'message' => $this::UPDATE_SUCCESS . " (WITH PRODUCT IMAGE LEAD)"
                        
                                        ),
                                        $this::HTTP_OK
                                    );
                                } else {
                                    $this->response(
                                        array(
                                            'status' => FALSE,
                                            'message' => $this::UPDATE_FAILED . " (PRODUCT IMAGE LEAD)"
                        
                                        ),
                                        $this::HTTP_INTERNAL_ERROR
                                    );
                                }
                            }
                            $this->response(
                                array(
                                    'status' => TRUE,
                                    'message' => $this::UPDATE_SUCCESS . " (WITH PRODUCT IMAGES)"
                
                                ),
                                $this::HTTP_OK
                            );
                        } else {
                            $this->response(
                                array(
                                    'status' => FALSE,
                                    'message' => $this::UPDATE_FAILED . " (PRODUCT IMAGES)"
                
                                ),
                                $this::HTTP_INTERNAL_ERROR
                            );
                        }
                    }
                    $this->response(
                        array(
                            'status' => TRUE,
                            'message' => $this::UPDATE_SUCCESS
        
                        ),
                        $this::HTTP_OK
                    );
                }
        }
    }

}