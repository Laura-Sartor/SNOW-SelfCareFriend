<?php
include("conexao.php");
session_start(); // Sempre no início para acessar $_SESSION

// Verifique se o usuário está logado (opcional, mas recomendado para páginas protegidas)
if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../login/autentica&login.php'); // Redirecione para login se não logado
    exit();
}

// Receber data da agenda
$data_selecionada = $_GET['data'] ?? date('Y-m-d');

// Buscar emoções da tabela emocao
$sql_emocoes = "SELECT id_emocao, nome, icone FROM emocao";
$result_emocoes = mysqli_query($conn, $sql_emocoes);

// Verificar se há registro existente para edição
$id_usuario = $_SESSION['id_usuario'];
$sql_buscar = "SELECT d.id_diario, d.descricao, de.id_emocao, de.intensidade FROM diario d LEFT JOIN diario_emocao de ON d.id_diario = de.id_diario WHERE d.id_usuario = ? AND d.data_registro = ?";
$stmt_buscar = mysqli_prepare($conn, $sql_buscar);
mysqli_stmt_bind_param($stmt_buscar, "is", $id_usuario, $data_selecionada);
mysqli_stmt_execute($stmt_buscar);
$result_buscar = mysqli_stmt_get_result($stmt_buscar);
$registro_existente = mysqli_fetch_assoc($result_buscar);
mysqli_stmt_close($stmt_buscar);

