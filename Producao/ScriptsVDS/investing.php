<?php
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	header('Content-type: text/xml charset="UTF-8"');
	require_once('simple_html_dom_2.php');
	
    $url_atlas_a	= "https://br.investing.com/equities/atlas-copco-a";
	$url_atlas_b	= "https://br.investing.com/equities/atlas-copco-b";

	$header='<?xml version="1.0" encoding="UTF-8" ?>
	<rss version="2.0">
	<channel>
		<title>Bolsas de Valores</title>
		<description>Bolsas de Valores - Investing</description>
		<link></link>
		<language>pt-br</language>';	
	echo $header;
	
	multipleTargets_investing($url_atlas_a,"Atlas Copco AB Class A (ATCOa)");
	multipleTargets_investing($url_atlas_b,"Atlas Copco AB Series B (ATCOb)");

	function multipleTargets_investing($url_resq, $nome){

		$url		= $url_resq;
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

		$corpo = str_get_html($page);

		$ul = $corpo->find('div[class^=overViewBox instrument]');

		foreach($ul[0]->find('div[class^=top bold inlineblock]') as $noticias) {
			
			$info			= $noticias -> find('<span');
			$valor			= trim(html_entity_decode($info[0]->plaintext));
			$variacao		= trim(html_entity_decode($info[1]->plaintext));
			$porcentagem	= trim(html_entity_decode($info[3]->plaintext));

				echo "<item>";
				echo "<nome>".$nome."</nome>";
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

		$corpo -> clear();
		unset($corpo);
	}
		
	echo "</channel>
	</rss>";
?>