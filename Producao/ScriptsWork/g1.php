<?php
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	//header('Content-type: text/xml charset="UTF-8"');
	require_once('simple_html_dom.php');
	
	$item 		= $_GET["item"];
	$url		= "https://g1.globo.com/".$item;
    
	$userAgent 	= "Mozilla/5.0 (Windows NT 5.1; pt-BR; rv:1.8.1.6) Gecko/20070725 Firefox/61.0";
	
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
	curl_getinfo($c, CURLINFO_HTTP_CODE);
    $page = curl_exec($c);
    curl_close($c);

	$corpo = str_get_html($page);
		
	$header="<head>
				<title>G1 - ".$item."</title>

				    <style type='text/css'> 

				        body {
				            margin: 0%;
				            padding: 2%;
				            color: #444444;
				            font-family: Verdana, Arial, Helvetica, sans-serif;
				            font-size: 20px;
				            line-height: 30px; 
				        }

				        h1 { font-size:38px; margin-bottom: 20px; }
				        h2 { font-size:30px; margin-bottom: 20px; }
				        h3 { font-size:20px; margin-bottom: 20px; }
				        h4 { font-size:16px; margin-bottom: 10px; font-weight:100 }
				        h5 { font-size:14px }
				        h6 { font-size:12px }

				        .cleaner { 
				            clear: both; 
				            display: none;
				        }

				        .post {
				            margin-bottom: 2%;
				            padding-bottom: 2%;
				        }

				        .post h2 { 
				            margin-bottom: 2%; 
				        }

				        .post img { 
				            background: #fff; 
				            border: 1px solid #DCDCDC; 
				            padding: 1%; 
				            width: 100%;
				        }

				        #templatemo_wrapper {
				            position: relative;
				            margin: 0%;
				            width: 80%;
				        }

				        #sidebar, .col13 { width: 95% }
				        #content, .col23 { width: 95% }

				    </style>
				</head>
					<body>
    					<center>
    					<div id='templatemo_wrapper'>
    					<h1>Notícias</h1>";	
	echo $header;
	
	$page1 = explode('<div id="feed-placeholder" class="feed-placeholder">', $page);
	$page2 = explode('<link id="bstn-external-style"', $page1[1]);

	$corpo = str_get_html($page2[0]);
	
	foreach($corpo->find('div[class=bastian-feed-item]') as $noticias) {
		
		$title 			= $noticias -> find('<a');
		$description 	= $noticias -> find('div[class=feed-post-body-resumo]');
		$img 			= $noticias -> find('img');
		$date			= $noticias -> find('span[class=feed-post-datetime]');
		$local			= $noticias -> find('span[class=feed-post-metadata-section]');
		
		$title			= trim(html_entity_decode($title[0]->plaintext));
		$description	= trim(html_entity_decode($description[0]->plaintext));
		$date			= trim(html_entity_decode($date[0]->plaintext));
		$local			= trim(html_entity_decode($local[0]->plaintext));

		$img			= $img[0]->srcset;
		$img 			= explode(',',$img);
		$img			= explode(' ',$img[1]);
	
		if($img[0]){
			if(strpos($img[0], '.jpg') !== false || strpos($img[0], '.png') !== false || strpos($img[0], '.jpeg') !== false){
				if($description){

					echo "<div class='post'>
                    	  <h2 align='justify'>".$title."</h2>
                    	<div class='col col23'>
                        <img src='".$img[0]."'>
                        <p align='justify'><b>Data de Publicação: </b>".$date."</p>
                  		<p align='justify'>".$description."</p>
                    	</div>
                    	<div class='cleaner'></div>
                		</div>";

					//echo "<pubdate><![CDATA[".$date."]]></pubdate>";
					//echo "<local><![CDATA[".$local."]]></local>";		
					//echo "<datahora>".date("d/m/Y H:i:s")."</datahora>";
					
					$idx++;
					
				} else if($description = " "){

					echo "<div class='post'>
                    	  <h2 align='justify'>".$local."</h2>
                    	<div class='col col23'>
                        <img src='".$img[0]."'/>
                        <p align='justify'><b>Data de Publicação: </b>".$date."</p>
                        <p align='justify'>".$title."</p>
                    	</div>
                    	<div class='cleaner'></div>
                		</div>";

					$idx++;
				}
			}
		}
		
		if($idx > 4){
			break;
		}
	}
	
	echo "</div>
		</center>
		</body>";
	
	$corpo -> clear();
	unset($corpo);
?>