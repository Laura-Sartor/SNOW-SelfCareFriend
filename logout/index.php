<?php session_start(); 
$seguranca = isset($_SESSION['ativa']) ? TRUE : header("location:");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>teste logout</title>
</head>
<body>
    <?php if($seguranca){ ?>

        <h1>Painel ADM </h1>
        <h3>bem vindo, <?php echo $_SESSION['nome'];?></h3>
        <a href="logout.php">Sair</a>

        <?php } 
        ?>
</body>
</html>