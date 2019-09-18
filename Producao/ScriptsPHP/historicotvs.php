<?php
//error_reporting(E_ERROR | E_WARNING | E_PARSE);
include_once('simple_html_dom.php');
header('Content-Type: text/html; charset=utf-8');
set_time_limit(80);

$executionStartTime = microtime(true);

$servername = "localhost";
$mydb		= "historicob2bkp";
$username 	= "b2";
//$username = "b2midia";
$password 	= "dsrloZm5WjX0ecDIDpyTUMLk00ZFBq";
//$password = "b2midia10";

$margem		= 30; //Margem de tempo em minutos para o momento de checagem para determinar se está online ou não
$qtdVzs		= 4;

//=========================================================================================================

function separResParaArray($meusDados, $meuMes){
	$resMeses 	= explode("\n", $meusDados);
	$indiceMes	= intval($meuMes) - 1;
	if (empty($indiceMes[$indiceMes])) {
		$indiceMes = count($resMeses) - 1;
	}
	$resDiasTotal = $resMeses[$indiceMes];
	$resDias = explode(',', $resDiasTotal);
	return $resDias;
}

//=========================================================================================================

function readAllTerminals($url, $servidor){

	$idxTotal 		= 0;
	$dateAgora 		= new DateTime('now');
	$dateAgora		-> modify('-3 hours');
	$diaTotalNorm	= $dateAgora->format('d-m-Y H:i:s');
	$diaTotalY		= $dateAgora->format('Y-m-d H:i:s');
	$diaAgora 		= $dateAgora->format('d');
	$mesAgora 		= $dateAgora->format('m');
	$anoAgora		= $dateAgora->format('Y');
	$mesAnoAgora	= $dateAgora->format('m-Y');
	
	$conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['mydb']);
	mysqli_set_charset($conn, "utf8");

	if (!$conn){
		die("<br/><br/> Não há conexão ou há algum bloqueio na conexão com o banco de dados. <br/><br/>");
	}
	
	$page	= file_get_contents ($url);
	$corpo 	= str_get_html($page);
	$dados 	= $corpo->find('div[class=row-fluid]');

	foreach ($dados[0]->find('tr') as $linhas) {

		if (!empty($linhas->find('td'))) {
			
			$infolinhas = $linhas->find('td');

			if (count($infolinhas) > 2) {
				$conta_nome			= trim($infolinhas[2]->plaintext);	//Nome conta
				$conta_email		= trim($infolinhas[1]->plaintext);	//E-mail conta
				$ultima_sinc		= trim($infolinhas[3]->plaintext);	//Ultima sincronização
				$ultima_sinc_str	= strtotime($ultima_sinc);
				$diaHoje_time		= strtotime($diaTotalNorm);
				$dataSincFormat		= date("Y-m-d H:i:s", $ultima_sinc_str);
				$dataDif_mins		= abs(round(($diaHoje_time - $ultima_sinc_str) / 60));
				
				//echo "<br/> diaTotalNorm = $diaTotalNorm";
				//echo "<br/> ultima_sinc = $ultima_sinc <br/>";
				
				$status_atual = "0";
				if ($dataDif_mins < intval($GLOBALS['margem'])) {
					$status_atual = "1";
				}
				
				$sql = "SELECT historico_dados, mediames_on_dados FROM dadosgerais WHERE servidor_dados='$servidor' AND conta_dados='$conta_email' AND nome_dados='$conta_nome' AND YEAR(dataisdb_dados) =" . $anoAgora . " LIMIT 1;";
				$result = mysqli_query($conn, $sql);

				if ($result) {
					
					if ($result->num_rows === 0) {
						$sql = "INSERT INTO dadosgerais (servidor_dados, conta_dados, nome_dados, datasinc_dados, historico_dados, mediames_on_dados) VALUES ('$servidor', '$conta_email', '$conta_nome', '$dataSincFormat', '" . $mesAnoAgora . "," . $diaAgora . "$status_atual', '" . $mesAgora . "-');";
						if (!mysqli_query($conn, $sql)) {
							die("<br/>Erro nova info!<br/>");
						}

					} else {
						
						$sql = "UPDATE dadosgerais SET dataisdb_dados = '" . $diaTotalY . "' WHERE servidor_dados='$servidor' AND conta_dados='$conta_email' AND nome_dados='$conta_nome' AND YEAR(dataisdb_dados) = " . $anoAgora . " LIMIT 1;";
						if (!mysqli_query($conn, $sql)) {
							die("<br/>Erro ao tentar atualizar o horário/momento de última ação para este terminal!<br/>");
						}
						
						$qtdChars				= $GLOBALS['qtdVzs'] + 2;
						$row 					= $result->fetch_assoc();
						$myResult_text 			= $row["historico_dados"];
						$myMedia_text 			= $row["mediames_on_dados"];
						$resDias 				= separResParaArray($myResult_text, $mesAgora);
						$firstDados				= separResParaArray($myMedia_text, $mesAgora);
						$resDias_qtd 			= count($resDias)-1;
						$resMes 				= substr($firstDados[count($firstDados) - 1], 0, 2);
						$resDias_mes 			= substr($resDias[0], 0, 2);
						$dadosDia_hoje 			= $resDias[$resDias_qtd];
						$resDia_status 			= substr($dadosDia_hoje, 2, $qtdChars - 2);
						$qtdCharDia 			= strlen($dadosDia_hoje);
						$idxMediaAtual			= 1;
						$idxMediaAtualEfetiva	= 1;
						$mediaAtual 			= 0;
						$resultaDia_somastatus	= 0;

						if ($resDias_mes != $mesAgora) {
							$sql = "UPDATE dadosgerais SET historico_dados = CONCAT(historico_dados, '\n" . $mesAnoAgora . "," . $diaAgora . "$status_atual'), datasinc_dados = '$dataSincFormat' WHERE servidor_dados='$servidor' AND conta_dados='$conta_email' AND nome_dados='$conta_nome' AND YEAR(dataisdb_dados) = " . $anoAgora . " LIMIT 1;";
							if (!mysqli_query($conn, $sql)) {
								die("Erro ao tentar atualizar para nova linha em historico_dados!<br/>");
							}
							
						} else {
							
							if (substr($dadosDia_hoje, 0, 2) != $diaAgora) {
								$sql = "UPDATE dadosgerais SET historico_dados = CONCAT(historico_dados, '," . $diaAgora . "$status_atual'), datasinc_dados = '$dataSincFormat'  WHERE servidor_dados='$servidor' AND conta_dados='$conta_email' AND nome_dados='$conta_nome' AND YEAR(dataisdb_dados) = " . $anoAgora . " LIMIT 1;";
								if (!mysqli_query($conn, $sql)) {
									die("<br/>Erro ao tentar atualizar para novo índice separado por vírgula (novo dia)!<br/>");
								}

							} else {

								if ($qtdCharDia < $qtdChars) {
									$sql = "UPDATE dadosgerais SET historico_dados = CONCAT(historico_dados, '$status_atual'), datasinc_dados = '$dataSincFormat' WHERE servidor_dados='$servidor' AND conta_dados='$conta_email' AND nome_dados='$conta_nome' AND YEAR(dataisdb_dados) = " . $anoAgora . " LIMIT 1;";
									if (!mysqli_query($conn, $sql)) {
										die("<br/>Erro ao tentar atualizar para status novo no mesmo dia!<br/>");
									}

								} else if ($qtdCharDia == $qtdChars) {
									$resultaDia_somastatus = intval(substr($resDia_status, 0, 1)) + intval(substr($resDia_status, 1, 1)) + intval(substr($resDia_status, 2, 1)) + intval(substr($resDia_status, 3, 1));
									$resDia_soma = $resultaDia_somastatus * (100 / ($qtdChars - 2)); // Aqui o valor primeiramente é/foi * 25 { no lugar de * (100 / ($qtdChars - 2)) } porque realizamos até 4 conexões no dia, ou seja, 100/4 = 25. Se necessário trocar, lá em cima substitua o valor da variável qtdVzs
									$sql_upd = "UPDATE dadosgerais SET historico_dados = CONCAT(historico_dados, '$resDia_soma'), datasinc_dados = '$dataSincFormat' WHERE servidor_dados='$servidor' AND conta_dados='$conta_email' AND nome_dados='$conta_nome' AND YEAR(dataisdb_dados) = " . $anoAgora . " LIMIT 1;";
									if (!mysqli_query($conn, $sql_upd)) {
										die("<br/>Erro ao tentar atualizar status da média para o final do dia!<br/>");
									}
								
								} else if ($qtdCharDia > $qtdChars) {
									
									$idxTeste = 0;
									
									if ($resDias_qtd < 2) {
										$mediaAtual = intval(substr($dadosDia_hoje, $qtdChars));
										
									} else {
										
										while ($idxMediaAtual < $resDias_qtd + 1) {
											if(strlen($resDias[$idxMediaAtual]) > $qtdChars + 2){
												$mediaAtual += intval(substr($resDias[$idxMediaAtual], $qtdChars));
												$idxMediaAtualEfetiva++;
											}
											$idxMediaAtual++;
										}
									}

									if ($idxMediaAtualEfetiva > 0) {
										
										if($idxMediaAtualEfetiva > 1){
											$idxMediaAtualEfetiva -= 1;
										}

										$resMedia = $mediaAtual / $idxMediaAtualEfetiva;
										$resMedia = (int)$resMedia;
										
										if ($resMes != $mesAgora) {
											$sql = "UPDATE dadosgerais SET mediames_on_dados = CONCAT(mediames_on_dados, ',$mesAgora" . "-" . "$resMedia'), datasinc_dados = '$dataSincFormat' WHERE servidor_dados='$servidor' AND conta_dados='$conta_email' AND nome_dados='$conta_nome' AND YEAR(dataisdb_dados) = " . $anoAgora . " LIMIT 1;";
											if (!mysqli_query($conn, $sql)) {
												die("<br/>Erro ao tentar atualizar para nova 'vírgula' em mediames_on_dados!<br/>");
											}

										} else {
											$resGeral = "$resMes" . "-" . "$resMedia";
											$sql = "UPDATE dadosgerais SET mediames_on_dados = '$resGeral', datasinc_dados = '$dataSincFormat' WHERE servidor_dados='$servidor' AND conta_dados='$conta_email' AND nome_dados='$conta_nome' AND YEAR(dataisdb_dados) = " . $anoAgora . " LIMIT 1;";
											if (!mysqli_query($conn, $sql)) {
												die("<br/>Erro ao tentar atualizar direto no valor de média do mês em mediames_on_dados!<br/>");
											}
										}
									}
								} else {
									die("<br/><br/><br/> Erro no Banco de Dados!");
								}
							}
						}
					}
				} else {
					die("<br/><br/><br/> Erro no Banco de Dados!");
				}
			}
		}
	}

	$conn  -> close();
	$corpo -> clear();
	unset($corpo);
}

//====================================================================================

readAllTerminals("http://b2midia:b2m1d1410@tv.b2midia.com.br/admin/?trigger_event_0=terminalindex&status=all", "tv1");
readAllTerminals("http://b2midia:b2m1d1410@tv2.b2midia.com.br/admin/?trigger_event_0=terminalindex&status=all", "tv2");

$executionEndTime 	= microtime(true);
$duration 			= $executionEndTime - $executionStartTime;
$hours 				= (int)($duration / 3600);
$minutes 			= (int)($duration / 60) - ($hours * 60);
$seconds 			= (int)$duration - ($hours * 3600) - ($minutes * 60);

echo "<br/><br/> <h2 style='color: red;'>Total de segundos gastos para execução deste script = " . $seconds . "</h2> <br/><br/>";