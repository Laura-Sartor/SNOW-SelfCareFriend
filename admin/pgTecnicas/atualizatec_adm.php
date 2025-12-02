<!--Érica Bonfanti e Laura Sartor - 3-52 -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="tecnicas.css">
    <title>Atualizar Técnicas</title>
</head>
<body>

<style>
    .top-navbar {
    background: white !important;
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
                    <a href="../pgFeedback/feedback.html">
                        <i class="fas fa-comments"></i>
                        <span>Feedback</span>
                    </a>
                </li>
                <li>
                    <a href="../pgHistorico/consulta.php">
                        <i class="fas fa-history"></i>
                        <span>Histórico</span>
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

<html>
    <link rel="stylesheet" href="tecnicas.css">
<?php
//Conexão com o banco de dados
include("../../conexao.php");

//Recebe os dados que foram enviados pelo formulário
$id_tecnicas = $_POST['id_tecnicas'];
$nome = $_POST['nome'];
$descricao =$_POST['descricao'];
$categoria = $_POST['categoria'];
$tempo_estimado = $_POST['tempo_estimado'];
$data_criacao =$_POST['data_criacao'];
$video =$_POST['video'];

//Consulta SQL para atualizar
$sql = "UPDATE tecnicas SET
                        nome ='$nome',
                        descricao ='$descricao',
                        categoria ='$categoria',
                        tempo_estimado ='$tempo_estimado',
                        data_criacao ='$data_criacao',
                        video ='$video'
                        where id_tecnicas = $id_tecnicas";

//Executa a consulta no banco de dados
$res = mysqli_query($id,$sql);

// Verifica se deu certo ou errado
if($res){
    // Redireciona direto pra lista sem mostrar mensagem
    header("Location: ../pgTecnicas/listatecnicas_adm.php");
    exit();
}else{
    echo "<p align='center'>Não foi possível excluir!"; // Mensagem de erro
}
?>


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
     footer {
            background-color: #415aca;
            padding: 20px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        /* Container principal */
        .container-footer {
            max-width: 1400px;
            padding: 0 4%;
            margin: auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Logo Snow com floco de neve */
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

        /* Direitos autorais */
        .rodape-direitos {
            color: white;
            font-size: 14px;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .container-footer {
                flex-direction: column;
                gap: 15px;
            }
            
            .logo-snow {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <footer>
        <div class="container-footer">
            <!-- Logo Snow com floco de neve -->
            <div class="logo-snow">
                Sn<span class="snowflake">❄</span>w
            </div>
            
            <!-- Copyright no lado direito -->
            <p class="rodape-direitos">Copyright © 2024 – Todos os Direitos Reservados.</p>
        </div>
    </footer>
</body>
    </html>