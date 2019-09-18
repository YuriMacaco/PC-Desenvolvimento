<?php

if (isset($_POST['email']) && !empty($_POST['email'])){

	$nome 		= addslashes($_POST['name']);
	$email 		= addslashes($_POST['email']);
	$mensagem 	= addslashes($_POST['mesage']);

	$to 		= 	"b2midia@gmail.com";
	$subject 	= 	"Inova - Registro - ES";
	$body 		= 	"Nombre: ".$nome."\r\n".
					"email: ".$email."\r\n".
					"Mensaje: ".$mensagem;

	$header 	= 	"From: app@b2midia.com.br"."\r\n"
					."Reply-To:".$email."\r\n"
					."X=Mailer:PHP/".phpversion();

	if (mail($to,$subject,$body,$header)){
		echo 	"<h1>E-mail enviado con éxito!</h1> 
					<br> 
						<h2>En breve recibirá un E-mail con las credenciales de acceso
						<br>Mientras tanto, no dejes de disfrutar de nuestra aplicación ...</h2>";
	}else{
		echo "<h1> E-mail no se puede enviar! </h1>";
	}

}

?> 