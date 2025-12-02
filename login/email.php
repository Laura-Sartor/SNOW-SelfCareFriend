<!--Érica Bonfanti e Laura Sartor - 3-52 -->

<?php
// Carrega o PHPMailer (ajuste os caminhos se necessário)
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/PHPMailer/src/Exception.php';
require '../vendor/PHPMailer/src/PHPMailer.php';
require '../vendor/PHPMailer/src/SMTP.php';

// Cria uma instância do PHPMailer
$mail = new PHPMailer(true);

try {
    // Configurações do servidor SMTP do Gmail
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'keka.art2@gmail.com';  // Seu email
    $mail->Password = 'ucorqsymidgdvbrf';     // Substitua pela senha de app gerada
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Configura o email
    $mail->setFrom('keka.art2@gmail.com', 'Seu Nome');
    $mail->addAddress('keka.art2@gmail.com');  // Destinatário
    $mail->Subject = 'Teste de Email com PHPMailer';
    $mail->Body = 'Olá, este é um teste de envio de email via PHPMailer.';

    // Envia
    $mail->send();
    echo 'Email enviado com sucesso!';
} catch (Exception $e) {
    echo "Erro ao enviar: " . $mail->ErrorInfo;
}
?>
