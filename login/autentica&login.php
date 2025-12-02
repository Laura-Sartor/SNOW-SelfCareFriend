<?php
session_start();
include 'conexaoLogin.php';

$cadastro_sucesso = false;
$erro_cadastro = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login_action'])) {
        // Lógica de Login
        $login = $_POST['login'];
        $senha = $_POST['senha'];
        
        // Verificação hardcoded para o admin
        if ($login === 'admin@gmail.com' && $senha === 'senhaadmin') {
            $_SESSION['id_usuario'] = 0;
            $_SESSION['tipo'] = 'A';
            $_SESSION['login'] = 'admin@gmail.com';
            header('Location: ../admin/pginicialADM/admin.php');
            exit();
        } else {
            // Para usuários normais, buscar no banco
            $stmt = $conn->prepare("SELECT id_usuario, tipo, login FROM usuario WHERE login = ? AND senha = MD5(?)");
            $stmt->bind_param("ss", $login, $senha);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                $_SESSION['id_usuario'] = $user['id_usuario'];
                $_SESSION['tipo'] = $user['tipo'];
                $_SESSION['login'] = $user['login'];
                header('Location: ../index.php');
                exit();
            } else {
                $error = "Login ou senha inválidos.";
            }
        }
    } elseif (isset($_POST['cadastro_action'])) {
        // Lógica de Cadastro
        $login = $_POST['cadastro_login'];
        $senha = $_POST['cadastro_senha'];
        $tipo = 'U';
        
        // Verificar se login já existe
        $stmt_check = $conn->prepare("SELECT id_usuario FROM usuario WHERE login = ?");
        $stmt_check->bind_param("s", $login);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        
        if ($result_check->num_rows > 0) {
            $erro_cadastro = "Login já existe. Escolha outro.";
        } else {
            // Inserir novo usuário
            $stmt = $conn->prepare("INSERT INTO usuario (login, senha, tipo) VALUES (?, MD5(?), ?)");
            $stmt->bind_param("sss", $login, $senha, $tipo);
            if ($stmt->execute()) {
                $cadastro_sucesso = true;
            } else {
                $erro_cadastro = "Erro ao cadastrar. Tente novamente.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="login.css">
    <title>Login | SNOW</title>
</head>

<body>
    
    <div class="container-custom" id="container">
        <!-- Formulário de Cadastro -->
        <div class="form-container sign-up">
            <form method="POST">
                <h1>Criar Conta</h1>
                
                <?php if ($cadastro_sucesso): ?>
                    <div class="alert alert-success">Cadastro realizado com sucesso! Faça login.</div>
                <?php endif; ?>
                
                <?php if ($erro_cadastro): ?>
                    <div class="alert alert-danger"><?php echo $erro_cadastro; ?></div>
                <?php endif; ?>
                
                <input type="hidden" name="cadastro_action" value="1">
                <input type="email" name="cadastro_login" placeholder="Email" required />
                <input type="password" name="cadastro_senha" placeholder="Senha" required />
                <button type="submit">Cadastrar</button>
            </form>
        </div>

        <!-- Formulário de Login -->
        <div class="form-container sign-in">
            <form method="POST">
                <h1>Entrar</h1>
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <input type="hidden" name="login_action" value="1">
                <input type="email" name="login" placeholder="Email" required />
                <input type="password" name="senha" placeholder="Senha" required />
                <a href="#" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">Esqueceu sua senha?</a>
                <button type="submit">Entrar</button>
            </form>
        </div>
<div class="snowflakes">
        <!-- Painel de Alternância -->
        <div class="toggle-container">
            <!-- Flocos de Neve -->

            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Bem-vindo de volta!</h1>
                    <p>Entre com seus dados pessoais para usar todos os recursos do site</p>
                    <button class="hidden" id="login">Entrar</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Olá, Amigo!</h1>
                    <p>Cadastre-se com seus dados pessoais para usar todos os recursos do site</p>
                    <button class="hidden" id="register">Cadastrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Esqueci minha senha -->
    <div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="forgotPasswordModalLabel">Recuperar Senha</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="forgot_password.php" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="email" class="form-label">Digite seu email (login):</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Enviar Link de Recuperação</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const container = document.getElementById("container");
        const registerBtn = document.getElementById("register");
        const loginBtn = document.getElementById("login");

        registerBtn.addEventListener("click", () => {
            container.classList.add("active");
        });

        loginBtn.addEventListener("click", () => {
            container.classList.remove("active");
        });

        // Mostrar cadastro se houve erro no cadastro
        <?php if ($erro_cadastro): ?>
            container.classList.add("active");
        <?php endif; ?>
    </script>
</body>
</html>