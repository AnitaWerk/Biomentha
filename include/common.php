<?php
require_once 'vendor/autoload.php';

// include database configuration file
include_once 'config/constants.php';

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function mysql_escape_mimic($inp) {
    if(is_array($inp))
        return array_map(__METHOD__, $inp);

    if(!empty($inp) && is_string($inp)) {
        return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $inp);
    }

    return $inp;
}

function generateToken($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }

    return $randomString;
}

function sendMail($orderID, $token) {
    $subject = 'Nueva orden generada #' . $orderID;
    $body = '
    <html>
        <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
            .body {
            padding: 20px 0;
            background-color: #eee;
            color: #424242;
            font-family: Trebuchet MS;
            margin: 0;
            }
            .container {
            width:80%;
            margin:40px auto;
            padding:30px 5px 30px 5px;
            border-top: 1px solid rgba(185,185,185,0.4);
            border-bottom: 1px solid rgba(185,185,185,0.4);
            border-radius: 6px;
            }
            #in {
            width:80%;
            margin: 0 auto;
            padding:30px 0 30px 0;
            }
            #banner {
            width: 80%;
            background-color: rgba(39, 141, 194, 0.25);
            border: 2px solid rgba(87, 103, 67, 0.16);
            border-radius: 3px;
            margin: 0 auto;
            text-align: center;
            }
            #banner h2 {
            color: #278dc2;
            font-size: 1.35rem;
            }
            #yey {
            text-align: center;
            margin-bottom: 65px;
            }
            #content {
            width: 90%;
            border-collapse: separate;
            border-radius: 6px;
            border-style: hidden;
            margin: 15px auto;
            border-spacing: 0;
            }
            .bg-title {
            background: rgb(71, 170, 217);
            }
            #ths {
            font-size: 20px;
            color: #fff6f6;
            border-radius: 6px 6px 0 0;
            }
            #thi {
            font-size: 20px;
            color: #fff6f6;
            }
            .td1 {
            border-right: 2px solid rgba(97, 97, 97, 0.7);
            border-left: 1px solid rgba(97, 97, 97, 0.7);
            font-size: 16px;
            font-family: Trebuchet MS;
            width: 28%;
            }
            .td2 {
            border-right: 1px solid rgba(97, 97, 97, 0.7);
            font-size: 16px;
            font-family: Trebuchet MS;
            }
            #last {
            font-size: 16px;
            border-right: 1px solid rgba(97, 97, 97, 0.7);
            border-bottom: 1px solid rgba(97, 97, 97, 0.7);
            border-left: 1px solid rgba(97, 97, 97, 0.7);
            border-radius: 0 0 6px 6px;
            padding: 20px;
            }
            #footer {
            width: 60%;
            margin: 0 auto;
            text-align: center;
            }
            @media screen and (max-width: 991px) {
            #yey {
                margin-bottom: 50px;
            }
            }
            @media screen and (max-width: 768px) {
            .container {
                width: 90%;
                margin: 30px auto;
            }
            #banner h2 {
                font-size: 1.1rem;
            }
            #yey {
                font-size: 0.8rem;
            }
            #footer {
                font-size: 0.9rem;
            }
            }
            @media screen and (max-width: 480px) {
            .container {
                width: 100%;
                margin: 0;
                padding: 30px 0 0 0;
            }
            #banner h2 {
                font-size: 0.7rem;
            }
            #yey {
                font-size: 0.5rem;
                margin-bottom: 28px;
            }
            #ths, #thi {
                font-size: 13px;
            }
            .td1, .td2 {
                font-size: 8px;
            }
            #last {
                font-size: 10px;
            }
            #footer {
                font-size: 0.5rem;
            }
            }
        </style>
        </head>
        <body class="body">
        <div class="container">
            <div id="in">
            <div id="banner">
                <h2>¡Nueva orden generada!</h2>
            </div>
            <h4 id="yey">Un usuario ha generado un nuevo pedido a través de la Web de <em>' . COMPANY_NAME . '</em> ✉️</h4>
            <table id="content" cellpadding="15">
                <thead>
                <tr class="bg-title">
                    <th colspan="2" id="ths">Has click en el siguiente enlace para visualizar la orden</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="td1"><strong> Orden #' . $orderID . ' </strong></td>
                    <td class="td2"><a href="' . WEBSITE . '/order.php?id=' . $orderID . '&token=' . $token . '" target="_blank">Ver Orden</a></td>
                </tr>
                </tbody>
            </table>
            <br>
            </div>
        </div>
        <br>
        <div id="footer">
            <em>Este mensaje fue generado a través de la web de <a href="' . WEBSITE . '">' . COMPANY_NAME . '</a></em>
        </div>
        <br>
        </body>
    </html>';
    
    // Instantiation and passing `true` enables exceptions
    $mail = new PHPMailer();

    try {
        //Server settings
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;              // Enable verbose debug output
        $mail->isSMTP();                                    // Send using SMTP
        $mail->Host       = SMTP_SERVER;                    // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                           // Enable SMTP authentication
        $mail->Username   = EMAIL_USER;                     // SMTP username
        $mail->Password   = EMAIL_PASSWORD;                 // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;    // PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = SMTP_PORT;                      // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
    
        //Recipients
        $mail->setFrom(EMAIL_USER, COMPANY_NAME);
        $mail->addAddress(DEFAULT_EMAIL, COMPANY_NAME);     // Add a recipient
    
        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $body;
    
        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

    //mail(DEFAULT_EMAIL, $subject, $body, "MIME-Version: 1.0" . "\r\n" . "Content-type:text/html;charset=UTF-8" . "\r\n");
}

