<?php
include("../../conexao.php");

$id_emocao = $_POST['id_emocao']; 
$nome = $_POST['nome'];

// Se veio imagem nova
if ($_FILES['icone']['size'] > 0) {
    $icone = $_FILES['icone']['name'];
    $sql = "UPDATE emocao SET nome ='$nome', icone ='$icone' WHERE id_emocao = $id_emocao";
} else {
    // Se não veio imagem nova, NÃO atualiza o campo icone
    $sql = "UPDATE emocao SET nome ='$nome' WHERE id_emocao = $id_emocao";
}

mysqli_query($id,$sql);
header("Location: listaemocao_adm.php");
exit();
?>