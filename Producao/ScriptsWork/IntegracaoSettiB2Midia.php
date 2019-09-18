<?php

	// Definição do link/url para requisição
	$url = "http://escola24x7.com.br/Setti_Libs/Setti/php/API/SettiAPI.php";

	// Valor de param
	$parametro 	 = "=AlVWFHVudGMRBTM1I1Vw1UTrxWVUhlVyJVRxUTTFJ1alpGbIVleSNnYWBHdNZEZh1ERshkVtlVNWFDbxJVbxoWTFVTdZ5WW4ZVMwZUTVJFaNVkWIZFVSNnYW9WP";

	//Primeira requisição que será feita à API, passando func, param e method
	//aqui funciona como esperado
	$requisicao1 = 'func=apiTotem&param="'.$parametro.'"&method=authenticate';

	//Iniciamos a requisição
	$curl1 = curl_init($url);
	curl_setopt($curl1, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl1, CURLOPT_POSTFIELDS, $requisicao1);
	//Aqui recebemos (com sucesso) a primeira resposta da API
	$resposta1 = curl_exec($curl1);
	curl_close($curl1);

	//Com a resposta recebida, separamos apenas o valor da Token nova recebida no formato string
	$tokenGerada = explode('"token":"', $resposta1)[1];
	$tokenGerada = explode('"}}', $tokenGerada)[0];
	$tokenGerada = $tokenGerada;

	//Segunda requisição que será feita à API, passando func, param e method
	//aqui não funciona
	//Antes era:
	//$requisicao2 = 'func=apiTotem&param='.$parametro.'&method=getSenhas&config{"totemId":2,"token":"'.$tokenGerada.'"}';
	$requisicao2 = 'func=apiTotem&param='.$parametro.'&method=getSenhas&config["totemId"]=2&config["token"]="'.$tokenGerada.'"';
	$data = array(
		'func'   => 'apiTotem',
		'param'  => $parametro,
		'method' => 'getSenhas',
		'config' => json_encode(array(
			'totemId' => 2,
			'token'   => $tokenGerada
		))
	);

	$curl2 = curl_init($url);
	curl_setopt($curl2, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl2, CURLOPT_POSTFIELDS, http_build_query($data));
	$resposta2 = curl_exec($curl2);
	curl_close($curl2);

	//Exibe o resultado de cada requisição em linhas formatadas abaixo
	echo "<br/><b>Primeira requisição:</b><br/>".$requisicao1;
	echo "<br/><br/><b>Token Gerada:</b><br/>".$tokenGerada;
	echo "<br/><br/><br/><br/><b>Segunda requisição:</b><br/>".$requisicao2;
	echo "<br/><br/><br/><br/><b>Resposta da primeira requisição:</b><br/>".$resposta1;
	echo '<br/><br/><br/><br/><b>Resposta final do servidor:</b><br/>"'.$resposta2.'"';
?>