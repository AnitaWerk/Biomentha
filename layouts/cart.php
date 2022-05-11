<?php
// initializ shopping cart class
include 'session.php';
$cart = new Cart;
?>

<style>
.container{padding: 20px;}
input[type="number"]{width: 20%;}
.font{font-family: tahoma;}
.right{text-align:right;}
input[type="number"] {
    width:60px;
}
</style>
<script>
function updateCartItem(obj,id){
    $.get("AccionCarta.php", {action:"updateCartItem", id:id, qty:obj.value}, function(data){
        if (data == 'ok') {
            location.reload();
        } else {
            alert('Cart update failed, please try again.');
        }
    });
}
</script>

<div class="container">

    <h1>Carrito de compras</h1>
    <div class="table table-responsive table-hover">
        <table class="table">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if($cart->total_items() > 0){
                //get cart items from session
                $cartItems = $cart->contents();
                foreach($cartItems as $item){
            ?>
            <tr>
                <td><?=utf8_encode($item["name"]); ?></td>
                <td><?='$'.$item["price"]; ?></td>
                <td><input type="number" min="1" max="50" class="form-control text-center" value="<?php echo $item["qty"]; ?>" onchange="updateCartItem(this, '<?= $item['rowid'] ?>')"></td>
                <td><?='$'.$item["subtotal"]; ?></td>
                <td class="right">
                    <a href="AccionCarta.php?action=removeCartItem&id=<?php echo $item["rowid"]; ?>" class="btn btn-danger" onclick="return confirm('Confirma eliminar?')"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
            <?php } 
            } else { 
            ?>
            <tr>
                <td colspan="5"><p>Tu cesta esta vacía...</p></td>
            </tr>
            <?php 
            } 
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td><a href="store.php" class="btn btn-warning"><i class="fa fa-shopping-cart"></i> Continue Comprando</a></td>
                <td colspan="2"></td>
                <?php if($cart->total_items() > 0){ ?>
                <td class="text-center"><strong>Total <?='$'.$cart->total(); ?></strong></td>
                <td><a href="checkout.php" class="btn btn-success btn-block"><i class="fa fa-credit-card"></i> Pagos</a></td>
                <?php } ?>
            </tr>
        </tfoot>
        </table>
    </div>

    </div>
 
</div>
