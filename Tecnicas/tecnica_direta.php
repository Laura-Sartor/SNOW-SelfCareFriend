<?php
session_start();
include("../conexaoO.php");

// Verificar se veio redirecionamento do diário
if (!isset($_GET['tecnica_id']) || !isset($_GET['categoria'])) {
    header('Location: tecnicas.php');
    exit();
}

$tecnica_id = intval($_GET['tecnica_id']);
$categoria = $_GET['categoria'];

// Buscar dados da técnica
$sql_tecnica = "SELECT * FROM tecnicas WHERE id_tecnicas = ?";
$stmt_tecnica = mysqli_prepare($conn, $sql_tecnica);
mysqli_stmt_bind_param($stmt_tecnica, "i", $tecnica_id);
mysqli_stmt_execute($stmt_tecnica);
$result_tecnica = mysqli_stmt_get_result($stmt_tecnica);

if (mysqli_num_rows($result_tecnica) === 0) {
    header('Location: tecnicas.php');
    exit();
}

$tecnica = mysqli_fetch_assoc($result_tecnica);

// Processar URL do YouTube
$video_url = $tecnica['video'];
$embed_url = '';

// Extrair ID do vídeo do YouTube
if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $video_url, $matches)) {
    $video_id = $matches[1];
    $embed_url = "https://www.youtube.com/embed/$video_id";
} else {
    // Se não for YouTube, usar URL original
    $embed_url = $video_url;
}

// Verificar sessão do usuário
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
        header("Location: ../login/autentica&login.php");
        exit();
    }
    $stmt->close();
}

