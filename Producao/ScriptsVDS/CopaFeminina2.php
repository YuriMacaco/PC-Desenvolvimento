<?php
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	header('Content-type: text/xml charset="utf-8"');
	require_once('simple_html_dom_2.php');
	
	$item 		= $_GET["item"];
    $url		= "https://globoesporte.globo.com/futebol/copa-do-mundo-feminina/";
	$page		= file_get_contents($url);
	$page_p1	= explode('<div id="bstn-fd-launcher">', $page);
	$page_p2	= explode('<link id="bstn-external-style"', $page_p1[1]);
	$page_p3 	= trim($page_p2[0]);
	$corpo 		= str_get_html($page_p3);

	$header	= '<?xml version="1.0" encoding="utf-8" ?>
	<rss version="2.0">
	<channel>
		<title>Copa Feminina</title>
		<description>Copa do Mundo Feminino</description>
		<link></link>
		<language>pt-br</language>';
	echo $header;

	$parte 			= $corpo -> find('div[class=feed-root]');
	$parte_noticias = $parte[0];
	$i  			= 0;

	foreach($parte_noticias -> find('div[class=bastian-feed-item]') as $noticias) {

		$title 			= $noticias -> find('<a');
		$description 	= $noticias -> find('div[class=feed-post-body-resumo]');
		$img 			= $noticias -> find('<img');
		$date			= $noticias -> find('span[class=feed-post-datetime]');

		$title			= trim(html_entity_decode($title[0]->plaintext));
		$description	= trim(html_entity_decode($description[0]->plaintext));
		$date			= trim(html_entity_decode($date[0]->plaintext));
		
		$img			= $img[0];
		$img 			= explode('"',$img);
		$img 			= explode(',',$img[3]);
		$img			= explode(' 2x',$img[2]);

		if ($description){

				echo "<item>";
				echo "<pubdate><![CDATA[".$date."]]></pubdate>";
				echo "<title><![CDATA[".$title."]]></title>";
				echo "<description><![CDATA[".$description."]]></description>";
				echo "<linkfoto>".$img[0]."</linkfoto>";	
				echo "<datahora>".date("d/m/Y H:i:s")."</datahora>";
				echo "</item>";

			$i++;
			if ($i >=5){
				break;
			}
		}
	}

	echo "</channel>
	</rss>";
	
	$corpo -> clear();
	unset($corpo);

?>