<?php
include("../../conexao.php");

// Consultas para obter os dados reais do banco
$tecnicas = mysqli_query($id, "SELECT COUNT(*) as total FROM tecnicas");
$usuarios = mysqli_query($id, "SELECT COUNT(*) as total FROM usuario");
$feedbacks = mysqli_query($id, "SELECT COUNT(*) as total FROM feedback");
$emocoes = mysqli_query($id, "SELECT COUNT(*) as total FROM emocao");

// Obter totais
$total_tecnicas = mysqli_fetch_assoc($tecnicas)['total'];
$total_usuarios = mysqli_fetch_assoc($usuarios)['total'];
$total_feedbacks = mysqli_fetch_assoc($feedbacks)['total'];
$total_emocoes = mysqli_fetch_assoc($emocoes)['total'];
?>