<?php
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	include('simple_html_dom_2.php');
	header('Content-type: text/xml charset="utf-8"');
	
	$item 	= $_GET["item"];
    $url	= "https://forbes.uol.com.br/".$item."/";
	$url2	= "forbes.uol.com.br/".$item."/";
	$page 	= file_get_contents($url);
	$page_1 = explode('<div class="mh-wrapper clearfix">', $page)[1];
	$page_2	= explode('<div class="mh-loop-pagination clearfix">', $page_1)[0];
	$corpo  = str_get_html($page_2);
	$corpo2 = str_get_html($page_1);
	
	$header = '<?xml version="1.0" encoding="utf-8" ?>
		<rss version="2.0">
		<channel>
		<title>Forbes</title>
		<description>Forbes - '.$item.'</description>
		<link></link>
		<language>pt-br</language>';
	echo $header;
	
	$editoria = $corpo2 -> find('<header');
	//echo $editoria[0]->plaintext;

	$idx = 0;
	
	foreach($corpo -> find('<article') as $noticias) {
		
		
		
		$title 				= $noticias -> find('<h3');
		$title_final		= trim(html_entity_decode($title[0] -> plaintext));
		
		$img				= $noticias	-> find('<img');
		$imagem 			= $img[0]	-> src;
		$imagem_part1		= explode("-", $imagem);
		$imagem_part2		= $imagem_part1[count($imagem_part1)- 1];
		$imagem_part3		= explode($imagem_part2, $imagem)[0];
		$imagem_part4		= substr($imagem_part3, 0, -1).".jpg";
		
		$datapub			= $noticias -> find('span[class=entry-meta-date updated]');
		$datapub			= strip_tags($datapub[0]);
		$datapub			= str_replace(" de ", "/", $datapub);
		$datapub			= str_replace(["janeiro", "fevereiro", "março", "abril", "maio", "junho", "julho", "agosto", "setembro", "outubro", "novembro", "dezembro"], ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"], $datapub);
		$datapub_dia		= explode('/', $datapub)[0];
		$datapub_mes		= explode('/', $datapub)[1];
		$datapub_ano		= explode('/', $datapub)[2];
		$datapub_dia		= strlen($datapub_dia) < 2 ? "0".$datapub_dia : $datapub_dia;
		$datapub_mes		= strlen($datapub_mes) < 2 ? "0".$datapub_mes : $datapub_mes;
		$datapub			= $datapub_dia."/".$datapub_mes."/".$datapub_ano;
		
		$desc				= $noticias	-> find('div[class=mh-excerpt]');
		$description_final	= $desc[0] 	-> find('<p');
		$description_final	= preg_replace('/(^¦\s)(http:\/\/)?(www\.)?[\.0-9a-zA-Z\-_~]+\.(com¦net¦org¦info¦name¦biz¦.+\.\w\w)((\/)?[0-9a-zA-Z\.\-_~#]+)?\b/', '', $description_final[0]);		
		$description_final	= html_entity_decode($description_final, ENT_QUOTES, 'UTF-8');
		$description_final	= strip_tags($description_final);
		$description_final	= str_replace(" […]", "", $description_final.". - Leia mais em: ".$url2);
		
		$title_final 		= html_entity_decode($title_final, ENT_QUOTES, 'UTF-8');
		$description_final 	= html_entity_decode($description_final, ENT_QUOTES, 'UTF-8');
		
		echo "<item>";
		echo "<title><![CDATA[".$editoria[0]->plaintext."]]></title>";
		echo "<description><![CDATA[".$title_final." - ".$description_final."]]></description>";
		echo "<linkfoto><![CDATA[".$imagem_part4."]]></linkfoto>";
		echo "<pubdate><![CDATA[".$datapub." 00:00"."]]></pubdate>";
		//echo "<linkpub><![CDATA[".$link."]]></linkpub>";
		echo "<datahora><![CDATA[".date("d/m/Y H:i:s")."]]></datahora>";
		echo "</item>";
		
		$idx++;
		
		if($idx > 4){
			break;
		}
	}
	
	echo "</channel>
	</rss>";
	
	$corpo -> clear();
	unset($corpo);
?>