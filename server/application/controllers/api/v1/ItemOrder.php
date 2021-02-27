<?php

use chriskacerguis\RestServer\RestController;

class ItemOrder extends RestController {

    function __construct() {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
    }

    public function index_get() {
        $this->load->model("ItemOrderModel");
        $this->load->model("OrderHistoryModel");
        $this->load->model("CustomerModel");
        $this->load->model("ProductModel");

            // $id = $this->get("id");
            // $result = $this->OrderHistoryModel->getAll();
            // $index=0;
            // foreach($result as $row) {
            //     $item_orders = $this->ItemOrderModel->get_by_order_id($row['id']);
            //     $items = [];
            //     foreach($item_orders as $item) {
            //         $itemTemp = $this->ProductModel->get_by_id($item['itemId'])[0];
            //         $itemTemp['imageLead'] = $this->ProductImageModel->get_by_id($itemTemp['id'], 1);
            //         $itemTemp['quantity'] = $item['amount'];
            //         array_push($items, $itemTemp);
            //     }
            //     $customer = $this->CustomerModel->get_by_id($row['userId'])[0];
            //     $promo = null;
            //     if($row['promoId'] != null) {
            //         $promo = $this->PromoModel->get_by_id($row['promoId'])[0];
            //     }
            //     $temp = array_merge($result[$index], array('products' => $items, 'customer' => $customer, 'promo' => $promo));
            //     $result[$index] = $temp;
            //     $index++;
            // }
            // $this->response($result, $this::HTTP_OK);
        switch($this->get("command")) {
            default:
                $result = $this->ItemOrderModel->getAll();
                $index=0;
                foreach($result as $row) {
                    $order_history = $this->OrderHistoryModel->get_by_id($row['oh_id'])[0];
                    $customer = $this->CustomerModel->get_by_id($order_history['userId'])[0];
                    $product = $this->ProductModel->get_by_id($row['item_id'])[0];
                    $temp = array_merge($result[$index], array('orderHistory' => $order_history, 'customer' => $customer, 'product' => $product));
                    $result[$index] = $temp;
                    $index++;
                }
                $this->response($result, $this::HTTP_OK);
        }

    }

    public function index_put() {
        $this->load->model("ItemOrderModel");
        switch($this->put("command")) {
            case "writeReview":
                $review = $this->put("review");
                $review_message = $this->put("reviewMessage");
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
                if($this->ItemOrderModel->id_exist($id) == 0) {
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
                if(isset($review)) {
                    $datas = array_merge($datas, array("review" => $review));
                }
                if(isset($review_message)) {
                    $datas = array_merge($datas, array("review_message" => $review_message));
                }

                if($this->ItemOrderModel->update($id, $datas)) {
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