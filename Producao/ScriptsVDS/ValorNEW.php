<?php
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	header('Content-type: text/xml charset="utf-8"');
	require_once('simple_html_dom_2.php');
	
	$item 		= $_GET["item"];
    $url		= "http://www.valor.com.br/".$item;
	$page		= file_get_contents($url);
	$page_p1	= explode('<div id="content">', $page);
	$page_p2	= explode('<div id="sidebar-right" class="region">', $page_p1[1]);
	$page_p3 	= trim($page_p2[0]);
	$corpo 		= str_get_html($page_p3);

	$header	= '<?xml version="1.0" encoding="utf-8" ?>
	<rss version="2.0">
	<channel>
		<title>Valor Economico</title>
		<description>Valor Economico - '.$item.'</description>
		<link></link>
		<language>pt-br</language>';
	echo $header;

	$parte 			= $corpo -> find('div[class=templates]');
	$parte_noticias = $parte[0];
	$i  			= 0;

	foreach($parte_noticias -> find('div[class=noticias]') as $noticias) {
		
		$title 				= $noticias -> find('<h2');
		$editoria 			= $noticias -> find('div[class=teaser-date]');
		$link				= $title[0] -> find('<a');
		$link				= $link[0] 	-> href;

		$page2 				= file_get_contents($link);
		$page2_p1			= explode('<div id="content-area">', $page2);
		$cont_noticia 		= str_get_html($page2_p1[1]);

		$description_onPage	= $cont_noticia -> find('div[class=node-body]');
		$date_onPage		= $cont_noticia	-> find('span[class=date submitted]');
		$description_part2	= html_entity_decode($description_onPage[0]);

		$date_onPage		= $date_onPage[0];
		$date_onPage		= str_replace(' &agrave;s ', ' ', $date_onPage);
		$date_onPage		= str_replace('h', ':', $date_onPage);
		
		if (strpos($description_part2, '<p') !== false) {
			$description_test = strip_tags($description_test[0]);
			
			if($description_test != '' && $description_test != ' '){
				$description_part2 	= $description_test;
			
			}else{
				$description_part2	= strip_tags($description_part2);
			}
		}

		$description_part2 	= strip_tags($description_part2);
		$description_part2 	= explode('.', $description_part2)[0] . ". " . explode('.', $description_part2)[1] . ". ";
		$editoria 			= explode('</a>', $editoria[0]);
		$editoria 			= explode('>', $editoria[0]);

		echo "<item>";	
		echo "<title><![CDATA[".$editoria[2]."]]></title>";
		echo "<description><![CDATA[".html_entity_decode(trim(strip_tags($title[0])))." - ".trim($description_part2)."]]></description>";
		echo "<pubdate>".trim(strip_tags($date_onPage))."</pubdate>";
		echo "<datahora>".date("d/m/Y H:i:s")."</datahora>";
		echo "</item>";
		
		$i++;
		if ($i > 4){
			break;
		}
	}

	echo "</channel>
	</rss>";
	
	$corpo -> clear();
	unset($corpo);

?>