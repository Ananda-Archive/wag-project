<?php

use chriskacerguis\RestServer\RestController;

class Rajaongkir extends RestController {

    function __construct() {
        parent::__construct();
    }

    function index_get() {
        switch($this->get("command")) {
            case "getCustomerInfo":
                $curl = curl_init();
                $city_id = $this->get("cityId");
                $subdistrict_id = $this->get("subdistrictId");
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://pro.rajaongkir.com/api/subdistrict?id=".$subdistrict_id."city=".$city_id,
                    CURLOPT_SSL_VERIFYHOST => 0,
                    CURLOPT_SSL_VERIFYPEER => 0,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => array(
                    "key: f8b3826f4d75bd3124336030d72d45d4"
                    ),
                ));
                
                $response = curl_exec($curl);
                $err = curl_error($curl);
                
                curl_close($curl);
                
                if ($err) {
                    $this->response(
                        array(
                            'status' => FALSE,
                            'message' => $err
                        ), $this::HTTP_BAD_REQUEST
                    );
                } else {
                    $this->response(
                        array(
                            'status' => TRUE,
                            'message' => json_decode($response, TRUE)
                        ), $this::HTTP_OK
                    );
                }
            case "getAllProvince":
                $curl = curl_init();

                curl_setopt_array($curl, array(
                  CURLOPT_URL => "https://pro.rajaongkir.com/api/province",
                  CURLOPT_SSL_VERIFYHOST => 0,
                  CURLOPT_SSL_VERIFYPEER => 0,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 30,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => "GET",
                  CURLOPT_HTTPHEADER => array(
                    "key: f8b3826f4d75bd3124336030d72d45d4"
                  ),
                ));
                
                $response = curl_exec($curl);
                $err = curl_error($curl);
                
                curl_close($curl);
                
                if ($err) {
                    $this->response(
                        array(
                            'status' => FALSE,
                            'message' => $err
                        ), $this::HTTP_BAD_REQUEST
                    );
                } else {
                    $this->response(
                        array(
                            'status' => TRUE,
                            'message' => json_decode($response, TRUE)
                        ), $this::HTTP_OK
                    );
                }

            case "getAllCity":
                $curl = curl_init();
                $province_id = $this->get("provinceId");
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://pro.rajaongkir.com/api/city?province=".$province_id,
                    CURLOPT_SSL_VERIFYHOST => 0,
                    CURLOPT_SSL_VERIFYPEER => 0,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => array(
                    "key: f8b3826f4d75bd3124336030d72d45d4"
                    ),
                ));
                
                $response = curl_exec($curl);
                $err = curl_error($curl);
                
                curl_close($curl);
                
                if ($err) {
                    $this->response(
                        array(
                            'status' => FALSE,
                            'message' => $err
                        ), $this::HTTP_BAD_REQUEST
                    );
                } else {
                    $this->response(
                        array(
                            'status' => TRUE,
                            'message' => json_decode($response, TRUE)
                        ), $this::HTTP_OK
                    );
                }

            case "getAllSubdistrict":
                $curl = curl_init();
                $city_id = $this->get("cityId");
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://pro.rajaongkir.com/api/subdistrict?city=".$city_id,
                    CURLOPT_SSL_VERIFYHOST => 0,
                    CURLOPT_SSL_VERIFYPEER => 0,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => array(
                    "key: f8b3826f4d75bd3124336030d72d45d4"
                    ),
                ));
                
                $response = curl_exec($curl);
                $err = curl_error($curl);
                
                curl_close($curl);
                
                if ($err) {
                    $this->response(
                        array(
                            'status' => FALSE,
                            'message' => $err
                        ), $this::HTTP_BAD_REQUEST
                    );
                } else {
                    $this->response(
                        array(
                            'status' => TRUE,
                            'message' => json_decode($response, TRUE)
                        ), $this::HTTP_OK
                    );
                }

            case "getOngkir":
                $origin = $this->get("origin");
                $destination = $this->get("destination");
                $weight = $this->get("weight");
                $courier = $this->get("courier");
                $curl = curl_init();
                curl_setopt_array($curl, array(
                CURLOPT_URL => "https://pro.rajaongkir.com/api/cost",
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "origin=".$origin."&originType=subdistrict&destination=".$destination."&destinationType=subdistrict&weight=".$weight."&courier=".$courier,
                CURLOPT_HTTPHEADER => array(
                    "content-type: application/x-www-form-urlencoded",
                    "key: f8b3826f4d75bd3124336030d72d45d4"
                ),
                ));

                $response = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                if ($err) {
                    $this->response(
                        array(
                            'status' => FALSE,
                            'message' => $err
                        ), $this::HTTP_BAD_REQUEST
                    );
                } else {
                    $this->response(
                        array(
                            'status' => TRUE,
                            'message' => json_decode($response, TRUE)
                        ), $this::HTTP_OK
                    );
                }
        }
    }

}