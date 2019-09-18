<?php

require_once('simple_html_dom.php');
error_reporting(E_ERROR | E_WARNING | E_PARSE);

//Data de Hoje
$meses = array (1 => "Janeiro", 2 => "Fevereiro", 3 => "Março", 4 => "Abril", 5 => "Maio", 6 => "Junho", 7 => "Julho", 8 => "Agosto", 9 => "Setembro", 10 => "Outubro", 11 => "Novembro", 12 => "Dezembro");
$diasdasemana = array (1 => "Segunda-Feira",2 => "Terça-Feira",3 => "Quarta-Feira",4 => "Quinta-Feira",5 => "Sexta-Feira",6 => "Sábado",0 => "Domingo");
$variavel = date("d/m/Y");
$variavel = str_replace('/','-',$variavel);
$hoje = getdate(strtotime($variavel));
$dia = $hoje["mday"];
$mes = $hoje["mon"];
$nomemes = $meses[$mes];
$diadasemana = $hoje["wday"];
$nomediadasemana = $diasdasemana[$diadasemana];

//Clima São Paulo
$url_clima		= "https://custom-scripts.worksphere.com.br/Crawlers/ClimaAug2019/clima.php?local=12996";
$corpo_clima 	= str_get_html(file_get_contents($url_clima));

$temp_agora		= $corpo_clima->find('<temperature');
$tempo_agora	= $temp_agora[0]->plaintext;
$tempo_agora01	= explode("<![CDATA[", $tempo_agora);
$tempo_agora02	= explode("]]>", $tempo_agora01[1]);

$imagem_clima	= $corpo_clima->find('<image');
$imagem_agora	= $imagem_clima[0]->plaintext;
$imagem_agora01	= explode("<![CDATA[", $imagem_agora);
$imagem_agora02	= explode("]]>", $imagem_agora01[1]);

$min			= $corpo_clima->find('<low');
$min_hoje		= $min[0]->plaintext;
$min_hoje01		= explode("<![CDATA[", $min_hoje);
$min_hoje02		= explode("]]>", $min_hoje01[1]);
$min_amanha		= $min[1]->plaintext;
$min_amanha01	= explode("<![CDATA[", $min_amanha);
$min_amanha02	= explode("]]>", $min_amanha01[1]);

$max			= $corpo_clima->find('<high');
$max_hoje		= $max[0]->plaintext;
$max_hoje01		= explode("<![CDATA[", $max_hoje);
$max_hoje02		= explode("]]>", $max_hoje01[1]);
$max_amanha		= $max[1]->plaintext;
$max_amanha01	= explode("<![CDATA[", $max_amanha);
$max_amanha02	= explode("]]>", $max_amanha01[1]);

//Cotações dólar e Euro
$url_dolar_euro		= "https://economia.uol.com.br/cotacoes/xml/cotacoesmidia.jhtm";
$corpo_dolar_euro 	= str_get_html(file_get_contents($url_dolar_euro));
$dolar_euro 		= $corpo_dolar_euro->find('<indice');
$dolar_venda		= $dolar_euro[0]->plaintext;
$dolar_compra		= $dolar_euro[2]->plaintext;
$euro				= $dolar_euro[4]->plaintext;

$dolar_venda_indice	= $dolar_euro[1]->plaintext;
if (substr($dolar_venda_indice, 0,1) == "-") {
	$imagem_venda_indice = "maior.png";
} else if ($dolar_venda_indice == "0") {
	$imagem_venda_indice = "";
}else {
	$imagem_venda_indice = "menor.png";
}

$dolar_compra_indice = $dolar_euro[3]->plaintext;
if (substr($dolar_compra_indice, 0,1) == "-") {
	$imagem_compra_indice = "maior.png";
} else if ($dolar_compra_indice == "0") {
	$imagem_compra_indice = "";
} else {
	$imagem_compra_indice = "menor.png";
}

$euro_indice = $dolar_euro[5]->plaintext;
if (substr($euro_indice, 0,1) == "-") {
	$imagem_euro_indice = "maior.png";
} else if ($euro_indice == "0") {
	$imagem_euro_indice = "";
} else {
	$imagem_euro_indice = "menor.png";
}