// Processar submissão do formulário
$tecnica_recomendada = null;
$tecnica_id = null;
$mensagem = ''; // Variável para mensagens de sucesso ou recomendação
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $acao = $_POST['acao'] ?? 'salvar'; // 'salvar' ou 'excluir'
    $descricao = mysqli_real_escape_string($conn, $_POST['descricao']);
    $id_emocao = intval($_POST['id_emocao']);
    $intensidade = intval($_POST['intensidade']);

    if ($acao === 'excluir' && $registro_existente) {
        // Excluir registro
        $sql_delete_emocao = "DELETE FROM diario_emocao WHERE id_diario = ?";
        $stmt_delete_emocao = mysqli_prepare($conn, $sql_delete_emocao);
        mysqli_stmt_bind_param($stmt_delete_emocao, "i", $registro_existente['id_diario']);
        mysqli_stmt_execute($stmt_delete_emocao);
        mysqli_stmt_close($stmt_delete_emocao);

        $sql_delete_diario = "DELETE FROM diario WHERE id_diario = ?";
        $stmt_delete_diario = mysqli_prepare($conn, $sql_delete_diario);
        mysqli_stmt_bind_param($stmt_delete_diario, "i", $registro_existente['id_diario']);
        mysqli_stmt_execute($stmt_delete_diario);
        mysqli_stmt_close($stmt_delete_diario);

        $mensagem = 'Registro excluído com sucesso.';
        // Não redirecionar, apenas mostrar mensagem
    } elseif ($acao === 'salvar') {
        if ($registro_existente) {
            // Editar: UPDATE
            $sql_diario = "UPDATE diario SET descricao = ? WHERE id_diario = ?";
            $stmt_diario = mysqli_prepare($conn, $sql_diario);
            mysqli_stmt_bind_param($stmt_diario, "si", $descricao, $registro_existente['id_diario']);
            mysqli_stmt_execute($stmt_diario);
            mysqli_stmt_close($stmt_diario);

            $sql_emocao = "UPDATE diario_emocao SET id_emocao = ?, intensidade = ? WHERE id_diario = ?";
            $stmt_emocao = mysqli_prepare($conn, $sql_emocao);
            mysqli_stmt_bind_param($stmt_emocao, "iii", $id_emocao, $intensidade, $registro_existente['id_diario']);
            mysqli_stmt_execute($stmt_emocao);
            mysqli_stmt_close($stmt_emocao);
        } else {
            // Novo: INSERT
            $sql_diario = "INSERT INTO diario (descricao, data_registro, dia_semana, id_usuario) VALUES (?, ?, DATE_FORMAT(?, '%w'), ?)";
            $stmt_diario = mysqli_prepare($conn, $sql_diario);
            mysqli_stmt_bind_param($stmt_diario, "sssi", $descricao, $data_selecionada, $data_selecionada, $id_usuario);
            mysqli_stmt_execute($stmt_diario);
            $id_diario = mysqli_insert_id($conn);
            mysqli_stmt_close($stmt_diario);

            $sql_emocao = "INSERT INTO diario_emocao (id_diario, id_emocao, intensidade, hora) VALUES (?, ?, ?, CURTIME())";
            $stmt_emocao = mysqli_prepare($conn, $sql_emocao);
            mysqli_stmt_bind_param($stmt_emocao, "iii", $id_diario, $id_emocao, $intensidade);
            mysqli_stmt_execute($stmt_emocao);
            mysqli_stmt_close($stmt_emocao);
        }

        // Lógica de mapeamento para técnica
        $mapeamento_tecnicas = [
            '2_3+' => 1,
            '3_2+' => 2,
        ];
        $tecnica_id = 1; // Padrão
        if ($id_emocao == 2 && $intensidade >= 3) {
            $tecnica_id = $mapeamento_tecnicas['2_3+'];
        } elseif ($id_emocao == 3 && $intensidade >= 2) {
            $tecnica_id = $mapeamento_tecnicas['3_2+'];
        }

        $sql_tecnica = "SELECT nome FROM tecnicas WHERE id_tecnicas = ?";
        $stmt_tecnica = mysqli_prepare($conn, $sql_tecnica);
        mysqli_stmt_bind_param($stmt_tecnica, "i", $tecnica_id);
        mysqli_stmt_execute($stmt_tecnica);
        $result_tecnica = mysqli_stmt_get_result($stmt_tecnica);
        if (mysqli_num_rows($result_tecnica) > 0) {
            $tecnica_recomendada = mysqli_fetch_assoc($result_tecnica);
            $mensagem = 'Registro salvo com sucesso. Técnica recomendada: ' . htmlspecialchars($tecnica_recomendada['nome']);
        } else {
            $mensagem = 'Registro salvo com sucesso. Nenhuma técnica específica recomendada.';
        }
        mysqli_stmt_close($stmt_tecnica);

        // Não redirecionar, mostrar mensagem na página
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Diário Emocional - <?php echo htmlspecialchars($data_selecionada); ?></title>
    <link rel="stylesheet" href="_styyle.css">
</head>
<body>
    <?php if ($mensagem): ?>
        <div class="mensagem">
            <p><?php echo $mensagem; ?></p>
        </div>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="acao" value="salvar"> <!-- Para distinguir salvar de excluir -->
        <div class="container">
            <!-- Ícone e textos iniciais -->
            <div class="pIcon">
                <div class="iconAndFirstP">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                        <path d="m15 5 4 4"></path>
                        <path d="m9 11 4 4"></path>
                        <path d="m2 22 1.5-5.5"></path>
                    </svg>
                    <p class="p1"><strong><?php echo $registro_existente ? 'Editar' : 'Como você está se sentindo hoje?'; ?></strong></p>
                </div>
                <p class="p2"><strong><?php echo $registro_existente ? 'edite seus sentimentos, pensamentos e emoções do dia' : 'registre seus sentimentos, pensamentos e emoções do dia'; ?></strong></p>
            </div>

            <!-- Diário e data -->
            <div class="diario">
                <h1 class="titulo">Diário Emocional</h1>
                <h1 class="data"><?php echo date('d/m/Y', strtotime($data_selecionada)); ?></h1>
            </div>

            <!-- Descrição -->
            <div class="descricao">
                <p class="p3"><strong>Como você está se sentindo hoje?</strong></p>
                <textarea name="descricao" required><?php echo htmlspecialchars($registro_existente['descricao'] ?? ''); ?></textarea>
            </div>

            <!-- Seleção de Emoção -->
            <div class="op-emocao">
                <label>Selecione uma Emoção:</label><br>
                <?php mysqli_data_seek($result_emocoes, 0); // Resetar ponteiro ?>
                <?php while ($emocao = mysqli_fetch_assoc($result_emocoes)): ?>
                    <div class="labelRadio">
                        <input type="radio" name="id_emocao" value="<?php echo $emocao['id_emocao']; ?>" class="emocao-radio" required <?php echo ($registro_existente && $registro_existente['id_emocao'] == $emocao['id_emocao']) ? 'checked' : ''; ?>>
                        <?php echo htmlspecialchars($emocao['nome']); ?>
                    </div>
                    <div class="icon">
                        <?php if ($emocao['icone']): ?>
                            <img src="data:image/png;base64,<?php echo base64_encode($emocao['icone']); ?>" alt="<?php echo htmlspecialchars($emocao['nome']); ?>" width="60" height="60">
                        <?php endif; ?>
                    </div>
                    <div class="labelIcon">
                        <!-- Intensidade -->
                        <div class="intensidade" id="intensidade-<?php echo $emocao['id_emocao']; ?>" style="display: <?php echo ($registro_existente && $registro_existente['id_emocao'] == $emocao['id_emocao']) ? 'block' : 'none'; ?>;">
                            <label>Intensidade:</label>
                            <select name="intensidade">
                                <option value="1" <?php echo ($registro_existente && $registro_existente['intensidade'] == 1) ? 'selected' : ''; ?>>1</option>
                                <option value="2" <?php echo ($registro_existente && $registro_existente['intensidade'] == 2) ? 'selected' : ''; ?>>2</option>
                                <option value="3" <?php echo ($registro_existente && $registro_existente['intensidade'] == 3) ? 'selected' : ''; ?>>3</option>
                                <option value="4" <?php echo ($registro_existente && $registro_existente['intensidade'] == 4) ? 'selected' : ''; ?>>4</option>
                                <option value="5" <?php echo ($registro_existente && $registro_existente['intensidade'] == 5) ? 'selected' : ''; ?>>5</option>
                            </select>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <!-- Botões -->
            <div class="botoes">
                <button type="submit">Salvar</button>
                <?php if ($registro_existente): ?>
                    <button type="submit" name="acao" value="excluir" onclick="return confirm('Tem certeza que deseja excluir este registro?');">Excluir</button>
                <?php endif; ?>
            </div>
        </div>
    </form>

    <!-- JavaScript para mostrar/ocultar intensidade -->
    <script>
        document.querySelectorAll('.emocao-radio').forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.intensidade').forEach(div => div.style.display = 'none');
                document.getElementById('intensidade-' + this.value).style.display = 'block';
            });
        });
    </script>
</body>
</html>
<style>
        /* Reset e estilos base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
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