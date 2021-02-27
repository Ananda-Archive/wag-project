<?php

use chriskacerguis\RestServer\RestController;

class SelfDiscovery extends RestController {

    function __construct() {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
    }

    public function index_post() {
        $this->load->model("SelfDiscoveryModel");
        
        switch($this->post("command")) {
            default:
                $title = $this->post("title");
                $content = $this->post("content");
                $image = $this->post("image");

                if(!isset($title) || !isset($content)) {
                    $required_parameters = [];
                    if(!isset($title)) array_push($required_parameters, "title");
                    if(!isset($content)) array_push($required_parameters, "content");

                    $this->response(
                        array(
                            "status" => FALSE,
                            "message" => $this::REQUIRED_PARAMETERS . implode(', ', $required_parameters)
                        ), $this::HTTP_BAD_REQUEST
                    );
                }

                if($this->SelfDiscoveryModel->create($title, $content, $image)) {
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
        $this->load->model("SelfDiscoveryModel");

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
                if($this->SelfDiscoveryModel->id_exist($id) == 0) {
                    $this->response(
                        array(
                            'status' => FALSE,
                            'message' => $this::INVALID_ID_MESSAGE. " id does not exist"
                        ),
                        $this::HTTP_BAD_REQUEST
                    );
                }

                if($this->SelfDiscoveryModel->delete($id)) {
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
                $this->response($this->SelfDiscoveryModel->get_by_id($this->get("id"))[0], $this::HTTP_OK);
            default:
                $this->response($this->SelfDiscoveryModel->getAll(), $this::HTTP_OK);
        }

    }

    public function index_put() {
        $this->load->model("SelfDiscoveryModel");

        switch($this->put("command")) {
            default:
                $id = $this->put("id");
                $title = $this->put("title");
                $content = $this->put("content");
                $image = $this->put("image");
                if(!isset($id)){
                    $this->response(
                        array(
                            'status' => FALSE,
                            'message' => $this::REQUIRED_PARAMETERS." id"
                        ),
                        $this::HTTP_BAD_REQUEST
                    );
                }
                if($this->SelfDiscoveryModel->id_exist($id) == 0) {
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
                if(isset($title)) {
                    $datas = array_merge($datas, array("title"=>$title));
                }
                if(isset($content)) {
                    $datas = array_merge($datas, array("content"=>$content));
                }
                if(isset($image)) {
                    $datas = array_merge($datas, array("image"=>$image));
                }
                if($this->SelfDiscoveryModel->update($id, $datas)) {
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