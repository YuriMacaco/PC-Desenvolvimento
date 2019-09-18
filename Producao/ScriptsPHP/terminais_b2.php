<?php
error_reporting(0);

echo "
<head>
	<meta charset='UTF-8'>
	<link rel='stylesheet' href='css/styleLogin.css'>
</head>
<div style='
	background-image:	url(images/patternfail.jpg);
	background-repeat:	repeat;
	width: 				100%;
	height: 			100%;
	position: 			absolute;
	top: 				0; 
	left: 				0;
	z-index:			-10;
	opacity: 			0.05;'>
</div>
<div class='myFooter'></div>
<div class='allContent'>
	<img src=\"images/logob2.png\" class='logoPrincipal' alt=\"Logo\">
	<div class='materialContainer'>
		<form id='dados' action='dados.php' method='post' accept-charset='UTF-8'>
			<div class='materialContainer'>
				<div class='box'>
					<div class='title'>LOGIN</div>
					<div class='input'>
						<label for='name'>Usuário</label>
						<input type='text' name='username' id='username'>
						<span class='spin'></span>
					</div>
					<div class='input'>
						<label for='pass'>Senha</label>
						<input type='password' name='password' id='password'>
						<span class='spin'></span>
					</div>
					<div class='button login'>
						<button><span>Entrar</span> <i class='fa fa-check'></i></button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<div class='myBottom'></div>";