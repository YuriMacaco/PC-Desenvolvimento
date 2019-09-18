<?php
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	header('Content-type: text/xml charset="UTF-8"');
	require_once('simple_html_dom_2.php');
	
	$item 		= $_GET["item"];
	$cidade		= $_GET["cidade"];
    $url		= "https://www.cinemark.com.br/".$cidade."/cinemas?cinema=".$item;
	$userAgent 	= "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36";
	
	$c = curl_init();
	curl_setopt($c, CURLOPT_USERAGENT, 		$userAgent);
	curl_setopt($c, CURLOPT_URL,			$url);
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
		<title>Cinemark</title>
		<description>Cinemark Programação</description>
		<link></link>
		<language>pt-br</language>';	
	echo $header;
	
	$ul = $corpo->find('div[class=active]');

	function validImage($file) {
		$size = getimagesize($file);
		return (strtolower(substr($size['mime'], 0, 5)) == 'image' ? true : false);  
	}
		
		foreach($ul[0]->find('div[class=theater]') as $noticias) {
			
			$filme 			= $noticias -> find('<h3');
			$filme			= trim(html_entity_decode($filme[0]->plaintext));
			
			$sala			= $noticias -> find('<ul');

			$linkfoto		= $noticias -> find('a');

			$descfoto		= $linkfoto[0]->href;
			$descfoto 		= explode("/", $descfoto);

			$links			= $linkfoto[2]->href;
			$links 			= explode("/", $links);

			echo "<item>";
			echo "<filme><![CDATA[".$filme."]]></filme>";

			$domain1 = "https://www.cinemark.com.br/content/uploads/movie/".$links[5]."/".$descfoto[2]."-poster-desktop.png";
			$domain2 = "https://www.cinemark.com.br/content/uploads/movie/".$links[5]."/".$descfoto[2]."-poster-desktop.jpg";

			/*$filmes = explode("-", $filme);
			if ($filmes[1]){
				$descfotos = explode("-", $descfoto[2]);
				echo $descfotos[0];
			}*/

			$linkacessose 	= $linkfoto[0]->href;

			$image 	= validImage($domain1);
			$image2 = validImage($domain2);

			if ($image){
				echo "<linkfoto><![CDATA[".$domain1."]]></linkfoto>";
			}else if($image2){
				echo "<linkfoto><![CDATA[".$domain2."]]></linkfoto>";
			}else{
				$url2		= "https://www.cinemark.com.br/".$linkacessose;
				$userAgent2	= "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36";
				
				$c2 = curl_init();
				curl_setopt($c2, CURLOPT_USERAGENT, 		$userAgent2);
				curl_setopt($c2, CURLOPT_URL,				$url2);
				curl_setopt($c2, CURLOPT_HEADER, 			false);
				curl_setopt($c2, CURLOPT_FAILONERROR, 		true);
				curl_setopt($c2, CURLOPT_FOLLOWLOCATION, 	true);
				curl_setopt($c2, CURLOPT_AUTOREFERER, 		true);
				curl_setopt($c2, CURLOPT_RETURNTRANSFER,	true);
				curl_setopt($c2, CURLOPT_VERBOSE, 			false);
				curl_setopt($c2, CURLOPT_SSL_VERIFYHOST, 	false);
				curl_setopt($c2, CURLOPT_SSL_VERIFYPEER, 	false);
				curl_setopt($c2, CURLOPT_POST,				true);
				curl_setopt($c2, CURLOPT_POSTFIELDS,		"regno=$Number");
				curl_getinfo($c2, CURLINFO_HTTP_CODE);
			    $page2 = curl_exec($c2);
			    curl_close($c2);

			    $corpo2 = str_get_html($page2);
			    //echo $corpo2;
			    $hey = $corpo2->find('meta[property=og:image]');
			    $hey = $hey[0]->content;

			    echo "<linkfoto><![CDATA[".$hey."]]></linkfoto>";

			}

			foreach($sala[0]->find('<li ') as $salas){

				$salinha 	= $salas -> find('span[class=times-auditorium]');
				$salinha	= trim(html_entity_decode($salinha[0]->plaintext));

				if ($salinha){
					echo "<sala>";
					echo "<nome><![CDATA[".$salinha."]]></nome>";

					$horario	= $salas -> find('<ul');

					if($horario[0]){
						echo "<horarios>";
							foreach($horario[0]->find('li') as $horarios){
								$horarinho 	= $horarios -> find('<span');
								$horarinho	= trim(html_entity_decode($horarinho[0]->plaintext));
								echo "<horario><![CDATA[".$horarinho."]]></horario>";
							}
						echo "</horarios>";
					}

					if($horario[1]){
								$tiposala	= $horario[1] -> find('<span');
								$dbox		= trim(html_entity_decode($tiposala[0]->plaintext));
								$xd			= trim(html_entity_decode($tiposala[1]->plaintext));

								if ($dbox and $xd){
									echo "<tiposala><![CDATA["."D-Box ".$dbox." Sala ".$xd."]]></tiposala>";
								}else if($dbox or $xd == "prime"){
									echo "<tiposala><![CDATA["."Sala Prime"."]]></tiposala>";
								}else if($dbox and $xd != "xd" and "prime"){
									echo "<tiposala><![CDATA["."Sala D-Box ".$dbox."]]></tiposala>";
								}else if($dbox == "xd"){
									echo "<tiposala><![CDATA["."Sala XD"."]]></tiposala>";
								}else if($xd == "xd"){
									echo "<tiposala><![CDATA["."Sala XD"."]]></tiposala>";
								}

					}


					if($horario[2]){
								$audio 	= $horario[2] -> find('<span');
								$audios	= trim(html_entity_decode($audio[0]->plaintext));
								$d3		= trim(html_entity_decode($audio[1]->plaintext));

								if ($d3 and $audios == "dub"){
									echo "<audio><![CDATA[".$audios."lado em 3D"."]]></audio>";
								}else if($d3 and $audios == "orig"){
									echo "<audio><![CDATA[".$audios."inal em 3D"."]]></audio>";
								}else if($d3 and $audios == "leg"){
									echo "<audio><![CDATA[".$audios."endado em 3D"."]]></audio>";
								}else if($audios == "dub"){
									echo "<audio><![CDATA[".$audios."lado"."]]></audio>";
								}else if($audios == "orig"){
									echo "<audio><![CDATA[".$audios."inal"."]]></audio>";
								}else if($audios == "leg"){
									echo "<audio><![CDATA[".$audios."endado"."]]></audio>";
								}

					}

					echo "</sala>";

				}

			}
		
			echo "<datahora>".date("d/m/Y H:i:s")."</datahora>";
			echo "</item>";
			
			/*$i++;
			if ($i >=3){
				break;
			}*/
		}

	echo "</channel>
	</rss>";
	
	$corpo -> clear();
	unset($corpo);
?>