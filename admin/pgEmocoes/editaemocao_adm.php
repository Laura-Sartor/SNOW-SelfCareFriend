<!--Érica Bonfanti e Laura Sartor - 3-52 -->
<html>
     <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="emocao.css">
    <title>Área do Administrador - SNOW</title>
</head>
<body>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Madimi+One&display=swap'); 

    .top-navbar {
        background: white !important;
        flex-shrink: 0;
    }

    body {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Madimi One', sans-serif;
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

    /* --- Estilos do Formulário em Tabela (Baseado no novo design) --- */
    .form-wrapper {
        margin: auto; /* Centraliza vertical e horizontalmente */
        width: 100%;
        max-width: 500px;
        padding: 0 15px;
    }

    .form-table {
        width: 100%;
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
        color: #34495e;
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
        color: #333;
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

    /* Estilos do footer */
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

     <!-- Navbar Superior-->
    <div class="top-navbar">
        <div class="logo">
            <img src="logoFloco.png">
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
                <li class="active">
                    <a href="./pgDiario/listadiarioemocao_adm.php">
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
                <span id="admin-name">Administrador</span>
                <div class="user-avatar">A</div>
            </div>
            <div class="user-menu" id="user-menu">
                <div class="user-menu-item">
        
                </div>
                <div class="user-menu-divider"></div>
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
        window.location.href = 'http://localhost/CRUD_TCC/logout/logout.php';
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


<?php
//Conexão com o banco de dados
include("../../conexao.php");

// Recebe o ID que será editado, com verificação para evitar erro
$id_emocao = isset($_GET['id_emocao']) ? $_GET['id_emocao'] : null;

// Inicializa $linha para evitar erro de variável indefinida se não houver ID
$linha = null;

if ($id_emocao) {
    // Consulta SQL para editar o ID selecionado
    $sql = "SELECT *  from emocao where id_emocao=" . $id_emocao;
    // Executa a consulta no banco de dados
    $res = mysqli_query($id, $sql);

    // Dados do registro
    $linha = mysqli_fetch_array($res);
}

// Apenas exibe o formulário se houver dados para preencher (modo edição)
if ($linha) {?>
<div class="form-wrapper">
    <table border="0" align="center" class="form-table">
        <form action="atualizaemocao_adm.php" method="post" enctype="multipart/form-data" onsubmit="return confirm('✅ Emoção atualizada com sucesso!')">
            <tr>
                <th colspan="2"><h1>Editar Emoção</h1></th>
            </tr>
            <input type="hidden" name="id_emocao" value='<?php echo $linha['id_emocao'];?>'>
        <tr>
            <th>Nome:</th>
            <td><input type="text" name="nome" value='<?php echo $linha['nome'];?>'></td>
        </tr>
        <tr>
    <th>Ícone:</th>
    <td>
        <input type="file" name="icone">
        <br>
        <small style="color: #666;">
            Imagem atual: <?php echo $linha['icone']; ?>
        </small>
    </td>
</tr>
        <tr>
            <td colspan="2"><input type="submit" name="Enviar" value="Atualizar Emoção"></td>
        </tr>
        </form>
    </table>
</div>
<?php } else { ?>
<div class="form-wrapper">
    <table border="0" align="center" class="form-table">
        <tr>
            <th colspan="2"><h1>Erro</h1></th>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center; color: #e74c3c;">Nenhum ID de emoção foi fornecido ou encontrado no banco de dados.</td>
        </tr>
    </table>
</div>
<?php } ?>

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
