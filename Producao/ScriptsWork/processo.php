<?php

$idade = $_POST['idade'];

$mensagens = array();

if (!is_numeric($idade)) {
	$mensagens['erro'] = 1;
	$mensagens['msg'] = "Você não digitou um numero !";
}else{
	$mensagens['erro'] = 0;
	$ano = date("Y") - $idade;
	$mensagens['ano'] = $ano;
}

die (json_encode($mensagens));

?>