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
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://cdn.boxicons.com/3.0.3/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Técnicas de Respiração - SNOW</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Madimi+One&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            font-family: 'Madimi One', sans-serif;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: rgb(101, 141, 169);
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            padding-top: 70px !important;
        }

        /* Conteúdo principal */
        .main-content {
            flex: 1;
            padding: 40px 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        h1 {
            text-align: center;
            color: white;
            margin-bottom: 30px;
            font-size: 2.5rem;
        }
        
        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
            padding: 20px;
        }
        
        .card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }
        
        .card-title {
            font-size: 1.4rem;
            color: #264187;
            margin: 15px 0;
        }
        
        .card-button {
            background: #264187;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            margin: 10px 0;
            font-family: 'Madimi One', sans-serif;
            font-size: 1rem;
            transition: background 0.3s ease;
        }
        
        .card-button:hover {
            background: #1e3369;
        }
        
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.7);
            z-index: 1000;
        }
        
        .modal-content {
            background: white;
            margin: 50px auto;
            padding: 20px;
            border-radius: 10px;
            width: 90%;
            max-width: 600px;
            position: relative;
        }
        
        .close {
            position: absolute;
            right: 15px;
            top: 10px;
            font-size: 24px;
            cursor: pointer;
        }
        
        .modal-buttons {
            margin-top: 20px;
            text-align: center;
        }
        
        .feedback-btn {
            padding: 10px 15px;
            margin: 5px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .refazer-btn {
            background: #28a745;
            color: white;
        }
        
        .sair-btn {
            background: #6c757d;
            color: white;
        }
        
        .video-fullscreen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: black;
            z-index: 1001;
            display: none;
        }
        
        .video-fullscreen iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
        
        .btn-sair-fullscreen {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(0,0,0,0.5);
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            z-index: 1002;
        }

        /* Header e Navbar - ATUALIZADA */
        .top-navbar {
            background-color: #ffffff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 15px;
            height: 50px;
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

        .user-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: linear-gradient(135deg, #9534dbff, #2980b9);
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

        /* Footer */
        footer {
            background-color: #457f9e;
            padding: 20px 0;
            margin-top: auto;
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
            
            .grid-container {
                gap: 20px;
                padding: 10px;
            }
            
            .navbar {
                gap: 30px !important;
            }
        }
    </style>
</head>
<body>

<!-- Navbar Superior ATUALIZADA -->
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
            <a href="login/edita_usu.php?id_usuario=<?php echo $_SESSION['id_usuario']; ?>" style="text-decoration: none; color: inherit;">
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

<div class="main-content">
    <div class="container">
        <h1>Técnicas de Respiração</h1>
        
        <div class="grid-container">
            <?php
            // Buscar técnicas de respiração (categoria 'R')
            $result = $conn->query("SELECT * FROM tecnicas WHERE categoria = 'R' ORDER BY data_criacao DESC");
            
            if ($result->num_rows > 0) {
                while ($tecnica = $result->fetch_assoc()) {
                    $video_url = $tecnica['video'];
                    $embed_url = '';
                    
                    // Assumindo que a URL é do YouTube; extrair ID para embed
                    if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $video_url, $matches)) {
                        $video_id = $matches[1];
                        $embed_url = "https://www.youtube.com/embed/$video_id";
                    } else {
                        $embed_url = $video_url;
                    }
            ?>
                <div class="card">
                    <iframe width="100%" height="200" src="<?php echo $embed_url; ?>" title="Vídeo da Técnica" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen style="border-radius: 10px;"></iframe>
                    
                    <h2 class="card-title"><?php echo htmlspecialchars($tecnica['nome']); ?></h2>
                    
                    <button class="card-button" onclick="abrirDetalhes(<?php echo $tecnica['id_tecnicas']; ?>, '<?php echo htmlspecialchars($tecnica['nome']); ?>', '<?php echo htmlspecialchars($tecnica['descricao']); ?>', '<?php echo $embed_url; ?>')">
                        Ver Detalhes
                    </button>
                </div>
            <?php
                }
            } else {
                echo '<p style="color: white; text-align: center; grid-column: 1 / -1;">Nenhuma técnica de respiração disponível.</p>';
            }
            
            $conn->close();
            ?>
        </div>
    </div>
