<?php
session_start(); // ADICIONE ESTA LINHA NO TOPO
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>teste</title>
</head>
<body>

<h1>iniwefie</h1>

<?php 
// Verifica se a sessão existe antes de exibir
if(isset($_SESSION['login'])) {
    echo $_SESSION['login']; 
} else {
    echo "Usuário não logado";
}
?>

<a href="logout.php">Sair</a>

</body>
</html>