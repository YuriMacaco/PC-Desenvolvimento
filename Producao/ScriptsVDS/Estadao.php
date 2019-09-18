<?php
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	header('Content-type: text/xml charset="UTF-8"');
	require_once('simple_html_dom_2.php');
	
	$item 		= $_GET["item"];
    $url		= "https://".$item.".estadao.com.br/#ultimas";
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
		<title>Estadao</title>
		<description>Estadao - '.$item.'</description>
		<link></link>
		<language>pt-br</language>';	
	echo $header;
	
	$ul 		= $corpo->find('div[class=lista]');
	$procura = ("&#039;");
	$acha = ("'");
	
	//echo $editoria;
	
	foreach($ul[0]->find('div[class=box]') as $noticias) {
		
		$editoria 		= $noticias -> find('<h4');
		$title 			= $noticias -> find('h3[class=third]');
		$description 	= $noticias -> find('<p');
		$img 			= $noticias -> find('<img');
		$date			= $noticias -> find('span[class=data-posts]');
		
		$editoria		= trim(html_entity_decode($editoria[0]->plaintext));
		$title			= trim(html_entity_decode($title[0]->plaintext));
		$title			= str_replace($procura, $acha, $title);
		$description	= trim(html_entity_decode($description[0]->plaintext));
		$description	= str_replace($procura, $acha, $description);
		$date			= trim(html_entity_decode($date[0]->plaintext));
		
		$img			= $img[0];
		$img 			= explode('"',$img);

		$data       	= $noticias -> find('<section');
		$data  			= explode('<span class="data-posts">',$data[0]);
		$data           = explode('</span>',$data[1]);
		//echo $data[0];
		//$img 			= explode('/200/',$img[3]);

			echo "<item>";
			echo "<editoria><![CDATA[".$editoria."]]></editoria>";
			echo "<pubdate><![CDATA[".$data[0]."]]></pubdate>";
			echo "<title><![CDATA[".$title."]]></title>";
			echo "<description>".$description."</description>";
			echo "<linkfoto>".$img[3]."</linkfoto>";	
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