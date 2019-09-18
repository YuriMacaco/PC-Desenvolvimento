<?php
	error_reporting(0);
	
	//session_start();
	
	$login = $_POST['username'];
	$senha = $_POST['password'];
		
	header("Content-Type: text/html; charset=utf-8");
	
	echo'
	<style>
	
		h1 {
			display: 			block;
			font: 				600 1.5em/1 \'Open Sans\', sans-serif;
			text-align: 		center;
			letter-spacing: 	.2em;
			line-height: 		1.6;
			top: 				4%;
			margin: 			auto;
			width: 				50%;
			padding:			10px;
			background-color: 	#0083b9;
		}

		span {
			font-family: 		"Open Sans";
			font-size: 			14px;
			z-index: 			10;
		}
		
		span.mySpan{
			padding:			10px;
			background-color:	#0083b9;
			display:			block;
			margin: 			auto;
			width: 				50%;
			height:				auto;
			bottom: 			15;
			word-wrap: 			break-word;
			min-height:			160px;
		}

		.allContent{
			width: 				80%;
			margin:				auto;
			margin-top:			50px;
			overflow: 			scroll;
			height:				500px;
			overflow:			auto;
			background: 		rgb(252, 252, 252);
			border: 			3px solid rgb(245, 245, 245, 0.5);
			border-radius: 		5px;
		}
		
		table {
			font-family: 		"Trebuchet MS", Arial, Helvetica, sans-serif;
			border-collapse: 	collapse;
			width: 				100%;
			margin:				auto;
			padding:			15px;
		}
		
		.allContent::-webkit-scrollbar {
			width: 15px;
		}

		.allContent::-webkit-scrollbar-track {
			box-shadow: 	inset 0 0 5px grey; 
			border-radius: 	10px;
		}
		 
		.allContent::-webkit-scrollbar-thumb {
			background: 	#cccccc; 
			border-radius: 	10px;
		}

		.allContent::-webkit-scrollbar-thumb:hover {
			background: 	#dddddd; 
		}

		td, th {
			border: 	1px solid #ddd;
			padding: 	8px;
		}

		tr:nth-child(even){background-color: #f2f2f2;}
		tr:hover {background-color: #ddd;}

		th {
			padding-top: 		12px;
			padding-bottom: 	12px;
			text-align: 		left;
			background-color: 	#0083b9;
			color: 				white;
		}
		
		th:hover{
			cursor:				pointer;
			background-color:	#00b3ff;
			color: 				#ffffff;
		}
		
	</style>
	<meta charset="utf-8">';

	$servername = "localhost";
	$username 	= "b2";
	$password 	= "dsrloZm5WjX0ecDIDpyTUMLk00ZFBq";
	$dbname 	= "b2_votorantim";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	mysqli_set_charset($conn, 'utf-8');
	mysqli_query("SET NAMES 'utf8'");
        mysqli_query('SET character_set_connection=utf8');
        mysqli_query('SET character_set_client=utf8');
        mysqli_query('SET character_set_results=utf8');
	
	// Check connection
	if ($login != "vot" || $senha != "b2@vot123" || $conn -> connect_error) {
		die("
		<head>
			<meta charset='UTF-8'>
			<link rel='stylesheet' href='css/styleLogin.css'>
		</head>
		<div style='
			background-image:	url(/b2midia/integracoes/images/patternfail.jpg);
			background-repeat:	repeat;
			width: 				100%;
			height: 			100%;
			position: 			absolute;
			top: 				0; 
			left: 				0;
			z-index:			-10;
			opacity: 			0.1;'>
		</div>
		<br/><br/>
		<center>
		<h1>Não há conexão ou há algum bloqueio na conexão com o banco de dados</h1>
		<br/><br/>
		<span>
		Você está realizou o login corretamente? <b><a href='noticias_votorantim.php'>Clique aqui para realizar o login</a></b>
		<br/>
		Em caso de dúvidas ou incidentes, entre em contato com a B2 Mídia<br/>
		<br/><br/><br/>
		<b>suporte@b2midia.com.br</b><br/><br/><b>(11) 2382-9631</b>
		<br/><br/><br/><br/><br/>
		<span class='mySpan'><b>Report técnico</b><br/>
		<br/>".str_replace(["bmidiaco_", "@", "localhost", "to database"], "", trim($conn->connect_error))."</span></span>");

	}else{

		$resultado = mysqli_query($conn, " SELECT vot_news_name, vot_news_categ, vot_news_likes FROM Noticias ");
		
		if ($resultado -> num_rows > 0) {
			
			$tableFile = '
			<div style="
				background-image:	url(/b2midia/integracoes/images/patternfail.jpg);
				background-repeat:	repeat;
				width: 				100%;
				height: 			100%;
				position: 			absolute;
				top: 				0; 
				left: 				0;
				z-index:			-10;
				opacity: 			0.1;">
			</div>
			<div class="myFooter"></div>
			<div class="allContent">
				<table id="minhasCurtidas">
				<tr>
					<th onclick="sortTable(0)">Item</th>
					<th onclick="sortTable(1)">Categoria</th> 
					<th onclick="sortTable(2)">Curtidas</th>
				</tr>';
			
			while($row = $resultado -> fetch_assoc()) {
				$tableFile .= "<tr><td>".utf8_encode($row["vot_news_name"])."</td><td>".$row["vot_news_categ"]."</td><td>".$row["vot_news_likes"]."</td></tr>";
			}
			
			$tableFile .= '</table></div>
				<div class="myBottom"></div>
				<script>
					function sortTable(n) {
					  
						var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
						table 		= document.getElementById("minhasCurtidas");
						switching 	= true;
						dir 		= "asc"; 

						while (switching) {

							switching = false;
							rows = table.rows;

							for (i = 1; i < (rows.length - 1); i++) {

								shouldSwitch 	= false;
								x 				= rows[i].getElementsByTagName("TD")[n];
								y 				= rows[i + 1].getElementsByTagName("TD")[n];
								x_number		= Number(x.innerHTML);
								y_number		= Number(y.innerHTML);
								
								if(isNaN(x_number)){
									if (dir == "asc" && x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
										shouldSwitch = true;
										break;
										
									} else if (dir == "desc" && x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
										shouldSwitch = true;
										break;
									}
								}else{
									if (dir == "asc" && x_number > y_number) {
										shouldSwitch = true;
										break;
										
									} else if (dir == "desc" && x_number < y_number) {
										shouldSwitch = true;
										break;
									}
								}
							}
							
							if (shouldSwitch) {
								rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
								switching = true;
								switchcount ++;
								
							} else {
								/* If no switching has been done AND the direction is "asc",
								set the direction to "desc" and run the while loop again. */
								if (switchcount == 0 && dir == "asc") {
									dir = "desc";
									switching = true;
								}
							}
						}
					}
				</script>';
			
			echo $tableFile;
		}
	}
	
	$conn->close();
?>