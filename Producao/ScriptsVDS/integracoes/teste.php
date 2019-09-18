<?php

$servername 	= "54.94.146.16";
$username 		= "b2";
$password 		= "dsrloZm5WjX0ecDIDpyTUMLk00ZFBq";
$dbname 		= "b2_votorantim";

$conn = new mysqli($servername, $username, $password, $dbname);

if($conn){
	echo "Conectou";
}else{
	echo "Não Conectou";
}

?>