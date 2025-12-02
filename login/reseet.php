 <!DOCTYPE html>
 <html lang="en">
 <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="reset.css">
 </head>
 <body>
 
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
                       <button type="button" onclick="voltarPagina()" class="btn btn-secondary">
                        <i class="fas fa-times"><A/i> Cancelar
                    </button>
                        <button type="submit" class="btn btn-primary">Enviar Link de Recuperação</button>
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
</body></html>