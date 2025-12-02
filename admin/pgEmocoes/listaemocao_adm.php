<?php
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

<html>
     <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="emocao.css">
    <title>Adcionar emoção - SNOW</title>
</head>
<body>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Madimi+One&display=swap'); 
    :root {
        --secondary-color: #3498db;
        --accent-color: #e74c3c;
        --success-color: #2ecc71;
        --warning-color: #f39c12;
        --light-color: #ecf0f1;
        --dark-color: #34495e;
        --text-color: #333;
        --border-radius: 8px;
        --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Madimi One', sans-serif;
    }

    body {
        background-image: url(fundoo.jpg);
        color: var(--text-color);
        line-height: 1.6;
        min-height: 100vh;
        position: relative;
        padding-bottom: 0px;
        display:flex;
        flex-direction:column;
        padding-top:70px;
    }

    body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url(fundoo.jpg);
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-attachment: fixed;
        z-index: -1;
    }

    /* NAVBAR */
.top-navbar {
    background-color: #ffffff;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 6px 15px;
    height: 40px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 1000;
    transition: all 0.3s ease;
}

.logo img {
    height: 60px;
    width: auto;
    transition: transform 0.3s ease;
}

.logo:hover img {
    transform: scale(1.05);
}

.navbar-container {
    flex: 1;
    display: flex;
    justify-content: center;
    margin: 0 15px;
}

.navbar {
    display: flex;
    list-style: none;
    gap: 99px;
    margin: 0;
    padding: 0;
}

.navbar li a {
    color: rgb(0, 0, 0);
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 8px 15px;
    transition: all 0.3s ease;
    border-radius: 8px;
    font-size: 13px;
    white-space: nowrap;
    position: relative;
    overflow: hidden;
}

.navbar li a::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
    transition: left 0.5s;
}

.navbar li a:hover::before {
    left: 100%;
}

