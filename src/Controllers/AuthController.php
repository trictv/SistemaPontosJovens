<?php

namespace Controllers;

use Services\AuthService;
use Models\Usuario;
use Services\EmailService;

class AuthController {
    private $authService;
    private $usuarioModel;

    public function __construct() {
        $this->authService = new AuthService();
        $this->usuarioModel = new Usuario();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $senha = $_POST['senha'] ?? '';

            if ($this->authService->login($email, $senha)) {
                if ($_SESSION['user_tipo'] === 'admin') {
                    header("Location: /admin");
                } else {
                    header("Location: /supervisor");
                }
                die();
            } else {
                $_SESSION['flash_message'] = "E-mail ou senha inválidos.";
                $_SESSION['flash_type'] = "error";
            }
        }

        $title = "Login";
        ob_start();
        include __DIR__ . '/../Views/public/login.php';
        $content = ob_get_clean();
        include __DIR__ . '/../Views/layouts/main.php';
    }

    public function logout() {
        $this->authService->logout();
        header("Location: /");
        die();
    }
}
