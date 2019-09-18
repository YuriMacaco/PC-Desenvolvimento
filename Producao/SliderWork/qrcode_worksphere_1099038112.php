<!DOCTYPE html>
<html>
	<head>
		<script>
			var nomeCompleto = String("<?php echo basename($_SERVER['PHP_SELF']); ?>");
			var nome = nomeCompleto.split("_")[1].split(".")[0];
			var id = nomeCompleto.split("_")[2].split(".")[0];
			
			if(nome != null && nome != ""){
				if (navigator.userAgent.match(/Android/i)) {
					if(nome == "worksphere"){
						window.location.href = "http://play.google.com/store/apps/details?id=br.com.b2midia.b2news";
					}else{
						window.location.href = "http://play.google.com/store/apps/details?id=br.com.b2midia.b2news.".concat(nome);
					}
				}else if (navigator.userAgent.match(/iPhone|iPad|iPod/i)){
					window.location.href = String("https://itunes.apple.com/app/id").concat(id);
					
				}else{
					document.write("Navegador inválido");
				}
			}else{
				document.write("Nome inexistente");
			}
		</script>
	</head>
</html>