.navbar li a:hover {
    background-color: rgba(101, 141, 169, 0.1);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

/* APENAS UM ITEM DEVE TER ESTA CLASSE */
.navbar li.active a {
    background-color: rgba(101, 141, 169, 0.2);
    font-weight: 600;
    transform: translateY(-1px);
}

.navbar li a i {
    transition: transform 0.3s ease;
}

.navbar li a:hover i {
    transform: scale(1.2);
}

.user-section {
    position: relative;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    padding: 8px 12px;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.user-info:hover {
    background-color: rgba(101, 141, 169, 0.1);
    transform: translateY(-2px);
}

#admin-name {
    font-size: 13px;
    white-space: nowrap;
}

.user-avatar {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: linear-gradient(135deg, #db3434ff, #2980b9);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 13px;
    transition: all 0.3s ease;
}

.user-info:hover .user-avatar {
    transform: scale(1.1);
    box-shadow: 0 4px 8px rgba(52, 152, 219, 0.3);
}

.user-menu {
    position: absolute;
    top: 45px;
    right: 0;
    background: white;
    border-radius: 8px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
    padding: 8px 0;
    min-width: 160px;
    display: none;
    z-index: 100;
    opacity: 0;
    transform: translateY(-10px);
    transition: all 0.3s ease;
}

.user-menu.show {
    display: block;
    opacity: 1;
    transform: translateY(0);
}

.user-menu-item {
    padding: 8px 16px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    color: #333;
    transition: all 0.3s ease;
    border: none;
    background: none;
    width: 100%;
    text-align: left;
}

.user-menu-item:hover {
    background-color: rgba(101, 141, 169, 0.1);
    padding-left: 20px;
}

.user-menu-divider {
    height: 1px;
    background-color: #e0e0e0;
    margin: 4px 0;
}
    /* NOVO CSS PARA CARDS */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 30px 80px;
        gap: 20px;
        width:100%;
    }

    .page-header h1 {
        margin: 0;
        font-size: 28px;
        font-weight: bold;
        color: #000000;
    }

    .add-btn {
        background: #3498db;
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: bold;
        text-decoration: none;
        display: inline-block;
        transition: background 0.3s;
       position: relative; 
     margin-left: auto; 
    margin-top: -30px;    
    margin-right: 155px
    }

    .add-btn:hover {
        background: #2980b9;
    }

    /* Container de cards */
    .cards-wrapper {
        width: 90%;
        max-width: 1000px;
        margin: 30px auto;
        padding: 0 20px;
    }

    .cards-title {
        font-size: 16px;
        font-weight: 800;
        color: #1e88e5;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 24px;
        padding: 0 10px;
        font-family: 'Madimi One', sans-serif;
    }

     .cards-container {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
}

    /* Card individual COM ÍCONES E MENOR */
    .card {
        background: linear-gradient(135deg, #ffffff 0%, #f0f8ff 100%);
        border: 2px solid #e3f2fd;
        border-radius: 12px;
        padding: 20px 24px; /* REDUZIDO */
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px; /* REDUZIDO */
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: #1e88e5;
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.3s ease;
    }

    .card:hover {
        border-color: #bbdefb;
        box-shadow: 0 8px 20px rgba(30, 136, 229, 0.08);
        background: linear-gradient(135deg, #ffffff 0%, #f8fbff 100%);
    }

    .card:hover::before {
        transform: scaleX(1);
    }

    .card-content {
        flex: 1;
        min-width: 0;
        display: flex;
        align-items: center;
        gap: 16px; /* REDUZIDO */
    }

    .card-icon {
        width: 60px; /* REDUZIDO */
        height: 60px; /* REDUZIDO */
        border-radius: 12px;
        background: linear-gradient(135deg, rgba(30, 136, 229, 0.1) 0%, rgba(66, 165, 245, 0.1) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .card-icon img {
        width: 40px; /* REDUZIDO */
        height: 40px; /* REDUZIDO */
        object-fit: contain;
    }

    .card-info {
        flex: 1;
    }

    .card-title {
        font-size: 17px;
        font-weight: 700;
        color: #000000;
        margin-bottom: 5px;
    }

    .card-meta {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: #666;
    }

    .card-info-item {
        display: flex;
        align-items: center;
        gap: 4px;
        color: #555;
    }

    .card-info-item i {
        color: #1e88e5;
        font-size: 12px;
    }

    .card-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
    }

    .card-action-btn {
        width: 40px;
        height: 40px;
        border: none;
        background: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: all 0.2s ease;
        color: #bbb;
        padding: 0;
    }

    .card-action-btn:hover {
        background-color: #f0f8ff;
        color: #1e88e5;
    }

    .card-action-btn.delete:hover {
        background-color: #fff5f5;
        color: #d32f2f;
    }

    .card-action-btn img {
        width: 18px;
        height: 18px;
    }

    .card-action-btn a {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
    }

    .cards-footer {
        margin-top: 20px;
        padding: 0 10px;
        font-size: 12px;
        color: #999;
    }

    .cards-footer strong {
        color: #555;
        font-weight: 600;
    }

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
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            margin: 20px 20px;
        }

        .cards-wrapper {
            width: 95%;
            padding: 0 10px;
        }

        .card {
            flex-direction: column;
            align-items: flex-start;
        }

        .card-content {
            flex-direction: column;
            align-items: flex-start;
        }

        .card-actions {
            align-self: flex-end;
            margin-top: 10px;
        }
    
</style>

     <!-- Navbar Superior-->
    <div class="top-navbar">
        <div class="logo">
    <a href="../pginicialADM/admin.php" style="display: inline-block;">
        <img src="logoFloco.png">
    </a>
    </div>
        
        <div class="navbar-container">
            <ul class="navbar">
                <li>
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
                <li class="active">
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
    <div class="user-menu-item"></div>
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

<script>
        
        function confirmarLogout() {
        if (confirm('Tem certeza que deseja sair?')) {
        window.location.href = '../../login/autentica&login.php';
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

<!-- Header da página -->
<div class="page-header">
    <h1>Gerenciar Emoção</h1>
    <a href="../pgAddEmocao/addemocao_esse.php" class="add-btn">
        <i class="fas fa-plus"></i> Adicionar Emoção
    </a>
</div>

<div class="cards-wrapper">
    <div class="cards-title"><i class="fas fa-smile"></i> Emoções Cadastradas</div>
    
    <div class="cards-container">
        <?php
        // Conexão com o banco de dados
        include("../../conexao.php");

        // Consulta SQL para buscar os registros 
        $sql = "Select * from emocao";

        // Executa a consulta no banco de dados
        $res = mysqli_query($id, $sql);

        // Exibir os registros dos dados abaixo
        while ($linha = mysqli_fetch_array ($res)) { 
            // Monta o caminho completo para o ícone
            $caminho_icone = '../pgAddEmocao/icones_emocao/' . $linha['icone'];
        ?>
        <div class="card">
            <div class="card-content">
                <div class="card-icon">
                    <?php if (!empty($linha['icone'])): ?>
                        <!-- Exibe o ícone da emoção se existir no banco -->
                        <img src="<?php echo $caminho_icone; ?>" alt="Ícone da Emoção">
                    <?php else: ?>
                        <!-- Exibe ícone padrão se não houver ícone no banco -->
                        <i class="fas fa-smile" style="font-size: 30px; color: #1e88e5;"></i>
                    <?php endif; ?>
                </div>
                <div class="card-info">
                    <h3 class="card-title"><?php echo $linha['nome']; ?></h3>
                    <div class="card-meta">
                        <span class="card-info-item"><i class="fas fa-hashtag"></i> ID: <?php echo $linha['id_emocao']; ?></span>
                    </div>
                </div>
            </div>
            <div class="card-actions">
                <button class="card-action-btn" title="Editar">
                    <a href="editaemocao_adm.php?id_emocao=<?php echo $linha['id_emocao']; ?>">
                        <img src='editar.png' alt="Editar">
                    </a>
                </button>
                <button class="card-action-btn delete" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir esta emoção?')">
                    <a href="deleteemocao_adm.php?id_emocao=<?php echo $linha['id_emocao'];?>">
                        <img src='lixo.png' alt="Excluir">
                    </a>
                </button>
            </div>
        </div>
        <?php } ?>
    </div>

    <div class="cards-footer">
        Total: <strong id="total-count"><?php echo mysqli_num_rows($res); ?></strong> emoções cadastradas
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
</body>
</html>