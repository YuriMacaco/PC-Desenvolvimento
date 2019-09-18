<?php
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	header('Content-type: text/xml charset="UTF-8"');
	require_once('simple_html_dom_2.php');

	$url		= "https://br.investing.com/currencies/sek-brl";
	$userAgent 	= "Mozilla/5.0 (Windows NT 5.1; pt-BR; rv:1.8.1.6) Gecko/20070725 Firefox/61.0";

	$c = curl_init();
	curl_setopt($c, CURLOPT_USERAGENT, 		$userAgent);
	curl_setopt($c, CURLOPT_URL,			$url);
	curl_setopt($c, CURLOPT_HEADER, 		false);
	curl_setopt($c, CURLOPT_FAILONERROR, 	true);
	curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($c, CURLOPT_AUTOREFERER, 	true);
	curl_setopt($c, CURLOPT_RETURNTRANSFER,	true);
	curl_setopt($c, CURLOPT_VERBOSE, 		false);
    $page = curl_exec($c);
    curl_close($c);

    $corpinho 	= explode('<div class="overViewBox instrument">', $page);
    $corpinho01	= explode('<div class="bottom lighterGrayFont arial_11">', $corpinho[1]);

    //echo $corpinho01[0];

	$corpo = str_get_html($corpinho01[0]);

	$header='<?xml version="1.0" encoding="UTF-8" ?>
	<rss version="2.0">
	<channel>
		<title>Moeda</title>
		<description>Moeda - Coroa Sueca</description>
		<link></link>
		<language>pt-br</language>';	
	echo $header;

	$ul = $corpo->find('div[class=left]');

	foreach($ul[0]->find('div[class^=top bold inlineblock]') as $noticias) {
		
		$info			= $noticias -> find('<span');
		$valor			= trim(html_entity_decode($info[0]->plaintext));
		$variacao		= trim(html_entity_decode($info[1]->plaintext));
		$porcentagem	= trim(html_entity_decode($info[3]->plaintext));

			echo "<item>";
			echo "<valor>".$valor."</valor>";
			echo "<variacao>".$variacao."</variacao>";
			echo "<porcentagem>".$porcentagem."</porcentagem>";
			echo "<datahora>".date("d/m/Y H:i:s")."</datahora>";
			echo "</item>";

			$i++;
			if ($i >=3){
				break;
			}
	}
	echo "</channel>
	</rss>";

	$corpo->clear();
	unset($corpo);
?>