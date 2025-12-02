<?php
session_start();
include ("../conexao.php");

$login_usuario = $_SESSION['login'];
$senha_digitada = $_POST['senha'];

if($login_usuario === 'admin@gmail.com') {
    if($senha_digitada === 'senhaadmin') {
        $sql_admin = "SELECT id_usuario FROM usuario WHERE login = 'admin@gmail.com'";
        $result = mysqli_query($id, $sql_admin);
        
        if(mysqli_num_rows($result) > 0) {
            $admin = mysqli_fetch_assoc($result);
            $id_usuario = $admin['id_usuario'];
            
           
            mysqli_query($id, "DELETE FROM diario_emocao WHERE id_diario IN (SELECT id_diario FROM diario WHERE id_usuario = $id_usuario)");
            mysqli_query($id, "DELETE FROM historico WHERE id_usuario = $id_usuario");
            mysqli_query($id, "DELETE FROM diario WHERE id_usuario = $id_usuario");
            mysqli_query($id, "DELETE FROM usuario WHERE id_usuario = $id_usuario");
        }
        
        file_put_contents('admin_bloqueado.txt', 'excluido');
        echo "Excluído com sucesso";
        exit();
    } else {
        echo "Senha incorreta";
        exit();
    }
}


$sql = "SELECT * FROM usuario WHERE login = '$login_usuario'";
$result = mysqli_query($id, $sql);

if(mysqli_num_rows($result) == 0) {
    echo "Usuário não encontrado";
    exit();
}

$usuario = mysqli_fetch_assoc($result);

if($usuario['senha'] === md5($senha_digitada)) {
    $id_usuario = $usuario['id_usuario'];
    
 
    mysqli_query($id, "DELETE FROM diario_emocao WHERE id_diario IN (SELECT id_diario FROM diario WHERE id_usuario = $id_usuario)");
    
    
    mysqli_query($id, "DELETE FROM diario WHERE id_usuario = $id_usuario");
    
   
    mysqli_query($id, "DELETE FROM usuario WHERE id_usuario = $id_usuario");
    
    echo "Excluído com sucesso!";
} else {
    echo "Senha incorreta";
}
?>