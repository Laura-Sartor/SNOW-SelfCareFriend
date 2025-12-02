<?php
include("../../conexao.php");
$nome = $_POST['nome'];
$icone = $_FILES['icone']['name'];
$sql = "INSERT INTO emocao(nome, icone) VALUES('$nome', '$icone')";
mysqli_query($id,$sql);
header("Location: ../pgEmocoes/listaemocao_adm.php");
exit();
?>