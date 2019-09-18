<?php
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	header('Content-type: text/xml charset="UTF-8"');
	require_once('simple_html_dom.php');
	
	$item 		= $_GET["item"];
    $url		= "https://www.eltiempo.com/mundo/latinoamerica";
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
		<title>El Tiempo</title>
		<description>Latino American</description>
		<link></link>
		<language>pt-br</language>';	
	echo $header;
	
	$ul = $corpo->find('div[class=notas mas-notas-bk]');
	//$i 	= 0;
	//echo $ul[0];
	
	foreach($ul[0]->find('div[class=nota listing]>') as $noticias) {

		//echo $noticias;
		
		$title 			= $noticias -> find('a[class=boton]');
		//$description 	= $noticias -> find('<p');
		$img 			= $noticias -> find('<meta');
		$date			= $noticias -> find('<span');
		
		$titles			= trim(html_entity_decode($title[0]->plaintext));
		$description	= trim(html_entity_decode($title[1]->plaintext));
		$date			= trim(html_entity_decode($date[0]->plaintext));

		$img			= $img[1]->content;
		$img 			= explode('secondary_default',$img);

		//$img			= substr($img, 0, strlen($img) - 6)."md.jpg";
		
		echo "<item>";
		echo "<datas><![CDATA[".$titles."]]></datas>";
		echo "<title><![CDATA[".$date."]]></title>";
		echo "<description><![CDATA[".$description."]]></description>";
		echo "<linkfoto>".$img[0]."article_main".$img[1]."</linkfoto>";		
		echo "<datahora>".date("d/m/Y H:i:s")."</datahora>";
		echo "</item>";
		
		/*$i++;
		if ($i >=3){
			break;
		}*/
	}
	
	echo "</channel>
	</rss>";
	
	$corpo -> clear();
	unset($corpo);
?>