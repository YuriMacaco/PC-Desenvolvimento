<?php
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	header('Content-type: text/xml charset="UTF-8"');
	require_once('simple_html_dom_2.php');
	
	$item 		= $_GET["item"];
    $url		= "https://www.guiadasemana.com.br/sao-paulo/agenda";
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
		<title>Guia da Semana - SP</title>
		<description>Guia da Semana</description>
		<link></link>
		<language>pt-br</language>';	
	echo $header;
	
	$ul 		= $corpo->find('div[class=row]');
	$procura = ("&#039;");
	$acha = ("'");
	
	//echo $editoria;
	
	foreach($ul[0]->find('div[class^=item-]') as $noticias) {
	
		$tipo 			= $noticias -> find('<i');
		$title 			= $noticias -> find('<h3');
		$description 	= $noticias -> find('<p');
		$date			= $noticias -> find('p[class=date]');
		$local 			= $noticias -> find('<span');
		$img 			= $noticias -> find('<meta');
		

		if ($tipo[1]->plaintext == ""){
			$tipo		= trim(html_entity_decode($tipo[0]->plaintext));
		}else {
			$tipo		= trim(html_entity_decode($tipo[1]->plaintext));
		}

		$title			= trim(html_entity_decode($title[0]->plaintext));
		$description	= trim(html_entity_decode($description[0]->plaintext));
		$date			= trim(html_entity_decode($date[0]->plaintext));
		$local			= trim(html_entity_decode($local[0]->plaintext));

		
		$img			= $img[1]->content;
		//$img 			= explode('"',$img);

		//echo $data[0];
		//$img 			= explode('/200/',$img[3]);

			echo "<item>";
			echo "<tipodoevento><![CDATA[".$tipo."]]></tipodoevento>";
			echo "<title><![CDATA[".$title."]]></title>";
			echo "<description>".$description."</description>";
			echo "<dataevento><![CDATA[".$date."]]></dataevento>";

			if ($local){
				echo "<local><![CDATA[".$local."]]></local>";
			} else{
				echo "<local><![CDATA[ SEM LOCAL CONFIRMADO ]]></local>";
			}
			
			echo "<linkfoto>".$img."</linkfoto>";	
			echo "<datahora>".date("d/m/Y H:i:s")."</datahora>";
			echo "</item>";

		/*$i++;
		if ($i >=5){
			break;
		}*/
	}
	
	echo "</channel>
	</rss>";
	
	$corpo -> clear();
	unset($corpo);
?>