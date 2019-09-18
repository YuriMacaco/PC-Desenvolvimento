<?php
error_reporting(0);

$servername 	= "localhost";
$username 		= "b2";
$password 		= "dsrloZm5WjX0ecDIDpyTUMLk00ZFBq";
$dbname 		= "b2_votorantim";

session_start();

//$_SESSION['username'] = $username;

// Escape user inputs for security
$categoria 		= $_REQUEST['categoria'];
$titulo 		= $_REQUEST['titulo'];

// Check connection
if($categoria != null && $categoria != "" && $titulo != null && $titulo != ""){
	
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	mysqli_set_charset($conn, "utf8");
	
	if ($conn -> connect_error) {
		die("<br/><br/>
			Não há conexão ou há algum bloqueio na conexão com o banco de dados.
			<br/><br/>
			Entre em contato com a B2 Mídia através do e-mail <b> suporte@b2midia.com.br </b> ou através do telefone <b> (11) 2382-9631 </b>");
	
	}else{

		echo "<br/><br/>";
		$resultado = mysqli_query($conn,"SELECT * FROM Noticias WHERE vot_news_categ='".$categoria."' AND vot_news_name='".$titulo."'");
		
		if($resultado){
			
			$count = mysqli_num_rows($resultado);

			if($count == 0){

				$sql = "INSERT INTO Noticias (vot_news_categ, vot_news_name, vot_news_likes) VALUES ('".$categoria."', '".$titulo."', 1)";

				echo "<br/>";
				echo $sql;
				echo "<br/>";

				if(mysqli_query($conn, $sql)){
					echo "<br/>";
					echo "È uma nova notícia! Estou cadastrando no Banco de Dados!";
					echo "<br/>";
				}else{
					echo "<br/>";
					echo "Não foi possível cadastrar uma nova notícia!";
					echo "<br/>";
				}
				
			}else{
				$row = mysqli_fetch_array($resultado);

				echo "<br/>";
				echo "<pre>".print_r($row)."</pre>";
				echo "Curtidas anteriores = " + $row['vot_news_likes'];
				echo "<br/>";
				echo "Curtidas DEPOIS = " + intval($row['vot_news_likes']) + 1;

				$like = intval($row['vot_news_likes']) + 1;

				$sql = "UPDATE Noticias SET vot_news_likes=".$like." WHERE vot_news_name='".$titulo."' and vot_news_categ='".$categoria."'; "  or die("NÃO FOI POSSÍVEL ATUALIZAR OS DADOS");

				echo "<br/>";
				echo $sql;
				echo "<br/>";

				if(mysqli_query($conn, $sql)){
					echo "<br/>";
					echo "É uma notícia já existente! Estou atualizando no Banco de Dados!";
					echo "<br/>";
				}else{
					echo "<br/>";
					echo "Não foi possível atualizar a notícia!";
					echo "<br/>";
				}
			}
		  
		}else{
			echo "Erro na busca dos dados<br>";
		}
	}
}else{
	// Entra aqui quando conseguiu conexão com o Banco de Dados com sucesso, mas não passou os valores (Votorantim) e precisa fazer o login para visualizar a tabela de dados;
	
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
		<img src=\"images/votorantim.png\" class='logoPrincipal' alt=\"Logo\">
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
}

$conn->close();