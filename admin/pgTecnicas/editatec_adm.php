<!--Érica Bonfanti e Laura Sartor - 3-52 -->
<html>
     <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="tecnicas.css">
    <title>Área do Administrador - SNOW</title>
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
            padding-top:70px;
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

        /* DESIGN SIMPLES DA TABELA - MANTENDO ESTRUTURA */
        table {
            width: 90%;
            max-width: 800px;
            margin: 30px auto;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-collapse: collapse;
        }

        table h1 {
            text-align: center;
            color: #2c3e50;
            margin: 0;
            padding: 20px;
            font-size: 24px;
            background: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }

        table th, table td {
            padding: 15px;
            text-align: left;
            border: 1px solid #dee2e6;
        }

        table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #2c3e50;
            width: 25%;
        }

        table input[type="text"],
        table input[type="time"],
        table input[type="date"],
        table select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 14px;
        }

        table input[type="submit"] {
            background: #007bff;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        table input[type="submit"]:hover {
            background: #0056b3;
        }

        table tr:hover {
            background: #f8f9fa;
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

//Recebe o ID que será editado 
$id_tecnica= $_GET['id_tecnicas'];
//Consulta SQL para editar o ID selecionado
$sql = "SELECT * FROM tecnicas WHERE id_tecnicas =".$id_tecnica;
//Executa a consulta no banco de dados
$res = mysqli_query($id,$sql);

//Dados do registro
while($linha = mysqli_fetch_array($res)){?>

    <!--Tabela para formulário-->
    <table border="0" align="center">
        <!--Formulário que vai enviar os dados para atualiza.php-->
        <form action="atualizatec_adm.php" method="post" onsubmit="alert('✅ Técnica atualizada com sucesso!')">
             <tr>
                 <!--Campo oculto para enviar o ID-->
                  <input type="hidden" name="id_tecnicas" value='<?php echo $linha['id_tecnicas'];?>'>
                  <!--Campo para digitar o nome-->
</br>
                  <tr>
                    <th colspan="3"><h1>Editar Técnica</h1></th>
                  </tr>

            <th>Nome:</th>
            <th colspan="2"><input type="text" name="nome" value='<?php echo $linha['nome'];?>'></th>
         </tr>
                <!--Campo para a descrição das técnicas-->
             <tr>
            <th>Descrição das Técnicas:</th>
            <th colspan="2"><input type="text" name="descricao" value='<?php echo $linha['descricao'];?>'></th>
         </tr>
          <!--Campo para selecionar a técnica -->
            <th>Categoria</th>
         <th>
               <select name="categoria">
                    <option value="V" <?php echo ($linha['categoria'] == 'V') ? 'selected' : ''; ?>>Visualização</option>
                    <option value="M" <?php echo ($linha['categoria'] == 'M') ? 'selected' : ''; ?>>Meditação</option>
                    <option value="R" <?php echo ($linha['categoria'] == 'R') ? 'selected' : ''; ?>>Respiração</option>
                </select>
            </th>
                 <!--Campo para selecionar o tempo  -->
            <tr>
            <th>Tempo Estimado:</th>
            <th colspan="2"><input type="time" step='60' name="tempo_estimado" value='<?php echo $linha['tempo_estimado'];?>'></th>
         </tr>
            <!--Campo para selecionar a data -->
            <tr>
            <th>Data da Criação:</th>
            <th colspan="2"><input type="date" name="data_criacao" value='<?php echo $linha['data_criacao'];?>'></th>
         </tr>
             <!--Campo para o vídeo-->
            <tr>
            <th>Vídeo:</th>
            <th colspan="2"><input type="text" name="video" value='<?php echo $linha['video'];}?>'></th>
         </tr>
            <!--Botão de enviar-->
          <tr>
            <th colspan="3" align="center" style="text-align: center;">
                <input type="submit" name="cadastrar" value="Atualizar Técnica">
            </th>
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