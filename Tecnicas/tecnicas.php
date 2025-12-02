<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://cdn.boxicons.com/3.0.3/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="tecnicas.css">
    <title>Técnicas - SNOW</title>
</head>
<body>

<?php
session_start();
include("../conexaoO.php");

$user_login = $_SESSION['login'];
$user_initial = strtoupper(substr($user_login, 0, 1));

if (empty($user_login)) {
    $id_usuario = $_SESSION['id_usuario'];
    
    $stmt = $conn->prepare("SELECT login FROM usuario WHERE id_usuario = ?");
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user_login = $row['login'];
        $_SESSION['login'] = $user_login;
    } else {
        header("Location: ../TCC_SNOW/login/autentica&login.php");
        exit();
    }
    $stmt->close();
}
?>

<!-- Navbar Superior -->
<div class="top-navbar">
    <div class="logo">
        <a href="../index.php">
            <img src="logoFloco.png" alt="Logo Floco - Página Inicial">
        </a>
    </div>
    
    <div class="navbar-container">
        <ul class="navbar">
            <li>
                <a href="../agenda/agenda.php">
                    <i class="fas fa-book"></i>
                    <span>Diário</span>
                </a>
            </li>
            <li class="active">
                <a href="../tecnicas/tecnicas.php">
                    <i class="fas fa-hands-helping"></i>
                    <span>Técnicas</span>
                </a>
            </li>
            <li>
                <a href="../historicoUsuario/graficos.php">
                    <i class="fas fa-chart-line"></i>
                    <span>Histórico</span>
                </a>
            </li>
            <li>
                <a href="../feedbackUsuario/feedback.php">
                    <i class="fas fa-comments"></i>
                    <span>Feedback</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="user-section">
        <div class="user-info" id="user-menu-toggle">
            <span id="admin-name"><?php echo htmlspecialchars($user_login); ?></span>
            <div class="user-avatar"><?php echo $user_initial; ?></div>
        </div>
        <div class="user-menu" id="user-menu">
            <a href="../login/edita_usu.php?id_usuario=<?php echo $_SESSION['id_usuario']; ?>" style="text-decoration: none; color: inherit;">
                <div class="user-menu-item" style="cursor: pointer;">
                    <i class="fas fa-user"></i>
                    <span>Meu Perfil</span>
                </div>
            </a>
            <div class="user-menu-divider"></div>
            <div class="user-menu-item" onclick="confirmarLogout()" style="cursor: pointer;">
                <i class="fas fa-sign-out-alt"></i>
                <span>Sair</span>
            </div>
        </div>
    </div>
</div>

<!-- Conteúdo Principal -->
<div class="main-content">
  <div class="card">
    <img src="3.jpg">
    <div>
      <h1>Respiração</h1>
      <h2>Técnicas de respiração</h2>
      <span>ㅤ</span>
      <a href="respiracao.php"><button> Saiba mais </button></a>
    </div>
  </div>

  <div class="card">
    <img src="2.jpg">
    <div>
      <h1>Meditação</h1>
      <h2>Técnicas de meditação</h2>
       <span>ㅤ</span>
      <a href="meditacao.php"><button> Saiba mais </button></a>
    </div>
  </div>

  <div class="card">
    <img src="4.jpg">
    <div>
      <h1>Vídeos</h1>
      <h2>Vídeos de relaxamento</h2>
      <span>ㅤ</span>
      <a href="visualizacao.php"><button> Saiba mais </button></a>
    </div>
  </div>
</div>

<!-- Footer -->
<footer>
   <div class="container-footer">
       <div class="logo-snow">
           Sn<span class="snowflake">❄</span>w
       </div>
       <p class="rodape-direitos">Copyright © 2024 – Todos os Direitos Reservados.</p>
   </div>
</footer>

<script>
    function confirmarLogout() {
        if (confirm('Tem certeza que deseja sair?')) {
            window.location.href = '../login/autentica&login.php';
        }
    }
    
    
    document.getElementById('user-menu-toggle').addEventListener('click', function() {
        document.getElementById('user-menu').classList.toggle('show');
    });

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.user-section')) {
            document.getElementById('user-menu').classList.remove('show');
        }
    });
</script>

</body>
</html>