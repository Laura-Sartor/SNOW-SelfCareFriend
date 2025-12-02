<?php include 'adm.php'; 
session_start();

include("../../conexao.php");
$sql_admin = "SELECT id_usuario FROM usuario WHERE tipo = 'A' LIMIT 1";
$res_admin = mysqli_query($id, $sql_admin);

if($res_admin && mysqli_num_rows($res_admin) > 0) {
    $admin = mysqli_fetch_array($res_admin);
    $admin_id = $admin['id_usuario'];
} else {
    // Se não encontrar admin, usa o ID da sessão
    $admin_id = $_SESSION['id_usuario'];
}


?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="admin.css">
    <title>Área do Administrador - SNOW</title>
</head>
<body>
    <style>
    .top-navbar {
    background: white !important;
    }
    </style>
    <!-- Navbar Superior Compacta -->
    <div class="top-navbar">
        <div class="logo">
    <a href="../pginicialADM/admin.php" style="display: inline-block;">
        <img src="logoFloco.png">
    </a>
    </div>
        
        <div class="navbar-container">
            <ul class="navbar">
                <li class="active">
                    <a href="../pginicialADM/admin.php">
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
                    <a href="../pgDiario/listadiarioemocao_adm.php">
                        <i class="fas fa-history"></i>
                        <span>Diário|Emoção</span>
                    </a>
                </li>
                <li>
                    <a href="../pgEmocoes/listaemocao_adm.php">
                        <i class="fas fa-smile"></i>
                        <span>Emoção</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="user-section">
            <div class="user-info" id="user-menu-toggle">
                <span id="admin-name"><?php echo $_SESSION['login']; ?></span>
<div class="user-avatar"><?php echo strtoupper(substr($_SESSION['login'], 0, 1)); ?></div>
            </div>
          <div class="user-menu" id="user-menu">
    <div class="user-menu-item">
        
    </div>
     <div class="user-menu-divider"></div>
       <a href="../../login/edita_usu.php?id_usuario=<?php echo $admin_id; ?>" style="text-decoration: none; color: inherit;">
    <div class="user-menu-item" style="cursor: pointer;">
        <i class="fas fa-user"></i>
        <span>Meu Perfil</span>
    </div>
</a>
    <div class="user-menu-item" onclick="confirmarLogout()" style="cursor: pointer;">
        <i class="fas fa-sign-out-alt"></i>
        <span>Sair</span>
    </div>
</div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header">
            <h2>Visão Geral</h2>
        </div>
        
        <div class="cards-container">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Técnicas</h2>
                    <div class="card-icon techniques">
                        <i class="fas fa-spa"></i>
                    </div>
                </div>
                <div class="card-content">
                    <div class="stat-number"><?php echo $total_tecnicas; ?></div>
                    <div class="stat-description">Técnicas Cadastradas</div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Usuários</h2>
                    <div class="card-icon users">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="card-content">
                    <div class="stat-number"><?php echo $total_usuarios; ?></div>
                    <div class="stat-description">Usuários Cadastrados</div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Feedbacks</h2>
                    <div class="card-icon feedbacks">
                        <i class="fas fa-comments"></i>
                    </div>
                </div>
                <div class="card-content">
                    <div class="stat-number"><?php echo $total_feedbacks; ?></div>
                    <div class="stat-description">Feedbacks Recebidos</div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Emoções</h2>
                    <div class="card-icon emotions">
                        <i class="fas fa-smile"></i>
                    </div>
                </div>
                <div class="card-content">
                    <div class="stat-number"><?php echo $total_emocoes; ?></div>
                    <div class="stat-description">Emoções Cadastradas</div>
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
</body>
</html>

<style>
@import url('https://fonts.googleapis.com/css2?family=Madimi+One&display=swap'); 

        /* Reset e estilos base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Madimi One', sans-serif;
        }

        /* Estilos do footer */
     /* Footer NO FINAL DA PÁGINA */
footer {
  background-color: #457f9e;
  padding: 20px 0;
  margin-top: auto; /* Isso empurra o footer para baixo */
  width: 100%;
}

.container-footer {
  max-width: 1400px;
  padding: 0 4%;
  margin: auto;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.logo-snow {
  display: flex;
  align-items: center;
  color: white;
  font-size: 24px;
  font-weight: 600;
  letter-spacing: -1px;
}

.snowflake {
  margin: 0 2px;
  font-size: 20px;
  font-weight: 300;
  animation: spin 4s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.rodape-direitos {
  color: white;
  font-size: 14px;
}

@media (max-width: 768px) {
  .container-footer {
    flex-direction: column;
    gap: 15px;
  }
  
  .logo-snow {
    font-size: 20px;
  }
  
  .main-content {
    gap: 20px;
    padding: 20px 10px;
  }
}
    </style>
</head>
<body>
   <!-- Footer -->
<footer>
   <div class="container-footer">
       <div class="logo-snow">
           Sn<span class="snowflake">❄</span>w
       </div>
       <p class="rodape-direitos">Copyright © 2024 – Todos os Direitos Reservados.</p>
   </div>
</footer>

</body>
    </html>