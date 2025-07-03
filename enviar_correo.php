<?php
// Enviar correo de confirmación de pedido usando PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/lib/PHPMailer/src/Exception.php';
require_once __DIR__ . '/lib/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/lib/PHPMailer/src/SMTP.php';

function enviarCorreoPedido($correoDestino, $nombreUsuario, $pedido_id, $productos, $total)
{
    $mail = new PHPMailer(true);
    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'chridavid.gutierrez@gmail.com'; // Cambia por tu correo
        $mail->Password = 'tfiikqmbzxphxfxo'; // Cambia por tu contraseña de app
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Remitente y destinatario
        $mail->setFrom('chridavid.gutierrez@gmail.com', 'Bodega Web');
        $mail->addAddress($correoDestino, $nombreUsuario);

        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = 'Confirmación de pedido #' . $pedido_id;
        $body = '<h3>¡Gracias por tu compra, ' . htmlspecialchars($nombreUsuario) . '!</h3>';
        $body .= '<p>Tu pedido ha sido registrado con éxito. Estos son los detalles:</p>';
        $body .= '<ul>';
        foreach ($productos as $prod) {
            $body .= '<li>' . htmlspecialchars($prod['nombre']) . ' x ' . $prod['cantidad'] . ' - S/ ' . number_format($prod['precio_unitario'], 2) . '</li>';
        }
        $body .= '</ul>';
        $body .= '<p><strong>Total: S/ ' . number_format($total, 2) . '</strong></p>';
        $mail->Body = $body;

        // Log del cuerpo del correo para depuración
        error_log('Cuerpo del correo enviado:\n' . $body);

        // Activar debug SMTP de PHPMailer
        $mail->SMTPDebug = 2; // 2 = client y server messages
        $mail->Debugoutput = function ($str, $level) {
            error_log("PHPMailer SMTP: $str");
        };

        $mail->send();
        // Retornar también el cuerpo del correo para depuración en frontend
        return ["success" => true, "body" => $body];
    } catch (Exception $e) {
        return ["error" => $mail->ErrorInfo, "body" => isset($body) ? $body : null];
    }
}
