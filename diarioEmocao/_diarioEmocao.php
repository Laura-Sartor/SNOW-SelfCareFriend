<?php
// Inclui o arquivo de conexão com o banco de dados
include("conexao.php");

// Obtém a data selecionada via GET, ou usa a data atual se não for fornecida
$data_selecionada = $_GET['data'] ?? date('Y-m-d');

// Query para buscar todas as emoções da tabela 'emocao'
$sql_emocoes = "SELECT id_emocao, nome, icone FROM emocao";
$result_emocoes = mysqli_query($conn, $sql_emocoes);

// Inicializa variáveis para processamento do formulário
$tecnica_recomendada = null;
$tecnica_id = null;
$tecnica_pasta = null;
$mensagem = '';
$mostrar_modal = false;

// Verifica se já existe um registro para a data atual e usuário logado
session_start();
$id_usuario = $_SESSION['id_usuario'] ?? null;

$registro_existente = null;
$diario_emocao_existente = null;

if ($id_usuario) {
    // Busca registro no diário para a data e usuário
    $sql_busca_registro = "SELECT d.id_diario, d.descricao, d.data_registro 
                          FROM diario d 
                          WHERE d.data_registro = ? AND d.id_usuario = ?";
    $stmt_busca = mysqli_prepare($conn, $sql_busca_registro);
    mysqli_stmt_bind_param($stmt_busca, "si", $data_selecionada, $id_usuario);
    mysqli_stmt_execute($stmt_busca);
    $result_busca = mysqli_stmt_get_result($stmt_busca);
    
    if (mysqli_num_rows($result_busca) > 0) {
        $registro_existente = mysqli_fetch_assoc($result_busca);
        
        // Busca a emoção associada a este registro do diário
        $sql_busca_emocao = "SELECT de.id_emocao, de.intensidade 
                            FROM diario_emocao de 
                            WHERE de.id_diario = ?";
        $stmt_emocao = mysqli_prepare($conn, $sql_busca_emocao);
        mysqli_stmt_bind_param($stmt_emocao, "i", $registro_existente['id_diario']);
        mysqli_stmt_execute($stmt_emocao);
        $result_emocao = mysqli_stmt_get_result($stmt_emocao);
        
        if (mysqli_num_rows($result_emocao) > 0) {
            $diario_emocao_existente = mysqli_fetch_assoc($result_emocao);
        }
        mysqli_stmt_close($stmt_emocao);
    }
    mysqli_stmt_close($stmt_busca);
}

// Processa a exclusão do registro
if (isset($_GET['excluir']) && $registro_existente) {
    $id_diario_excluir = $registro_existente['id_diario'];
    
    // Exclui primeiro os registros relacionados em diario_emocao
    $sql_excluir_emocao = "DELETE FROM diario_emocao WHERE id_diario = ?";
    $stmt_excluir_emocao = mysqli_prepare($conn, $sql_excluir_emocao);
    mysqli_stmt_bind_param($stmt_excluir_emocao, "i", $id_diario_excluir);
    mysqli_stmt_execute($stmt_excluir_emocao);
    mysqli_stmt_close($stmt_excluir_emocao);
    
    // Exclui o registro do diário
    $sql_excluir_diario = "DELETE FROM diario WHERE id_diario = ?";
    $stmt_excluir_diario = mysqli_prepare($conn, $sql_excluir_diario);
    mysqli_stmt_bind_param($stmt_excluir_diario, "i", $id_diario_excluir);
    
    if (mysqli_stmt_execute($stmt_excluir_diario)) {
        $mensagem = 'Registro excluído com sucesso.';
        $registro_existente = null;
        $diario_emocao_existente = null;
    } else {
        $mensagem = 'Erro ao excluir registro.';
    }
    mysqli_stmt_close($stmt_excluir_diario);
    
    // Redireciona para limpar o parâmetro da URL
    header("Location: ?data=" . urlencode($data_selecionada));
    exit();
}

