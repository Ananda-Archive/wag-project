<?php

use chriskacerguis\RestServer\RestController;

class Orderhistory extends RestController {

    function __construct() {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
    }

    public function index_post() {
        $this->load->model("OrderHistoryModel");
        $this->load->model("ItemOrderModel");
        $this->load->model("CustomerModel");
        $linkWeb = "http://localhost:8080/payment";
        switch($this->post("command")) {
            case "remindCustomer":
                $result = $this->OrderHistoryModel->remind();
                $index = 0;
                $email = [];
                foreach($result as $row) {
                    $customer = $this->CustomerModel->get_by_id($row['user_id'])[0];
                    $temp = array_merge($result[$index], array('customer' => $customer));
                    $result[$index] = $temp;
                    $index++;

                    $from = 'shiningassvj@gmail.com';
                    $subject = "INVOICE ORDER - REMINDER";
                    $link = $linkWeb."/".$row['id'];
                    $content = '
                        <html>
                            <head></head>
                            <body style="padding:40px 0px">
                                <table max-width="800px" style="margin: auto;border-spacing:0; border-radius:10px; border: 1px solid black; padding: 20px 2%">
                                    <table max-width="750px" style="margin: auto;">
                                        <tr>
                                            <td style="background:#FFF; text-align:center; vertical-align : middle; color: black; font-weight: bold; font-size: 15px;">
                                                <h1>INVOICE</h1>
                                                <span style="margin-top:-20px">'.$row['name'].'</span>
                                            </td>
                                        </tr>
                                    </table>
                                    <table max-width="750px" style="margin: auto;background:#F3F4F5;border-radius:10px; padding: 10px">
                                        <tr>
                                            <td>Invoice</td>
                                            <td>:</td>
                                            <td>'.$row['name'].'</td>
                                        </tr>
                                        <tr>
                                            <td style="vertical-align:top">Rekening</td>
                                            <td style="vertical-align:top">:</td>
                                            <td style="vertical-align:top">
                                                <ul style="vertical-align:top; display: inline-block;margin:0px;padding:0px">
                                                    <li style="vertical-align:top"><b>Bank Mandiri: No. Rekening XXX An. CV GLAM ESTETIKA</b></li>
                                                    <li style="vertical-align:top"><b>Bank BCA: No. Rekening XXX An. CV GLAM ESTETIKA</b></li>
                                                    <li style="vertical-align:top"><b>Bank BNI: No. Rekening XXX An. CV GLAM ESTETIKA</b></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Upload Bukti Pembayaran</td>
                                            <td style="vertical-align:top">:</td>
                                            <td><a href="'.$link.'">'.$link.'</a></td>
                                        </tr>
                                    </table>
                                    <table width="550px" style="margin: auto;margin-top: 10px">
                                        <tr>
                                            <td style="background:#FFF; text-align:center; vertical-align : middle; color: black">
                                                Copyright &#169; '.date('Y').' <a href="WAGOfficial.com">WAG</a>
                                            </td>
                                        </tr>
                                    </table>
                                </table>
                            </body>
                        </html>
                    ';
                    $config = Array(
                        'protocol' => 'smtp',
                        'smtp_host' => 'ssl://smtp.googlemail.com',
                        'smtp_port' => 465,
                        'smtp_user' => $from,
                        'smtp_pass' => 'sodebade',
                        'mailtype'  => 'html', 
                        'charset'   => 'iso-8859-1'
                    );
                    $this->load->library("email",$config);
                    $this->email->set_newline("\r\n");
                    $this->email->from($from);
                    $this->email->to($customer['email']);
                    $this->email->subject($subject);
                    $this->email->message($content);
                    if($this->email->send()) {
                        array_push($email, $customer['email']);
                    //    continue;
                    } else {
                        $this->response(
                            array(
                                "status" => FALSE,
                                "message" => $this->email->print_debugger()
                            ), $this::HTTP_INTERNAL_ERROR
                        );
                    }
                }
                $this->response(
                    array(
                        "status" => TRUE,
                        "message" => sizeof($email)." Customers"
                    ), $this::HTTP_OK
                );
            case "post":
                $user_id = $this->post("userId");
                $courier = $this->post("courier");
                $courier_type = $this->post("courierType");
                $shipping_cost = $this->post("shippingCost");
                $promo_id = $this->post("promoId");
                $item_orders = $this->post("itemOrders");
                $note = $this->post("note");
                $email = $this->post("email");
                $status = 0;
                $bukti = null;

                if(!isset($user_id) || !isset($courier) || !isset($courier_type) || !isset($shipping_cost) || !isset($item_orders) || !isset($email)) {
                    $required_parameters = [];
                    if(!isset($user_id)) array_push($required_parameters, "user_id");
                    if(!isset($courier)) array_push($required_parameters, "courier");
                    if(!isset($courier_type)) array_push($required_parameters, "courier_type");
                    if(!isset($shipping_cost)) array_push($required_parameters, "shipping_cost");
                    if(!isset($item_orders)) array_push($required_parameters, "item_orders");
                    if(!isset($email)) array_push($required_parameters, "email");

                    $this->response(
                        array(
                            "status" => FALSE,
                            "message" => $this::REQUIRED_PARAMETERS . implode(', ', $required_parameters)
                        ), $this::HTTP_BAD_REQUEST
                    );
                }

                do {
                    $id = bin2hex(random_bytes(8));
                } while($this->OrderHistoryModel->id_exist($id));
                $total = 0;
                foreach($item_orders as $item) {
                    $total += ($item["price"]*$item["amount"]);
                }
                $total+=$shipping_cost;

                date_default_timezone_set('Asia/Jakarta');
                $name = "INV/WAG/".date('dmYHis');

                if($this->OrderHistoryModel->create($id, $name, $user_id, $courier, $courier_type, $shipping_cost, $promo_id, $note, $status, $bukti)){
                    if($this->ItemOrderModel->create_item_order($id, $item_orders)) {
                        // Sending Email
                        $from = 'shiningassvj@gmail.com';
                        $subject = "INVOICE ORDER";
                        $link = $linkWeb."/".$id;
                        $content = '
                            <html>
                                <head></head>
                                <body style="padding:40px 0px">
                                    <table max-width="800px" style="margin: auto;border-spacing:0; border-radius:10px; border: 1px solid black; padding: 20px 2%">
                                        <table max-width="750px" style="margin: auto;">
                                            <tr>
                                                <td style="background:#FFF; text-align:center; vertical-align : middle; color: black; font-weight: bold; font-size: 15px;">
                                                    <h1>INVOICE</h1>
                                                    <span style="margin-top:-20px">'.$name.'</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="background:#FFF;padding:2%; text-align:left; color: black;">
                                                    <p>Here is yout Invoice Detail:</p>
                                                </td>
                                            </tr>
                                        </table>
                                        <table max-width="750px" style="margin: auto;background:#F3F4F5;border-radius:10px; padding: 10px">
                                            <tr>
                                                <td>Invoice</td>
                                                <td>:</td>
                                                <td>'.$name.'</td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align:top">Rekening</td>
                                                <td style="vertical-align:top">:</td>
                                                <td style="vertical-align:top">
                                                    <ul style="vertical-align:top; display: inline-block;margin:0px;padding:0px">
                                                        <li style="vertical-align:top"><b>Bank Mandiri: No. Rekening XXX An. CV GLAM ESTETIKA</b></li>
                                                        <li style="vertical-align:top"><b>Bank BCA: No. Rekening XXX An. CV GLAM ESTETIKA</b></li>
                                                        <li style="vertical-align:top"><b>Bank BNI: No. Rekening XXX An. CV GLAM ESTETIKA</b></li>
                                                    </ul>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Upload Bukti Pembayaran</td>
                                                <td style="vertical-align:top">:</td>
                                                <td><a href="'.$link.'">'.$link.'</a></td>
                                            </tr>
                                        </table>
                                        <table width="550px" style="margin: auto;margin-top: 10px">
                                            <tr>
                                                <td style="background:#FFF; text-align:center; vertical-align : middle; color: black">
                                                    Copyright &#169; '.date('Y').' <a href="WAGOfficial.com">WAG</a>
                                                </td>
                                            </tr>
                                        </table>
                                    </table>
                                </body>
                            </html>
                        ';
                        $config = Array(
                            'protocol' => 'smtp',
                            'smtp_host' => 'ssl://smtp.googlemail.com',
                            'smtp_port' => 465,
                            'smtp_user' => $from,
                            'smtp_pass' => 'sodebade',
                            'mailtype'  => 'html', 
                            'charset'   => 'iso-8859-1'
                        );
                        $this->load->library("email",$config);
                        $this->email->set_newline("\r\n");
                        $this->email->from($from);
                        $this->email->to($email);
                        $this->email->subject($subject);
                        $this->email->message($content);
                        if($this->email->send()) {
                            $this->response(
                                array(
                                    "status" => TRUE,
                                    "message" => $id
                                ), $this::HTTP_CREATED
                            );
                        } else {
                            $this->response(
                                array(
                                    "status" => FALSE,
                                    "message" => $this->email->print_debugger()
                                ), $this::HTTP_INTERNAL_ERROR
                            );
                        }
                    } else {
                        $this->response(
                            array(
                                "status" => FALSE,
                                "message" => $this::INSERT_FAILED." ERROR IN CREATING ITEM ORDERS"
                            ), $this::HTTP_INTERNAL_ERROR
                        );
                    }
                }
                $this->response(
                    array(
                        "status" => FALSE,
                        "message" => $this::INSERT_FAILED." ERROR IN CREATING ORDER HISTORY"
                    ), $this::HTTP_INTERNAL_ERROR
                );
        }
    }

