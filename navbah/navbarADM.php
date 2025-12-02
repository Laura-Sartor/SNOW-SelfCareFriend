
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


    <!-- Navbar Superior Compacta -->
    <div class="top-navbar">
        <div class="logo">
            <img src="logoFloco.png">
        </div>
        
        <div class="navbar-container">
            <ul class="navbar">
                <li class="active">
                    <a href="../pginicialADM/admin.html">
                        <i class="fas fa-chart-bar"></i>
                        <span>Visão Geral</span>
                    </a>
                </li>
                <li>
                    <a href="../pgTecnicas/listatecnicas_adm.php">
                        <i class="fas fa-spa"></i>
                        <span>Técnicas</span>
                    </a>
                </li>
                <li>
                    <a href="../pgUsuarios/listausuario_adm.php">
                        <i class="fas fa-users"></i>
                        <span>Usuários</span>
                    </a>
                </li>
                <li>
                    <a href="../pgFeedback/lista_feedback_adm.php">
                        <i class="fas fa-comments"></i>
                        <span>Feedback</span>
                    </a>
                </li>
                <li>
                    <a href="../pgHistorico/historico.html">
                        <i class="fas fa-history"></i>
                        <span>Histórico</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="user-section">
            <div class="user-info" id="user-menu-toggle">
                <span id="admin-name">Administrador</span>
                <div class="user-avatar">A</div>
            </div>
            <div class="user-menu" id="user-menu">
                <div class="user-menu-item">
                    <i class="fas fa-user"></i>
                    <span>Meu Perfil</span>
                </div>
                <div class="user-menu-divider"></div>
                <div class="user-menu-item" onclick="confirmarLogout()" style="cursor: pointer;">
                <i class="fas fa-sign-out-alt"></i>
                <span>Sair</span>
</div>
            </a>
                
                </div>
            </div>
        </div>
    </div>

    
    <script>
        
        function confirmarLogout() {
        if (confirm('Tem certeza que deseja sair?')) {
        window.location.href = '../../login/autentica&login.php';
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