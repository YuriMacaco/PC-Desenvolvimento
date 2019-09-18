
<?php
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	header('Content-type: text/xml charset="UTF-8"');
	require_once('simple_html_dom_old.php');
	
	$item 		= $_GET["item"];
    $url		= "https://www1.folha.uol.com.br/".$item."/";
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
		<title>Folha</title>
		<description>Folha</description>
		<link></link>
		<language>pt-br</language>';	
	echo $header;
	
	$procura = array("&#8220;","&#8221;","&nbsp;","&quot;");
	$acha	 = array('"','"','','"');
	
	$ul = $corpo -> find('<ol');
	$i 	= 0;
	
	foreach($ul[0]->find('<li') as $noticias) {
		
		$title 			= $noticias -> find('<h3');
		$description 	= $noticias -> find('<p');
		$img 			= $noticias -> find('<img');
		
		$title			= trim(str_replace($procura, $acha, html_entity_decode($title[0] -> plaintext)));
		$description	= trim(str_replace($procura, $acha, html_entity_decode($description[0] -> plaintext)));
		$img			= $img[0] -> src;
		$img			= substr($img, 0, strlen($img) - 6)."md.jpg";

		if ($img){
		
			echo "<item>";
			echo "<title><![CDATA[".$title."]]></title>";	
			echo "<description><![CDATA[".$description."]]></description>";
			echo "<linkfoto>".$img."</linkfoto>";		
			echo "<datahora>".date("d/m/Y H:i:s")."</datahora>";
			echo "</item>";

			$i++;

		}
		
		if ($i >=5){
			break;
		}
	}
	
	echo "</channel>
	</rss>";
	
	$corpo -> clear();
	unset($corpo);
?>