<?php
session_start();
include("../conexao.php");

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

if (!$id) {
    die("Erro de conexão com o banco de dados");
}

//Recebe os dados que foram enviados pelo formulário
$data_feedback = $_POST['data_feedback'];
$comentario = $_POST['comentario'];
$avaliacao = $_POST['avaliacao'];

//Consulta SQL para inserir os dados
$sql = "INSERT INTO feedback(data_feedback, comentario, avaliacao)
        VALUES('".$data_feedback."',
               '".$comentario."',
               '".$avaliacao."')";

//Executa a consulta no banco de dados
$res = mysqli_query($id, $sql);

// Retorna apenas sucesso ou erro
if ($res) {
    echo "<script>
        window.location.href = 'feedback.php';
    </script>";

}else {
    echo "<script>
        alert('❌ Erro ao enviar feedback!');
        window.location.href = 'feedback.php';
    </script>";
}
?>