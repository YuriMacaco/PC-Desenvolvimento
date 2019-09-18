<?php

if (isset($_POST['email']) && !empty($_POST['email'])){

	$nome 		= addslashes($_POST['name']);
	$email 		= addslashes($_POST['email']);
	$mensagem 	= addslashes($_POST['mesage']);

	$to 		= 	"b2midia@gmail.com";
	$subject 	= 	"Worksphere - Subscription - PT";
	$body 		= 	"Nome: ".$nome."\r\n".
					"email: ".$email."\r\n".
					"Mensagem: ".$mensagem;

	$header 	= 	"From: app@b2midia.com.br"."\r\n"
					."Reply-To:".$email."\r\n"
					."X=Mailer:PHP/".phpversion();

	if (mail($to,$subject,$body,$header)){
		echo 	"<h1>Email enviado com sucesso!</h1> 
					<br> 
						<h2>Em breve você receberá um E-mail com as credenciais de acesso
						<br>Enquanto isso, não deixe de degustar do nosso aplicativo...</h2>";
	}else{
		echo "Email não pode ser enviado!";
	}

}

?> 