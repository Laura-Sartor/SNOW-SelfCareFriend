<?php
// Conexão ao banco de dados
include("conexao.php");
session_start();

// Verifique se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../login/autentica&login.php');
    exit();
}

// Buscar datas com registros do usuário logado
$id_usuario = $_SESSION['id_usuario'];
$sql_registros = "SELECT DISTINCT DATE(data_registro) AS data_registro FROM diario WHERE id_usuario = ?";
$stmt_registros = mysqli_prepare($conn, $sql_registros);
mysqli_stmt_bind_param($stmt_registros, "i", $id_usuario);
mysqli_stmt_execute($stmt_registros);
$result_registros = mysqli_stmt_get_result($stmt_registros);
$datas_registradas = [];
while ($row = mysqli_fetch_assoc($result_registros)) {
    $datas_registradas[] = $row['data_registro'];
}
mysqli_stmt_close($stmt_registros);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <title>Calendario - SNOW</title>
  <link href='https://cdn.boxicons.com/3.0.3/fonts/basic/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  
  <style>

@import url('https://fonts.googleapis.com/css2?family=Madimi+One&display=swap');

    /* Variáveis CSS para o tema */
    :root {
      --body-color: #FFFCFF;
      --header-color: #d36c6c;
      --header-button: #92a1d1;
      --color-weekdays: #247BA0;
      --box-shadow: #CBD4C2;
      --hover: #e8faed;
      --current-day: #e8f4fa;
      --event-color: #58bae4;
      --modal-event: #e8f4fa;
      --color-day: white;
    }

/* Estilos do Calendário */
body {
    background-color: rgb(101, 141, 169);
    display: flex;
    margin-top: 70px; /* Aumentei para 70px para igualar ao segundo */
    justify-content: center;
    flex-direction: column;
    align-items: center;
    min-height: 100vh;
    font-family: 'Madimi One', sans-serif;
    padding: 0;
    margin-left: 0;
    margin-right: 0;
}

button {
    width: 75px;
    cursor: pointer;
    box-shadow: 0px 0px 2px gray;
    border: none;
    outline: none;
    padding: 5px;
    border-radius: 5px;
    color: white;
}

#header {
    padding: 10px;
    color: #042c45e8;
    font-size: 26px;
    font-family: sans-serif;
    display: flex;
    justify-content: space-between;
    font-family: 'Madimi One', sans-serif;
    align-items: center;
    width: 100%;
}

#header button {
    background-color: #042c45e8;
}

#container {
    width: 770px;
    background-color: #ffffffff;
    padding: 20px;
    border-radius: 20px;
    border: 2px solid #add8e6;
    margin-top: 20px;
    margin-bottom: 20px; /* Adicione esta linha */
}

#weekdays {
    width: 100%;
    display: flex;
    color: rgb(61, 115, 154);
}

#weekdays div {
    width: 100px;
    padding: 10px;
    text-align: center;
    font-weight: bold;
}

#calendar {
    width: 100%;
    margin: auto;
    display: flex;
    flex-wrap: wrap;
}

.day {
    width: 100px;
    padding: 10px;
    height: 100px;
    cursor: pointer;
    box-sizing: border-box;
    background-color: rgba(101, 141, 169, 0.36);
    margin: 5px;
    box-shadow: 0px 0px 3px rgba(0, 0, 0, 0.2);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    border-radius: 15%;
    font-family: 'Madimi One', sans-serif;
    font-weight: bold;
    color: #042c45e8;
    transition: background-color 0.3s;
}

.day:hover {
    background-color: rgba(255, 255, 255, 1);
}

.day.registrado {
    background-color: #43799be8 !important;
    color: white !important;
}

#currentDay {
    background-color: rgba(61, 115, 154, 0.5);
}

.padding {
    cursor: default !important;
    background-color: transparent !important;
    box-shadow: none !important;
}

