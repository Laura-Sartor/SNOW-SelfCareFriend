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
$mostrar_modal = false; // Flag para mostrar o modal de técnica recomendada
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

        // Lógica de mapeamento completa para técnica
        $mapeamento_tecnicas = [
            '1_1+' => 1,    // Tristeza intensidade >=1
            '1_2+' => 2,    // Tristeza intensidade >=2
            '1_3+' => 3,    // Tristeza intensidade >=3
            '2_1+' => 4,    // Ansiedade intensidade >=1
            '2_2+' => 5,    // Ansiedade intensidade >=2
            '2_3+' => 6,    // Ansiedade intensidade >=3
            '3_1+' => 7,    // Raiva intensidade >=1
            '3_2+' => 8,    // Raiva intensidade >=2
            '3_3+' => 9,    // Raiva intensidade >=3
            '6_1+' => 10,   // Preocupação intensidade >=1
            '6_2+' => 11,   // Preocupação intensidade >=2
            '6_3+' => 12,   // Preocupação intensidade >=3
        ];

        $tecnica_id = 1; // Valor padrão
        $tecnica_pasta = "geral"; // Pasta padrão (caso não encontre)

        // Mapeamento baseado em emoção e intensidade
        if ($id_emocao == 1) { // Tristeza
            $tecnica_pasta = "tristeza";
            if ($intensidade >= 3) {
                $tecnica_id = $mapeamento_tecnicas['1_3+'];
            } elseif ($intensidade >= 2) {
                $tecnica_id = $mapeamento_tecnicas['1_2+'];
            } else {
                $tecnica_id = $mapeamento_tecnicas['1_1+'];
            }
        } elseif ($id_emocao == 2) { // Ansiedade
            $tecnica_pasta = "ansiedade";
            if ($intensidade >= 3) {
                $tecnica_id = $mapeamento_tecnicas['2_3+'];
            } elseif ($intensidade >= 2) {
                $tecnica_id = $mapeamento_tecnicas['2_2+'];
            } else {
                $tecnica_id = $mapeamento_tecnicas['2_1+'];
            }
        } elseif ($id_emocao == 3) { // Raiva
            $tecnica_pasta = "raiva";
            if ($intensidade >= 3) {
                $tecnica_id = $mapeamento_tecnicas['3_3+'];
            } elseif ($intensidade >= 2) {
                $tecnica_id = $mapeamento_tecnicas['3_2+'];
            } else {
                $tecnica_id = $mapeamento_tecnicas['3_1+'];
            }
        } elseif ($id_emocao == 6) { // Preocupação
            $tecnica_pasta = "preocupacao";
            if ($intensidade >= 3) {
                $tecnica_id = $mapeamento_tecnicas['6_3+'];
            } elseif ($intensidade >= 2) {
                $tecnica_id = $mapeamento_tecnicas['6_2+'];
            } else {
                $tecnica_id = $mapeamento_tecnicas['6_1+'];
            }
        }

        // Buscar técnica (apenas para verificar se existe, mas não exibir detalhes)
        $sql_tecnica = "SELECT nome FROM tecnicas WHERE id_tecnicas = ?";
        $stmt_tecnica = mysqli_prepare($conn, $sql_tecnica);
        mysqli_stmt_bind_param($stmt_tecnica, "i", $tecnica_id);
        mysqli_stmt_execute($stmt_tecnica);
        $result_tecnica = mysqli_stmt_get_result($stmt_tecnica);
        if (mysqli_num_rows($result_tecnica) > 0) {
            $tecnica_recomendada = mysqli_fetch_assoc($result_tecnica);
            $mostrar_modal = true; // Ativar flag para mostrar modal
        }
        mysqli_stmt_close($stmt_tecnica);

        $mensagem = 'Registro salvo com sucesso.';
        // Não redirecionar, mostrar mensagem e modal na página
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Diário Emocional - <?php echo htmlspecialchars($data_selecionada); ?></title>
    <link rel="stylesheet" href="_style.css">
    <style>
        /* Estilos básicos para o modal */
        #aviso-tecnica {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            border: 10px #175eebff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(5, 10, 93, 0.5);
            z-index: 1000;
        }
        #aviso-tecnica button {
            margin: 5px;
        }
    </style>
</head>
<body>
    <?php if ($mensagem): ?>
        <div class="mensagem">
            <p><?php echo $mensagem; ?></p>
        </div>
    <?php endif; ?>

    <!-- Modal para aviso de técnica recomendada -->
    <div id="aviso-tecnica" style="display: <?php echo $mostrar_modal ? 'block' : 'none'; ?>;">
        <p>Baseado na sua emoção e intensidade, recomendamos a técnica: <strong><?php echo htmlspecialchars($tecnica_recomendada['nome'] ?? 'Nenhuma'); ?></strong></p>
        <p>Deseja fazer essa técnica agora?</p>
        <button onclick="irParaTecnica(<?php echo $tecnica_id; ?>)">Sim, fazer técnica</button>
        <button onclick="fecharAviso()">Não, obrigado</button>
    </div>

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

    <!-- JavaScript para mostrar/ocultar intensidade e modal -->
    <script>
        document.querySelectorAll('.emocao-radio').forEach(function(radio) {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.intensidade').forEach(function(div) {
                    div.style.display = 'none';
                });
                const selectedId = this.value;
                document.getElementById('intensidade-' + selectedId).style.display = 'block';
            });
        });

        function irParaTecnica(id) {
            window.location.href = '../tecnicas/crud/tec.php?id=' + id;
        }

        function fecharAviso() {
            document.getElementById('aviso-tecnica').style.display = 'none';
        }
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