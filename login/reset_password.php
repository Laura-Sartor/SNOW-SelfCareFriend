<?php
session_start();
include 'conexaoLogin.php';

$mensagem = '';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $decoded = base64_decode($token);
    list($id_usuario, $expiracao) = explode('|', $decoded);

    if (time() > $expiracao) {
        $mensagem = "<div class='alert alert-danger'>Token expirado.</div>";
    } else {
        // Verificar se o usuário existe
        $stmt = $conn->prepare("SELECT id_usuario FROM usuario WHERE id_usuario = ?");
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $nova_senha = $_POST['nova_senha'];
                // Atualizar senha com MD5
                $stmt_update = $conn->prepare("UPDATE usuario SET senha = MD5(?) WHERE id_usuario = ?");
                $stmt_update->bind_param("si", $nova_senha, $id_usuario);
                if ($stmt_update->execute()) {
                    $mensagem = "<div class='alert alert-success'>Senha alterada com sucesso! <a href='autentica&login.php'>Fazer login</a></div>";
                } else {
                    $mensagem = "<div class='alert alert-danger'>Erro ao alterar senha.</div>";
                }
            }
        } else {
            $mensagem = "<div class='alert alert-danger'>Usuário inválido.</div>";
        }
    }
} else {
    $mensagem = "<div class='alert alert-danger'>Token inválido.</div>";
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha</title>
    <link rel="stylesheet" href="reset.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Modal para Redefinir Senha -->
<div class="modal fade show d-block" tabindex="-1" aria-labelledby="redefinirSenhaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="redefinirSenhaModalLabel">Redefinir Senha</h5>
                <button type="button" class="btn-close" onclick="voltarPagina()" aria-label="Close"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <?php echo $mensagem; ?>
                    <?php if (!isset($_POST['nova_senha']) && isset($_GET['token']) && time() <= $expiracao && $result->num_rows > 0): ?>
                    <div class="mb-3">
                        <label for="nova_senha" class="form-label">Nova Senha:</label>
                        <input type="password" name="nova_senha" class="form-control" required>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="voltarPagina()" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <?php if (!isset($_POST['nova_senha']) && isset($_GET['token']) && time() <= $expiracao && $result->num_rows > 0): ?>
                    <button type="submit" class="btn btn-primary">Alterar Senha</button>
                    <?php else: ?>
                    <a href="autentica&login.php" class="btn btn-primary">Voltar ao Login</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function voltarPagina() {
        // Volta para a página anterior no histórico
        window.history.back();
    }
</script>

</body>
</html>