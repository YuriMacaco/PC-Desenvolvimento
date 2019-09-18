<style type="text/css">h3{
margin-left: 100px;
}

.caixa{
	display;		block;
	position:		relative;
	font-family: 	"Verdana";
	margin:			20px auto;
	width: 			95%;
	height: 		480px;
	border-radius: 	20px;
	text-align: 	left;
	color:			white;
	text-shadow: 	2px 2px 1px rgba(0,0,0,0.5);
	vertical-align: middle;
}

#descricao{
	display;		block;
	text-align: 	center;
	vertical-align: bottom;
	text-shadow: 	none;
	word-wrap: 		break-word;
	padding:		0 25px 60px 25px;
}

img{
	position: 		relative;
	display: 		block;
	float: 			left;
	top: 			12px;
	left: 			35px;
	width: 			46px;
	height: 		auto;
	z-index: 		3;
	filter: 		invert(100%);
}
</style>
<p>&nbsp;</p>

<div class="caixa" style="background-color: #0083b9;"><img alt="" src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5a/Clock_12-51.svg/2000px-Clock_12-51.svg.png" syle="width: 100% height: auto;" />
<div style="padding-top: 5px">
<h3>Banco de horas</h3>
</div>

<div class="caixa" style="background-color: #00afef; height: 220px; text-align: center;">
	;<input id="login" name="viewer" type="hidden" value="||username||" />
<div id="meuValor" style="font-size: 40px; font-weight: bold; padding-top: 86px">Carregando...</div>
</div>

<div id="descricao">Carregando...</div>
</div>
<script>
	$(document).ready(function () {
		var username = document.getElementById("login").value;
		ajaxBuscaTotalHorasRandon(username);
	});

	function ajaxBuscaTotalHorasRandon(username) {

		var meuStatus;
	
		$.ajax({
			type: 'POST',
			dataType: 'json',
			dataType: 'html',
			async: false,
			url: 'ajax/ajaxBuscaTotalHorasRandon.php',
			async: true,
			data: {"Usuario":  username },
			success: function (response) {
				if (response) {

					var text = response;

					obj = JSON.parse(text);
					document.getElementById("meuValor").outerHTML = "<div id='meuValor' style='font-size: 70px; font-weight: bold; padding-top: 60px'>.</div>";
					document.getElementById("meuValor").innerHTML = "<span style='font-size: 70px;'>" + obj.horas + "</span>";
					
					if(obj.horas.replace(":", "") < 0){
					
						meuStatus = "<span style='color: #ff9f80; font-weight: bold;'>NEGATIVO</span>";
						document.getElementById("descricao").innerHTML = "Você possui " + obj.horas.substring(1) + " horas em " + meuStatus + ".<br/> O saldo de horas refere-se às informações atualizadas até o dia anterior.<br/> Em caso de alguma discordância, esquecimento de marcação ou justificativa de ausência, você deve abrir chamado até o dia de fechamento do seu ponto.<br/> Dúvidas, contate CAER - Ramal 2015 ";
					
					}else if(!isNaN(obj.horas.replace(":", ""))){
					
						meuStatus = "<span style='color: #9fff80; font-weight: bold;'>POSITIVO</span>";
						document.getElementById("descricao").innerHTML = "Você possui " + obj.horas + " horas em " + meuStatus + ".<br/> O saldo de horas refere-se às informações atualizadas até o dia anterior.<br/> Em caso de alguma discordância, esquecimento de marcação ou justificativa de ausência, você deve abrir chamado até o dia de fechamento do seu ponto.<br/> Dúvidas, contate CAER - Ramal 2015 ";
					
					}else{
						document.getElementById("meuValor").outerHTML = "<div id='meuValor' style='font-size: 40px; font-weight: bold; padding-top: 80px'></div>";
						document.getElementById("descricao").innerHTML = "<span style='color: #ff9f80; font-weight: bold;'>Não foi possível carregar os dados. Favor verificar com a equipe responsável.</span>";
					}
				}
			},
			error: function () {
				document.getElementById("descricao").innerHTML = "Falha no carregamento dos dados.";
			}
		});
	}
</script>