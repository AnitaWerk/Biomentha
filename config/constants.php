<?php

const DEV_MODE = false;

const COMPANY_NAME = 'BIOMENTHA';

const WEBSITE = 'https://biomentha.com.mx';

const DIR_LAYOUTS = 'layouts/';

const DEFAULT_EMAIL = 'eco_forestal@hotmail.com';
#const DEFAULT_EMAIL = 'cesar.ramone@gmail.com';

/* EMAIL CONFIG */

const EMAIL_USER = 'no-responder@biomentha.com.mx';

const EMAIL_PASSWORD = 'QJe-5Xi-kpW-nen';

const SMTP_SERVER = 'mail.biomentha.com.mx';

const SMTP_PORT = 465;

/* MERCADOPAGO */
 
const MP_PUBLIC_KEY = 'TEST-3e27880a-716c-4f8c-873e-c670649c3bb3';

const MP_ACCESS_TOKEN = 'TEST-414935433842919-091101-bcb979a1759157a49495415f2ab18a3f-643720204';

const MP_RESPONSES = [
    'SUCCESS' => '¡Listo! Se acreditó tu pago.',
    'PENDING_01' => 'Estamos procesando tu pago. No te preocupes, menos de 2 días hábiles te avisaremos por e-mail si se acreditó.',
    'PENDING_02' => 'Estamos procesando tu pago. No te preocupes, menos de 2 días hábiles te avisaremos por e-mail si se acreditó o si necesitamos más información.',
    'REJECTED_01' => 'Revisa el número de tarjeta.',
    'REJECTED_02' => 'Revisa la fecha de vencimiento.',
    'REJECTED_03' => 'Revisa los datos.',
    'REJECTED_04' => 'Revisa el código de seguridad de la tarjeta.',
    'REJECTED_05' => 'No pudimos procesar tu pago.',
    'REJECTED_06' => 'Debes autorizar el pago.',
    'REJECTED_07' => 'Tarjeta inactiva, llama al banco emisor para activarla, el telefono esta al dorso de tu tarjeta.',
    'REJECTED_08' => 'No pudimos procesar tu pago.',
    'REJECTED_09' => 'Ya hiciste un pago por ese valor. Si necesitas volver a pagar usa otra tarjeta u otro medio de pago.',
    'REJECTED_10' => 'Tu pago fue rechazado. Elige otro de los medios de pago, te recomendamos con medios en efectivo.',
    'REJECTED_11' => 'Sin fondos suficientes.',
    'REJECTED_12' => 'Tu metodo de pago no procesa pagos por cuotas.',
    'REJECTED_13' => 'Llegaste al límite de intentos permitidos. Elige otra tarjeta u otro medio de pago.',
    'REJECTED_14' => 'Tu banco no proceso el pago.',
];

//DB details
/*if (!DEV_MODE) {
    $DB_HOST = 'localhost';
    $DB_USER = 'biomdtpc_user';
    $DB_PASSWORD = 'X8H-3Aq-7pA-3K4';
    $DB_NAME = 'biomdtpc_db';
} else {
    $DB_HOST = 'localhost';
    $DB_USER = 'forge';
    $DB_PASSWORD = 'rdsistemas2000';
    $DB_NAME = 'biomenthadb';
}
*/

$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASSWORD = '';
$DB_NAME = 'biomenth_db';


//Create connection and select DB
$db = new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);

if ($db->connect_error) {
    die("No hay Conexion con la base de datos: " . $db->connect_error);
} 