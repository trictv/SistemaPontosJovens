<?php

namespace Controllers;

use Services\AuthService;
use Models\Grupo;
use Models\Usuario;
use Models\Atividade;
use Models\Historico;
use Config\Config;

class AdminController {
    private $authService;
    private $grupoModel;
    private $usuarioModel;
    private $atividadeModel;
    private $historicoModel;

    public function __construct() {
        $this->authService = new AuthService();
        $this->authService->requireAdmin();

        $this->grupoModel = new Grupo();
        $this->usuarioModel = new Usuario();
        $this->atividadeModel = new Atividade();
        $this->historicoModel = new Historico();
    }

    public function dashboard() {
        $grupos = $this->grupoModel->getAll();
        $usuarios = $this->usuarioModel->getAll();
        $atividades = $this->atividadeModel->getAll();
        $historico = $this->historicoModel->getRecentes(10);

        $campeonato_inicio = Config::get('campeonato_inicio');
        $campeonato_fim = Config::get('campeonato_fim');

        $title = "Painel Administrativo";
        ob_start();
        include __DIR__ . '/../Views/admin/dashboard.php';
        $content = ob_get_clean();
        include __DIR__ . '/../Views/layouts/main.php';
    }

    // --- GRUPOS ---

    public function grupos() {
        $grupos = $this->grupoModel->getAll();

        $title = "Gerenciar Grupos";
        ob_start();
        include __DIR__ . '/../Views/admin/grupos.php';
        $content = ob_get_clean();
        include __DIR__ . '/../Views/layouts/main.php';
    }

    public function criarGrupo() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nome' => $_POST['nome'],
                'cor' => $_POST['cor'] ?? null,
                'versiculo' => $_POST['versiculo'] ?? null,
                'descricao' => $_POST['descricao'] ?? null
            ];
            $id = $this->grupoModel->create($data);
            $this->historicoModel->log('Criou grupo', null, null, "Grupo: {$data['nome']}");

            $_SESSION['flash_message'] = "Grupo criado com sucesso!";
            $_SESSION['flash_type'] = "success";
            header("Location: /admin/grupos");
            die();
        }
    }

    // --- USUÁRIOS ---

    public function usuarios() {
        $usuarios = $this->usuarioModel->getAll();
        $grupos = $this->grupoModel->getAll();

        $title = "Gerenciar Usuários";
        ob_start();
        include __DIR__ . '/../Views/admin/usuarios.php';
        $content = ob_get_clean();
        include __DIR__ . '/../Views/layouts/main.php';
    }

    public function criarUsuario() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nome' => $_POST['nome'],
                'email' => $_POST['email'],
                'senha' => $_POST['senha'],
                'tipo' => $_POST['tipo'],
                'grupo_id' => $_POST['grupo_id'] ?: null
            ];
            $this->usuarioModel->create($data);
            $this->historicoModel->log('Criou usuário', null, null, "Usuário: {$data['nome']} ({$data['tipo']})");

            $_SESSION['flash_message'] = "Usuário criado com sucesso!";
            $_SESSION['flash_type'] = "success";
            header("Location: /admin/usuarios");
            die();
        }
    }

    // --- ATIVIDADES ---

    public function atividades() {
        $atividades = $this->atividadeModel->getAll();

        $title = "Gerenciar Atividades";
        ob_start();
        include __DIR__ . '/../Views/admin/atividades.php';
        $content = ob_get_clean();
        include __DIR__ . '/../Views/layouts/main.php';
    }

    public function criarAtividade() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nome' => $_POST['nome'],
                'tipo_pontuacao' => $_POST['tipo_pontuacao'],
                'tipo_entrada' => $_POST['tipo_entrada'],
                'pontos' => $_POST['pontos']
            ];
            $this->atividadeModel->create($data);
            $this->historicoModel->log('Criou atividade', null, null, "Atividade: {$data['nome']}");

            $_SESSION['flash_message'] = "Atividade criada com sucesso!";
            $_SESSION['flash_type'] = "success";
            header("Location: /admin/atividades");
            die();
        }
    }

    // --- PONTUAÇÃO MANUAL (BÔNUS/PENALIDADE) ---

    public function pontuacao() {
        $grupos = $this->grupoModel->getAll();

        $title = "Lançar Pontuação Manual";
        ob_start();
        include __DIR__ . '/../Views/admin/pontuacao.php';
        $content = ob_get_clean();
        include __DIR__ . '/../Views/layouts/main.php';
    }

    public function lancarPontuacao() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $grupo_id = $_POST['grupo_id'];
            $valor = $_POST['valor'];
            $motivo = $_POST['motivo'];

            // Cria um registro para o lançamento manual
            $registroModel = new \Models\Registro();
            $registro_id = $registroModel->create($grupo_id, $_SESSION['user_id'], date('Y-m-d H:i:s'));

            $registroItemModel = new \Models\RegistroItem();
            $registroItemModel->create([
                'registro_id' => $registro_id,
                'valor_manual' => $valor,
                'motivo' => $motivo,
                'pontos_calculados' => $valor
            ]);

            $acao = $valor >= 0 ? 'Aplicou Bônus' : 'Aplicou Penalidade';
            $this->historicoModel->log($acao, $valor, $motivo, null, $grupo_id);

            $_SESSION['flash_message'] = "Pontuação lançada com sucesso!";
            $_SESSION['flash_type'] = "success";
            header("Location: /admin/pontuacao");
            die();
        }
    }
}
