<?php
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	header('Content-type: text/xml charset="UTF-8"');
	require_once('simple_html_dom_2.php');
	
    $url		= "http://www.cetsp.com.br/noticias.aspx";
    $url2		= "http://cetsp1.cetsp.com.br/monitransmapa/agora/graficolimite.asp";
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

	$header='<?xml version="1.0" encoding="UTF-8" ?>
	<rss version="2.0">
	<channel>
		<title>CET SP</title>
		<description>CET SP</description>
		<link></link>
		<language>pt-br</language>';	
	echo $header;
	
	$ul = $corpo->find('div[class=boxConteudoPrincipal]');

	foreach($ul[0]->find('div[class=boxItemNoticia]') as $noticias) {
		
		$data 			= $noticias -> find('<strong');
		$title 			= $noticias -> find('<a');
		$description 	= $noticias -> find('<a');

		$title			= trim(html_entity_decode($title[0]->plaintext));
		$description	= trim(html_entity_decode($description[1]->plaintext));
		$data			= trim(html_entity_decode($data[0]->plaintext));

			echo "<item>";
			echo "<pubdate><![CDATA[".$data."]]></pubdate>";
			echo "<title><![CDATA[".$title."]]></title>";
			echo "<description>".$description."</description>";
			echo "<datahora>".date("d/m/Y H:i:s")."</datahora>";
			echo "</item>";

			$i++;
			if ($i >=3){
				break;
			}
	}
	
	echo "</channel>
	</rss>";
	
	$corpo -> clear();
	unset($corpo);
?>