<?php
$dbname ="tccsnow";
if(!($id = mysqli_connect("localhost","root"))){
    echo "Não foi possível estabelecer uma conexão com o gerenciador MySql";
    exit;
}if(!($con = mysqli_select_db($id,$dbname))){
    echo "Não foi possível estabelecer uma conexão com o banco";
    exit;
}

?>