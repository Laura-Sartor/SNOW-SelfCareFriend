<?php
// Conexão com o banco de dados
include("conexao.php");
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../login/autentica&login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_diario = $_POST['id_diario'];
    $data_registro = $_POST['data_registro'];
    $entrada = $_POST['entrada'];
    $id_usuario = $_SESSION['id_usuario'];
    
    // Atualizar registro
    $sql = "UPDATE diario SET data_registro = ?, entrada = ? WHERE id_diario = ? AND id_usuario = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssii", $data_registro, $entrada, $id_diario, $id_usuario);
    
    if (mysqli_stmt_execute($stmt)) {
        header('Location: agenda.php?sucesso=1');
    } else {
        header('Location: agenda.php?erro=1');
    }
} else {
    header('Location: agenda.php');
}
?>