function sendMailRecoverPassword($email, $password) {
    $subject = 'Recuperacion de Contraseña';
    $body = '
    <html>
        <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
            .body {
            padding: 20px 0;
            background-color: #eee;
            color: #424242;
            font-family: Trebuchet MS;
            margin: 0;
            }
            .container {
            width:80%;
            margin:40px auto;
            padding:30px 5px 30px 5px;
            border-top: 1px solid rgba(185,185,185,0.4);
            border-bottom: 1px solid rgba(185,185,185,0.4);
            border-radius: 6px;
            }
            #in {
            width:80%;
            margin: 0 auto;
            padding:30px 0 30px 0;
            }
            #banner {
            width: 80%;
            background-color: rgba(39, 141, 194, 0.25);
            border: 2px solid rgba(87, 103, 67, 0.16);
            border-radius: 3px;
            margin: 0 auto;
            text-align: center;
            }
            #banner h2 {
            color: #278dc2;
            font-size: 1.35rem;
            }
            #yey {
            text-align: center;
            margin-bottom: 65px;
            }
            #content {
            width: 90%;
            border-collapse: separate;
            border-radius: 6px;
            border-style: hidden;
            margin: 15px auto;
            border-spacing: 0;
            }
            .bg-title {
            background: rgb(71, 170, 217);
            }
            #ths {
            font-size: 20px;
            color: #fff6f6;
            border-radius: 6px 6px 0 0;
            }
            #thi {
            font-size: 20px;
            color: #fff6f6;
            }
            .td1 {
            border-right: 2px solid rgba(97, 97, 97, 0.7);
            border-left: 1px solid rgba(97, 97, 97, 0.7);
            font-size: 16px;
            font-family: Trebuchet MS;
            width: 28%;
            }
            .td2 {
            border-right: 1px solid rgba(97, 97, 97, 0.7);
            font-size: 16px;
            font-family: Trebuchet MS;
            }
            #last {
            font-size: 16px;
            border-right: 1px solid rgba(97, 97, 97, 0.7);
            border-bottom: 1px solid rgba(97, 97, 97, 0.7);
            border-left: 1px solid rgba(97, 97, 97, 0.7);
            border-radius: 0 0 6px 6px;
            padding: 20px;
            }
            #footer {
            width: 60%;
            margin: 0 auto;
            text-align: center;
            }
            @media screen and (max-width: 991px) {
            #yey {
                margin-bottom: 50px;
            }
            }
            @media screen and (max-width: 768px) {
            .container {
                width: 90%;
                margin: 30px auto;
            }
            #banner h2 {
                font-size: 1.1rem;
            }
            #yey {
                font-size: 0.8rem;
            }
            #footer {
                font-size: 0.9rem;
            }
            }
            @media screen and (max-width: 480px) {
            .container {
                width: 100%;
                margin: 0;
                padding: 30px 0 0 0;
            }
            #banner h2 {
                font-size: 0.7rem;
            }
            #yey {
                font-size: 0.5rem;
                margin-bottom: 28px;
            }
            #ths, #thi {
                font-size: 13px;
            }
            .td1, .td2 {
                font-size: 8px;
            }
            #last {
                font-size: 10px;
            }
            #footer {
                font-size: 0.5rem;
            }
            }
        </style>
        </head>
        <body class="body">
        <div class="container">
            <div id="in">
            <div id="banner">
                <h2>¡Se ha generado una nueva contraseña!</h2>
            </div>
            <h4 id="yey">Ha solicitado una contraseña temporal a traves de la Web <em>' . COMPANY_NAME . '</em> ✉️</h4>
            <table id="content" cellpadding="15">
                <thead>
                <tr class="bg-title">
                    <th colspan="2" id="ths">Tu contraseña temporal es la siguiente:</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="td1"><strong>' . $password . '</strong></td>
                    <td class="td2"><a href="' . WEBSITE . '/registro.php">Ir al portal</a></td>
                </tr>
                </tbody>
            </table>
            <br>
            </div>
        </div>
        <br>
        <div id="footer">
            <em>Este mensaje fue generado a través de la web de <a href="' . WEBSITE . '">' . COMPANY_NAME . '</a></em>
        </div>
        <br>
        </body>
    </html>';
    
    // Instantiation and passing `true` enables exceptions
    $mail = new PHPMailer();

    try {
        //Server settings
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;              // Enable verbose debug output
        $mail->isSMTP();                                    // Send using SMTP
        $mail->Host       = SMTP_SERVER;                    // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                           // Enable SMTP authentication
        $mail->Username   = EMAIL_USER;                     // SMTP username
        $mail->Password   = EMAIL_PASSWORD;                 // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;    // PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = SMTP_PORT;                      // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
    
        //Recipients
        $mail->setFrom(EMAIL_USER, COMPANY_NAME);
        $mail->addAddress($email, 'Recuperacion de Contraseña');     // Add a recipient
    
        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $body;
    
        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

function verifyStatusMP($status, $status_detail, array &$result) {

    if ($status == 'approved' && $status_detail == 'accredited') {
        $result = [true, 'SUCCESS'];
    }

    if ($status == 'in_process') {
        if ($status_detail == 'pending_contingency') {
            $result = [true, 'PENDING_01'];
        }
        if ($status_detail == 'pending_review_manual') {
            $result = [true, 'PENDING_02'];
        }
    }

    if ($status == 'rejected') {
        switch ($status_detail) {
            case 'cc_rejected_bad_filled_card_number':
                $result = [false, 'REJECTED_01'];
                break;
            case 'cc_rejected_bad_filled_date':
                $result = [false, 'REJECTED_02'];
                break;
            case 'cc_rejected_bad_filled_other':
                $result = [false, 'REJECTED_03'];
                break;
            case 'cc_rejected_bad_filled_security_code':
                $result = [false, 'REJECTED_04'];
                break;
            case 'cc_rejected_blacklist':
                $result = [false, 'REJECTED_05'];
                break;
            case 'cc_rejected_call_for_authorize':
                $result = [false, 'REJECTED_06'];
                break;
            case 'cc_rejected_card_disabled':
                $result = [false, 'REJECTED_07'];
                break;
            case 'cc_rejected_card_error':
                $result = [false, 'REJECTED_08'];
                break;
            case 'cc_rejected_duplicated_payment':
                $result = [false, 'REJECTED_09'];
                break;
            case 'cc_rejected_high_risk':
                $result = [false, 'REJECTED_10'];
                break;
            case 'cc_rejected_insufficient_amount':
                $result = [false, 'REJECTED_11'];
                break;
            case 'cc_rejected_invalid_installments':
                $result = [false, 'REJECTED_12'];
                break;
            case 'cc_rejected_max_attempts':
                $result = [false, 'REJECTED_13'];
                break;
            case 'cc_rejected_other_reason':
                $result = [false, 'REJECTED_14'];
                break;
            default:
                $result = [false, $status_detail];
        }
    }
}

function verifyUser($email) {
    global $db;
    $query = $db->query("SELECT usuario, email FROM usuarios WHERE email = '" . $email . "'");

    if ($query->num_rows > 0) {
        return true;
    }
    return false;
}

function getUser($customerID) {
    global $db;
    $query = $db->query("SELECT * FROM usuarios WHERE id = '" . $customerID . "'");

    if ($query->num_rows > 0) {
        return $query->fetch_assoc();
    }
    return false;
}