// Processa a submissão do formulário via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $descricao = mysqli_real_escape_string($conn, $_POST['descricao']);
    $id_emocao = intval($_POST['id_emocao']);
    $intensidade = intval($_POST['intensidade']);
    
    // Determina o dia da semana (1=Dom, 2=Seg, ..., 7=Sáb)
    $dia_semana = date('N', strtotime($data_selecionada));

    if ($registro_existente) {
        // MODE EDITION: Atualiza registro existente
        $sql_update_diario = "UPDATE diario SET descricao = ?, dia_semana = ? WHERE id_diario = ?";
        $stmt_update = mysqli_prepare($conn, $sql_update_diario);
        mysqli_stmt_bind_param($stmt_update, "sii", $descricao, $dia_semana, $registro_existente['id_diario']);
        
        if (mysqli_stmt_execute($stmt_update)) {
            // Atualiza ou insere a emoção
            if ($diario_emocao_existente) {
                $sql_update_emocao = "UPDATE diario_emocao SET id_emocao = ?, intensidade = ?, hora = NOW() WHERE id_diario = ?";
                $stmt_update_emocao = mysqli_prepare($conn, $sql_update_emocao);
                mysqli_stmt_bind_param($stmt_update_emocao, "iii", $id_emocao, $intensidade, $registro_existente['id_diario']);
                mysqli_stmt_execute($stmt_update_emocao);
                mysqli_stmt_close($stmt_update_emocao);
            } else {
                $sql_insert_emocao = "INSERT INTO diario_emocao (id_diario, id_emocao, intensidade, hora) VALUES (?, ?, ?, NOW())";
                $stmt_insert_emocao = mysqli_prepare($conn, $sql_insert_emocao);
                mysqli_stmt_bind_param($stmt_insert_emocao, "iii", $registro_existente['id_diario'], $id_emocao, $intensidade);
                mysqli_stmt_execute($stmt_insert_emocao);
                mysqli_stmt_close($stmt_insert_emocao);
            }
            
            $mensagem = 'Registro atualizado com sucesso.';
            
            // Atualiza as variáveis de estado para que o fluxo de redirecionamento funcione
            $registro_existente['descricao'] = $descricao;
            $diario_emocao_existente = [
                'id_emocao' => $id_emocao,
                'intensidade' => $intensidade
            ];
            
        } else {
            $mensagem = 'Erro ao atualizar registro.';
        }
        mysqli_stmt_close($stmt_update);
    } else {
        // NEW REGISTRATION: Insere novo registro
        $sql_insert_diario = "INSERT INTO diario (descricao, data_registro, dia_semana, id_usuario) VALUES (?, ?, ?, ?)";
        $stmt_insert = mysqli_prepare($conn, $sql_insert_diario);
        mysqli_stmt_bind_param($stmt_insert, "ssii", $descricao, $data_selecionada, $dia_semana, $id_usuario);
        
        if (mysqli_stmt_execute($stmt_insert)) {
            $id_novo_diario = mysqli_insert_id($conn);
            
            // Insere a emoção associada
            $sql_insert_emocao = "INSERT INTO diario_emocao (id_diario, id_emocao, intensidade, hora) VALUES (?, ?, ?, NOW())";
            $stmt_insert_emocao = mysqli_prepare($conn, $sql_insert_emocao);
            mysqli_stmt_bind_param($stmt_insert_emocao, "iii", $id_novo_diario, $id_emocao, $intensidade);
            mysqli_stmt_execute($stmt_insert_emocao);
            mysqli_stmt_close($stmt_insert_emocao);
            
            $mensagem = 'Registro salvo com sucesso.';
            
            // Atualiza as variáveis de estado para que o fluxo de redirecionamento funcione
            $registro_existente = [
                'id_diario' => $id_novo_diario,
                'descricao' => $descricao,
                'data_registro' => $data_selecionada
            ];
            $diario_emocao_existente = [
                'id_emocao' => $id_emocao,
                'intensidade' => $intensidade
            ];
            
        } else {
            $mensagem = 'Erro ao salvar registro.';
        }
        mysqli_stmt_close($stmt_insert);
    }

    // Mapeamento de técnicas baseado na emoção e intensidade
    $mapeamento_tecnicas = [
        '1_1' => ['id' => 7, 'categoria' => 'R'],
        '1_2' => ['id' => 8, 'categoria' => 'M'],
        '1_3' => ['id' => 9, 'categoria' => 'V'],
        '2_1' => ['id' => 1, 'categoria' => 'V'],
        '2_2' => ['id' => 2, 'categoria' => 'M'],
        '2_3' => ['id' => 3, 'categoria' => 'R'],
        '3_1' => ['id' => 4, 'categoria' => 'R'],
        '3_2' => ['id' => 5, 'categoria' => 'M'],
        '3_3' => ['id' => 6, 'categoria' => 'V'],
        '6_1' => ['id' => 10, 'categoria' => 'R'],
        '6_2' => ['id' => 11, 'categoria' => 'M'],
        '6_3' => ['id' => 12, 'categoria' => 'V'],
        '28_2' => ['id' => 27, 'categoria' => 'V'],
    ];

    $chave_mapeamento = $id_emocao . '_' . $intensidade;
    
    if (isset($mapeamento_tecnicas[$chave_mapeamento])) {
        $tecnica_mapeada = $mapeamento_tecnicas[$chave_mapeamento];
        $tecnica_id = $tecnica_mapeada['id'];
        $categoria = $tecnica_mapeada['categoria'];
        
        $sql_tecnica = "SELECT id_tecnicas, nome FROM tecnicas WHERE id_tecnicas = ?";
        $stmt_tecnica = mysqli_prepare($conn, $sql_tecnica);
        mysqli_stmt_bind_param($stmt_tecnica, "i", $tecnica_id);
        mysqli_stmt_execute($stmt_tecnica);
        $result_tecnica = mysqli_stmt_get_result($stmt_tecnica);
        
        if (mysqli_num_rows($result_tecnica) > 0) {
            $tecnica_recomendada = mysqli_fetch_assoc($result_tecnica);
            $mostrar_modal = true;
        }
        mysqli_stmt_close($stmt_tecnica);
    }
    
    if ($tecnica_id && $categoria && $tecnica_recomendada) {
        header("Location: ../tecnicas/tecnica_direta.php?tecnica_id=$tecnica_id&categoria=$categoria&nome=" . urlencode($tecnica_recomendada['nome']));
        exit();
    }
}

