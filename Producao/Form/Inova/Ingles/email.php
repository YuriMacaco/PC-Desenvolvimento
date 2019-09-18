<?php

if (isset($_POST['email']) && !empty($_POST['email'])){

	$nome 		= addslashes($_POST['name']);
	$email 		= addslashes($_POST['email']);
	$mensagem 	= addslashes($_POST['mesage']);

	$to 		= 	"b2midia@gmail.com";
	$subject 	= 	"Inova - Subscription - EN";
	$body 		= 	"Name: ".$nome."\r\n".
					"email: ".$email."\r\n".
					"Message: ".$mensagem;

	$header 	= 	"From: app@b2midia.com.br"."\r\n"
					."Reply-To:".$email."\r\n"
					."X=Mailer:PHP/".phpversion();

	if (mail($to,$subject,$body,$header)){
		echo 	"<h1>Email successfully sent!</h1> 
					<br> 
						<h2>You will soon receive an Email with your access credentials
						<br>In the meantime, be sure to try our app</h2>";
	}else{
		echo "<h1>Email can not be sent!</h1>";
	}

}

?> 