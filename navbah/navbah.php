
<?php

// Agora, como o usuário está logado, busque o email se necessário (mas ele já deve estar na sessão)
$user_login = $_SESSION['login'];  // Use diretamente da sessão, pois foi salvo no login
$user_initial = strtoupper(substr($user_login, 0, 1));  // Primeira letra (sempre definida agora)

// Se, por algum motivo, precisar buscar do banco (ex.: se a sessão expirou), use isso (opcional e seguro)
if (empty($user_login)) {
    $id_usuario = $_SESSION['id_usuario'];
    
    // Use prepared statement para segurança
    $stmt = $conn->prepare("SELECT login FROM usuario WHERE id_usuario = ?");
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user_login = $row['login'];
        $_SESSION['login'] = $user_login;  // Atualize a sessão
    } else {
        // Se não encontrar, algo está errado - redirecione
    header("Location: ../login/autentica&login.php");
        exit();
    }
    $stmt->close();
}
?>



<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="navbah.css">
</head>
<body>
    <!-- Navbar Superior Compacta -->
    <div class="top-navbar">
        <div class="logo">
            <img src="../KEKA_06_11/pginicial/iconFloco.png">
        </div>
        
        <div class="navbar-container">
            <ul class="navbar">
                <li class="active">
                <li>
                    <a href="../TCC_SNOW/agenda/agenda.php">
                        <i class="fas fa-diario"></i>
                        <span>Diário</span>
                    </a>
                </li>
                <li>
                    <a href="../TCC_SNOW/tecnicas/tecnicas.html">
                        <i class="fas fa-tecnicas"></i>
                        <span>Técnicas</span>
                    </a>
                </li>
                <li>
                    <a href="../TCC_SNOW/historico/historico.php">
                        <i class="fas fa-historico"></i>
                        <span>Historico</span>
                    </a>
                </li>
                <li>
                    <a href="../TCC_SNOW/feednack/feedback.html">
                        <i class="fas fa-feedback"></i>
                        <span>Feedback</span>
                    </a>
                </li>
            </ul>
        </div>
        

        
       
<div class="user-section">
    <div class="user-info" id="user-menu-toggle">
        <span id="admin-name">
            <?php echo htmlspecialchars($user_login); ?>
        </span>
        <div class="user-avatar"><?php echo $user_initial; ?></div>
    </div>
<div class="user-menu" id="user-menu">
    <div class="user-menu-item">
        <a href="edita_usu.php?id_usuario=<?= $_SESSION['id_usuario'] ?>" style="text-decoration: none; color: inherit; display: flex; align-items: center;">
            <i class="fas fa-user"></i>
            <span>Meu Perfil</span>
        </a>
    </div>
</div>
        <div class="user-menu-divider"></div>
        <div class="user-menu-item" onclick="confirmarLogout()" style="cursor: pointer;">
            <i class="fas fa-sign-out-alt"></i>
            <span>Sair</span>
        </div>
    </div>
</div>


<script>
        
        function confirmarLogout() {
        if (confirm('Tem certeza que deseja sair?')) {
        window.location.href = '../TCC_SNOW/login/autentica&login.php';
    }
    
}
        // JavaScript para o menu do usuário
        document.getElementById('user-menu-toggle').addEventListener('click', function() {
            document.getElementById('user-menu').classList.toggle('show');
        });

        // Fechar menu ao clicar fora
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.user-section')) {
                document.getElementById('user-menu').classList.remove('show');
            }
        });
    </script>
</body>
</html>








