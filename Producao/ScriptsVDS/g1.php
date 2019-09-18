<?php
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	header('Content-type: text/xml charset="UTF-8"');
	require_once('simple_html_dom_2.php');
	
	$item 		= $_GET["item"];
	$url		= "https://g1.globo.com/".$item;
    
	$userAgent 	= "Mozilla/5.0 (Windows NT 5.1; pt-BR; rv:1.8.1.6) Gecko/20070725 Firefox/61.0";
	
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
		<title>G1</title>
		<description>G1 noticias</description>
		<link></link>
		<language>pt-br</language>';	
	echo $header;
	
	$ul = $corpo->find('div[class=_ir]');

	if(!$ul || $ul == null){
		$ul = $corpo->find('div[class=_sr]');
	}
	
	foreach($ul[0]->find('div[class^=feed-post bstn-item-shape]') as $noticias) {
		
		$title 			= $noticias -> find('<a');
		$description 	= $noticias -> find('div[class=feed-post-body-resumo]');
		$img 			= $noticias -> find('img');
		$date			= $noticias -> find('span[class=feed-post-datetime]');
		$local			= $noticias -> find('span[class=feed-post-metadata-section]');
		
		$title			= trim(html_entity_decode($title[0]->plaintext));
		$description	= trim(html_entity_decode($description[0]->plaintext));
		$date			= trim(html_entity_decode($date[0]->plaintext));
		$local			= trim(html_entity_decode($local[0]->plaintext));

		$img			= $img[0]->srcset;
		$img 			= explode(',',$img);
		$img			= explode(' ',$img[1]);

			if($img[0]){
				if($description){
					echo "<item>";
					echo "<title><![CDATA[".$title."]]></title>";
					echo "<description><![CDATA[".$description."]]></description>";
					echo "<pubdate><![CDATA[".$date."]]></pubdate>";
					echo "<local><![CDATA[".$local."]]></local>";	
					echo "<linkfoto>".$img[0]."</linkfoto>";		
					echo "<datahora>".date("d/m/Y H:i:s")."</datahora>";
					echo "</item>";
				} else if($description = " "){
					echo "<item>";
					echo "<title><![CDATA[".$local."]]></title>";
					echo "<description><![CDATA[".$title."]]></description>";
					echo "<pubdate><![CDATA[".$date."]]></pubdate>";
					echo "<local><![CDATA[".$local."]]></local>";	
					echo "<linkfoto>".$img[0]."</linkfoto>";		
					echo "<datahora>".date("d/m/Y H:i:s")."</datahora>";
					echo "</item>";
				}
			}

	}
	
	echo "</channel>
	</rss>";
	
	$corpo -> clear();
	unset($corpo);
?>