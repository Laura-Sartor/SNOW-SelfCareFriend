<!--Érica Bonfanti e Laura Sartor - 3-52 -->
<?php
session_start();
include 'conexaoLogin.php';  // Conexão com o banco

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/PHPMailer/src/Exception.php';
require '../vendor/PHPMailer/src/PHPMailer.php';
require '../vendor/PHPMailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Verificar se o email existe na tabela usuario
    $stmt = $conn->prepare("SELECT id_usuario FROM usuario WHERE login = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $id_usuario = $user['id_usuario'];

        // Gerar token: base64_encode(id_usuario|timestamp_expiracao)
        $expiracao = time() + 3600;  // 1 hora
        $token = base64_encode($id_usuario . '|' . $expiracao);

        // Link para redefinição
        $reset_link = "http://localhost/TCC_SNOW/TCC_SNOW/login/reset_password.php?token=" . urlencode($token);

        // Enviar email com PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'keka.art2@gmail.com';  // Seu Gmail
            $mail->Password = 'ucorqsymidgdvbrf';     // Senha de app
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('keka.art2@gmail.com', 'Diário Emocional');
            $mail->addAddress($email);
            
            // Forçar UTF-8 para evitar problemas com acentos
            $mail->CharSet = 'UTF-8';
            
            // Definir como HTML para melhor formatação
            $mail->isHTML(true);
            $mail->Subject = 'Recuperação de Senha - Diário Emocional';
            $mail->Body = "
<!DOCTYPE html>
<html lang='pt-BR'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Recuperação de Senha - SNOW</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e0f7ff 0%, #f0f9ff 100%);
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 123, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        .header {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            text-align: center;
            padding: 40px 20px;
            position: relative;
        }
        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 100 100\"><defs><pattern id=\"snow\" x=\"0\" y=\"0\" width=\"20\" height=\"20\" patternUnits=\"userSpaceOnUse\"><circle cx=\"10\" cy=\"10\" r=\"1\" fill=\"rgba(255,255,255,0.3)\"/></pattern></defs><rect width=\"100\" height=\"100\" fill=\"url(%23snow)\"/></svg>');
            opacity: 0.1;
        }
        .header img {
            max-width: 120px;
            height: auto;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.2));
            position: relative;
            z-index: 1;
        }
        .header h1 {
            color: white;
            font-size: 28px;
            margin-top: 15px;
            font-weight: 300;
            letter-spacing: 1px;
            position: relative;
            z-index: 1;
        }
        .content {
            padding: 50px 40px;
            text-align: center;
        }
        .content h2 {
            color: #007bff;
            font-size: 24px;
            margin-bottom: 25px;
            font-weight: 600;
        }
        .content p {
            font-size: 16px;
            line-height: 1.7;
            margin-bottom: 25px;
            color: #555;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: #ffffff;
            text-decoration: none;
            padding: 16px 40px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            margin: 25px 0;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
            border: none;
            cursor: pointer;
        }
        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
            background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
        }
        .footer {
            background: #f8f9fa;
            text-align: center;
            padding: 30px 20px;
            border-top: 1px solid #e9ecef;
        }
        .footer p {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }
        .footer a {
            color: #007bff;
            text-decoration: none;
        }
        .footer a:hover {
            text-decoration: underline;
        }
        .security-note {
            background: #f8f9fa;
            border-left: 4px solid #007bff;
            padding: 15px;
            margin: 20px 0;
            text-align: left;
            border-radius: 4px;
        }
        .security-note p {
            font-size: 14px;
            color: #666;
            margin: 0;
        }
        @media (max-width: 600px) {
            .content {
                padding: 30px 20px;
            }
            .header {
                padding: 30px 20px;
            }
            .header h1 {
                font-size: 24px;
            }
            .content h2 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class='email-container'>
        <div class='header'>
            <img src='../logoFloco.png' alt='Logo SNOW' />
            <h1>SNOW Self-Care Friend</h1>
        </div>
        
        <div class='content'>
            <h2>Recuperação de Senha</h2>
            <p>Olá,</p>
            <p>Recebemos uma solicitação para redefinir sua senha no <strong>SNOW</strong>. Clique no botão abaixo para criar uma nova senha segura.</p>
            <p><strong>Este link é válido por 1 hora.</strong></p>
            
            <a href='" . $reset_link . "' class='button'>Redefinir Minha Senha</a>
            
            <div class='security-note'>
                <p><strong>⚠️ Dica de segurança:</strong> Se você não solicitou esta redefinição, ignore este email e verifique as configurações de segurança da sua conta.</p>
            </div>
            
            <p style='color: #888; font-size: 14px;'>Caso o botão não funcione, copie e cole o seguinte link em seu navegador:<br><span style='color: #007bff; word-break: break-all;'>" . $reset_link . "</span></p>
        </div>
        
        <div class='footer'>
            <p><strong>Atenciosamente,</strong><br>Equipe SNOW Self-Care Friend</p>
            <p>&copy; 2023 SNOW. Todos os direitos reservados.</p>
            <p>Precisa de ajuda? <a href='mailto:suporte@snow.com'>suporte@snow.com</a></p>
        </div>
    </div>
</body>
</html>
            ";
            
            $mail->send();
            echo "
            <div class='custom-alert success'>
                <div class='alert-icon'>✓</div>
                <div class='alert-content'>
                    <h3>Email enviado com sucesso!</h3>
                    <p>Verifique sua caixa de entrada e também a pasta de spam.</p>
                </div>
            </div>";
        } catch (Exception $e) {
            echo "
            <div class='custom-alert error'>
                <div class='alert-icon'>✗</div>
                <div class='alert-content'>
                    <h3>Erro ao enviar email</h3>
                    <p>Ocorreu um erro: " . $mail->ErrorInfo . "</p>
                    <p>Tente novamente em alguns minutos.</p>
                </div>
            </div>";
        }
    } else {
        echo "
        <div class='custom-alert warning'>
            <div class='alert-icon'>⚠</div>
            <div class='alert-content'>
                <h3>Email não encontrado</h3>
                <p>O email informado não está cadastrado em nosso sistema.</p>
                <p>Verifique o endereço digitado ou <a href='cadastro.php'>crie uma nova conta</a>.</p>
            </div>
        </div>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Recuperação de Senha</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #e0f7ff 0%, #f0f9ff 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .custom-alert {
            max-width: 500px;
            width: 100%;
            margin: 20px 0;
            padding: 25px;
            border-radius: 16px;
            display: flex;
            align-items: flex-start;
            gap: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(10px);
            animation: slideIn 0.5s ease-out;
        }
        
        .custom-alert.success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border-left: 6px solid #28a745;
        }
        
        .custom-alert.error {
            background: linear-gradient(135deg, #f8d7da 0%, #f1b0b7 100%);
            border-left: 6px solid #dc3545;
        }
        
        .custom-alert.warning {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border-left: 6px solid #ffc107;
        }
        
        .alert-icon {
            font-size: 24px;
            font-weight: bold;
            min-width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            flex-shrink: 0;
        }
        
        .success .alert-icon {
            background: #28a745;
            color: white;
        }
        
        .error .alert-icon {
            background: #dc3545;
            color: white;
        }
        
        .warning .alert-icon {
            background: #ffc107;
            color: #212529;
        }
        
        .alert-content h3 {
            margin: 0 0 8px 0;
            font-weight: 600;
            color: #212529;
        }
        
        .alert-content p {
            margin: 0 0 8px 0;
            color: #495057;
            line-height: 1.5;
        }
        
        .alert-content a {
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
        }
        
        .alert-content a:hover {
            text-decoration: underline;
        }
        
        .container.mt-8 {
            margin-top: 30px;
            text-align: center;
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
        }
        
        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(108, 117, 125, 0.4);
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @media (max-width: 576px) {
            .custom-alert {
                flex-direction: column;
                text-align: center;
            }
            
            .alert-icon {
                align-self: center;
            }
        }
    </style>
</head>
<body>
    <div class="container mt-8">
        <a href="autentica&login.php" class="btn btn-secondary">Voltar ao Login</a>
    </div>
</body>
</html>