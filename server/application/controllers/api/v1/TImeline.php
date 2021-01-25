<?php

use chriskacerguis\RestServer\RestController;

class Timeline extends RestController {

    function __construct() {
        parent::__construct();
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, PATCH, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding, Authorization");
        if ( "OPTIONS" === $_SERVER['REQUEST_METHOD'] ) {
            die();
        }
    }

    public function index_post() {
        $this->load->model("TimelineModel");
        $date_start = $this->post("dateStart");
        $date_end = $this->post("dateEnd");

        // Checking Required Parameter
        if(!isset($date_start) || !isset($date_end)) {
            $required_parameters = [];
            if(!isset($date_start)) array_push($required_parameters,"date_start");
            if(!isset($date_end)) array_push($required_parameters,"date_end");
            $this->response(
                array(
                    "status" => FALSE,
                    "message" => $this::REQUIRED_PARAMETERS . implode(', ', $required_parameters)
                ), $this::HTTP_BAD_REQUEST
            );
        }

        // Creating unique ID
        // Using do while instead of while do to reducing declaration
        do {
            $id = bin2hex(random_bytes(16));
        } while($this->TimelineModel->id_exist($id));

        if($this->TimelineModel->create_timeline($id, $date_start, $date_end)) {
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
                "status" => TRUE,
                "message" => $this::INSERT_FAILED
            ), $this::HTTP_INTERNAL_ERROR
        );
    }

    public function index_get() {
        $this->load->model("TimelineModel");
        switch($this->get("command")) {
            // Get All Timeline Without Any Command
            case "getCurrent":
                $this->response($this->TimelineModel->get_current(), $this::HTTP_OK);
            default:
                $this->response($this->TimelineModel->get_all(), $this::HTTP_OK);
        }
    }

    public function index_put() {
        $this->load->model("TimelineModel");
        $id = $this->put("id");
        $date_start = $this->put("dateStart");
        $date_end = $this->put("dateEnd");

        $datas = array();

        if(!isset($id)) {
            $this->response(
                array(
                    "status" => FALSE,
                    "message" => $this::REQUIRED_PARAMETERS . " id"
                ), $this::HTTP_BAD_REQUEST
            );
        }
        if(!$this->TimelineModel->id_exist($id)) {
            $this->response(
                array(
                    "status" => FALSE,
                    "message" => $this::INVALID_ID_MESSAGE
                ), $this::HTTP_NOT_FOUND
            );
        }
        $datas = array_merge($datas, array('id' => $id));
        if(isset($date_start)){
            $datas = array_merge($datas, array('date_start' => $date_start));
        }
        if(isset($date_end)){
            $datas = array_merge($datas, array('date_end' => $date_end));
        }
        if($this->TimelineModel->update_timeline($id, $datas)) {
            $this->response(
                array(
                    "status" => TRUE,
                    "message" => $this::UPDATE_SUCCESS
                ), $this::HTTP_OK
            );
        }
        // False Status
        $this->response(
            array(
                "status" => TRUE,
                "message" => $this::UPDATE_FAILED
            ), $this::HTTP_INTERNAL_ERROR
        );
    }

}