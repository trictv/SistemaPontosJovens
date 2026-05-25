<?php

namespace Services;

use Models\Usuario;
use Models\Historico;

class AuthService {
    private $usuarioModel;
    private $historicoModel;

    public function __construct() {
        $this->usuarioModel = new Usuario();
        $this->historicoModel = new Historico();
    }

    public function login($email, $senha) {
        $user = $this->usuarioModel->findByEmail($email);

        if ($user && password_verify($senha, $user['senha'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nome'] = $user['nome'];
            $_SESSION['user_tipo'] = $user['tipo'];
            $_SESSION['user_grupo_id'] = $user['grupo_id'];

            $this->historicoModel->log('Login no sistema');
            return true;
        }

        return false;
    }

    public function logout() {
        $this->historicoModel->log('Logout do sistema');
        session_destroy();
    }

    public function isAuthenticated() {
        return isset($_SESSION['user_id']);
    }

    public function isAdmin() {
        return isset($_SESSION['user_tipo']) && $_SESSION['user_tipo'] === 'admin';
    }

    public function requireAuth() {
        if (!$this->isAuthenticated()) {
            $_SESSION['flash_message'] = "Você precisa estar logado para acessar esta página.";
            $_SESSION['flash_type'] = "error";
            header("Location: /login");
            die();
        }
    }

    public function requireAdmin() {
        $this->requireAuth();
        if (!$this->isAdmin()) {
            $_SESSION['flash_message'] = "Acesso negado. Apenas administradores.";
            $_SESSION['flash_type'] = "error";
            header("Location: /");
            die();
        }
    }
}
