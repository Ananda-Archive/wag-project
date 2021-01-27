<?php

use chriskacerguis\RestServer\RestController;

class User extends RestController {

    function __construct() {
        parent::__construct();
    }

    public function index_post() {
        $this->load->model("UserModel");
        switch($this->post("command")) {
            // Login
            case "login":
                $username = $this->post("username");
                $password = $this->post("password");
                $auth = $this->UserModel->auth($username,$password);
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
                        'message' => $this->UserModel->get_by_id($auth)
                    ), $this::HTTP_OK
                );
                break;
            default:
                $username = $this->post("username");
                $password = $this->post("password");

                if(!isset($username) || !isset($password)) {
                    $required_parameters = [];
                    if(!isset($username)) array_push($required_parameters, "username");
                    if(!isset($password)) array_push($required_parameters, "password");
                    $this->response(
                        array(
                            "status" => FALSE,
                            "message" => $this::REQUIRED_PARAMETERS . implode(', ', $required_parameters)
                        ), $this::HTTP_BAD_REQUEST
                    );
                }

                // Hashing Password using BCRYPT
                // cost => 10 means 2^10 times hashing
                $options = [
                    'cost' => 10,
                ];
                $password = password_hash($password, PASSWORD_DEFAULT, $options);

                // Creating unique ID
                // Using do while instead of while do to reducing declaration
                do {
                    $id = bin2hex(random_bytes(16));
                } while($this->UserModel->id_exist($id));

                // Check if username exist
                if($this->UserModel->username_exist($username)) {
                    $this->response(
                        array(
                            "status" => FALSE,
                            "message" => $this::USERNAME_EXIST
                        ), $this::HTTP_CONFLICT
                    );
                }

                if($this->UserModel->create_user($id, $username, $password)) {
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
    }

}