</div>

<!-- Modal para detalhes -->
<div id="modalDetalhes" class="modal">
    <div class="modal-content">
        <span class="close" onclick="fecharModal()">&times;</span>
        <h2 id="tituloTecnica"></h2>
        
        <iframe id="videoTecnica" width="100%" height="315" src="" title="Vídeo da Técnica" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen style="border-radius: 10px; cursor: pointer;" onclick="entrarTelaCheia()"></iframe>
        
        <div id="descricaoTecnica" style="margin: 15px 0;"></div>
        
        <div class="modal-buttons">
            <button class="card-button" onclick="iniciarTecnica()" id="btnIniciar">Iniciar Técnica</button>
        </div>
    </div>
</div>

<!-- Tela cheia -->
<div id="telaCheia" class="video-fullscreen">
    <button class="btn-sair-fullscreen" onclick="sairTelaCheia()">X Sair</button>
    <iframe id="videoTelaCheia" width="100%" height="100%" src="" title="Vídeo em Tela Cheia" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
</div>

<!-- Modal opções pós-técnica -->
<div id="modalOpcoes" class="modal">
    <div class="modal-content">
        <h2>Técnica Concluída!</h2>
        <p>O que você gostaria de fazer agora?</p>
        
        <div class="modal-buttons">
            <button class="feedback-btn refazer-btn" onclick="refazerTecnica()">Refazer Técnica</button>
            <button class="feedback-btn sair-btn" onclick="sairTecnica()">Sair</button>
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
    let tecnicaAtualId = null;
    let videoModal = null;
    let videoTelaCheia = null;
    let embedUrlAtual = null;

    // Abrir detalhes da técnica
    function abrirDetalhes(id, nome, descricao, embedUrl) {
        tecnicaAtualId = id;
        embedUrlAtual = embedUrl;
        
        document.getElementById('tituloTecnica').textContent = nome;
        document.getElementById('descricaoTecnica').innerHTML = '<p>' + descricao + '</p>';
        
        videoModal = document.getElementById('videoTecnica');
        videoModal.src = embedUrl;
        
        videoTelaCheia = document.getElementById('videoTelaCheia');
        videoTelaCheia.src = embedUrl;
        
        document.getElementById('btnIniciar').style.display = 'block';
        document.getElementById('modalDetalhes').style.display = 'block';
    }

    // Entrar em tela cheia
    function entrarTelaCheia() {
        document.getElementById('modalDetalhes').style.display = 'none';
        document.getElementById('telaCheia').style.display = 'block';
    }

    // Sair da tela cheia
    function sairTelaCheia() {
        document.getElementById('telaCheia').style.display = 'none';
    }

    // Iniciar técnica
    function iniciarTecnica() {
        alert('Clique no vídeo para iniciar.');
        document.getElementById('btnIniciar').style.display = 'none';
    }

    // Refazer técnica
    function refazerTecnica() {
        document.getElementById('modalOpcoes').style.display = 'none';
        document.getElementById('modalDetalhes').style.display = 'block';
        
        videoModal.src = embedUrlAtual;
        document.getElementById('btnIniciar').style.display = 'block';
    }

    // Sair da técnica
    function sairTecnica() {
        document.getElementById('modalOpcoes').style.display = 'none';
        fecharModal();
    }

    // Fechar todos os modais
    function fecharModal() {
        const modais = document.querySelectorAll('.modal');
        modais.forEach(modal => {
            modal.style.display = 'none';
        });
        document.getElementById('telaCheia').style.display = 'none';
        if (videoModal) videoModal.src = '';
        if (videoTelaCheia) videoTelaCheia.src = '';
    }

    // Fechar ao clicar fora
    window.onclick = function(event) {
        const modais = document.querySelectorAll('.modal');
        modais.forEach(modal => {
            if (event.target == modal) {
                fecharModal();
            }
        });
        
        const telaCheia = document.getElementById('telaCheia');
        if (event.target == telaCheia) {
            sairTelaCheia();
        }
    }

    // Funções para menu do usuário
    function confirmarLogout() {
        if (confirm('Tem certeza que deseja sair?')) {
            window.location.href = '../TCC_SNOW/login/autentica&login.php';
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