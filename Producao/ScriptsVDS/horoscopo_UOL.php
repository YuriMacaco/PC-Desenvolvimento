<?php
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	header('Content-type: text/xml charset="utf-8"');
	require_once('simple_html_dom_2.php');
	
	$item 		= $_GET["item"];
	$url 		= "https://universa.uol.com.br/horoscopo/".$item."/horoscopo-do-dia/";
	$userAgent 	= "Firefox (WindowsXP) - Mozilla/5.0 (Windows; U; Windows NT 5.1; en-GB; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6";
	
	$c = curl_init();
	curl_setopt($c, CURLOPT_USERAGENT, 		$userAgent);
	curl_setopt($c, CURLOPT_URL,			$url);
	curl_setopt($c, CURLOPT_FAILONERROR, 	true);
	curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($c, CURLOPT_AUTOREFERER, 	true);
	curl_setopt($c, CURLOPT_RETURNTRANSFER,	true);
	curl_setopt($c, CURLOPT_VERBOSE, 		false);
	$page = curl_exec($c);
	curl_close($c);

	$corpo 	= str_get_html($page);
	
	$header	= '<?xml version="1.0" encoding="utf-8" ?>
		<rss version="2.0">
		<channel>
			<title>Horóscopo</title>
			<description>'.ucfirst($item).'</description>
			<link></link>
			<language>pt-br</language>';	
	echo $header;

	$i 				= 0;

	$dia 	= $corpo -> find('<h4');
	$dia	= $dia[0] -> plaintext;

	$title	= $corpo -> find('<h1');
	$title	= $title[0] -> plaintext;
	$title 	= explode("(",$title);

	$description	= $corpo -> find('div[class=text]');
	$description	= $description[0] -> plaintext;
	$description	= str_replace("?", "-", $description);
	
	echo "<item>";
	echo "<dia><![CDATA[".trim($dia)."]]></dia>";
	echo "<title><![CDATA[".trim($title[0])."]]></title>";
	echo "<periodo><![CDATA["."(".trim($title[1])."]]></periodo>";
	echo "<description><![CDATA[".trim($description)."]]></description>";
	echo "<datahora>".date("d/m/Y H:i:s")."</datahora>";
	echo "</item>";

	echo "</channel>
	</rss>";
	
	$corpo -> clear();
	unset($corpo);
?>