//Coroa Sueca
$url_coroa		= "https://custom-scripts.worksphere.com.br/Crawlers/coroaSueca.php";
$corpo_coroa	= str_get_html(file_get_contents($url_coroa));
$valor_coroa	= $corpo_coroa->find('<valor');
$valor_coroaS 	= $valor_coroa[0]->plaintext;
$valor_coroaS 	= substr($valor_coroaS, 0,4);
$variacao_coroa = $corpo_coroa->find('<variacao');
$variacao_coroaS= $variacao_coroa[0]->plaintext;
if (substr($variacao_coroaS, 0,1) == "-") {
	$imagem_coroaS = "maior.png";
} else if ($variacao_coroaS == "0") {
	$imagem_coroaS = "";
} else {
	$imagem_coroaS = "menor.png";
}

//Ibovespa
$url_ibovespa		= "https://custom-scripts.worksphere.com.br/Crawlers/bovespa_bvsp.php";
$corpo_ibovespa		= str_get_html(file_get_contents($url_ibovespa));
$valor_ibovespa		= $corpo_ibovespa->find('<valor');
$valor_ibovespaS 	= $valor_ibovespa[0]->plaintext;
$variacao_ibovespa 	= $corpo_ibovespa->find('<variacao');
$variacao_ibovespaS	= $variacao_ibovespa[0]->plaintext;
$porcen_ibovespa 	= $corpo_ibovespa->find('<porcentagem');
$porcen_ibovespa 	= $porcen_ibovespa[0]->plaintext;
if (substr($variacao_ibovespaS, 0,1) == "-") {
	$imagem_ibovespaS = "maior.png";
} else if ($variacao_ibovespaS == "0") {
	$imagem_ibovespaS = "";
} else {
	$imagem_ibovespaS = "menor.png";
}

//Ações Atlas Copco
$url_atco		= "https://custom-scripts.worksphere.com.br/Crawlers/investing.php";
$corpo_atco		= str_get_html(file_get_contents($url_atco));
$valor_atco 	= $corpo_atco->find('<valor');
$valor_atcoA 	= $valor_atco[0]->plaintext;
$valor_atcoB 	= $valor_atco[1]->plaintext;
$variacao_atco 	= $corpo_atco->find('<variacao');
$variacao_atcoA = $variacao_atco[0]->plaintext;
$variacao_atcoB = $variacao_atco[1]->plaintext;
$porcen_atco 	= $corpo_atco->find('<porcentagem');
$porcen_atcoA 	= $porcen_atco[0]->plaintext;
$porcen_atcoB 	= $porcen_atco[1]->plaintext;
if (substr($porcen_atcoA, 0,1) == "-") {
	$imagem_atcoA = "maior.png";
} else if ($porcen_atcoA == "0") {
	$imagem_atcoA = "";
} else {
	$imagem_atcoA = "menor.png";
}

if (substr($porcen_atcoB, 0,1) == "-") {
	$imagem_atcoB = "maior.png";
} else if ($porcen_atcoB == "0") {
	$imagem_atcoB = "";
} else {
	$imagem_atcoB = "menor.png";
}

echo "<!DOCTYPE html>
<html lang='pt-br'>
<head>
	<meta charset='utf-8'>
	<meta name='viewport' content='width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1'>
	<title>Página Personalizada</title>
	<style type='text/css'>
		@font-face {
    		font-family: SourceSemiBold;
    		src: url(SourceSansPro-SemiBold.ttf)
		}
		@font-face {
    		font-family: SourceBold;
    		src: url(SourceSansPro-Bold.ttf)
		}
		@font-face {
    		font-family: SourceRegular;
    		src: url(SourceSansPro-Regular.ttf)
		}
		body {
			font-family: SourceBold;
			width: 100%;
			margin: auto;
		}
		header, article {
			align-content: center;
			position: relative;
			padding-left: 20px;
			padding-right: 20px;
		}
		h1 {
			color: #0078A1;
			font-size: 20pt;
			padding-top: 10px;
			padding-bottom: 0px;
			margin: 0px;
			margin-bottom: -5px;
		}
		h1.principal {
			color: #0078A1;
			font-size: 20pt;
		}
		.data {
			color: #000000;
		}
		.linha-01 {
			border-top: 2px solid #0078A1;
		}
		.linhas {
			border-top: 1px solid #C4C4C4;
		}
		br {
			margin:5px 0px 5px 0px;			
		}		
		span.titulo {
			font-size: 14pt;
			color: #000000;
			font-weight: bold;
			padding-left: -10px;
			margin-left: -10px;
		}
		span.subTitle {
			font-size: 9pt;
			color: #C4C4C4;
			padding-left: -10px;
			margin-left: -10px;
		}
		span.grau {
			font-size: 16pt;
			color: #000000;
			font-weight: bold;
		}
		div.linha-vertical01 {
  			height: 45px;
  			border-left: 2.5px solid #0078A1;
  			margin-left: 15px;
  			margin-right: 5px;
		}
		span.maxMin {
			font-family: SourceSemiBold;
			font-size: 8pt;
			color: #C4C4C4;
		}
		span.numero {
			font-family: SourceRegular;
			font-size: 13pt;
		}
		span.tituloAcao {
			font-size: 14pt;
			color: #0078A1;
			font-weight: bold;
			padding-left: -10px;
			margin-left: -10px;
		}
	</style>
