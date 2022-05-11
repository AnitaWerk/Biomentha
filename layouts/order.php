<?php
// include database configuration file
include_once 'config/constants.php';
include_once 'include/common.php';

$order_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$token = filter_input(INPUT_GET, 'token', FILTER_SANITIZE_SPECIAL_CHARS);
$response = filter_input(INPUT_GET, 'success', FILTER_SANITIZE_SPECIAL_CHARS);

// get customer & order details
$query = $db->query("
    SELECT clientes.*, orden.*, pagos.* FROM orden
    JOIN clientes ON orden.customer_id = clientes.id
    JOIN pagos ON orden.id = pagos.order_id
    WHERE orden.id = " . mysql_escape_mimic($order_id) . " 
    AND orden.token = '" . mysql_escape_mimic($token) . "'
");
$order = $query->fetch_object();

// redirect to home if query is null
if (is_null($order)) {
    header("Location: index.php");
}

$query = $db->query("
    SELECT mis_productos.name as name, mis_productos.price as price, orden_articulos.quantity as qty
    FROM orden_articulos 
    JOIN mis_productos ON orden_articulos.product_id = mis_productos.id
    WHERE orden_articulos.order_id = " . $order_id
);
/******************************************/
// Esta function requiere mysqlnd (no esta presente en namecheap :/)
// Fatal error: Uncaught Error: Call to undefined method mysqli_result::fetch_all() in /home/animdhbi/public_html/order.php:39 Stack trace: #0 {main} thrown in /home/animdhbi/public_html/order.php on line 39
//$order_reng = $query->fetch_all(MYSQLI_ASSOC);
/*******************************************/

for ($order_reng = array(); $tmp = $query->fetch_array(MYSQLI_ASSOC);) $order_reng[] = $tmp;

?>

<div class="container">

    <?php if (!empty($response)): ?>
    <div class="alert alert-success" role="alert">
    <?=MP_RESPONSES[$response]?>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    </div>
    <?php endif; ?>

    <h1>Vista previa de la Orden # <?=$order_id;?></h1>
    <table class="table">
    <thead>
        <tr>
            <th>Producto</th>
            <th>Precio</th>
            <th>Cantidad</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach($order_reng as $key => $item) {
        ?>
        <tr>
            <td><?php echo utf8_encode($item["name"]); ?></td>
            <td><?php echo '$' . $item["price"]; ?></td>
            <td><?php echo $item["qty"]; ?></td>
            <td><?php echo '$' . ($item["price"] * $item["qty"]); ?></td>
        </tr>
        <?php } ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3"></td>
            <td class="text-center"><strong>Total <?php echo '$' . $order->total_price; ?></strong></td>
        </tr>
    </tfoot>
    </table>

    <div class="row row-fluid">
        <div class="col-lg-6">
            <h4>Datos Personales, Direccion de Envio e Información de Pago</h4>
            <hr />
            <p><strong>Nombre:</strong> <?=$order->name?></p>
            <p><strong>Email:</strong> <?=$order->email?></p>
            <p><strong>Teléfono:</strong> <?=$order->phone?></p>
            <p><strong>Dirección:</strong> <?=$order->address?></p>
            <p><strong>Calle:</strong> <?=$order->street?></p>
            <p><strong>Colonia:</strong> <?=$order->suburb?></p>
            <p><strong>Codigo Postal:</strong> <?=$order->postal_code?></p>
            <p><strong>Informacion Adicional:</strong> <?=$order->additional_address?></p>
            <hr />
            <p><strong>Banco:</strong> <?=$order->bank?></p>
            <p><strong>Fecha Transacción:</strong> <?=$order->datep?></p>
            <p><strong>Referencia:</strong> <?=$order->ref?></p>
            <p><strong>Monto:</strong> <?=$order->total_price?></p>
        </div>
        <!--div class="col-lg-6">
            <h4>Datos Bancarios</h4>
            <hr />
            <p>
                <strong>Banorte</strong> <br />
                Cuenta: 063 163 4223<br />
                Clabe: 0723 2000 6316 342232<br />
                <hr />

                <strong>BBVA</strong> <br />
                Cuenta: 279 758 4008<br />
                Clabe: 0126 8002 7975 840087<br />
                <hr />
            </p>
        </div-->
    </div>

    <br />

</div>