<?php
include("../../conexao.php");
$deletar = $_GET['id_tecnicas'];
$sql = "DELETE FROM tecnicas WHERE id_tecnicas = $deletar";
mysqli_query($id, $sql);
header("Location: listatecnicas_adm.php");
exit();
?>