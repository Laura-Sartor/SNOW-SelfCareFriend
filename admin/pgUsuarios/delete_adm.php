<?php
include("../../conexao.php");
$deletar = $_GET['id_usuario'];
$sql = "DELETE FROM usuario WHERE id_usuario = $deletar";
mysqli_query($id, $sql);
header("Location: ../pgUsuarios/listausuario_adm.php");
exit();
?>