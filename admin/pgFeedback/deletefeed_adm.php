<?php
include("../../conexao.php");
$deletar = $_GET['id_feedback'];
$sql = "DELETE FROM feedback WHERE id_feedback = $deletar";
mysqli_query($id, $sql);
header("Location: lista_feedback_adm.php");
exit();
?>