<?php

namespace Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailService {
    private $mailer;

    public function __construct() {
        $this->mailer = new PHPMailer(true);
        // Setup base config for PHPMailer
        // In a real scenario these should come from Config::get() or environment variables
        $this->mailer->isSMTP();
        $this->mailer->Host       = 'localhost'; // Or your SMTP host
        $this->mailer->SMTPAuth   = false;
        $this->mailer->Port       = 25;
        $this->mailer->setFrom('noreply@youthscore.com', 'Youth Score System');
        $this->mailer->isHTML(true);
    }

    public function enviar($para, $assunto, $mensagem) {
        try {
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($para);
            $this->mailer->Subject = $assunto;
            $this->mailer->Body    = $mensagem;
            $this->mailer->AltBody = strip_tags($mensagem);

            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            // Log erro
            error_log("Erro ao enviar e-mail: {$this->mailer->ErrorInfo}");
            return false;
        }
    }

    public function enviarBemVindo($usuario) {
        $assunto = "Conta criada - Youth Score";
        $mensagem = "Olá {$usuario['nome']},<br><br>Sua conta foi criada no sistema Youth Score.";
        return $this->enviar($usuario['email'], $assunto, $mensagem);
    }

    public function enviarRecuperacaoSenha($usuario, $token) {
        $link = "http://" . $_SERVER['HTTP_HOST'] . "/recuperar-senha?token=" . $token;
        $assunto = "Recuperação de Senha - Youth Score";
        $mensagem = "Olá {$usuario['nome']},<br><br>Você solicitou a recuperação de senha. Clique no link abaixo para criar uma nova senha:<br><br><a href='{$link}'>{$link}</a>";
        return $this->enviar($usuario['email'], $assunto, $mensagem);
    }

    public function enviarNovoSupervisor($usuario) {
        $assunto = "Novo Supervisor - Youth Score";
        $mensagem = "Olá {$usuario['nome']},<br><br>Você foi adicionado como supervisor de grupo no sistema Youth Score.";
        return $this->enviar($usuario['email'], $assunto, $mensagem);
    }

    public function enviarMudancaSenha($usuario) {
        $assunto = "Senha Alterada - Youth Score";
        $mensagem = "Olá {$usuario['nome']},<br><br>Sua senha foi alterada com sucesso.";
        return $this->enviar($usuario['email'], $assunto, $mensagem);
    }
}
