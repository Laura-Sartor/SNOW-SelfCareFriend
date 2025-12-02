<?php  
session_start();

include("../../conexao.php");
$sql_admin = "SELECT id_usuario FROM usuario WHERE tipo = 'A' LIMIT 1";
$res_admin = mysqli_query($id, $sql_admin);

if($res_admin && mysqli_num_rows($res_admin) > 0) {
    $admin = mysqli_fetch_array($res_admin);
    $admin_id = $admin['id_usuario'];
} else {
    $admin_id = $_SESSION['id_usuario'];
}

// Consulta para usuários
$query_usuarios = mysqli_query($id, "SELECT * FROM usuario");
$total_usuarios = mysqli_num_rows($query_usuarios);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Usuários | SNOW</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Madimi+One&display=swap'); 
        :root {
            --primary-color: #ffffff;
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
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        background-image: url(fundoo.jpg);
            padding-top: 70px;
        }

        .cards-wrapper {
            flex: 1; 
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

        /* Header da página */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 30px 60px;
            gap: 20px;
        }

        .page-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
            color: #000000;
            text-align: left;
            flex: 1;
        }

        .main-content {
            flex: 1; 
        }

        /* Footer */
        footer {
            background-color: #457f9e;
            padding: 20px 0;
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
            
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                margin: 20px 20px;
            }
        }
        
        /* ESTILOS DOS CARDS DO SEGUNDO CÓDIGO */
        .cards-wrapper {
            width: 90%;
            max-width: 1200px;
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
            margin-top: 40px;
            padding: 0 10px;
            font-family: 'Madimi One', sans-serif;
        }

        .cards-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .card {
            background: linear-gradient(135deg, #ffffff 0%, #f0f8ff 100%);
            border: 2px solid #e3f2fd;
            border-radius: 12px;
            padding: 24px 32px;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 24px;
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
        }

        .card-title {
            font-size: 17px;
            font-weight: 700;
            color: #000000;
            margin-bottom: 10px;
        }

        .card-meta {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
            font-size: 13px;
            color: #000000;
        }

        .card-badge {
            display: inline-block;
            padding: 8px 14px;
            background: linear-gradient(135deg, rgba(30, 136, 229, 0.12) 0%, rgba(66, 165, 245, 0.12) 100%);
            color: #1565c0;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            border: 1.5px solid rgba(30, 136, 229, 0.25);
        }

        .card-info {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #555;
        }

        .card-info i {
            color: #1e88e5;
            font-size: 12px;
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

        .back-btn {
            background: #95a5a6;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            font-size: 14px;
            transition: background 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .back-btn:hover {
            background: #7f8c8d;
        }

        /* BOTÃO EXCLUIR */
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
    </style>
</head>
<body>

    <!-- Navbar Superior -->
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
                <li class="active">
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
        <h1>Gerenciar Usuários</h1>
    </div>

    <!-- CARDS IGUALZINHOS AO SEGUNDO CÓDIGO -->
    <div class="cards-wrapper">
        <div class="cards-title"><i class="fas fa-users"></i> Todos os Usuários</div>
        
        <div class="cards-container">
            <?php while ($row = mysqli_fetch_array($query_usuarios)) { ?>
            <div class="card">
                <div class="card-content">
                    <h3 class="card-title"><?php echo $row['login']; ?></h3>
                    <div class="card-meta">
                        <span class="card-badge"><?php echo $row['tipo'] == 'A' ? 'Administrador' : 'Usuário'; ?></span>
                        <span class="card-info"><i class="fas fa-key"></i> ID: <?php echo $row['id_usuario']; ?></span>
                        <span class="card-info"><i class="fas fa-lock"></i> Senha: <?php echo $row['senha']; ?></span>
                        <?php if(isset($row['data_cadastro'])): ?>
                        <span class="card-info"><i class="fas fa-calendar"></i> Cadastro: <?php echo date('d/m/Y', strtotime($row['data_cadastro'])); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- BOTÃO EXCLUIR -->
                <div class="card-actions">
                    <button class="card-action-btn delete" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir este usuário?')">
                        <a href="delete_adm.php?id_usuario=<?php echo $row['id_usuario']; ?>">
                            <img src="lixo.png" alt="Excluir">
                        </a>
                    </button>
                </div>
            </div>
            <?php } ?>
        </div>

        <div class="cards-footer">
            Total: <strong><?php echo $total_usuarios; ?></strong> usuários cadastrados no sistema
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