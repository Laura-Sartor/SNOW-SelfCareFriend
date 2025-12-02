<!--Érica Bonfanti e Laura Sartor - 3-52 -->
<?php
// Configurações do banco de dados
$servername = "localhost";  // **Substitua se o servidor for diferente (ex.: IP remoto)**
$username = "root";  // **Substitua pelo seu usuário do banco (ex.: usuário da hospedagem)**
$password = "";  // **Substitua pela senha do banco (deixe vazio se não houver)**
$dbname = "tccSnow";  // **Substitua pelo nome do seu banco (do dump SQL, é "tcc")**

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>
