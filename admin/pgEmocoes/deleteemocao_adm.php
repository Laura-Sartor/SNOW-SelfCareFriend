<?php
include("../../conexao.php");

$id_emocao = $_GET['id_emocao'];

$sql_delete_diario_emocao = "DELETE FROM diario_emocao WHERE id_emocao = $id_emocao";
mysqli_query($id, $sql_delete_diario_emocao);

$sql = "DELETE FROM emocao WHERE id_emocao = $id_emocao";
mysqli_query($id, $sql);

header("Location: listaemocao_adm.php");
exit();
?>