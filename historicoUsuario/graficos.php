<?php
// Inicia a sessão para acessar dados do usuário logado
session_start();

// Inclui o arquivo de conexão com o banco de dados
include("../conexao.php");

// Verifica se o usuário está logado, se não, redireciona para o login
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../../login/autentica&login.php");
    exit();
}

// Obtém o ID do usuário da sessão
$id_usuario = $_SESSION['id_usuario'];

// Verifica se a conexão com o banco foi bem sucedida
if (!$id) {
    die("Erro de conexão com o banco de dados");
}

// CONSULTA 1: Gráfico de Pizza - Distribuição de Emoções
$query_pizza = mysqli_query($id, 
    "SELECT e.nome, COUNT(de.id_emocao) as total
     FROM emocao e 
     JOIN diario_emocao de ON e.id_emocao = de.id_emocao 
     JOIN diario d ON de.id_diario = d.id_diario 
     WHERE d.id_usuario = $id_usuario
     GROUP BY e.id_emocao, e.nome"
);

$dados_pizza = [];
while ($row = mysqli_fetch_array($query_pizza)) {
    $dados_pizza[] = [$row['nome'], (int)$row['total']];
}

// Se não houver dados, cria um array vazio para evitar erros
if (empty($dados_pizza)) {
    $dados_pizza[] = ['Sem dados', 0];
}

// CONSULTA 2: Gráfico de Barras - Intensidade por Emoção
$query_barras = mysqli_query($id,
    "SELECT e.nome, AVG(CAST(de.intensidade AS UNSIGNED)) as media_intensidade
     FROM emocao e 
     JOIN diario_emocao de ON e.id_emocao = de.id_emocao 
     JOIN diario d ON de.id_diario = d.id_diario 
     WHERE d.id_usuario = $id_usuario
     GROUP BY e.id_emocao, e.nome"
);

$dados_barras = [];
while ($row = mysqli_fetch_array($query_barras)) {
    $dados_barras[] = [$row['nome'], round($row['media_intensidade'], 1)];
}

if (empty($dados_barras)) {
    $dados_barras[] = ['Sem dados', 0];
}

// CONSULTA 3: Gráfico de Linha - Evolução (TODOS OS DIAS)
$query_linha = mysqli_query($id,
    "SELECT DATE(d.data_registro) as data, 
            SUM(CAST(de.intensidade AS UNSIGNED)) as soma_intensidade
     FROM diario d
     JOIN diario_emocao de ON d.id_diario = de.id_diario
     WHERE d.id_usuario = $id_usuario 
     GROUP BY DATE(d.data_registro)
     ORDER BY data"
);

$dados_linha = [];
while ($row = mysqli_fetch_array($query_linha)) {
    $dados_linha[] = [date('d/m/Y', strtotime($row['data'])), (float)$row['soma_intensidade']];
}

if (empty($dados_linha)) {
    $dados_linha[] = ['Hoje', 0];
}

// Converte os dados PHP para JSON para uso no JavaScript
$dados_pizza_json = json_encode($dados_pizza);
$dados_barras_json = json_encode($dados_barras);
$dados_linha_json = json_encode($dados_linha);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Histórico - SNOW</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="historicoo.css">
    <script src="https://www.gstatic.com/charts/loader.js"></script>
</head>
<body>
<!-- Navbar Atualizada -->
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
            <li>
                <a href="../Tecnicas/tecnicas.php">
                    <i class="fas fa-hands-helping"></i>
                    <span>Técnicas</span>
                </a>
            </li>
            <li class="active">
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
            <span id="admin-name"><?php echo $_SESSION['login']; ?></span>
            <div class="user-avatar"><?php echo strtoupper(substr($_SESSION['login'], 0, 1)); ?></div>
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

<div class="graficos-container">
    <!-- Gráfico 1: Pizza - Distribuição de Emoções -->
    <div class="grafico-card">
        <div class="grafico-titulo">Distribuição das Emoções</div>
        <div id="graficoPizza" class="grafico"></div>
    </div>

    <!-- Gráfico 2: Barras - Intensidade  -->
    <div class="grafico-card">
        <div class="grafico-titulo">Intensidade das Emoções</div>
        <div id="graficoBarras" class="grafico"></div>
    </div>

    <!-- Gráfico 3: Linha - Evolução -->
    <div class="grafico-card" style="grid-column: span 2;">
        <div class="grafico-titulo">Evolução da Intensidade das Emoções (Todos os Dias)</div>
        <div id="graficoLinha" class="grafico"></div>
    </div>
</div>

<h2 class="main-title">Meu Histórico Completo</h2>

