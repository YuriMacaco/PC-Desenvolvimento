<?php
	error_reporting(0);
	set_time_limit(80);
	
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
			top: 				15px;
			margin: 			auto;
			width: 				50%;
			padding:			10px;
			background-color: 	#0d5b5d;
			color:				white;
		}
		span {
			font-family: 		"Open Sans";
			font-size: 			14px;
			z-index: 			10;
		}
		span.mySpan {
			padding:			10px;
			background-color:	#0d5b5d;
			display:			block;
			margin: 			auto;
			width: 				50%;
			height:				auto;
			bottom: 			15;
			word-wrap: 			break-word;
			min-height:			160px;
			color:				white;
		}
		.allContent {
			width: 				90%;
			margin:				auto;
			margin-top:			20px;
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
			font-size: 	14px;
		}
		tr:nth-child(even){background-color: #f2f2f2;}
		tr:hover {background-color: #ddd;}
		th {
			padding-top: 		12px;
			padding-bottom: 	12px;
			text-align: 		left;
			background-color: 	#0d5b5d;
			color: 				white;
		}
		th:hover {
			cursor:				pointer;
			background-color:	#6cb978;
			color: 				white;
		}
		table.dataTable thead .sorting:after,
		table.dataTable thead .sorting:before,
		table.dataTable thead .sorting_asc:after,
		table.dataTable thead .sorting_asc:before,
		table.dataTable thead .sorting_asc_disabled:after,
		table.dataTable thead .sorting_asc_disabled:before,
		table.dataTable thead .sorting_desc:after,
		table.dataTable thead .sorting_desc:before,
		table.dataTable thead .sorting_desc_disabled:after,
		table.dataTable thead .sorting_desc_disabled:before {
	</style>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script src="https://code.jquery.com/jquery-3.1.1.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> 
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.quicksearch/2.3.1/jquery.quicksearch.js"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	</head>';

	$servername = "localhost";
	$username 	= "b2";
	$password 	= "dsrloZm5WjX0ecDIDpyTUMLk00ZFBq";
	$dbname 	= "historicob2bkp";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	mysqli_set_charset($conn, "utf8");
	
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}
	
//================================================================================================
	
	function separMesParaArray($meusDados, $meuMes){
		$resMeses 	= explode(",", $meusDados);
		$indiceMes	= intval($meuMes) - 1;
		if(!empty($resMeses[0])){
			while (empty($resMeses[$indiceMes])) {
				$indiceMes -= 1;
			}
			$resDias = $resMeses[$indiceMes];
			return $resDias;.
		}else{
			return false;
		}
	}
	
	function separDiaParaArray($meusDados, $meuMes){
		$resMeses 	= explode("\n", $meusDados);
		$indiceMes	= intval($meuMes) - 1;
		if(!empty($resMeses[0])){
			while (empty($resMeses[$indiceMes])) {
				$indiceMes -= 1;
			}
			$resDiasTotal = $resMeses[$indiceMes];
			$resDias = explode(',', $resDiasTotal);
			return $resDias;
		}else{
			return false;
		}
	}
	
	function dbFail($mensagem){
		die("
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
			opacity: 			0.1;'>
		</div>
		<br/><br/>
		<center>
		<h1>Não há conexão ou há algum bloqueio na conexão com o banco de dados</h1>
		<br/><br/>
		<span>
		Você realizou o login corretamente? <b><a href='terminais_b2.php'>Clique aqui para realizar o login</a></b>
		<br/><br/><br/><br/><br/>
		<span class='mySpan'><b>Report técnico</b><br/>
		<br/>" . str_replace(["bmidiaco_", "@", "localhost", "to database"], "", $mensagem) . "</span></span>");
	}
	
//================================================================================================
	
	if($conn -> connect_error){
		echo "<br/> Erro de conexão";
	
	}else if (($login == "suporte@b2midia.com.br" && $senha == "b2@adm10321") xor ($login == "comercial@b2midia.com.br" && $senha == "b2@com10321")) {
	
		$sql = "SELECT * FROM dadosgerais;";
		$result = mysqli_query($conn, $sql);
		$tableFile = '
		<div style="
			background-image:	url(images/patternfail.jpg);
			background-repeat:	repeat;
			width: 				100%;
			height: 			100%;
			position: 			absolute;
			top: 				0; 
			left: 				0;
			z-index:			-10;
			opacity: 			0.1;">
		</div>
		<div class="form-group input-group" style="width: 90%; margin:auto; margin-top: 50px;">
			<span class="input-group-addon">
				<i class="glyphicon glyphicon-search"></i>
			</span>
			<input name="consulta" id="txt_consulta" placeholder="Consultar" type="text" class="form-control">
		</div>
		<div class="allContent">
			<table id="minhasCurtidas" class="table table-striped table-bordered table-sm" cellspacing="0" width="1280px">
				<thead>
					<tr height="30px">
						<th onclick="sortTable(0)">Servidor</th>
						<th onclick="sortTable(1)">Conta</th> 
						<th onclick="sortTable(2)">Nome do Terminal</th>
						<th onclick="sortTable(4)">Última Sincronização</th>
						<th onclick="sortTable(5)">Dia (Verificação)</th>
						<th onclick="sortTable(6)">Online no dia (%)</th>
						<th onclick="sortTable(7)">Online no mês (%)</th>
					</tr>
				</thead>
				<tbody>';
		
		$idx = 0;
		
		if ($result) {
		
			if ($result->num_rows > 0) {
				
				while ($row = $result->fetch_assoc()) {
					
					$resLinhaMes 	= separMesParaArray($row["mediames_on_dados"], date("m"));
					$resLinhasDias	= separDiaParaArray($row["historico_dados"], date("m"));
					
					if($resLinhaMes != false && $resLinhasDias != false){
						$diaMedia_dados	= $resLinhasDias[count($resLinhasDias) - 1];
						$diaMedia_dia	= substr($diaMedia_dados, 0, 2);
						$diaMedia_valor = trim(substr($diaMedia_dados, 6));
						$mesMedia_mes	= substr($resLinhaMes, 0, 2);
						$mesMedia_valor	= trim(substr($resLinhaMes, 3));
						$dataVerifica	= $diaMedia_dia . "/" . $mesMedia_mes;
						$tableFile .= "<tr><td><b>".$row["servidor_dados"]."</b></td><td>".$row["conta_dados"]."</td><td>".$row["nome_dados"]."</td><td>".$row["datasinc_dados"]."</td><td>".$dataVerifica."</td><td>".$diaMedia_valor."</td><td>".$mesMedia_valor."</td></tr>";
					}
				}
				
				$result->close();
			
			}else{
				dbFail("Não foi possível coletar os dados -1");
			}
			
			$tableFile .= '
					</tbody>
				</table>
			</div>
			<div class="myBottom"></div>
			<script>
				
				$("input#txt_consulta").quicksearch("table#minhasCurtidas tbody tr");
				
				$("th").click(function(){
				var table = $(this).parents("table").eq(0)
				var rows = table.find("tr:gt(0)").toArray().sort(comparer($(this).index()))
				this.asc = !this.asc
				
				if (!this.asc){rows = rows.reverse()}
					for (var i = 0; i < rows.length; i++){
						table.append(rows[i])}
				})
				
				function comparer(index) {
					return function(a, b) {
						var valA = getCellValue(a, index),
						valB = getCellValue(b, index)
						return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.toString().localeCompare(valB)
					}
				}
				
				function getCellValue(row, index){
					return $(row).children("td").eq(index).text()
				}
			</script>';
	
			echo $tableFile;
		}
	}else{
		echo "<br/> Erro de login ou conexão";
		dbFail(trim($conn->connect_error));
	}
	
	$conn->close();
?>