<?php

/*A API de criação de empresas está disponível no ambiente de testes do Worksphere. Para utilizá-la você deve fazer o seguinte: 
 
1. Obter um token de autenticação. Para isto fazer a chamada: 
POST https://api.a.worksphere.com.br/oauth 
Com o payload: 
{ 
    "grant_type":"password", 
    "username":"admin@b2midia.com.br", 
    "password":"12345678", 
    "client_id":"b2news-web" 
}  

Será retornado um JSON com um parâmetro access_token, você tem de guardar este valor para usar nas chamadas da API. Este token vale por 1 ano, então você pode obter uma vez e controlar a expiração para pedir outro. 

2. Criar a empresa através da chamada: 
POST https://api.a.worksphere.com.br/api/register-company 
Com o payload: 
{ 
    "name" : "Weckx Co.", 
    "slug" : "weckx", 
    "admin_name" : "Felipe Weckx", 
    "username" : "felipe", 
    "password" : "1234", 
    "email" : "felipe@weckx.net", 
    "events_enabled" : true, 
    "chat_enabled" : true, 
    "terms_enabled" : true, 
    "contact_enabled" : true 
}  

Os campos são bem auto-explicativos, mas se tiver alguma dúvida me avise. O campo slug é o que será usado para a URL da empresa (Ex: weber.worksphere.com.br, "weber" é o slug). 

Para apagar a empresa pode ser pelo próprio backend ou pela chamada: 

DELETE https://api.a.worksphere.com.br/api/register-company/[:id] 

Onde :id é o ID retornado na criação da empresa.

O código de status de resposta HTTP 400 Bad Request indica que o servidor não pode ou não irá processar a requisição devido a alguma coisa que foi entendida como um erro do cliente (por exemplo, sintaxe de requisição mal formada, enquadramento de mensagem de requisição inválida ou requisição de roteamento enganosa).

https://api.a.worksphere.com.br/oauth?gran_type=password&username=admin@b2midia.com.br&password=12345678&client_id=b2news-web

Senha B2 Admin = B2m1d1@01

#access_token=

*/ 

?>

<!DOCTYPE html>
<html>
<head>
    <title>
        Página Auto Create
    </title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js" type="text/javascript"></script>
</head>
<body>
    <div class="resultado" id="result">RESULTADO</div>

    <script>       
        
        /*$.ajax({
            url: 'https://api.a.worksphere.com.br/oauth',
            dataType: 'json',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify( {'grant_type':'password','username':'admin@b2midia.com.br','password':'12345678','client_id':'b2news-web'}),
            processData: false,
            success: function( data, textStatus, jQxhr ){
                $('#result').html(data.access_token);
            },
            error: function( jqXhr, textStatus, errorThrown ){
                console.log( errorThrown );
            }
        });*/

        $.ajax({
            url : 'https://api.a.worksphere.com.br/oauth',
            type : 'post',
            data : {
                grant_type : 'password',
                username :'admin@b2midia.com.br',
                password : '12345678',
                client_id : 'b2news-web'
            },
            beforeSend : function(){
                $("#resultado").html("ENVIANDO...");
            }
        })
        .done(function(msg){
            $("#resultado").html(msg);
        })
        .fail(function(jqXHR, textStatus, msg){
            alert("Sem acesso");
        }); 

    </script>
    
</body>
</html>