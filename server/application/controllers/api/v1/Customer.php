<?php

use chriskacerguis\RestServer\RestController;

class Customer extends RestController {

    function __construct() {
        parent::__construct();
    }

    public function index_post() {
        $this->load->model("CustomerModel");
        switch($this->post("command")) {
            case "register":
                $name = $this->post("name");
                $email = $this->post("email");
                $password = $this->post("password");
                $alamat = $this->post("alamat");
                $kecamatan = $this->post("kecamatan");
                $kota = $this->post("kota");
                $provinsi = $this->post("provinsi");
                $kode_pos = $this->post("kodePos");
                $phone = $this->post("phone");
                $is_member = $this->post("isMember");

                if(!isset($name) || !isset($email) || !isset($is_member) || !isset($alamat) || !isset($kecamatan) || !isset($kota) || !isset($provinsi) || !isset($kode_pos) || !isset($phone)) {
                    $required_parameters = [];
                    if(!isset($name)) array_push($required_parameters, "name");
                    if(!isset($email)) array_push($required_parameters, "email");
                    if(!isset($is_member)) array_push($required_parameters, "is_member");
                    if(!isset($alamat)) array_push($required_parameters, "alamat");
                    if(!isset($kecamatan)) array_push($required_parameters, "kecamatan");
                    if(!isset($kota)) array_push($required_parameters, "kota");
                    if(!isset($provinsi)) array_push($required_parameters, "provinsi");
                    if(!isset($kode_pos)) array_push($required_parameters, "kode_pos");
                    if(!isset($phone)) array_push($required_parameters, "phone");

                    $this->response(
                        array(
                            "status" => FALSE,
                            "message" => $this::REQUIRED_PARAMETERS . implode(', ', $required_parameters)
                        ), $this::HTTP_BAD_REQUEST
                    );
                }

                // check if email exist

                if($is_member!=0){
                    if($this->CustomerModel->alreadyRegistered($email)) {
                        $this->response(
                            array(
                                "status" => FALSE,
                                "message" => "E-mail Already Registered"
                            ), $this::HTTP_CONFLICT
                        );
                    }
                    // Hashing Password using BCRYPT
                    // cost => 10 means 2^10 times hashing
                    $options = [
                        'cost' => 10,
                    ];
                    $password = password_hash($password, PASSWORD_DEFAULT, $options);
                } else {
                    $password = null;
                }

                // Creating unique ID
                // Using do while instead of while do to reducing declaration
                do {
                    $id = bin2hex(random_bytes(8));
                } while($this->CustomerModel->id_exist($id));

                if($this->CustomerModel->create_user($id, $name, $email, $password, $alamat, $kecamatan, $kota, $provinsi, $is_member, $kode_pos, $phone)) {
                    $this->response(
                        array(
                            "status" => TRUE,
                            "message" => $id
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

            case "login":
                $email = $this->post("email");
                $password = $this->post("password");
                $auth = $this->CustomerModel->auth($email,$password);
                // User not found
                if($auth == -2) {
                    $this->response(
                        array(
                            'status' => FALSE,
                            'message' => $this::LOGIN_FAILED
                        ), $this::HTTP_INTERNAL_ERROR
                    );
                }
                // Wrong Password
                if($auth == -1) {
                    $this->response(
                        array(
                            'status' => FALSE,
                            'message' => $this::PASSWORD_FAILED
                        ), $this::HTTP_UNAUTHORIZED
                    );
                }
                // Success
                $this->response(
                    array(
                        'status' => TRUE,
                        'message' => $this->CustomerModel->get_by_id($auth)
                    ), $this::HTTP_OK
                );
        }
    }

    public function index_get() {
        $this->load->model("CustomerModel");
        switch($this->get("command")) {
            case "getById":
                $id = $this->get("id");
                $this->response($this->CustomerModel->get_by_id($id),$this::HTTP_OK);
        }
    }

}