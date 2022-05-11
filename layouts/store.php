<?php
// Iniciamos la clase de la cesta
include_once 'session.php';
$cart = new Cart;
?>

<style>
    .container{padding: 20px;}
    .cart-link{width: 100%;text-align: right;display: block;font-size: 22px;}
    .font{font-family: tahoma;}
</style>

<div class="container">

    <a href="cart.php" class="btn btn-sm btn-warning" title="Ver Carta"><i class="fa fa-shopping-cart"></i> Ver Cesta (<?=$cart->total_items()?>)</a>

    <h1>Mis Productos</h1>

    <br />
    <div class="row row-fluid">
        <?php
        //get rows query
        $query = $db->query("SELECT * FROM mis_productos WHERE status = 1 ORDER BY id ASC LIMIT 100");
        if($query->num_rows > 0){ 
            while($row = $query->fetch_assoc()){
                #$variantes = $db->query("SELECT * FROM mis_productos_variantes WHERE status = 1 AND product_id = {$row["id"]} ORDER BY size ASC LIMIT 100");
        ?>
        <div class="item col-lg-4" style="padding: 10px;">
            <div class="thumbnail">
                <div class="caption">
                    <img src="<?=$row["image"]?>" width="100%" class="img-responsive"/>
                    <h4 class="list-group-item-heading"><?=utf8_encode($row["name"]); ?></h4>
                    <p class="list-group-item-text"><?=utf8_encode($row["description"]); ?></p>
                    <div class="row">
                        
                        <?php 
                        if ($variantes->num_rows > 0) {
                            while($variante = $variantes->fetch_assoc()){
                        ?>

                        <div class="col-md-6">
                            <p class="lead"><?=$variante["size"]; ?> - <?='$'.$variante["price"]; ?></p>
                        </div>
                        <div class="col-md-6 text-right">
                            <a class="btn btn-sm btn-success" href="AccionCarta.php?action=addToCart&id=<?=$variante["id"]; ?>&variante=1"><i class="fa fa-shopping-cart"></i> Agregar</a>
                        </div>

                        <?php 
                            } // Fin variantes
                        } else { // Simple
                        ?>

                        <div class="col-md-6">
                            <p class="lead"><?='$'.$row["price"]; ?></p>
                        </div>
                        <div class="col-md-6 text-right">
                            <a class="btn btn-sm btn-success" href="AccionCarta.php?action=addToCart&id=<?=$row["id"]; ?>"><i class="fa fa-shopping-cart"></i> Agregar</a>
                        </div>

                        <?php 
                        } // end if
                        ?>
                    </div>
                </div>
                <hr />
            </div>
        </div>
        <?php } }else{ ?>
        <p>Producto(s) no existe.....</p>
        <?php } ?>
    </div>
 
</div>