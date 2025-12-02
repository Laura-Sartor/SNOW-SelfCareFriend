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

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="addemocao.css">
    <title>Adicionar Emoção - SNOW</title>
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
            color: var(--text-color);
            line-height: 1.6;
            min-height: 100vh;
            position: relative;
            padding-bottom: 80px;
            padding-top:70px;
            
        background-image: url(fundoo.jpg);
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

        /* --- Estilos do Formulário em Tabela CORRIGIDOS --- */
        .form-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
            min-height: calc(100vh - 130px);
        }

        .form-table {
            width: 100%;
            max-width: 500px;
            background: linear-gradient(135deg, #ffffff 0%, #f0f8ff 100%);
            border: 2px solid #e3f2fd;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(30, 136, 229, 0.1);
            border-collapse: collapse;
            overflow: hidden;
            margin: 0; 
        }

        .form-table h1 {
            font-size: 26px;
            font-weight: 700;
            color: var(--dark-color);
            margin: 0;
            text-align: center;
        }

        .form-table th, .form-table td {
            padding: 15px 20px;
            border: none;
            vertical-align: middle;
        }

        .form-table tr:first-child th {
            padding: 20px;
            text-align: center;
            background-color: #f0f8ff;
        }

        .form-table th {
            text-align: right;
            font-weight: 600;
            font-size: 14px;
            color: #34495e;
            width: 30%;
            vertical-align: middle;
        }

        .form-table td {
            text-align: left;
            vertical-align: middle;
        }

        .form-table input[type="text"],
        .form-table input[type="file"] {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            color: var(--text-color);
            background-color: #ffffff;
            transition: all 0.3s ease;
        }

        .form-table input[type="text"]:focus,
        .form-table input[type="file"]:focus {
            outline: none;
            border-color: #1e88e5;
            box-shadow: 0 0 0 3px rgba(30, 136, 229, 0.15);
            background-color: #f8fbff;
        }

        .form-table input[type="submit"] {
            background: linear-gradient(135deg, #1e88e5 0%, #1565c0 100%);
            color: white;
            padding: 14px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(30, 136, 229, 0.3);
            width: 100%;
        }

        .form-table input[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(30, 136, 229, 0.4);
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

        /* Responsividade CORRIGIDA */
        @media (max-width: 768px) {
            .form-table {
                max-width: 100%;
            }
            .form-table th {
                text-align: left;
                width: 100%;
                display: block;
                padding-bottom: 5px;
            }
            .form-table td {
                display: block;
                width: 100%;
                padding-top: 0;
            }
            .form-table tr {
                display: block;
                margin-bottom: 15px;
                border-bottom: 1px solid #eee;
            }
            .form-table tr:last-child {
                border-bottom: none;
            }
            .form-table tr:last-child th {
                text-align: center;
                padding-top: 15px;
            }
        }
    </style>
</head>
<body>

    <!-- Navbar Superior (Mantido do original) -->
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
                    <a href="../pgFeedback/feedback.html">
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
    <div class="user-menu-item">
        
    </div>
    <div class="user-menu-divider"></div>
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

    <div class="form-wrapper">
        <!--Tabela para formulário-->
        <table border="0" align="center" class="form-table">
            <!--Formulário que vai enviar os dados para addemocao.php-->
            <form action="addemocao.php" method="post" onsubmit="alert('✅ Emoção adicionada com sucesso!')" enctype="multipart/form-data">
                
                <tr>
                    <th colspan='2'><h1>Adicionar Emoção</h1></th>
                </tr>

                <tr>
                    <th colspan="2" style="text-align: center; color: #1e88e5; font-size: 14px; font-weight: 600; background-color: #e3f2fd;">Preencha os dados abaixo</th>
                </tr>

                <tr>
                    <th>Nome:</th>
                    <td><input type="text" name="nome" placeholder="Ex: Felicidade" required></td>
                </tr>

                <tr>
                    <th>Ícone:</th>
                    <td><input type="file" name="icone" accept="image/*" required></td>
                </tr>

                <tr>
                    <td colspan="2" style="padding: 20px; text-align: center; background-color: #fafbfc;"> 
                        <input type="submit" name="Cadastrar" value="✓ Cadastrar Emoção">
                    </td>
                </tr>

            </form>
        </table>
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
