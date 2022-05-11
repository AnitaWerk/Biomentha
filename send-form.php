<?php
//  ==========================================
//    Author: Daniela Maissi 
//    Author mail: danielamaissi.dev@gmail.com
//  ==========================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	if (empty($_POST['Name']) OR empty($_POST['Message'])) {
		http_response_code(400);
		echo "One of the fields is empty, please check and try again.";
		exit;
	} else if (!filter_var($_POST['Email'], FILTER_VALIDATE_EMAIL)) {
		http_response_code(400);
		echo "Your email address is incorrect. Please verify it and try again.";
		exit;
	  } else {
		$name = trim($_POST['Name']);
		$email = filter_var(trim($_POST['Email']), FILTER_SANITIZE_EMAIL);
		$phone = trim($_POST['Phone']);
		$message = trim($_POST["Message"]);
		//$subjectform = trim($_POST["Subject"]);
		$message = str_replace("\n","<br>",$message);
		$recipient = 'eco_forestal@hotmail.com';
		$subject = 'Nuevo Mensaje';
		$body = '<html>
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
					<h2>¡Nuevo mensaje de usuario!</h2>
				</div>
				<h4 id="yey">Un usuario se ha comunicado a través de la Web de <em>Biomentha</em> ✉️</h4>
				<table id="content" cellpadding="15">
					<thead>
					<tr class="bg-title">
						<th colspan="2" id="ths">Datos del cliente</th>
					</tr>
					</thead>
					<tbody>
					<tr>
						<td class="td1"><strong> Nombre: </strong></td>
						<td class="td2">'.$name.'</td>
					</tr>
					<tr>
						<td class="td1"><strong> Email: </strong></td>
						<td class="td2">'.$email.'</td>
					</tr>
					<tr>
						<td class="td1"><strong> Teléfono: </strong></td>
						<td class="td2">'.$phone.'</td>
					</tr>
					<tr class="bg-title">
						<th colspan="2" id="thi">Mensaje</th>
					</tr>
					<tr>
						<td colspan="2" id="last">'.$message.'</td>
					</tr>
					</tbody>
				</table>
				<br>
				</div>
			</div>
			<br>
			<div id="footer">
				<em>Este mensaje fue generado a través de la web de <a href="biomentha.com.mx">BIOMENTHA </a></em>
			</div>
			<br>
			</body>
		</html>';
  			$headers[] = "MIME-Version: 1.0";
  			$headers[] = "Content-type:text/html;charset=UTF-8";
  			$headers[] = "From: Biomentha <eco_forestal@hotmail.com>";
  			if (mail($recipient, $subject, $body, implode("\r\n", $headers))) {
    			http_response_code(200);
    			echo "OK";
    			exit;
  			} else {
    			http_response_code(500);
    			echo "An error occurred on the server.";
    			exit;
  			}
        }

} else {
	http_response_code(400);
	echo 'Hubo un error con la petición.';
	exit;
}