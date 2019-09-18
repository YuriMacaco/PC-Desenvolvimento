<?php
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	header('Content-type: text/xml; charset=utf-8');
	include('simple_html_dom.php');

	$url 		= "http://mananciais.sabesp.com.br/Home";
	$userAgent	= "Firefox (WindowsXP) - Mozilla/5.0 (Windows; U; Windows NT 5.1; en-GB; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6";
	
	$c = curl_init();
	curl_setopt($c, CURLOPT_USERAGENT, 		$userAgent);
	curl_setopt($c, CURLOPT_URL,			$url);
	curl_setopt($c, CURLOPT_FAILONERROR, 	true);
	curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($c, CURLOPT_AUTOREFERER, 	true);
	curl_setopt($c, CURLOPT_RETURNTRANSFER,	true);
	curl_setopt($c, CURLOPT_VERBOSE, 		false);
	curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
	$page = curl_exec($c);
	curl_close($c);

	$resto 	= explode('<table id="tabDados" class="tabDados" cellspacing="1" cellpadding="0" align="Center" border="0" style="border-width:0px;width:279px;">', $page);
	$meio 	= explode('<!-- Coluna 3 -->',$resto[1]);
	$html 	= str_get_html(trim($meio[0]));
	$header = '<?xml version="1.0" encoding="utf-8" ?>
	<rss version="2.0">
	<channel>
		<title> B2midia </title>
		<description> Sabesp - Situação dos Mananciais </description>
		<link></link>
		<language>pt-br</language>';	
	echo $header;
	
	echo '<item>';
	foreach($html -> find('tr') as $tr) {
		$td = $tr -> find('td[class=guardaImgBgDetalhe]');
		if($td != "" && $td != null){
			echo "<valor>".trim($td[0] -> plaintext)."</valor>";
		}
	}
	echo "</item>";
	
	echo "</channel>
	</rss>";

	$html -> clear();
	unset($html);
?>