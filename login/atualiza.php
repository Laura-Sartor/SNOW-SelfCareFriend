<?php
//Conexão com o banco de dados
include("../conexaoO.php");

//Recebe os dados que foram enviados pelo formulário
$id_usuario = $_POST['id_usuario']; 
$login = $_POST['login'];
$tipo = $_POST['tipo'];

session_start();
$_SESSION['login'] = $login;


//Consulta SQL para atualizar
$sql = "UPDATE usuario SET 
                        login ='$login',
                        tipo ='$tipo'
                        WHERE id_usuario = $id_usuario";

//Executa a consulta no banco de dados
$res = mysqli_query($conn,$sql);
?>

<script>
    <?php if($res): ?>
        // Verifica se é admin pelo tipo
        <?php if($tipo == 'A'): ?>
            window.location.href = '../admin/pginicialADM/admin.php';
        <?php else: ?>
            window.location.href = '../index.php';
        <?php endif; ?>
    <?php else: ?>
        alert('❌ Erro ao atualizar usuário!');
        window.history.back();
    <?php endif; ?>
</script>