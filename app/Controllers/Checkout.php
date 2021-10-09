<?php

namespace App\Controllers;

use App\Models\OrderDetailModel;
use App\Models\OrdersModel;
use App\Models\productModel;
require_once('mail/PHPMailerAutoload.php');
use PHPMailer;


class Checkout extends BaseController
{
    public function index()
    {

        session_start();
        if (empty($_SESSION['customer'])) {
            return redirect()->to(base_url() . '/login');
        }
        $user_id= $_SESSION['customer']["id"];
        if ($this->request->getMethod() == 'post') {
            //phần cấu hình gửi mail
            // $mail = new PHPMailer;
            // $mail->isSMTP();                                      // Set mailer to use SMTP
            // $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
            // $mail->SMTPAuth = true;                               // Enable SMTP authentication
            // $mail->Username = 'cobetenlinh@gmail.com';                 // SMTP username
            // $mail->Password = 'hruvgkvuuaxjwbcz';                           // SMTP password
            // $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            // $mail->Port = 587;
            // $mail->CharSet = "UTF-8";              
            // $content='';


            $cPay= $this->request->getVar('cPay');
            $cName = $this->request->getVar('cName');
            $cPhone = $this->request->getVar('cPhone');
            $cEmail = $this->request->getVar('cEmail');
            $notes = $this->request->getVar('notes');

            $address = $this->request->getVar('cAddress');
            $subtotal = $this->request->getVar('subtotal');
            $productIds = $this->request->getVar('productIds');
            $productQuantities = $this->request->getVar('productQuantities');
            $productPrices = $this->request->getVar('productPrices');
            $productsCheckout = [];
            foreach ($productIds as $key => $value) {
                $products = [];
                array_push($products, $productIds[$key], $productQuantities[$key], $productPrices[$key]);
                array_push($productsCheckout, $products);
            }
            // var_dump($productsCheckout);
            $orderModel = new OrdersModel();
            $order_detailModel = new OrderDetailModel();

            $product_model= new productModel();
            foreach ($productsCheckout as $item) {
                $bef_amount = $product_model->getProdcutbyId($item[0]);
                if($item[1]> $bef_amount['quantity']){
                     $data['message'] = 'fail';
                     echo view("checkout", $data);
                }
                else{
                     $data_insert = [
                    'user_id' => $user_id,
                    'fullname' => $cName,
                    'phone' => $cPhone,
                    'email' => $cEmail,
                    'note' => $notes,
                    'paid_status' => 0,
                    'status' => 1,
                    'payments' => $cPay,
                    'create_on' => date("Y-m-d H:i:s"),
                    'shipping_status' => 0,
                    'bill_address' => $address
                                    ];
                    $newOrder = $orderModel->insert($data_insert);

                    foreach ($productsCheckout as $item) {
                        $total +=intval($item[1]) * intval($item[2]);
                        
                        $data_insert = [
                            'order_id' => $newOrder,
                            'product_id' => $item[0],
                            'product_amount' => $item[1],
                            'total_price' => intval($item[1]) * intval($item[2])
                                    ];
                        // var_dump($item,$data_insert);
                        // die();
                        $newOrderDetail = $order_detailModel->insert($data_insert);
                    }
                    if ($newOrderDetail > 0) {
                          $data['message'] = 'success';
                        return view("checkout", $data);
                    }
                    else {
                        $data['message'] = 'fail';
                        echo view("checkout", $data);
                    }
                }

            }
                
        }
        else{echo view("checkout");}
    }
}