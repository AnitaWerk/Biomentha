<?php
// include database configuration file
include_once 'config/constants.php';

// initializ shopping cart class
include 'session.php';
$cart = new Cart;

// set customer ID in session
//$_SESSION['sessCustomerID'] = 1;

// get customer details by session customer ID
//$query = $db->query("SELECT * FROM clientes WHERE id = ".$_SESSION['sessCustomerID']);
//$custRow = $query->fetch_assoc();

$response = filter_input(INPUT_GET, 'error', FILTER_SANITIZE_SPECIAL_CHARS);

// redirect to home if cart is empty
if ($cart->total_items() <= 0) {
    header("Location: cart.php");
}
?>

<div class="container">

    <?php if (!empty($response)) : ?>
        <div class="alert alert-danger" role="alert">
            <?= MP_RESPONSES[$response] ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <h1>Vista previa de la Orden</h1>
    <div class="table table-responsive table-hover">
        <table class="table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th>Env&iacute;o</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($cart->total_items() > 0) {
                    //get cart items from session
                    $cartItems = $cart->contents();
                    foreach ($cartItems as $item) {
                ?>
                        <tr>
                            <td><?= utf8_encode($item["name"]); ?></td>
                            <td><?= '$' . $item["price"]; ?></td>
                            <td><?= $item["qty"]; ?></td>
                            <td><?= '$' . $item["subtotal"]; ?></td>
                        </tr>
                    <?php }
                } else { ?>
                    <tr>
                        <td colspan="4">
                            <p>No hay articulos en tu carta......</p>
                        </td>
                    <?php } ?>
            </tbody>
            <tfoot>
                <!--<tr>
                    <td colspan="3"></td>
                    <?php /*if ($cart->total_items() > 0) { ?>
                        <td class="text-center"><strong>Total <?=  '$' . $cart->total(); ?></strong></td>
                    <?php } */?>
                </tr>-->
            </tfoot>
        </table>
    </div>

    <form action="AccionCarta.php" method="POST" id="paymentForm">
        <div class="row row-fluid">
            <div class="col-lg-6">
                <h4>Datos Personales, Direccion de Envio e Información de Pago</h4>
                <hr />
                <p><input type="text" name="name" class="form-control" placeholder="Nombre" required /></p>
                <p><input type="email" name="email" class="form-control" placeholder="Apellido" required /></p>
                <p><input type="text" name="phone" class="form-control" placeholder="Telefono" required /></p>
                <p><input type="text" name="phone" class="form-control" placeholder="Telefono 2" /></p>
                <p><textarea name="address" class="form-control" placeholder="Ciudad" required></textarea></p>
                <p><input type="text" name="street" class="form-control" placeholder="Codigo Postal" required /></p>
                <p><input type="text" name="suburb" class="form-control" placeholder="Direccion de Envio" required /></p>
                <p><input type="text" name="postal_code" class="form-control" placeholder="suemail@dominio.com" required /></p>
                
            </div>
            <div class="col-lg-6">
                <h4>Total a pagar</h4>
                <hr />
                <p>
                    <div class="mb-3 row">
                        <label for="example-text-input" class="col-sm-2 form-label align-self-center mb-lg-0 text-end">Total:</label>
                        <div class="col-sm-10">
                        <input type="text" name="name" class="form-control" value="<?= '$' . $cart->total(); ?>"   readonly/>
                        </div>
                    </div>
                </p>
            </div>
            <!--<div class="col-lg-6">
            <h4>Detalles de la tarjeta</h4>
            <hr />
            <div class="form-group">
                <label for="cardholderName">Titular de la tarjeta</label>
                <input class="form-control" id="cardholderName" data-checkout="cardholderName" type="text">
            </div>
            <div class="form-group">
                <label for="cardNumber">Número de la tarjeta</label>
                <div class="input-group">
                    <input class="form-control" type="text" id="cardNumber" data-checkout="cardNumber"
                        onselectstart="return false" onpaste="return false"
                        oncopy="return false" oncut="return false"
                        ondrag="return false" ondrop="return false" autocomplete=off>
                    <div class="input-group-append">
                        <span class="input-group-text text-muted">
                            <i class="fa fa-cc-visa mx-1"></i>
                            <i class="fa fa-cc-amex mx-1"></i>
                            <i class="fa fa-cc-mastercard mx-1"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8">
                    <div class="form-group">
                        <label for="">Fecha de vencimiento</label>
                        <div class="input-group">
                            <input class="form-control" type="text" placeholder="MM" id="cardExpirationMonth" data-checkout="cardExpirationMonth"
                                onselectstart="return false" onpaste="return false"
                                oncopy="return false" oncut="return false"
                                ondrag="return false" ondrop="return false" autocomplete=off>
                            <input class="form-control" type="text" placeholder="YY" id="cardExpirationYear" data-checkout="cardExpirationYear"
                                onselectstart="return false" onpaste="return false"
                                oncopy="return false" oncut="return false"
                                ondrag="return false" ondrop="return false" autocomplete=off>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group mb-4">
                        <label for="securityCode">CVV</label>
                        <div class="input-group">
                            <input class="form-control" id="securityCode" data-checkout="securityCode" type="text" placeholder="CVV"
                                onselectstart="return false" onpaste="return false"
                                oncopy="return false" oncut="return false"
                                ondrag="return false" ondrop="return false" autocomplete=off>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group" id="issuerInput">
                <label for="issuer">Banco emisor</label>
                <select id="issuer" name="issuer" data-checkout="issuer" class="form-control"></select>
            </div>
            <div>
                <input type="hidden" name="transactionAmount" id="transactionAmount" value="<?= $cart->total() ?>" />
                <input type="hidden" name="paymentMethodId" id="paymentMethodId" />
                <input type="hidden" name="description" id="description" />
                <input type="hidden" name="action" value="placeOrder" />
                <input type="hidden" name="bank" value="MERCADOPAGO"/>
                <br />
                <input class="btn btn-success" id="button-pay" type="submit" value="Pagar"></input>
                <br />
            </div>
        </div>-->
        </div>
        <div class="footBtn">
            <a href="store.php" class="btn btn-warning"><i class="fa fa-shopping-cart"></i> Continue Comprando</a>
        </div>
    </form>

    <br />

</div>