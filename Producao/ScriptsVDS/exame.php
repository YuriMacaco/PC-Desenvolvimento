<?php
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	header('Content-type: text/xml charset="utf-8"');
	require_once('simple_html_dom.php');
	
	$item 		= $_GET["item"];
    $url		= "https://exame.abril.com.br/".$item;
	$userAgent 	= "Firefox (WindowsXP) - Mozilla/5.0 (Windows; U; Windows NT 5.1; en-GB; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6";
	
    $c = curl_init();
	curl_setopt($c, CURLOPT_USERAGENT, 		$userAgent);
	curl_setopt($c, CURLOPT_URL,			$url);
	curl_setopt($c, CURLOPT_FAILONERROR, 	true);
	curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($c, CURLOPT_AUTOREFERER, 	false);
	curl_setopt($c, CURLOPT_RETURNTRANSFER,	true);
	curl_setopt($c, CURLOPT_VERBOSE, 		false);
    $page = curl_exec($c);
    curl_close($c);

	$corpo 	= str_get_html($page);
	$header = '<?xml version="1.0" encoding="utf-8" ?>
	<rss version="2.0">
	<channel>
		<title>Exame</title>
		<description>Exame - '.ucfirst($item).'</description>
		<link></link>
		<language>pt-br</language>';

	echo $header;
	
	$procura	= array("&#8220;","&#8221;","&nbsp;","&quot;","&#8211;","&#8212;","—");
	$acha		= array('"','"','','"','-',"-","-");
	
	$editoria	= $corpo -> find('h1[class*=page-title]');
	$postagens 	= $corpo -> find('ul[class=articles-list]');
	$editoria	= trim(str_replace($procura,$acha,html_entity_decode($editoria[0] -> plaintext)));

	

	foreach($postagens[0] -> find('<li') as $noticias) {

		$description 	= $noticias -> find('span[class=list-item-title]');
		$datapost		= $noticias -> find('span[class=list-date-description]');
		$linknoticia	= $noticias -> find('a');
		$linknoticia	= $linknoticia[0] -> href;
		
		//$title		= trim(str_replace($procura,$acha,$title[0]->plaintext));
		//$title		= html_entity_decode($title);
		//$description 	= $noticias -> find('p[class=caption]');
		$description	= html_entity_decode($description[0] -> plaintext);
		$description	= trim(str_replace($procura,$acha,$description));
		
		$datapost		= $datapost[0] -> plaintext;
		$datapost		= str_replace(" janeiro ",	"/01/", $datapost);
		$datapost		= str_replace(" fevereiro ","/02/", $datapost);
		$datapost		= str_replace(" março ",	"/03/", $datapost);
		$datapost		= str_replace(" abril ",	"/04/", $datapost);
		$datapost		= str_replace(" maio ",		"/05/", $datapost);
		$datapost		= str_replace(" junho ",	"/06/", $datapost);
		$datapost		= str_replace(" julho ",	"/07/", $datapost);
		$datapost		= str_replace(" agosto ",	"/08/", $datapost);
		$datapost		= str_replace(" setembro ",	"/09/", $datapost);
		$datapost		= str_replace(" outubro ",	"/10/", $datapost);
		$datapost		= str_replace(" novembro ",	"/11/", $datapost);
		$datapost		= str_replace(" dezembro ",	"/12/", $datapost);
		$datapost		= str_replace(",",			"", 	$datapost);
		$datapost		= str_replace("h",			":", 	$datapost);
		
		$img 			= $noticias -> find('<img');
		$image 			= $img[0] -> src;
		
		$c2 = curl_init();
		curl_setopt($c2, CURLOPT_USERAGENT, 	 $userAgent);
		curl_setopt($c2, CURLOPT_URL,			 $linknoticia);
		curl_setopt($c2, CURLOPT_FAILONERROR, 	 true);
		curl_setopt($c2, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($c2, CURLOPT_AUTOREFERER, 	 false);
		curl_setopt($c2, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($c2, CURLOPT_VERBOSE, 		 false);
		$page2 = curl_exec($c2);
		curl_close($c2);
		$corpo2 = str_get_html($page2);
		
		$subtitle = $corpo2 -> find('h2[class=article-subtitle]');
		$subtitle = $subtitle[0] -> plaintext;
		
		if($subtitle){
		
			$subtitle = html_entity_decode($subtitle);
			$description .= " - " . $subtitle;
			
			if(strlen($description) > 170){
				$description = $subtitle;
			}
			
			$description = str_replace($procura,$acha,$description);
		}
		
		if(strpos(strtolower($editoria), "vip ") !== false){
			$editoria = substr($editoria, 8);
		}

		echo "<item>";
		echo "<title><![CDATA[".$editoria."]]></title>";	
		echo "<description><![CDATA[".$description."]]></description>";
		echo "<linknoticia><![CDATA[".$linknoticia."]]></linknoticia>";
		echo "<linkfoto>".$image."</linkfoto>";		
		echo "<pubdate>".$datapost."</pubdate>";
		echo "<datahora>".date("d/m/Y H:i:s")."</datahora>";
		echo "</item>";

	}
	
	echo "</channel>
	</rss>";

	$corpo -> clear();
	unset($corpo);
?>