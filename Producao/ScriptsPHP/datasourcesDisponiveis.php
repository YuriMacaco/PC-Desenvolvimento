<?php
	require_once('simple_html_dom_2.php');
	error_reporting(0);
	
	$userAgent = "Firefox (WindowsXP) - Mozilla/5.0 (Windows; U; Windows NT 5.1; en-GB; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6";
	$url = "http://b2midia:b2m1d1410@tv.b2midia.com.br/admin/ajaxHandler.ud121?trigger_event_0=dsindexjson&iDisplayLength=-1&iSortCol_0=0&sSortDir_0=asc&iSortingCols=1&bSortable_0=true&bSortable_1=true&bSortable_2=true&bSortable_3=false";
	
	$header = '
	<head>
		<style>
			table {
			  border-collapse: collapse;
			  width: 100%;
			}

			th, td {
			  text-align: left;
			  padding: 8px;
			}
			
			tr {
			  height: 20px;
			}

			tr:nth-child(even){background-color: #e6e6e6}

			th {
			  background-color: #4CAF50;
			  color: white;
			}
		</style>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
	</head>';
	echo $header;
	
	$ds_numero 			= 0;
	$ds_online_hoje 	= 0;
	$ds_online_ontem 	= 0;
	$ds_offline_total 	= 0;
	
//====================================================================================
	
	$c = curl_init();
	curl_setopt($c, CURLOPT_USERAGENT, 			$userAgent);
	curl_setopt($c, CURLOPT_URL,				$url);
	curl_setopt($c, CURLOPT_FAILONERROR, 		true);
	curl_setopt($c, CURLOPT_FOLLOWLOCATION, 	true);
	curl_setopt($c, CURLOPT_AUTOREFERER, 		true);
	curl_setopt($c, CURLOPT_RETURNTRANSFER,		true);
	curl_setopt($c, CURLOPT_VERBOSE, 			false);
	$page = curl_exec($c);
	curl_close($c);
	
	$corpo 		= str_get_html($page);
	$dados 		= explode('["', $corpo);
	$diaHoje	= date('d/m/Y');
	$diaOntem	= date('d/m/Y',strtotime("-1 days"));
	
	$array_Editorial	= array();
	$array_Variedades	= array();
	$array_ClimaTempo	= array();
	$array_Transito		= array();
	$array_Sociais		= array();
	$array_Indicadores	= array();
	
//====================================================================================
	
	function CheckIfWordAppears($myString, $myStrArray){
		$temString = false;
		for($i = 0; $i < sizeof($myStrArray); $i++){
			if (strpos($myString, $myStrArray[$i]) !== false){
				$temString = true;
			}
		}
		return $temString;
	}
	
//====================================================================================
	
	for($idx = 1; $idx < sizeof($dados); $idx++) {
		
		$dado_parts		= explode('"', $dados[$idx]);
		$description	= html_entity_decode($dado_parts[0]);
		$ultima			= $dado_parts[4];
		$text 			= $description;
		$str     		= str_replace('\u','u',$text);
		$description 	= preg_replace('/u([\da-fA-F]{4})/', '&#x\1;', $str);
		$description	= html_entity_decode($description);
		$ultima			= str_replace('\/','/',$ultima);
		
		$dataSinc 		= substr($ultima, 0, 10);
		$dataSinc		= (string)$dataSinc;
		$diaHoje		= (string)$diaHoje;
	
		$GLOBALS['ds_numero'] += 1;
	
		if($dataSinc == $diaHoje){
			$GLOBALS['ds_online_hoje'] += 1;
			
			$strArray_Editorial			= 'EDITORIAL_';
			$strArray_Variedades		= 'VARIEDADES_';
			$strArray_ClimaTempo		= 'CLIMA_';
			$strArray_Transito			= 'TRANSITO_';
			$strArray_Sociais			= 'REDESSOCIAIS_';
			$strArray_Indicadores		= 'INDICADORES_';
			
			$strArray_Editorial			= explode(',', $strArray_Editorial);
			$strArray_Variedades		= explode(',', $strArray_Variedades);
			$strArray_ClimaTempo		= explode(',', $strArray_ClimaTempo);
			$strArray_Transito			= explode(',', $strArray_Transito);
			$strArray_Sociais			= explode(',', $strArray_Sociais);
			$strArray_Indicadores		= explode(',', $strArray_Indicadores);
			
			if(CheckIfWordAppears($description, $strArray_Editorial)){
				array_push($array_Editorial, trim($description));
			
			}else if(CheckIfWordAppears($description, $strArray_Variedades)){
				array_push($array_Variedades, trim($description));
			
			}else if(CheckIfWordAppears($description, $strArray_ClimaTempo)){
				array_push($array_ClimaTempo, trim($description));
			
			}else if(CheckIfWordAppears($description, $strArray_Transito)){
				array_push($array_Transito, trim($description));
			
			}else if(CheckIfWordAppears($description, $strArray_Sociais)){
				array_push($array_Sociais, trim($description));
			
			}else if(CheckIfWordAppears($description, $strArray_Indicadores)){
				array_push($array_Indicadores, trim($description));
			}
			
		}else if($dataSinc == $diaOntem){
			$GLOBALS['ds_online_ontem'] += 1;
		}else{
			if($dataSinc != $diaHoje){
				$GLOBALS['ds_offline_total'] += 1;
			}
		}
	}
	
	echo '
	<div class="table-responsive" id="datasources">
	<table>
		<tr>
			<th>Editorial ('.count($array_Editorial).')</th>
			<th>Variedades ('.count($array_Variedades).')</th>
			<th>Clima e Tempo ('.count($array_ClimaTempo).')</th>
			<th>Trânsito ('.count($array_Transito).')</th>
			<th>Mídias Sociais ('.count($array_Sociais).')</th>
			<th>Indicadores ('.count($array_Indicadores).')</th>
		</tr>';
	
	for($i = 0; $i < max(count($array_Editorial), count($array_Variedades), count($array_ClimaTempo), count($array_Transito), count($array_Sociais), count($array_Indicadores)); $i++){
	
		echo "<tr>";
	
		if($array_Editorial) {
			echo "<td>".$array_Editorial[$i]."</td>";
		}else{
			"<td></td>";
		}
		
		if($array_Variedades) {
			echo "<td>".$array_Variedades[$i]."</td>";
		}else{
			"<td></td>";
		}
		
		if($array_ClimaTempo) {
			echo "<td>".$array_ClimaTempo[$i]."</td>";
		}else{
			"<td></td>";
		}
		
		if($array_Transito) {
			echo "<td>".$array_Transito[$i]."</td>";
		}else{
			"<td></td>";
		}
		
		if($array_Sociais) {
			echo "<td>".$array_Sociais[$i]."</td>";
		}else{
			"<td></td>";
		}
		
		if($array_Indicadores) {
			echo "<td>".$array_Indicadores[$i]."</td>";
		}else{
			"<td></td>";
		}
		
		echo "</tr>"; 
	}
	
	echo '</table></div>
		<div align="center">  
			 <button name="create_excel" id="create_excel" class="btn btn-success" style="width:200px; height:50px">Gerar arquivo Excel</button>  
		</div>';
	
	echo "
	<script>  
	$(document).ready(function(){  
		$('#create_excel').click(function(){  
			var excel_data = $('#datasources').html();  
			excel_data = $.trim(excel_data);
			<?php header('Content-Type: application/vnd.ms-excel');  
			header('Content-disposition: attachment; filename=FeedsDisponiveis.xls');
			?>
		});  
	 });  
	 </script>";
	
	echo "<dados>";
	echo "<ds_numero><![CDATA[".$ds_numero."]]></ds_numero>";
	echo "<ds_online_hoje><![CDATA[".$ds_online_hoje."]]></ds_online_hoje>";
	echo "<ds_online_ontem><![CDATA[".$ds_online_ontem."]]></ds_online_ontem>";
	echo "<ds_offline_total><![CDATA[".$ds_offline_total."]]></ds_offline_total>";
	echo "<update><![CDATA[".date('d/m/Y H:i')."]]></update>";
	echo "</dados>";

	$corpo -> clear();
	unset($corpo);
	echo "</channel></rss>";
?>