    public function index_get() {
        $this->load->model("OrderHistoryModel");
        $this->load->model("ItemOrderModel");
        $this->load->model("CustomerModel");
        $this->load->model("ProductModel");
        $this->load->model("PromoModel");
        $this->load->model("ProductImageModel");
        switch($this->get("command")) {
            case "getById":
                $id = $this->get("id");
                $result = $this->OrderHistoryModel->get_by_id($id);
                $item_orders = $this->ItemOrderModel->get_by_order_id($result[0]['id']);
                $items = [];
                foreach($item_orders as $item) {
                    $itemTemp = $this->ProductModel->get_by_id($item['itemId'])[0];
                    $itemTemp['imageLead'] = $this->ProductImageModel->get_by_id($itemTemp['id'], 1);
                    $itemTemp['quantity'] = $item['amount'];
                    $itemTemp['review'] = $item['review'];
                    $itemTemp['reviewMessage'] = $item['reviewMessage'];
                    $itemTemp['itemOrderId'] = $item['id'];
                    array_push($items, $itemTemp);
                }
                $promo = null;
                    if($result[0]['promoId'] != null) {
                        $promo = $this->PromoModel->get_by_id($result[0]['promoId'])[0];
                    }
                $result[0] = array_merge($result[0], array('customer' => $this->CustomerModel->get_by_id($result[0]['userId'])[0]));
                $this->response(array_merge($result[0], array('products' => $items, 'promo' => $promo, 'customer' => $this->CustomerModel->get_by_id($result[0]['userId'])[0])), $this::HTTP_OK);
            default:
                $result = $this->OrderHistoryModel->getAll();
                $index=0;
                foreach($result as $row) {
                    $item_orders = $this->ItemOrderModel->get_by_order_id($row['id']);
                    $items = [];
                    foreach($item_orders as $item) {
                        $itemTemp = $this->ProductModel->get_by_id($item['itemId'])[0];
                        $itemTemp['imageLead'] = $this->ProductImageModel->get_by_id($itemTemp['id'], 1);
                        $itemTemp['quantity'] = $item['amount'];
                        array_push($items, $itemTemp);
                    }
                    $customer = $this->CustomerModel->get_by_id($row['userId'])[0];
                    $promo = null;
                    if($row['promoId'] != null) {
                        $promo = $this->PromoModel->get_by_id($row['promoId'])[0];
                    }
                    $temp = array_merge($result[$index], array('products' => $items, 'customer' => $customer, 'promo' => $promo));
                    $result[$index] = $temp;
                    $index++;
                }
                $this->response($result, $this::HTTP_OK);

        }
    }

