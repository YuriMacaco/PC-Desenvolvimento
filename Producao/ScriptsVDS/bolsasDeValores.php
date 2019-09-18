<?php
	require_once('simple_html_dom.php');
	header('Content-type: text/xml charset="utf-8"');
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	
	$url_americas	= "https://money.cnn.com/data/world_markets/americas/";
	$url_europe		= "https://money.cnn.com/data/world_markets/europe/";
	$url_asia		= "https://money.cnn.com/data/world_markets/asia/";
	$url_nasdaq		= "https://finance.yahoo.com/quote/%5EIXIC/";
	$url_ibex		= "https://finance.yahoo.com/quote/%5EIBEX?ltr=1";
	
	$header 		= '<?xml version="1.0" encoding="utf-8" ?>
	<rss version="2.0">
	<channel>
		<title>Bolsas de Valores</title>
		<description>Bolsas de Valores - CNN Money</description>
		<link></link>
		<language>pt-br</language>';
	echo $header;

	$corpo_americas	= str_get_html(file_get_contents($url_americas));
	$corpo_europe	= str_get_html(file_get_contents($url_europe));
	$corpo_asia		= str_get_html(file_get_contents($url_asia));
	$corpo_nasdaq	= str_get_html(file_get_contents($url_nasdaq));
	$corpo_ibex		= str_get_html(file_get_contents($url_ibex));
	
	multipleTargets_cnn($corpo_americas);
	multipleTargets_cnn($corpo_europe);
	multipleTargets_cnn($corpo_asia);
	multipleTargets_yahoo($corpo_nasdaq, $nome = "Nasdaq");
	multipleTargets_yahoo($corpo_ibex, $nome = "IBEX");
	
	function multipleTargets_yahoo($corpo, $nome){
		$dados 			= $corpo		 	-> find('div[class^=D(ib) Mend(20px)]');
		$dados_itens	= $dados[0] 		-> find('span');
		$valor			= $dados_itens[0] 	-> plaintext;
		$ultima			= trim($dados_itens[2] 	-> plaintext);
		$variacao		= explode("(", $dados_itens[1] -> plaintext);
		$variacao		= explode(")", $variacao[1])[0];
		
		$tipo = "";
		
		if(strpos($ultima, "PM") !== false){
			$ultima	= explode("PM", $ultima)[0];
			$tipo	= "PM";
		}else{
			$ultima	= explode("AM", $ultima)[0];
			$tipo	= "AM";
		}
		
		$ultima_length 	= count(explode(" ", $ultima));
		$ultima			= explode(" ", $ultima)[$ultima_length - 1];
		
		if($tipo == "AM"){		
			if(strlen($ultima) < 5){
				$ultima = "0".$ultima;
			}
		}else if($tipo == "PM"){
			$ultima_1 	= intval(explode(":", $ultima)[0]) + 12;
			$ultima_2 	= explode(":", $ultima)[1];
			$ultima 	= $ultima_1.":".$ultima_2;
		}
		
		echo "<item>";
		echo "<nome><![CDATA[".$nome."]]></nome>";
		echo "<valor><![CDATA[".trim($valor)."]]></valor>";
		echo "<variacao><![CDATA[".trim($variacao)."]]></variacao>";
		echo "<dataSinc><![CDATA[".date('d/m/Y ').$ultima."]]></dataSinc>";
		echo "<update><![CDATA[".date('d/m/Y H:i')."]]></update>";
		echo "</item>";
	}
	
	function multipleTargets_cnn($corpo){
	
		$dados 		= $corpo -> find('table');
		$idx		= 0;

		foreach($dados[0] -> find('tr') as $noticias) {
			
			if($idx > 0){
				$dados 		= $noticias -> find('td');
				$nome		= strip_tags($dados[1]);
				$valor		= strip_tags($dados[5]);
				$variacao	= strip_tags($dados[4]);
				$ultima 	= trim($dados[6] -> plaintext);	//Ultima sincronização
				
				$ultima		= str_replace("Jan", "01", $ultima);
				$ultima		= str_replace("Feb", "02", $ultima);
				$ultima		= str_replace("Mar", "03", $ultima);
				$ultima		= str_replace("Apr", "04", $ultima);
				$ultima		= str_replace("May", "05", $ultima);
				$ultima		= str_replace("Jun", "06", $ultima);
				$ultima		= str_replace("Jul", "07", $ultima);
				$ultima		= str_replace("Aug", "08", $ultima);
				$ultima		= str_replace("Sep", "09", $ultima);
				$ultima		= str_replace("Oct", "10", $ultima);
				$ultima		= str_replace("Nov", "11", $ultima);
				$ultima		= str_replace("Dec", "12", $ultima);
				
				$tipo = "";
		
				if(strpos($ultima, "pm") !== false){
					$ultima	= explode("pm", $ultima)[0];
					$tipo	= "pm";
				}else{
					$ultima	= explode("am", $ultima)[0];
					$tipo	= "am";
				}
				
				if(strpos($ultima, ":") !== false){
					if($tipo == "am"){		
						if(strlen(trim($ultima)) < 5){
							$ultima = date('d/m/Y')." 0".$ultima;
						}
					}else if($tipo == "pm"){
						$ultima_1 	= intval(explode(":", $ultima)[0]) + 12;
						$ultima_2 	= explode(":", $ultima)[1];
						$ultima 	= date('d/m/Y')." ".$ultima_1.":".$ultima_2;
					}
				}else{
					$ultima	= explode(" ", $ultima)[1]."/".explode(" ", $ultima)[0]."/".date('Y')." 00:00";
				}
				
				echo "<item>";
				echo "<nome><![CDATA[".$nome."]]></nome>";
				echo "<valor><![CDATA[".$valor."]]></valor>";
				echo "<variacao><![CDATA[".$variacao."]]></variacao>";
				echo "<dataSinc><![CDATA[".trim($ultima)."]]></dataSinc>";
				echo "<update><![CDATA[".date('d/m/Y H:i')."]]></update>";
				echo "</item>";
			}
			$idx++;
		}
	}
	
	echo "</channel>
	</rss>";

	$corpo_americas -> clear();
	$corpo_europe	-> clear();
	$corpo_asia		-> clear();
	unset($corpo_americas);
	unset($corpo_europe);
	unset($corpo_asia);

?>