<?php
if(!isset($_REQUEST['id'])){
    header("Location: index.php");
}
?>
<div class="container">

	<h1>Estado de su Orden</h1>
	<hr />
    <p>Su pedido ha sido enviado exitosamente. La ID del pedido es #<?php echo $_GET['id']; ?></p>

</div>
