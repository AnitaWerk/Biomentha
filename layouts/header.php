<?php
$file = basename($_SERVER["REQUEST_URI"]);

$class = 'scroll';
$active_home = '';
$active_store = '';

if ($file == 'store.php') {
	$class = '';
	$active_store = 'active';
}

if ($file == 'index.php') {
	$active_home = 'active';
}
?>

	<!-- header -->

	<header>

		<div class="container">

			<nav class="navbar navbar-expand-lg navbar-light">

				<h1>

					<a class="navbar-brand text-capitalize" href="index.php">

					<img src="images/logo.png" width="35%" alt="biomentha cosmetica natural"/>

					</a>

				</h1>

				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"

				    aria-expanded="false" aria-label="Toggle navigation">

					<span class="navbar-toggler-icon"></span>

				</button>



				<div class="collapse navbar-collapse" id="navbarSupportedContent">

					<ul class="navbar-nav text-center  ml-lg-auto">

						<li class="nav-item <?= $active_home ?>  mr-3">

							<a class="nav-link" href="index.php">Inicio

								<span class="sr-only">(current)</span>

							</a>

						</li>

						<li class="nav-item  mr-3">

							<a class="nav-link <?= $class ?>" href="index.php#about">Biomentha</a>

						</li>

						<li class="nav-item dropdown mr-3">

							<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true"

							    aria-expanded="false">

								Productos

							</a>

							<div class="dropdown-menu" aria-labelledby="navbarDropdown">

								<a class="dropdown-item <?= $class ?>" href="index.php#shampoo">Shampoo Sólido</a>

								<!--a class="dropdown-item <?= $class ?>" href="index.php#services">Shampoo para mascotas</a>

								<div class="dropdown-divider"></div-->

								<a class="dropdown-item <?= $class ?>" href="index.php#cepillos">Cepillos</a>



								<a class="dropdown-item <?= $class ?>" href="index.php#gallery">Galería</a>

							</div>

						</li>

						<li class="nav-item <?= $active_store ?> mr-3">

							<a class="nav-link" href="store.php">Tienda</a>

						</li>

						<li class="nav-item">

							<a class="nav-link <?= $class ?>" href="index.php#contact">Contacto</a>

						</li>

					</ul>

				</div>

			</nav>

		</div>

	</header>

	<!-- //header -->