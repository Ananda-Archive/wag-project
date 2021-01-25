<?php

use chriskacerguis\RestServer\RestController;

class User extends RestController {

    function __construct() {
        parent::__construct();
    }

    public function index_post() {
        $this->load->model("UserModel");
        switch($this->post("command")) {
            // Login and Auth Process
            case "login":
                $username = $this->post("username");
                $password = $this->post("password");
                $auth = $this->UserModel->auth($username,$password);
                // User Not Verified Yet
                if($auth == -1) {
                    $this->response(
                        array(
                            'status' => FALSE,
                            'message' => $this::VERIFIED_FAILED
                        ), $this::HTTP_FORBIDDEN
                    );
                }
                // Wrong Password
                if($auth == -2) {
                    $this->response(
                        array(
                            'status' => FALSE,
                            'message' => $this::PASSWORD_FAILED
                        ), $this::HTTP_UNAUTHORIZED
                    );
                }
                // User Not Found
                if($auth == -3) {
                    $this->response(
                        array(
                            'status' => FALSE,
                            'message' => $this::LOGIN_FAILED
                        ), $this::HTTP_INTERNAL_ERROR
                    );
                }
                // Login Success
                $this->response(
                    array(
                        'status' => TRUE,
                        'message' => $this->UserModel->get_by_id($auth)
                    ), $this::HTTP_OK
                );
                break;
                
            default:
                $name = $this->post("name");
                $email = $this->post("email");
                $phone = $this->post("phone");
                $username = $this->post("username");
                $password = $this->post("password");
                $verified = $this->post("verified");
                $role = $this->post("role");
                $privilege = $this->post("privilege");

                // Checking Required Parameter
                if( !isset($name) || !isset($email) || !isset($phone) || !isset($username) || !isset($password) || !isset($verified) || !isset($role)) {
                    $required_parameters = [];
                    if(!isset($name)) array_push($required_parameters, "name");
                    if(!isset($email)) array_push($required_parameters, "email");
                    if(!isset($phone)) array_push($required_parameters, "phone");
                    if(!isset($username)) array_push($required_parameters, "username");
                    if(!isset($password)) array_push($required_parameters, "password");
                    if(!isset($verified)) array_push($required_parameters, "verified");
                    if(!isset($role)) array_push($required_parameters, "role");

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

                // Create User
                // Why can do if else with this? coz create user will either return 0 if failed or 1 if success. I can use this as Boolean status
                // True Status
                if($this->UserModel->create_user($id, $name, $email, $phone, $username, $password, $verified, $role, $privilege)) {
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

    public function index_get() {
        $this->load->model("UserModel");
        switch($this->get("command")) {
            // Get All unverified User
            case "unverified":
                $this->response($this->UserModel->get_unverified(), $this::HTTP_OK);
            // Get Specific Id
            case "getid":
                $id = $this->get("id");
                $this->response($this->UserModel->get_by_id($id), $this::HTTP_OK);
            // Get All Dosen
            case "getDosen":
                $this->response($this->UserModel->get_dosen(), $this::HTTP_OK);
            // Delete User
            case "deleteUser":
                $id = $this->input->get("id");
                if(!isset($id)) {
                    $this->response(
                        array(
                            "status" => FALSE,
                            "message" => $this::REQUIRED_PARAMETERS . " id"
                        ), $this::HTTP_BAD_REQUEST
                    );
                }
                if(!$this->UserModel->id_exist($id)) {
                    $this->response(
                        array(
                            "status" => FALSE,
                            "message" => $this::INVALID_ID_MESSAGE
                        ), $this::HTTP_NOT_FOUND
                    );
                }

                if($this->UserModel->delete_user($id)) {
                    $this->response(
                        array(
                            "status" => TRUE,
                            "message" => $this::DELETE_SUCCESS
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
            // Get All User Without Any Command
            default:
                $this->response($this->UserModel->get_all(), $this::HTTP_OK);
        }
    }

    public function index_put() {
        $this->load->model("UserModel");
        switch($this->put("command")) {
            case "verify":
                $users = $this->put("users");
                $vor = $this->put("vor");
                if(!isset($users)) {
                    $this->response(
                        array(
                            "status" => FALSE,
                            "message" => $this::REQUIRED_PARAMETERS
                        ), $this::HTTP_BAD_REQUEST
                    );
                }
                if(!isset($vor)) {
                    $this->response(
                        array(
                            "status" => FALSE,
                            "message" => $this::REQUIRED_PARAMETERS
                        ), $this::HTTP_BAD_REQUEST
                    );
                }
                if($vor == 1) {
                    if($this->UserModel->verify($users)) {
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
                } else {
                    if($vor == 0) {
                        if($this->UserModel->reject($users)) {
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
            default:
                $id = $this->put("id");
                $name = $this->put("name");
                $email = $this->put("email");
                $phone = $this->put("phone");
                $username = $this->put("username");
                $role = $this->put("role");
                $privilege = $this->put("privilege");

                $datas = array();

                if(!isset($id)) {
                    $this->response(
                        array(
                            "status" => FALSE,
                            "message" => $this::REQUIRED_PARAMETERS . " id"
                        ), $this::HTTP_BAD_REQUEST
                    );
                }
                if(!$this->UserModel->id_exist($id)) {
                    $this->response(
                        array(
                            "status" => FALSE,
                            "message" => $this::INVALID_ID_MESSAGE
                        ), $this::HTTP_NOT_FOUND
                    );
                }
                $datas = array_merge($datas, array('id' => $id));
                if(isset($name)) {
                    $datas = array_merge($datas, array("name" => $name));
                }
                if(isset($email)) {
                    $datas = array_merge($datas, array("email" => $email));
                }
                if(isset($phone)) {
                    $datas = array_merge($datas, array("phone" => $phone));
                }
                if(isset($username)) {
                    $datas = array_merge($datas, array("username" => $username));
                }
                if(isset($role)) {
                    $datas = array_merge($datas, array("role" => $role));
                }
                if(isset($privilege)) {
                    $datas = array_merge($datas, array("privilege" => $privilege));
                }
                if($this->UserModel->update_user($id, $datas)) {
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

    public function index_delete() {
        $this->load->model("UserModel");
        

    }

}