    public function index_put() {
        $this->load->model("OrderHistoryModel");
        switch($this->put("command")) {
            case "uploadBukti":
                $id = $this->put("id");
                $link = $this->put("link");

                if(!isset($id)){
                    $this->response(
                        array(
                            'status' => FALSE,
                            'message' => $this::REQUIRED_PARAMETERS." id"
                        ),
                        $this::HTTP_BAD_REQUEST
                    );
                }

                if($this->OrderHistoryModel->uploadBukti($id, $link)) {
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
            case "changeStatus":
                $status = $this->put("status");
                $id = $this->put("id");
                if(!isset($id)){
                    $this->response(
                        array(
                            'status' => FALSE,
                            'message' => $this::REQUIRED_PARAMETERS." id"
                        ),
                        $this::HTTP_BAD_REQUEST
                    );
                }
                if($this->OrderHistoryModel->id_exist($id) == 0) {
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
                if(isset($status)) {
                    $datas = array_merge($datas, array("status" => $status));
                }
                if($this->OrderHistoryModel->update($id, $datas)) {
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

            case "sendResi":
                $resi = $this->put("resi");
                $id = $this->put("id");
                if(!isset($id)){
                    $this->response(
                        array(
                            'status' => FALSE,
                            'message' => $this::REQUIRED_PARAMETERS." id"
                        ),
                        $this::HTTP_BAD_REQUEST
                    );
                }
                if($this->OrderHistoryModel->id_exist($id) == 0) {
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
                if(isset($resi)) {
                    $datas = array_merge($datas, array("resi" => $resi));
                }
                if($this->OrderHistoryModel->update($id, $datas)) {
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