</head>
<body>
	<header>
		<h1 class='principal'>".$nomediadasemana.", <span class='data'>".$dia." de ".$nomemes."<span></h1>
		<hr class='linha-01'>
	</header>
	<article>
		<h1>Clima</h1>
		<table cellspacing='10'>
			<tr> 
				<td>
					<span class='titulo'>AGORA<br/></span>
					<span class='subTitle'>São Paulo</span>
				</td>
				<td><div class='linha-vertical01'></div></td>
				<td><img width='28' height='28' src='".$imagem_agora02[0]."'> <span class='grau'> &nbsp;&nbsp;".$tempo_agora02[0]."ºC</span></td>
			</tr>
			<tr> 
				<td>
					<span class='titulo'>HOJE<br/></span>
					<span class='subTitle'>São Paulo</span>
				</td>
				<td><div class='linha-vertical01'></div></td>
				<td><img src='max.png'/><span class='maxMin'> Máx. </span><span class='grau'>".$max_hoje02[0]."ºC</span></td>
				<td><img src='min.png'/><span class='maxMin'> Mín. </span><span class='grau'>".$min_hoje02[0]."ºC</span></td>
			</tr>
			<tr> 
				<td>
					<span class='titulo'>AMANHÃ<br/></span>
					<span class='subTitle'>São Paulo</span>
				</td>
				<td><div class='linha-vertical01'></div></td>
				<td><img src='max.png'/><span class='maxMin'> Máx. </span><span class='grau'>".$max_amanha02[0]."ºC</span></td>
				<td><img src='min.png'/><span class='maxMin'> Mín. </span><span class='grau'>".$min_amanha02[0]."ºC</span></td>
			</tr>
		</table>
		<hr class='linhas'>
	</article>

	<article>
		<h1>Cotações</h1>
		<table cellspacing='10'>
			<tr>
				<td>
					<span class='titulo'>DÓLAR<br/></span>
				</td>
				<td></td>
				<td><img src='".$imagem_compra_indice."'>&nbsp;&nbsp; R$ <span class='numero'>".$dolar_venda."</span></td>
				<td></td>
				<td>
					<span class='titulo'>EURO<br/></span>
				</td>
				<td></td>
				<td><img src='".$imagem_euro_indice."'>&nbsp;&nbsp; R$ <span class='numero'>".$euro."</span></td>
			</tr>
			<tr>
				<td>
					<span class='titulo'>COROA<br/></span>
					<span class='titulo'>SUECA</span>
				</td>
				<td></td>
				<td><img src='".$imagem_coroaS."'>&nbsp;&nbsp; R$ <span class='numero'>".$valor_coroaS."</span></td>
			</tr>
		</table>
		<hr class='linhas'>
	</article>

	<article>
		<h1>Ações</h1>
		<table cellspacing='10'>
			<tr>
				<td colspan='2'>
					<span class='titulo'>Atlas Copco AB<br/></span>
					<span class='subTitle' style='font-size: 9pt;'>Investing.com</span>
				</td>
				<td rowspan='2'><div class='linha-vertical01' style='margin-right: 10px; margin-left: 0px; height: 120px;''></div></td>
				<td colspan='2'>
					<span class='titulo'>Bolsa de Valores<br/></span>
					<span class='subTitle' style='font-size: 9pt;'>CNN Money/Yahoo</span>
				</td>
			</tr>
			<tr>
				<td>
					<span class='tituloAcao'>ATCO-A<br/></span>
				</td>
				<td>
					<span class='numero'><img src='".$imagem_atcoA."'> &nbsp;".$valor_atcoA."<br/></span>
					<span class='maxMin'>".$variacao_atcoA." (".$porcen_atcoA.")</span>
				</td>
				<td>
					<span class='tituloAcao'>BVSP<br/></span>
				</td>
				<td>
					<span class='numero'><img src='".$imagem_ibovespaS."'> &nbsp;".$valor_ibovespaS."<br/></span>
					<span class='maxMin'>".$variacao_ibovespaS." (".$porcen_ibovespa.")</span>
				</td>
			</tr>
		</table>
	</article>

</body>
</html>";

?>