<div class="sections-container">
    <!-- COLUNA 1: MEUS DIÁRIOS -->
    <div class="section-column">
        <div class="section-header">
            <h3><i class="fas fa-book"></i> Meus Diários</h3>
        </div>
        <div class="cards-wrapper">
            <div class="cards-container">
                <?php
                $query = mysqli_query($id, "SELECT * FROM diario WHERE id_usuario = $id_usuario");
                
                if (mysqli_num_rows($query) > 0) {
                    while ($row = mysqli_fetch_array($query)) {
                        $data_formatada = date('d/m/Y', strtotime($row['data_registro']));
                ?>
                <div class="data-card">
                    <div class="card-content">
                        <div class="card-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="card-info">
                            <h3 class="card-title">Diário #<?php echo $row['id_diario']; ?></h3>
                            <div class="card-details">
                                <div class="detail-item">
                                    <i class="fas fa-calendar"></i>
                                    <span><?php echo $data_formatada; ?></span>
                                </div>
                                <div class="detail-item">
                                    <i class="fas fa-clock"></i>
                                    <span><?php echo $row['dia_semana']; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php 
                    }
                } else {
                    echo '<div class="no-data-card">Nenhum diário encontrado</div>';
                }
                ?>
            </div>
        </div>
    </div>

    <!-- COLUNA 2: MINHAS EMOÇÕES POR DIÁRIO -->
    <div class="section-column">
        <div class="section-header">
            <h3><i class="fas fa-chart-line"></i> Emoções por Diário</h3>
        </div>
        <div class="cards-wrapper">
            <div class="cards-container">
                <?php
                $query = mysqli_query($id, 
                    "SELECT de.id_diarioemocao, d.descricao, e.nome as emocao_nome, de.intensidade, de.hora, d.id_diario
                     FROM diario_emocao de 
                     JOIN diario d ON de.id_diario = d.id_diario 
                     JOIN emocao e ON de.id_emocao = e.id_emocao
                     WHERE d.id_usuario = $id_usuario"
                );
                
                if (mysqli_num_rows($query) > 0) {
                    while ($row = mysqli_fetch_array($query)) {
                        $hora_formatada = date('H:i', strtotime($row['hora']));
                ?>
                <div class="data-card">
                    <div class="card-content">
                        <div class="card-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="card-info">
                            <h3 class="card-title"><?php echo $row['emocao_nome']; ?></h3>
                            <div class="card-details">
                                <div class="detail-item">
                                    <i class="fas fa-bolt"></i>
                                    <span>Intensidade: <?php echo $row['intensidade']; ?>/5</span>
                                </div>
                                <div class="detail-item">
                                    <i class="fas fa-clock"></i>
                                    <span>Hora: <?php echo $hora_formatada; ?></span>
                                </div>
                                <div class="detail-item">
                                    <i class="fas fa-book"></i>
                                    <span>Diário #<?php echo $row['id_diario']; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php 
                    }
                } else {
                    echo '<div class="no-data-card">Nenhuma emoção vinculada</div>';
                }
                ?>
            </div>
        </div>
    </div>
</div>

<footer>
    <div class="container-footer">
        <div class="logo-snow">
            <span>S</span><span>N</span><span class="snowflake">❄</span><span>W</span>
        </div>
        <div class="rodape-direitos">
            <p>&copy; 2024 SNOW. Todos os direitos reservados.</p>
        </div>
    </div>
</footer>

<script>
    // Função para confirmar o logout
    function confirmarLogout() {
        if (confirm('Tem certeza que deseja sair?')) {
            window.location.href = '../../login/autentica&login.php';
        }
    }

    // Google Charts
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawCharts);

    function drawCharts() {
        // --- Gráfico 1: Pizza ---
        var dataPizza = new google.visualization.DataTable();
        dataPizza.addColumn('string', 'Emoção');
        dataPizza.addColumn('number', 'Total');
        dataPizza.addRows(<?php echo $dados_pizza_json; ?>);

        var optionsPizza = {
            title: 'Distribuição das Emoções Registradas',
            is3D: true,
            backgroundColor: 'transparent',
            legend: { position: 'bottom' },
            chartArea: { width: '90%', height: '80%' }
        };

        var chartPizza = new google.visualization.PieChart(document.getElementById('graficoPizza'));
        chartPizza.draw(dataPizza, optionsPizza);

        // --- Gráfico 2: Barras ---
        var dataBarras = new google.visualization.DataTable();
        dataBarras.addColumn('string', 'Emoção');
        dataBarras.addColumn('number', 'Intensidade Média');
        dataBarras.addRows(<?php echo $dados_barras_json; ?>);

        var optionsBarras = {
            title: 'Intensidade Média por Emoção',
            legend: { position: 'none' },
            vAxis: { title: 'Intensidade Média (1-5)', minValue: 0, maxValue: 5 },
            hAxis: { title: 'Emoção' },
            backgroundColor: 'transparent',
            chartArea: { width: '90%', height: '80%' }
        };

        var chartBarras = new google.visualization.ColumnChart(document.getElementById('graficoBarras'));
        chartBarras.draw(dataBarras, optionsBarras);

        // --- Gráfico 3: Linha (Evolução) ---
        var dataLinha = new google.visualization.DataTable();
        dataLinha.addColumn('string', 'Data');
        dataLinha.addColumn('number', 'Soma das Intensidades');
        dataLinha.addRows(<?php echo $dados_linha_json; ?>);

        var optionsLinha = {
            title: 'Evolução da Intensidade das Emoções (Todos os Dias)',
            curveType: 'function',
            legend: { position: 'bottom' },
            vAxis: { title: 'Soma das Intensidades', minValue: 0 },
            hAxis: { title: 'Data', slantedText: true, slantedTextAngle: 45 },
            backgroundColor: 'transparent',
            chartArea: { width: '95%', height: '80%' }
        };

        var chartLinha = new google.visualization.LineChart(document.getElementById('graficoLinha'));
        chartLinha.draw(dataLinha, optionsLinha);
    }

    // Toggle do menu do usuário
    document.getElementById('user-menu-toggle').addEventListener('click', function() {
        document.getElementById('user-menu').classList.toggle('show');
    });

    // Fecha o menu dropdown quando clica fora
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.user-section')) {
            document.getElementById('user-menu').classList.remove('show');
        }
    });
</script>

</body>
</html>