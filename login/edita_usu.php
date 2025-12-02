<?php
session_start();

// Conexão com o banco de dados
include("../conexaoO.php");

// Verifica se o ID foi passado via GET
if (!isset($_GET['id_usuario']) || !is_numeric($_GET['id_usuario'])) {
    die("ID de usuário inválido.");
}


// Recebe o ID que será editado
$id_usuario = (int) $_GET['id_usuario'];

// Consulta SQL para editar o ID selecionado
$sql = "SELECT * FROM usuario WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

// Verifica se o usuário existe
if ($result->num_rows == 0) {
    echo "Usuário não encontrado.";
    exit();
}

// Dados do registro
$linha = $result->fetch_assoc();

// Verifica se o usuário é administrador; se for, não permite edição
//if ($linha['tipo'] == 'A') {
  // echo "Não é possível editar administradores.";
    //exit();
//}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil | SNOW</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap');

        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Montserrat', sans-serif;
        }

        body{
            background-color: #afdcf5;
            background: linear-gradient(to right, #e2e2e2,#afdcf5);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            min-height: 100vh;
            padding: 20px;
        }

        .container-custom{
            background-color: #fff;
            border-radius: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.35);
            position: relative;
            overflow: hidden;
            width: 768px;
            max-width: 100%;
            min-height: 480px;
        }

        .container-custom p{
            font-size: 14px;
            line-height: 20px;
            letter-spacing: 0.3px;
            margin: 20px 0;
        }

        .container-custom span{
            font-size: 12px;
            color: #666;
        }

        .container-custom a{
            color: #333;
            font-size: 13px;
            text-decoration: none;
            margin: 15px 0 10px;
        }

        .container-custom button{
            background-color: #11598a;
            color: #fff;
            font-size: 12px;
            padding: 10px 45px;
            border: 1px solid transparent;
            border-radius: 8px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-top: 10px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .container-custom button:hover{
            background-color: #457f9e;
        }

        .container-custom form{
            background-color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 40px;
            height: 100%;
        }

        .container-custom input, .container-custom select{
            background-color: #eee;
            border: none;
            margin: 8px 0;
            padding: 10px 15px;
            font-size: 13px;
            border-radius: 8px;
            width: 100%;
            outline: none;
            transition: border 0.3s;
        }

        .container-custom input:focus, .container-custom select:focus{
            border: 2px solid #457f9e;
        }

        .form-container{
            position: absolute;
            top: 0;
            height: 100%;
            width: 100%;
            z-index: 2;
        }

        .alert {
            padding: 8px 12px;
            font-size: 12px;
            margin: 10px 0;
            width: 100%;
            text-align: center;
        }

        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }

        .form-group {
            width: 100%;
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            text-align: left;
            width: 100%;
        }

        .password-group {
            display: flex;
            gap: 10px;
            align-items: flex-end;
        }

        .password-group input {
            flex: 1;
        }

        .btn-reset {
            background-color: #6c757d;
            white-space: nowrap;
            padding: 10px 15px;
        }

        .btn-reset:hover {
            background-color: #5a6268;
        }

        .btn-secondary {
            background-color: #6c757d;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
            width: 100%;
            margin-top: 20px;
        }

        .page-title {
            text-align: center;
            margin-bottom: 30px;
            color: #11598a;
            font-weight: 700;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .container-custom {
                width: 95%;
                min-height: 500px;
            }
            
            .form-container {
                padding: 0 20px;
            }
            
            .password-group {
                flex-direction: column;
            }
            
            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container-custom">
        <div class="form-container">
            <form action="atualiza.php" method="post">
                <h1 class="page-title">Editar Perfil</h1>
                
                <!-- Campo oculto para enviar o ID -->
                <input type="hidden" name="id_usuario" value="<?php echo $linha['id_usuario']; ?>">
                
                <div class="form-group">
                    <label for="login">E-mail:</label>
                    <input type="email" id="login" name="login" value="<?php echo htmlspecialchars($linha['login']); ?>" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="senha">Senha (nova):</label>
                    <div class="password-group">
                        <input type="password" id="senha" name="senha" placeholder="Deixe em branco para manter a atual" class="form-control">
                        <button type="button" onclick="window.location.href='reseet.php?id_usuario=<?php echo $linha['id_usuario']; ?>'" class="btn btn-reset">Modificar Senha</button>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="tipo">Tipo:</label>
                   <select name="tipo" class="form-select" disabled>
            <option value="U" <?php echo ($linha['tipo'] == 'U') ? 'selected' : ''; ?>>Usuário</option>
            <option value="A" <?php echo ($linha['tipo'] == 'A') ? 'selected' : ''; ?>>Administrador</option>
        </select>
                    <input type="hidden" name="tipo" value="U"> 
                </div>
                
                <div class="action-buttons">
                    <button type="button" onclick="voltarPagina()" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                <button type="button" onclick="atualizarConta()" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Atualizar
                </button>
                    <button type="button" onclick="excluirConta()" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Excluir Conta
                    </button>
                    
                </div>
            </form>
        </div>
    </div>

    <script>
    function voltarPagina() {
        // Volta para a página anterior no histórico
        window.history.back();
    }

    function atualizarConta(){
         if (confirm('Dados atualizados!')) {
               document.querySelector('form').submit();
        
        // Redireciona conforme o tipo de usuário
        <?php
        if ($_SESSION['tipo'] == 'A') {
            echo "window.location.href = '../admin/pginicialADM/admin.php';";
        } else {
            echo "window.location.href = '../index.php'";
        }
        ?>
        }
    }

    function excluirConta() {
        if(!confirm('Tem certeza que deseja excluir sua conta?')) {
            return;
        }
        
        let senha = prompt('Digite sua senha para confirmar a exclusão:');
        
        if(senha) {
            fetch('../admin/excluirconta.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'senha=' + encodeURIComponent(senha)
            })
            .then(response => response.text())
            .then(result => {
                if(result === 'Excluído com sucesso' || result === 'Excluído com sucesso!') {
                    alert('Conta excluída com sucesso! Redirecionando...');
                    window.location.href = 'http://localhost/TCC_SNOW/tcc_snow/login/autentica&login.php';
                } else if(result === 'Senha incorreta') {
                    alert('Senha incorreta! Conta não excluída.');
                } else {
                    alert('Erro: ' + result);
                    window.location.href = 'http://localhost/TCC_SNOW/tcc_snow/login/autentica&login.php';
                }
            })
            .catch(error => {
                alert('Erro na requisição: ' + error);
                window.location.href = 'http://localhost/TCC_SNOW/tcc_snow/login/autentica&login.php';
            });
        }
    }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>