mysqli_stmt_close($stmt_tecnica);
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($tecnica['nome']); ?> - SNOW</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href='https://cdn.boxicons.com/3.0.3/fonts/basic/boxicons.min.css' rel='stylesheet'>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Madimi+One&display=swap');
        @import url("https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap");

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
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        h1 {
            text-align: center;
            color: #264187;
            margin-bottom: 20px;
            font-size: 2.5rem;
        }

        .section-subheading {
            text-align: center;
            color: #264187;
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        
        .video-container {
            position: relative;
            width: 100%;
            height: 400px;
            border-radius: 10px;
            overflow: hidden;
            margin: 20px 0;
            cursor: pointer;
        }

        .video-container iframe {
            width: 100%;
            height: 100%;
            border-radius: 10px;
            border: none;
        }

        .descricao {
            margin: 25px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            border-left: 4px solid #264187;
        }

        .descricao h3 {
            color: #264187;
            margin-bottom: 10px;
            font-size: 1.3rem;
        }

        .descricao p {
            color: #333;
            line-height: 1.6;
            font-size: 1rem;
        }
        
        .botoes-container {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-family: 'Madimi One', sans-serif;
            font-size: 1rem;
            transition: all 0.3s ease;
            min-width: 180px;
        }
        
        .btn-iniciar {
            background: #264187;
            color: white;
        }
        
        .btn-iniciar:hover {
            background: #1e3369;
            transform: translateY(-2px);
        }
        
        .btn-voltar {
            background: #6c757d;
            color: white;
        }
        
        .btn-voltar:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .btn-feedback {
            background: #28a745;
            color: white;
        }
        
        .btn-feedback:hover {
            background: #218838;
            transform: translateY(-2px);
        }

        /* Header e Navbar */
        .top-navbar {
            background-color: white;
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            padding: 6px 15px !important;
            height: 50px !important;
            box-shadow: 0 2px 5px rgba(12, 85, 122, 0.1) !important;
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            z-index: 1000 !important;
        }

        .logo img {
            height: 60px !important;
            width: auto !important;
        }

        .navbar-container {
            flex: 1 !important;
            display: flex !important;
            justify-content: center !important;
            margin: 0 15px !important;
        }

        .navbar {
            display: flex !important;
            list-style: none !important;
            gap: 99px !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .navbar li a {
            color: #092f43 !important;
            text-decoration: none !important;
            display: flex !important;
            align-items: center !important;
            gap: 5px !important;
            padding: 5px 10px !important;
            transition: background-color 0.3s !important;
            border-radius: 8px !important;
            font-size: 13px !important;
            white-space: nowrap !important;
        }

        .navbar li a:hover {
            background-color: rgba(0, 0, 0, 0.05) !important;
        }

        .navbar li.active a {
            background-color: rgba(0, 0, 0, 0.1) !important;
            font-weight: 600 !important;
        }

        .user-section {
            position: relative !important;
        }

        .user-info {
            display: flex !important;
            align-items: center !important;
            gap: 6px !important;
            cursor: pointer !important;
            padding: 3px 6px !important;
            border-radius: 8px !important;
        }

        .user-avatar {
            width: 30px !important;
            height: 30px !important;
            border-radius: 50% !important;
            background-color: #11598a !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            color: white !important;
            font-weight: bold !important;
            font-size: 13px !important;
        }

        .user-menu {
            position: absolute !important;
            top: 40px !important;
            right: 0 !important;
            background: white !important;
            border-radius: 8px !important;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) !important;
            padding: 6px 0 !important;
            min-width: 150px !important;
            display: none !important;
            z-index: 100 !important;
        }

        .user-menu.show {
            display: block !important;
        }

        .user-menu-item {
            padding: 6px 12px !important;
            cursor: pointer !important;
            display: flex !important;
            align-items: center !important;
            gap: 6px !important;
            color: #333 !important;
        }

        .user-menu-item:hover {
            background-color: #f5f5f5;
        }

        .user-menu-divider {
            height: 1px;
            background-color: #eee;
            margin: 3px 0;
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

        /* Tela cheia */
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
            font-family: 'Madimi One', sans-serif;
        }

        @media (max-width: 768px) {
            .container-footer {
                flex-direction: column;
                gap: 15px;
            }
            
            .logo-snow {
                font-size: 20px;
            }
            
            .navbar {
                gap: 30px !important;
            }
            
            .video-container {
                height: 250px;
            }
            
            .botoes-container {
                flex-direction: column;
                align-items: center;
            }
            
            .btn {
                width: 100%;
                max-width: 300px;
            }
        }
    </style>
</head>
<body>

<!-- Navbar Superior -->
<div class="top-navbar">
   <div class="logo">
    <a href="../index.php">
        <img src="../pginicial/logoFloco.png" alt="Logo Floco - Página Inicial">
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
            <li>
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

<div class="main-content">
    <div class="container">
        <h3 class="section-subheading">- Técnica de Visualização -</h3>
        <h1><?php echo htmlspecialchars($tecnica['nome']); ?></h1>
        
        <div class="video-container" onclick="entrarTelaCheia()">
            <iframe 
                id="videoTecnica"
                src="<?php echo $embed_url; ?>?rel=0&modestbranding=1" 
                title="Vídeo da Técnica" 
                frameborder="0" 
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                referrerpolicy="strict-origin-when-cross-origin" 
                allowfullscreen>
            </iframe>
        </div>
        
        <div class="descricao">
            <h3>Descrição da Técnica</h3>
            <p><?php echo htmlspecialchars($tecnica['descricao'] ?? 'Descrição não disponível.'); ?></p>
        </div>
        
        <div class="botoes-container">
            <button class="btn btn-iniciar" onclick="iniciarTecnica()">Iniciar Técnica</button>
            <button class="btn btn-voltar" onclick="voltarParaDiario()">Voltar para o Diário</button>
            <button class="btn btn-feedback" onclick="irParaFeedback()">Fazer Feedback</button>
        </div>
    </div>
</div>

<!-- Tela cheia -->
<div id="telaCheia" class="video-fullscreen">
    <button class="btn-sair-fullscreen" onclick="sairTelaCheia()">X Sair</button>
    <iframe 
        id="videoTelaCheia" 
        src="" 
        title="Vídeo em Tela Cheia" 
        frameborder="0" 
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
        referrerpolicy="strict-origin-when-cross-origin" 
        allowfullscreen>
    </iframe>
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
    let videoNormal = document.getElementById('videoTecnica');
    let videoTelaCheia = document.getElementById('videoTelaCheia');
    let tecnicaConcluida = false;

    function iniciarTecnica() {
        // Para vídeos do YouTube, não podemos controlar a reprodução via JS devido às políticas de segurança
        // Então apenas entramos em tela cheia
        entrarTelaCheia();
        
        // Adicionar listener para detectar quando o vídeo terminar (aproximado)
        setTimeout(function() {
            if (!tecnicaConcluida) {
                tecnicaConcluida = true;
                if (confirm('Técnica concluída! Deseja fazer feedback?')) {
                    irParaFeedback();
                }
            }
        }, 60000); // Assumindo que a técnica dura cerca de 1 minuto
    }

    function entrarTelaCheia() {
        // Salvar a URL atual do iframe
        const currentSrc = videoNormal.src;
        videoTelaCheia.src = currentSrc + '&autoplay=1';
        
        document.getElementById('telaCheia').style.display = 'block';
        
        // Tentar detectar quando o vídeo termina (aproximado para YouTube)
        setTimeout(function() {
            if (!tecnicaConcluida && document.getElementById('telaCheia').style.display === 'block') {
                tecnicaConcluida = true;
                sairTelaCheia();
                if (confirm('Técnica concluída! Deseja fazer feedback?')) {
                    irParaFeedback();
                }
            }
        }, 60000);
    }

    function sairTelaCheia() {
        document.getElementById('telaCheia').style.display = 'none';
        // Limpar o src para parar o vídeo
        videoTelaCheia.src = '';
    }

    function voltarParaDiario() {
        window.location.href = '../diarioEmocao/_diarioEmocao.php';
    }

    function irParaFeedback() {
        window.location.href = '../feedbackUsuario/feedback.php?tecnica_id=<?php echo $tecnica_id; ?>';
    }

    // Fechar tela cheia se clicar fora do vídeo
    window.onclick = function(event) {
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

    // Tecla ESC para sair da tela cheia
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            sairTelaCheia();
        }
    });
</script>

</body>
</html>