/* Navbar Styles - Atualizada */
.top-navbar {
    background-color: #ffffff;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 6px 15px;
    height: 40px; /* Mudei de 50px para 60px para igualar ao segundo */
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

/* Footer Styles */
footer {
  background-color: #457f9e;
  padding: 20px 0;
  width: 100%;
  margin-top: 40px; /* Adiciona um espaçamento acima do footer */
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

    /* Instruções */
    .pIcon {
      display: flex;
      flex-direction: column;
      gap: 10px;
      margin-bottom: 20px;
      text-align: center;
    }

    .iconAndFirstP {
      display: flex;
      align-items: center;
      gap: 10px;
      justify-content: center;
    }

    .p1 {
      margin: 0;
      color: #000000;
      font-size: smaller;
    }

    @media (max-width: 768px) {
      .container-footer {
        flex-direction: column;
        gap: 15px;
      }
      
      .logo-snow {
        font-size: 20px;
      }
      
      #container {
        width: 95%;
        padding: 10px;
      }
      
      .day {
        width: 80px;
        height: 80px;
      }
    }
  </style>
</head>
<body><!-- Navbar Superior -->
<div class="top-navbar">
    <div class="logo">
        <a href="../index.php">
            <img src="logoFloco.png" alt="Logo Floco - Página Inicial">
        </a>
    </div>
    
    <div class="navbar-container">
        <ul class="navbar">
            <li class="active">
                <a href="agenda.php">
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


  <!-- Container Principal do Calendário -->
  <div id="container">
      <div id="header">
        <div id="monthDisplay"></div>
        <div>
          <button id="backButton">Voltar</button>
          <button id="nextButton">Próximo</button>
        </div>
      </div>

      <div id="weekdays">
        <div>Domingo</div>
        <div>Segunda</div>
        <div>Terça</div>
        <div>Quarta</div>
        <div>Quinta</div>
        <div>Sexta</div>
        <div>Sábado</div>
      </div>

      <!-- Calendário Dinâmico -->
      <div id="calendar"></div>
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

  <!-- Scripts -->
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

    function confirmarLogout() {
        if (confirm('Tem certeza que deseja sair?')) {
            window.location.href = '../login/autentica&login.php';
        }
    }

    // PHP para JavaScript - passar datas registradas
    const datasRegistradas = <?php echo json_encode($datas_registradas); ?>;
  </script>

  <script>
    // variaveis globais
    let nav = 0

    const calendar = document.getElementById('calendar')
    const weekdays = ['domingo','segunda-feira', 'terça-feira', 'quarta-feira', 'quinta-feira', 'sexta-feira', 'sábado']

    function selecionarDia(date){
        // Redirecionar para a página do diário de emoções
        window.location.href = '../diarioEmocao/_diarioEmocao.php?data=' + date;
    }

    function load(){ 
        const date = new Date() 
        
        if(nav !== 0){
            date.setMonth(new Date().getMonth() + nav) 
        }
        
        const day = date.getDate()
        const month = date.getMonth()
        const year = date.getFullYear()
        
        const daysMonth = new Date (year, month + 1 , 0).getDate()
        const firstDayMonth = new Date (year, month, 1)
        
        const dateString = firstDayMonth.toLocaleDateString('pt-br', {
            weekday: 'long',
            year:    'numeric',
            month:   'numeric',
            day:     'numeric',
        })
        
        const paddinDays = weekdays.indexOf(dateString.split(', ') [0])
        
        document.getElementById('monthDisplay').innerText = `${date.toLocaleDateString('pt-br',{month: 'long'})}, ${year}`

        calendar.innerHTML =''

        for (let i = 1; i <= paddinDays + daysMonth; i++) {
            const dayS = document.createElement('div')
            dayS.classList.add('day')

            const dayString = `${year}-${(month + 1).toString().padStart(2, '0')}-${(i - paddinDays).toString().padStart(2, '0')}`

            if (i > paddinDays) {
                dayS.innerText = i - paddinDays
                
                // Verificar se é uma data registrada no banco
                if (datasRegistradas.includes(dayString)) {
                    dayS.classList.add('registrado');
                }
                
                if(i - paddinDays === day && nav === 0){
                    dayS.id = 'currentDay'
                }

                // Ao clicar em qualquer dia, vai para o diário de emoções
                dayS.addEventListener('click', ()=> selecionarDia(dayString))

            } else {
                dayS.classList.add('padding')
            }
            
            calendar.appendChild(dayS)
        }
    }

    // botões de navegação
    function buttons (){
        document.getElementById('backButton').addEventListener('click', ()=>{
            nav--
            load()
        })

        document.getElementById('nextButton').addEventListener('click',()=>{
            nav++
            load()
        })
    }

    buttons()
    load()
  </script>
</body>
</html>