<?php

use chriskacerguis\RestServer\RestController;

use const chriskacerguis\RestServer\HTTP_OK;

class Promo extends RestController {

    function __construct() {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
    }

    public function index_post() {
        $this->load->model("PromoModel");
        
        switch($this->post("command")) {
            default:
                $name = $this->post("name");
                $code = $this->post("code");
                $discount = $this->post("discount");
                $type = $this->post("type");

                if(!isset($name) || !isset($code) || !isset($discount) || !isset($type)) {
                    $required_parameters = [];
                    if(!isset($name)) array_push($required_parameters, "name");
                    if(!isset($code)) array_push($required_parameters, "code");
                    if(!isset($discount)) array_push($required_parameters, "discount");
                    if(!isset($type)) array_push($required_parameters, "type");

                    $this->response(
                        array(
                            "status" => FALSE,
                            "message" => $this::REQUIRED_PARAMETERS . implode(', ', $required_parameters)
                        ), $this::HTTP_BAD_REQUEST
                    );
                }
                do {
                    $id = bin2hex(random_bytes(8));
                } while($this->PromoModel->id_exist($id));

                if($this->PromoModel->create($id, $name, $code, $discount, $type)) {
                    $this->response(
                        array(
                            "status" => TRUE,
                            "message" => $this::INSERT_SUCCESS
                        ), $this::HTTP_CREATED
                    );
                }
                // False Status
                $this->response(
                    array(
                        "status" => FALSE,
                        "message" => $this::INSERT_FAILED
                    ), $this::HTTP_INTERNAL_ERROR
                );
        }
    }

    public function index_get() {
        $this->load->model("PromoModel");

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
                if($this->PromoModel->id_exist($id) == 0) {
                    $this->response(
                        array(
                            'status' => FALSE,
                            'message' => $this::INVALID_ID_MESSAGE. " id does not exist"
                        ),
                        $this::HTTP_BAD_REQUEST
                    );
                }

                if($this->PromoModel->delete($id)) {
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
            default:
                $this->response($this->PromoModel->getAll(), $this::HTTP_OK);
        }
    }

    public function index_put() {
        $this->load->model("PromoModel");

        switch($this->put("command")) {
            default:
                $id = $this->put("id");
                $name = $this->put("name");
                $code = $this->put("code");
                $discount = $this->put("discount");
                $type = $this->put("type");
                if(!isset($id)){
                    $this->response(
                        array(
                            'status' => FALSE,
                            'message' => $this::REQUIRED_PARAMETERS." id"
                        ),
                        $this::HTTP_BAD_REQUEST
                    );
                }
                if($this->PromoModel->id_exist($id) == 0) {
                    $this->response(
                        array(
                            'status' => FALSE,
                            'message' => $this::INVALID_ID_MESSAGE. " id does not exist"
                        ),
                        $this::HTTP_BAD_REQUEST
                    );
                }
                $datas = array();
                $datas = array_merge($datas, array('id' => $id));
                if(isset($name)) {
                    $datas = array_merge($datas, array("name" => $name));
                }
                if(isset($code)) {
                    $datas = array_merge($datas, array("code" => $code));
                }
                if(isset($discount)) {
                    $datas = array_merge($datas, array("discount" => $discount));
                }
                if(isset($type)) {
                    $datas = array_merge($datas, array("type" => $type));
                }
                if($this->PromoModel->update($id, $datas)) {
                    $this->response(
                        array(
                            'status' => TRUE,
                            'message' => $this::UPDATE_SUCCESS
        
                        ),
                        $this::HTTP_OK
                    );
                }
                $this->response(
                    array(
                        'status' => FALSE,
                        'message' => $this::UPDATE_FAILED
    
                    ),
                    $this::HTTP_INTERNAL_ERROR
                );
        }
    }

}