// Sessão para navbar
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
    <title>Diário Emocional - <?php echo htmlspecialchars($data_selecionada); ?></title>
    <style>
        /* Todos os estilos anteriores permanecem iguais */
        @import url('https://fonts.googleapis.com/css2?family=Madimi+One&display=swap');

        :root {
            --primary-color: #415aca;
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
            background-color: rgba(101, 141, 169, 0.73);
            color: var(--text-color);
            line-height: 1.6;
            min-height: 100vh;
        }
        
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url(fundoSNow.jpg);
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            z-index: -1;
        }
        
        /* Navbar Styles do primeiro arquivo */
        .top-navbar {
            background: white;
            color: rgb(85, 103, 207);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 15px;
            height: 50px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 100;
            width: 100%;
        }
        
        .logo {
            display: flex;
            align-items: center;
        }

        .logo img {
            height: 60px;
            width: auto;
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
        
        .navbar li {
            border-radius: var(--border-radius);
            overflow: hidden;
        }
        
        .navbar li a {
            color: rgb(0, 0, 0);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 5px 10px;
            transition: background-color 0.3s;
            border-radius: var(--border-radius);
            font-size: 13px;
            white-space: nowrap;
        }
        
        .navbar li a:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }
        
        .user-section {
            position: relative;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            padding: 3px 6px;
            border-radius: var(--border-radius);
            transition: background-color 0.3s;
        }
        
        .user-info:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }
        
        #admin-name {
            font-size: 13px;
            white-space: nowrap;
        }
        
        .user-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: var(--secondary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 13px;
        }
        
        .user-menu {
            position: absolute;
            top: 40px;
            right: 0;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 6px 0;
            min-width: 150px;
            display: none;
            z-index: 100;
        }
        
        .user-menu.show {
            display: block;
        }
        
        .user-menu-item {
            padding: 6px 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--text-color);
            transition: background-color 0.3s;
            font-size: 13px;
        }
        
        .user-menu-item:hover {
            background-color: #f5f5f5;
        }
        
        .user-menu-divider {
            height: 1px;
            background-color: #eee;
            margin: 3px 0;
        }
        
        /* Main Content */
        .main-content {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .page-header {
            margin-bottom: 25px;
        }
        
        .page-header h2 {
            color: rgb(61, 115, 154);
            font-size: 24px;
            font-weight: 600;
            
        }
        
        /* Form Styles */
        .container {
            max-width: 800px;
            margin: 30px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        
        .pIcon {
            margin-bottom: 20px;
        }
        
        .iconAndFirstP {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 5px;
        }
        
        .p1 {
            font-size: 18px;
            color: #042c45e8;
        }
        
        .p2 {
            font-size: 14px;
            color: rgb(61, 115, 154);
        }
        
        .diario {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid #ecf0f1;
        }
        
        .titulo {
            font-size: 24px;
            color: #042c45e8;
        }
        
        .data {
            font-size: 18px;
            color: rgb(61, 115, 154);
        }
        
        .descricao {
            margin-bottom: 25px;
        }

        .descricaoArea{
            background-color:rgba(66, 116, 172, 0.26);
            color:white;
        }
        
        .p3 {
            font-size: 16px;
            margin-bottom: 10px;
            color: rgb(61, 115, 154);
        }
        
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            resize: vertical;
            min-height: 100px;
            font-family: 'Madimi One', sans-serif;
        }
        
        .op-emocao {
            margin-bottom: 25px;
        }

        .op-emocao label {
            font-size: 16px;
            margin-bottom: 15px;
            display: block;
            color: rgb(61, 115, 154);
        }

        .cards-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .emocao-item {
            padding: 15px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 15px;
            transition: all 0.3s;
            min-height: 100px;
        }

        .emocao-item:hover {
            background-color: #f8fafc;
            border-color: #042c45e8;
        }

        .labelRadio {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 5px;
            flex: 1;
        }

        .emocao-radio {
            transform: scale(1.2);
        }

        .icon img {
            width: 60px;
            height: 60px;
            object-fit: contain;
        }

        .intensidade {
            display: none;
            margin-top: 10px;
            flex-basis: 100%;
            grid-column: 1 / -1;
        }

        .intensidade label {
            margin-right: 10px;
            font-size: 14px;
        }

        .intensidade select {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-family: 'Madimi One', sans-serif;
        }

        @media (max-width: 768px) {
            .cards-container {
                grid-template-columns: 1fr;
            }
            
            .emocao-item {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }
            
            .labelRadio {
                justify-content: center;
            }
        }

        .botoes {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 25px;
        }
        
        .botoes button {
            padding: 12px 25px;
            background-color: #457f9e;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        
        .botoes button:hover {
            background-color: #3448a5;
        }
        
        /* Botões de ação para registros existentes */
        .acoes-registro {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #ecf0f1;
        }
        
        .btn-excluir {
              padding: 12px 25px;
            background-color: #af3939ff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        
        .btn-excluir:hover {
            background-color: #c0392b !important;
        }
        
        .btn-editar {
             padding: 12px 25px;
            background-color: #ce9127ff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        
        .btn-editar:hover {
            background-color: #e9ad16ff !important;
        }
        
        /* Modal Styles */
        #aviso-tecnica {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            border: 2px solid #175eeb;
            padding: 20px;
            box-shadow: 0 0 10px rgba(5, 10, 93, 0.5);
            z-index: 1000;
            border-radius: 10px;
            text-align: center;
            min-width: 300px;
        }
        
        #aviso-tecnica button {
            margin: 5px;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        
        #aviso-tecnica button:first-child {
            background-color: #175eeb;
            color: white;
        }
        
        #aviso-tecnica button:last-child {
            background-color: #f0f0f0;
            color: #333;
        }
        
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
        
        .mensagem {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            margin: 10px;
            border-radius: 5px;
            text-align: center;
        }
        
        /* Footer Styles do primeiro arquivo */
        footer {
            background-color: #457f9e;
            padding: 20px 0;
            width: 100%;
            margin-top: 40px;
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
            
            .navbar {
                gap: 30px;
            }
            
            .emocao-item {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .acoes-registro {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>

<!-- Navbar Superior do primeiro arquivo -->
<div class="top-navbar">
   <div class="logo">
    <a href="../index.php">
        <img src="../logoFloco.png" alt="Logo Floco - Página Inicial">
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

    <!-- Exibe mensagem se houver -->
    <?php if ($mensagem): ?>
        <div class="mensagem">
            <p><?php echo $mensagem; ?></p>
        </div>
    <?php endif; ?>

    <!-- Overlay para o modal -->
    <div class="overlay" id="overlay" style="display: <?php echo $mostrar_modal ? 'block' : 'none'; ?>;"></div>

    <!-- Modal para aviso de técnica recomendada -->
    <div id="aviso-tecnica" style="display: <?php echo $mostrar_modal ? 'block' : 'none'; ?>;">
        <h3>Técnica Recomendada</h3>
        <p>Baseado na sua emoção e intensidade, recomendamos a técnica:</p>
        <p><strong><?php echo htmlspecialchars($tecnica_recomendada['nome'] ?? 'Nenhuma técnica encontrada'); ?></strong></p>
        <p>Deseja fazer essa técnica agora?</p>
        <button onclick="irParaTecnicaDireta(<?php echo $tecnica_id ?? 0; ?>, '<?php echo $categoria ?? ''; ?>')">Sim, fazer técnica</button>
        <button onclick="fecharAviso()">Não, obrigado</button>
    </div>

    <!-- Formulário para entrada de dados -->
    <form method="POST">
        <div class="container">
            <!-- Ícone e textos iniciais -->
            <div class="pIcon">
                <div class="iconAndFirstP">
                    <!-- Ícone SVG de edição -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                        <path d="m15 5 4 4"></path>
                        <path d="m9 11 4 4"></path>
                        <path d="m2 22 1.5-5.5"></path>
                    </svg>
                    <!-- Texto para novo registro -->
                  
                  
                    <p class="p1"><strong>
                        <?php if ($registro_existente): ?>
                            Editar seu registro de hoje
                        <?php else: ?>
                            Como você está se sentindo hoje?
                        <?php endif; ?>
                    </strong></p>
                </div>
                <p class="p2"><strong>registre seus sentimentos, pensamentos e emoções do dia</strong></p>
            </div>

            <!-- Título do diário e data -->
            <div class="diario">
                <h1 class="titulo"></h1>
                <h1 class="data"><?php echo date('d/m/Y', strtotime($data_selecionada)); ?></h1>
            </div>

            <!-- Campo para descrição dos sentimentos -->
            <div class="descricao">
                <p class="p3"><strong></strong></p>
                <textarea name="descricao" class="descricaoArea" required placeholder="Descreva seus sentimentos e emoções..."><?php 
                    echo $registro_existente ? htmlspecialchars($registro_existente['descricao']) : ''; 
                ?></textarea>
            </div>

           <!-- Seleção de Emoção -->
<div class="op-emocao">
    <label>Selecione uma Emoção:</label><br>
    <div class="cards-container">
        <?php mysqli_data_seek($result_emocoes, 0); ?>
        <?php while ($emocao = mysqli_fetch_assoc($result_emocoes)): ?>
            <div class="emocao-item">
                <div class="labelRadio">
                    <input type="radio" name="id_emocao" value="<?php echo $emocao['id_emocao']; ?>" 
                           class="emocao-radio" required
                           <?php 
                           if ($diario_emocao_existente && $diario_emocao_existente['id_emocao'] == $emocao['id_emocao']) {
                               echo 'checked';
                           }
                           ?>>
                    <span><?php echo htmlspecialchars($emocao['nome']); ?></span>
                </div>
                <div class="icon">
                    <?php 
                    // Verifica se o ícone está no banco de dados como BLOB
                    if (!empty($emocao['icone'])): 
                        // Se for um caminho de arquivo (string), usa o caminho
                        if (is_string($emocao['icone']) && file_exists('../admin/pgAddEmocao/icones_emocao/' . $emocao['icone'])) {
                            $caminho_icone = '../admin/pgAddEmocao/icones_emocao/' . $emocao['icone'];
                            echo '<img src="' . $caminho_icone . '" alt="' . htmlspecialchars($emocao['nome']) . '">';
                        } 
                        // Se for BLOB (dados binários), exibe como base64
                        else {
                            echo '<img src="data:image/png;base64,' . base64_encode($emocao['icone']) . '" alt="' . htmlspecialchars($emocao['nome']) . '">';
                        }
                    else: 
                        // Ícone padrão caso não haja ícone
                        echo '<i class="fas fa-smile" style="font-size: 40px; color: #1e88e5;"></i>';
                    endif; 
                    ?>
                </div>
                <div class="intensidade" id="intensidade-<?php echo $emocao['id_emocao']; ?>"
                     style="<?php 
                     if ($diario_emocao_existente && $diario_emocao_existente['id_emocao'] == $emocao['id_emocao']) {
                         echo 'display: block;';
                     } elseif (!$registro_existente && $emocao['id_emocao'] == 1) {
                         echo 'display: block;';
                     }
                     ?>">
                    <label>Intensidade:</label>
                    <select name="intensidade">
                        <option value="1" <?php echo ($diario_emocao_existente && $diario_emocao_existente['intensidade'] == 1) ? 'selected' : ''; ?>>1 - Leve</option>
                        <option value="2" <?php echo ($diario_emocao_existente && $diario_emocao_existente['intensidade'] == 2) ? 'selected' : ''; ?>>2 - Moderada</option>
                        <option value="3" <?php echo ($diario_emocao_existente && $diario_emocao_existente['intensidade'] == 3) ? 'selected' : ''; ?>>3 - Intensa</option>
                    </select>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

            <!-- Botões principais -->
            <div class="botoes">
                <button type="submit">
                    <?php if ($registro_existente): ?>
                        Atualizar e Ver Técnica Recomendada
                    <?php else: ?>
                        Salvar e Ver Técnica Recomendada
                    <?php endif; ?>
                </button>
            </div>

            <!-- Botões de ação para registros existentes -->
            <?php if ($registro_existente): ?>
            <div class="acoes-registro">
                <button type="button" class="btn-excluir" onclick="confirmarExclusao()">
                    <i class="fas fa-trash"></i> Excluir Registro
                </button>
                <button type="button" class="btn-editar" onclick="limparFormulario()">
                    <i class="fas fa-edit"></i> Novo Registro
                </button>
            </div>
            <?php endif; ?>
        </div>
    </form>

    <!-- Footer do primeiro arquivo -->
    <footer>
       <div class="container-footer">
           <div class="logo-snow">
               Sn<span class="snowflake">❄</span>w
           </div>
           <p class="rodape-direitos">Copyright © 2024 – Todos os Direitos Reservados.</p>
       </div>
    </footer>

    <script>
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

        // Mostrar/ocultar intensidade baseado na emoção selecionada
        document.querySelectorAll('.emocao-radio').forEach(function(radio) {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.intensidade').forEach(function(div) {
                    div.style.display = 'none';
                });
                const selectedId = this.value;
                document.getElementById('intensidade-' + selectedId).style.display = 'block';
            });
        });

        function irParaTecnicaDireta(id, categoria) {
            if (id && id !== 0 && categoria) {
                window.location.href = '../tecnicas/tecnica_direta.php?tecnica_id=' + id + '&categoria=' + categoria;
            }
        }

        function fecharAviso() {
            document.getElementById('aviso-tecnica').style.display = 'none';
            document.getElementById('overlay').style.display = 'none';
        }

        function confirmarLogout() {
            if (confirm('Tem certeza que deseja sair?')) {
                window.location.href = '../TCC_SNOW/login/autentica&login.php';
            }
        }

        function confirmarExclusao() {
            if (confirm('Tem certeza que deseja excluir este registro? Esta ação não pode ser desfeita.')) {
                window.location.href = '?data=<?php echo urlencode($data_selecionada); ?>&excluir=1';
            }
        }

        function limparFormulario() {
            if (confirm('Deseja criar um novo registro? O registro atual será substituído.')) {
                // Limpa apenas os campos do formulário, mantendo a data
                document.querySelector('textarea[name="descricao"]').value = '';
                document.querySelectorAll('.emocao-radio').forEach(radio => radio.checked = false);
                document.querySelectorAll('.intensidade').forEach(div => div.style.display = 'none');
                
                // Mostra a intensidade para a primeira emoção por padrão
                document.getElementById('intensidade-1').style.display = 'block';
                document.querySelector('.emocao-radio[value="1"]').checked = true;
            }
        }

        document.getElementById('overlay').addEventListener('click', fecharAviso);

        // Inicialização - mostra intensidade para emoção selecionada ou primeira emoção
        document.addEventListener('DOMContentLoaded', function() {
            const emoçãoSelecionada = document.querySelector('.emocao-radio:checked');
            if (emoçãoSelecionada) {
                document.getElementById('intensidade-' + emoçãoSelecionada.value).style.display = 'block';
            } else if (!<?php echo $registro_existente ? 'true' : 'false'; ?>) {
                // Se não há registro existente, mostra a intensidade para a primeira emoção
                document.getElementById('intensidade-1').style.display = 'block';
                document.querySelector('.emocao-radio[value="1"]').checked = true;
            }
        });
    </script>
</body>
</html>