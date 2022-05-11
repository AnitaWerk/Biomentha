<?php
date_default_timezone_set("America/Mexico_City");
require_once 'vendor/autoload.php';

// include database configuration file
include_once 'config/constants.php';
include_once 'include/common.php';
MercadoPago\SDK::setAccessToken(MP_ACCESS_TOKEN); // Production or SandBox AccessToken

// Iniciamos la clase de la cesta
include_once 'session.php';
$cart = new Cart;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    if (isset($_REQUEST['action']) && !empty($_REQUEST['action'])){

        if ($_REQUEST['action'] == 'addToCart' && !empty($_REQUEST['id'])){
            $productID = $_REQUEST['id'];
            $variante = $_REQUEST['variante'];
            // get product details
            if (!empty($variante)) {
                $query = $db->query("
                    SELECT v.*, p.name FROM mis_productos_variantes v 
                    JOIN mis_productos p ON v.product_id = p.id
                    WHERE v.id = " . $productID);
                $row = $query->fetch_assoc();
                $itemData = array(
                    'id' => $row['id'],
                    'name' => $row['name'] . ' - ' . $row['size'],
                    'price' => $row['price'],
                    'qty' => 1
                );
            } else {
                $query = $db->query("SELECT * FROM mis_productos WHERE id = " . $productID);
                $row = $query->fetch_assoc();
                $itemData = array(
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'price' => $row['price'],
                    'qty' => 1
                );
            }
            


            $insertItem = $cart->insert($itemData);
            $redirectLoc = $insertItem ? 'cart.php' : 'index.php';
            header("Location: " . $redirectLoc);
        } elseif ($_REQUEST['action'] == 'updateCartItem' && !empty($_REQUEST['id'])){
            $itemData = array(
                'rowid' => $_REQUEST['id'],
                'qty' => $_REQUEST['qty']
            );
            $updateItem = $cart->update($itemData);
            echo $updateItem ? 'ok' : 'err'; die;
        } elseif ($_REQUEST['action'] == 'removeCartItem' && !empty($_REQUEST['id'])){
            $deleteItem = $cart->remove($_REQUEST['id']);
            header("Location: cart.php");
        } elseif ($_REQUEST['action'] == 'placeOrder' && $cart->total_items() > 0 && !empty($_SESSION['sessCustomerID'])){
            // insert order details into database
            $insertOrder = $db->query("INSERT INTO orden (customer_id, total_price, created, modified) VALUES ('".$_SESSION['sessCustomerID']."', '".$cart->total()."', '".date("Y-m-d H:i:s")."', '".date("Y-m-d H:i:s")."')");
            if ($insertOrder) {
                $orderID = $db->insert_id;
                $sql = '';
                // get cart items
                $cartItems = $cart->contents();
                foreach($cartItems as $item){
                    $sql .= "INSERT INTO orden_articulos (order_id, product_id, quantity) VALUES ('".$orderID."', '".$item['id']."', '".$item['qty']."');";
                }
                // insert order items into database
                $insertOrderItems = $db->multi_query($sql);
                if ($insertOrderItems) {
                    $cart->destroy();
                    header("Location: success.php?id=$orderID");
                } else {
                    header("Location: checkout.php");
                }
            } else {
                header("Location: checkout.php");
            }
        } else {
            header("Location: index.php");
        }
    } else {
        header("Location: index.php");
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($_POST['action'] == 'placeOrder' && $cart->total_items() > 0) {
        $transaction_id = $_POST['ref'] ? $_POST['ref'] : ''; // Referencia bancaria
        $success = 1;
        if ($_POST['bank'] == 'MERCADOPAGO') {
            $payment = new MercadoPago\Payment();
            $payment->transaction_amount = $_POST["transactionAmount"];
            $payment->token = $_POST["token"];
            $payment->description = "Compra en " . COMPANY_NAME . " por: " . $_POST["name"];
            $payment->installments = 1; // Una cuota
            $payment->payment_method_id = $_POST["paymentMethodId"];
            $payment->issuer_id = (int)$_POST['issuer'];

            $payment->payer = array(
                "email" => $_POST["email"]
            );

            $payment->save();
            $transaction_id = $payment->id; // Referencia Mercado Pago

            // Verificamos la respuesta
            $result = [];
            verifyStatusMP($payment->status, $payment->status_detail, $result);

            if ($result[0]) {
                $success = $result[1];
            } else {
                if (empty($result[1])) {
                    header("Location: checkout.php?error=".$payment->status_detail);
                    die;
                } else {
                    header("Location: checkout.php?error=".$result[1]);
                    die;
                }
            }
        }

        $insertCustomer = $db->query("
            INSERT INTO clientes (name, email, phone, address, street, suburb, postal_code, additional_address, created, modified) 
            VALUES (
                '" . mysql_escape_mimic($_POST['name']) . "', 
                '" . mysql_escape_mimic($_POST['email']) . "', 
                '" . mysql_escape_mimic($_POST['phone']) . "', 
                '" . mysql_escape_mimic($_POST['address']) . "',
                '" . mysql_escape_mimic(strtoupper($_POST['street'])) . "',
                '" . mysql_escape_mimic(strtoupper($_POST['suburb'])) . "',
                '" . mysql_escape_mimic(strtoupper($_POST['postal_code'])) . "',
                '" . mysql_escape_mimic(strtoupper($_POST['additional_address'])) . "',
                '" . date("Y-m-d H:i:s") . "',
                '" . date("Y-m-d H:i:s") . "'
            )
        ");

        if ($insertCustomer) {
            $customerID = $db->insert_id;
        } else {
            header("Location: checkout.php");
        }

        // Generamos el Token
        $token = generateToken();

        // Insert order details into database
        $insertOrder = $db->query("
            INSERT INTO orden (customer_id, total_price, created, modified, token) 
            VALUES (
                '" . $customerID . "', 
                '" . $cart->total() . "', 
                '" . date("Y-m-d H:i:s") . "', 
                '" . date("Y-m-d H:i:s") . "',
                '" . $token . "'
            )
        ");

        if ($insertOrder) {
            $orderID = $db->insert_id;

            // Payment
            $db->query("
                INSERT INTO pagos (order_id, bank, datep, ref, amount) 
                VALUES (
                    '" . $orderID . "', 
                    '" . mysql_escape_mimic($_POST['bank']) . "', 
                    '" . date("Y-m-d H:i:s") . "', 
                    '" . $transaction_id . "',
                    '" . $_POST['transactionAmount'] . "'
                )
            ");

            $sql = '';
            // get cart items
            $cartItems = $cart->contents();
            foreach($cartItems as $item){
                $sql .= "INSERT INTO orden_articulos (order_id, product_id, quantity, price) VALUES ('".$orderID."', '".$item['id']."', '".$item['qty']."', '".$item['price']."');";
            }

            // insert order items into database
            $insertOrderItems = $db->multi_query($sql);

            if ($insertOrderItems) {
                // Enviamos el email
                sendMail($orderID, $token);
                $cart->destroy();
                header("Location: order.php?id=$orderID&token=$token&success=$success");
            } else {
                header("Location: checkout.php");
            }
        }
    } else {
        // Cesta vacia
        header("Location: checkout.php");
    }
}