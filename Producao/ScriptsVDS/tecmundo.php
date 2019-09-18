<?php
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	header('Content-type: text/xml charset="UTF-8"');
	require_once('simple_html_dom_2.php');
	
	//$item 		= $_GET["item"];
    $url		= "https://www.tecmundo.com.br/noticias";
	$userAgent 	= "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36";
	
	$c = curl_init();
	curl_setopt($c, CURLOPT_USERAGENT, 		$userAgent);
	curl_setopt($c, CURLOPT_URL,			$url);
	curl_setopt($c, CURLOPT_REFERER, 		$url);
	curl_setopt($c, CURLOPT_HEADER, 		false);
	curl_setopt($c, CURLOPT_FAILONERROR, 	true);
	curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($c, CURLOPT_AUTOREFERER, 	true);
	curl_setopt($c, CURLOPT_RETURNTRANSFER,	true);
	curl_setopt($c, CURLOPT_VERBOSE, 		false);
	curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($c,	CURLOPT_POST,			true);
	curl_setopt($c,	CURLOPT_POSTFIELDS,		"regno=$Number");
	curl_getinfo($c, CURLINFO_HTTP_CODE);
    $page = curl_exec($c);
    curl_close($c);

	$corpo = str_get_html($page);

	$header='<?xml version="1.0" encoding="UTF-8" ?>
	<rss version="2.0">
	<channel>
		<title>TecMundo</title>
		<description>TecMundo</description>
		<link></link>
		<language>pt-br</language>';	
	echo $header;
	
	$ul = $corpo->find('div[class=tec--list tec--list--lg]');
	
	foreach($ul[0]->find('<article') as $noticias) {
		
		$title 			= $noticias -> find('<h3');
		$img 			= $noticias -> find('<img');
		$date			= $noticias -> find('div[class=tec--timestamp tec--timestamp--lg]');
		
		$title			= trim(html_entity_decode($title[0]->plaintext));
		$date			= trim(html_entity_decode($date[0]->plaintext));

		$date			= explode("       ", $date);
		
		$img			= $img[0];
		$img 			= explode('"',$img);
		$img			= explode("?w=164&h=118&mode=crop&scale=both", $img[1]);

			echo "<item>";
			echo "<pubdate><![CDATA[".$date[1]."]]></pubdate>";
			echo "<title><![CDATA[".$title."]]></title>";
			echo "<linkfoto>".$img[0]."</linkfoto>";	
			echo "<datahora>".date("d/m/Y H:i:s")."</datahora>";
			echo "</item>";

		$i++;
		if ($i >=5){
			break;
		}
	}
	
	echo "</channel>
	</rss>";
	
	$corpo -> clear();
	unset($corpo);
?>