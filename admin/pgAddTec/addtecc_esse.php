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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="addtec.css">
    <title>Adicionar Técnica</title>
</head>
<body>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Madimi+One&display=swap'); 
    .top-navbar {
        background: white !important;
    }

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
        background-color: #f5f7fa;
        color: var(--text-color);
        line-height: 1.6;
        min-height: 100vh;
        position: relative;
        padding-bottom: 0px;
         display: flex; 
         
        background-image: url(fundoo.jpg);
    flex-direction: column;
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
    /* NOVO CSS PARA TABELA */
    h1 {
        text-align: center;
        color: #000000;
        margin: 20px 0;
        font-size: 28px;
        font-weight: 700;
    }

    table {
        width: 90%;
        max-width: 800px;
        margin: 30px auto;
        background: linear-gradient(135deg, #ffffff 0%, #f0f8ff 100%);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 8px 24px rgba(30, 136, 229, 0.12);
        border: 2px solid #e3f2fd;
        border-collapse: collapse;
        font-size: 14px;
    }

    table th {
        background: white;
        color: #000000;
        padding: 16px 12px;
        text-align: left;
        font-weight: 600;
        font-size: 13px;
        border: none;
        border-bottom: 1px solid #e3f2fd;
    }

    table td {
        padding: 16px 12px;
        text-align: left;
        border-bottom: 1px solid #f0f8ff;
        color: #333;
        font-size: 13px;
        line-height: 1.4;
    }

    table tr:last-child td {
        border-bottom: none;
    }

    table tr:hover {
        background-color: #f8fbff;
        transition: background-color 0.2s ease;
    }

    table tr:nth-child(even) {
        background-color: #fafbff;
    }

    table input[type="text"],
    table input[type="time"],
    table input[type="date"],
    table input[type="file"],
    table select {
        width: 90%;
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
    }

    table input[type="submit"] {
        background: linear-gradient(135deg, #1e88e5 0%, #42a5f5 100%);
        color: white;
        border: none;
        padding: 12px 32px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(30, 136, 229, 0.25);
        display: block;
        margin: 0 auto;
    }

    table input[type="submit"]:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(30, 136, 229, 0.35);
        background: linear-gradient(135deg, #1565c0 0%, #1e88e5 100%);
    }

   /* Footer NO FINAL DA PÁGINA */
footer {
  background-color: #457f9e;
  padding: 20px 0;
  margin-top: auto; /* Isso empurra o footer para baixo */
  width: 100%;
  flex-shrink: 0; 
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
        table {
            width: 95%;
        }
    
</style>

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
            <li class="active">
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

<script>
    function confirmarLogout() {
        if (confirm('Tem certeza que deseja sair?')) {
            window.location.href = '../../login/autentica&login.php';
        }
    }

function excluirConta() {
    if(!confirm('Tem certeza que deseja excluir sua conta?')) {
        return;
    }
    
    let senha = prompt('Digite sua senha para confirmar a exclusão:');
    
    if(senha) {
        fetch('../excluirconta.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'senha=' + encodeURIComponent(senha)
        })
        .then(response => response.text())
        .then(result => {
            if(result === 'sucesso') {
                alert('Conta excluída com sucesso! Redirecionando...');
                // REDIRECIONAMENTO ABSOLUTO
                window.location.href = 'http://localhost/TCC_SNOW/login/autentica&login.php';
            } else if(result === 'senha_incorreta') {
                alert('Senha incorreta! Conta não excluída.');
            } else {
                alert('Erro: ' + result);
                // REDIRECIONA MESMO COM ERRO
                window.location.href = 'http://localhost/TCC_SNOW/login/autentica&login.php';
            }
        })
        .catch(error => {
            // REDIRECIONA SE DER ERRO NA REQUISIÇÃO
            window.location.href = 'http://localhost/TCC_SNOW/login/autentica&login.php';
        });
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

<!-- Tabela para formulário - ESTRUTURA ORIGINAL MANTIDA -->
<table border="2" align="center">
    <form action="addtec.php" method="post">
        <tr>
            <th colspan="3"><h1>Adicionar Técnica</h1></th>
        </tr>
        
        <!-- Campo para digitar o nome -->
        <tr>
            <th>Nome:</th>
            <th colspan="2"><input type="text" name="nome"></th>
        </tr>
        
        <!-- Campo para descrever as técnicas -->
        <tr>
            <th>Descrição das técnicas:</th>
            <th colspan="2"><input type="text" name="descricao"></th>
        </tr>
        
        <!-- Campo para selecionar a categoria da técnica -->
        <tr>
            <th>Categoria:</th>
            <th colspan="2">
                <select name="categoria">
                    <option value="V">Visualização</option>
                    <option value="M">Meditação</option>
                    <option value="R">Respiração</option>
                </select>
            </th>
        </tr>
        
        <!-- Campo para colocar o tempo estimado -->
        <tr>
            <th>Tempo Estimado:</th>
            <th colspan="2">
                <input type="time" name="tempo_estimado" step="1" value="00:00:00">
            </th>
        </tr>
        
        <!-- Campo para colocar a data de criação -->
        <tr>
            <th>Data de Criação:</th>
            <th colspan="2"><input type="date" name="data_criacao"></th>
        </tr>
        
        <!-- Campo para vídeo -->
        <tr>
            <th>Vídeo:</th>
            <th colspan="2"><input type="text" name="video"></th>
        </tr>
        
        <!-- Botão de enviar -->
        <tr>
            <th colspan="3" align="center"><input type="submit" name="Cadastrar" class="Enviar" onclick="alert('✅ Técnica adicionada com sucesso!')"></th>
        </tr>
    </form>
</table>

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
