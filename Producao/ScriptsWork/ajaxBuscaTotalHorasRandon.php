<?php

ini_set('memory_limit', '4095M');
set_time_limit(0);
error_reporting(0);

/*POST https://api.a.worksphere.com.br/oauth 
Com o payload: 

{ 
    "grant_type":"password", 
    "username":"admin@b2midia.com.br", 
    "password":"12345678", 
    "client_id":"b2news-web" 
} */

if(!$_POST['Usuario']){

  $retornoSaida["status"] = false;
  $retornoSaida["horas"] = "Usuario nao informado";

  echo json_encode($retornoSaida);

  die();
}

$dadosWeb = array();

$dadosWeb["API Producao 2"]["auth"] = "https://terceiros.randon.com.br:8443/pontosoft-bh-randon-api/auth";
$dadosWeb["API Producao 2"]["dados"] = "https://terceiros.randon.com.br:8443/pontosoft-bh-randon-api/saldo/";

$usuarioRandon[$i]["usuario"] = str_replace("@randon.com.br", "", $_POST['Usuario']);
$usuarioRandon[$i]["dataReferencia"] =  date('Y-m-d', strtotime(date('Y-m-d'). ' - 1 days'));

foreach( $usuarioRandon as $chaveA => $valorA){

    foreach( $dadosWeb as $chave => $valor){

        $data = "usuario=PONTOSOFTSALDOBH&senha=6DLCT3UGA";

        $ch = curl_init();

        curl_setopt( $ch, CURLOPT_URL, $valor["auth"] );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_VERBOSE, true );
        curl_setopt( $ch, CURLOPT_HEADER, false );
        curl_setopt( $ch, CURLOPT_POST, true);
        curl_setopt( $ch, CURLOPT_TIMEOUT, 1000 );
        curl_setopt( $ch, CURLOPT_POSTFIELDS,  $data );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
          "Content-Type: application/x-www-form-urlencoded",
          "Api-Key: df6a2fa5-da83-43c9-801a-b2b6a341a92d"
        ));

        $response = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        ob_flush();

        if($response){

            $retorno = json_decode($response);

            if( is_object($retorno)){

                if($retorno->success){

                    $ch = curl_init();

                    $params = '';

                    foreach($valorA as $key=>$value){

                        $params .= $key.'='.$value.'&';
                    }

                    $params = trim($params, '&');

                    $ch = curl_init();

                    curl_setopt($ch, CURLOPT_URL, $valor["dados"]."?".$params);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                    curl_setopt($ch, CURLOPT_HEADER, FALSE);
                    curl_setopt($ch, CURLOPT_POST, false);
                    curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 1000);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                      "Api-Key: df6a2fa5-da83-43c9-801a-b2b6a341a92d",
                      "Auth-Token: ".$retorno->token
                    ));

                    $fields = array();

                    $response = curl_exec($ch);
                    $info = curl_getinfo($ch);
                    curl_close($ch);

                    if($response){

                        $retornoW2 = json_decode($response);

                        if($retornoW2->success){

                            $retornoSaida["status"] = true;
                            $retornoSaida["horas"] = $retornoW2->saldoHoras;
                        }
                        else{

                            $retornoSaida["status"] = false;
                            $retornoSaida["horas"] = "Entre em contato com o RH";
                        }
                    }
                    else{

                        $retornoSaida["status"] = false;
                        $retornoSaida["horas"] = "Erro no layout da randon";
                    }
                }
                else{

                    $retornoSaida["status"] = false;
                    $retornoSaida["horas"] = "Usuario ou senha invalidos para pegar o tocken";
                }
            }
            else{

                $retornoSaida["status"] = false;
                $retornoSaida["horas"] = "Erro no layout da randon";
            }
        }
        else{

            $retornoSaida["status"] = false;
            $retornoSaida["horas"] = "Usuario ou senha invalidos para pegar o tocken";
        }
    }
}

echo json_encode($retornoSaida);

