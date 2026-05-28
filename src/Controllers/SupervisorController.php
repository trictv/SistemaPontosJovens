<?php

namespace Controllers;

use Services\AuthService;
use Models\Grupo;
use Models\Membro;
use Models\Atividade;
use Models\Registro;
use Models\RegistroItem;
use Models\Visitante;
use Models\Presenca;
use Models\Historico;

class SupervisorController {
    private $authService;
    private $grupo_id;
    private $grupoModel;
    private $membroModel;
    private $atividadeModel;
    private $registroModel;
    private $historicoModel;

    public function __construct() {
        $this->authService = new AuthService();
        $this->authService->requireAuth();

        if (!isset($_SESSION['user_grupo_id'])) {
            $_SESSION['flash_message'] = "Você não está vinculado a nenhum grupo.";
            $_SESSION['flash_type'] = "error";
            header("Location: /login");
            die();
        }

        $this->grupo_id = $_SESSION['user_grupo_id'];
        $this->grupoModel = new Grupo();
        $this->membroModel = new Membro();
        $this->atividadeModel = new Atividade();
        $this->registroModel = new Registro();
        $this->historicoModel = new Historico();
    }

    public function dashboard() {
        $grupo = $this->grupoModel->getById($this->grupo_id);
        $grupo['pontos'] = $this->grupoModel->getPontuacaoTotal($this->grupo_id);

        $membros = $this->membroModel->getByGrupo($this->grupo_id);
        $registros = $this->registroModel->getRecentesByGrupo($this->grupo_id, 10);

        $title = "Painel do Grupo";
        ob_start();
        include __DIR__ . '/../Views/supervisor/dashboard.php';
        $content = ob_get_clean();
        include __DIR__ . '/../Views/layouts/main.php';
    }

    public function registrarAtividade() {
        $atividades = $this->atividadeModel->getAll();
        $membros = $this->membroModel->getByGrupo($this->grupo_id);

        $title = "Registrar Atividade";
        ob_start();
        include __DIR__ . '/../Views/supervisor/registrar_atividade.php';
        $content = ob_get_clean();
        include __DIR__ . '/../Views/layouts/main.php';
    }

    public function salvarRegistro() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data_registro = $_POST['data'] ?? date('Y-m-d H:i:s');

            // Inicia o registro unificado
            $observacoes = isset($_POST['observacoes']) ? trim($_POST['observacoes']) : null;
            if (empty($observacoes)) $observacoes = null;
            $registro_id = $this->registroModel->create($this->grupo_id, $_SESSION['user_id'], $data_registro, $observacoes);
            $pontos_totais = 0;

            $registroItemModel = new RegistroItem();
            $presencaModel = new Presenca();
            $visitanteModel = new Visitante();

            $totalMembros = $this->membroModel->getCountByGrupo($this->grupo_id);
            if ($totalMembros == 0) $totalMembros = 1; // Evita divisão por zero

            // Loop pelas atividades submetidas
            if (isset($_POST['atividades']) && is_array($_POST['atividades'])) {
                foreach ($_POST['atividades'] as $atividade_id => $dados) {
                    $atividade = $this->atividadeModel->getById($atividade_id);
                    if (!$atividade) continue;

                    $pontos_calculados = 0;
                    $item_data = [
                        'registro_id' => $registro_id,
                        'atividade_id' => $atividade_id
                    ];

                    if ($atividade['tipo_entrada'] === 'check_membros') {
                        if (isset($dados['membros']) && is_array($dados['membros'])) {
                            $marcados = count($dados['membros']);
                            if ($atividade['tipo_pontuacao'] === 'proporcional') {
                                $percentual = $marcados / $totalMembros;
                                $pontos_calculados = round($percentual * $atividade['pontos'], 2);
                            } else {
                                $pontos_calculados = $marcados * $atividade['pontos'];
                            }

                            $item_data['pontos_calculados'] = $pontos_calculados;
                            $item_id = $registroItemModel->create($item_data);

                            foreach ($dados['membros'] as $membro_id) {
                                $presencaModel->create($membro_id, $item_id);
                            }
                        }
                    }
                    else if ($atividade['tipo_entrada'] === 'quantidade') {
                        $qtd = intval($dados['quantidade'] ?? 0);
                        if ($qtd > 0) {
                            $pontos_calculados = $qtd * $atividade['pontos'];
                            $item_data['quantidade'] = $qtd;
                            $item_data['pontos_calculados'] = $pontos_calculados;
                            $registroItemModel->create($item_data);
                        }
                    }
                    else if ($atividade['tipo_entrada'] === 'lista_nomes') {
                        if (!empty($dados['visitantes'])) {
                            $nomes = array_filter(array_map('trim', explode("\n", $dados['visitantes'])));
                            $qtd = count($nomes);
                            if ($qtd > 0) {
                                $pontos_calculados = $qtd * $atividade['pontos'];
                                $item_data['pontos_calculados'] = $pontos_calculados;
                                $item_id = $registroItemModel->create($item_data);

                                foreach ($nomes as $nome) {
                                    $visitanteModel->create($nome, $item_id);
                                }
                            }
                        }
                    }

                    $pontos_totais += $pontos_calculados;
                }
            }

            $this->historicoModel->log('Criou registro de atividades', $pontos_totais, null, null, $this->grupo_id);

            $_SESSION['flash_message'] = "Registro salvo com sucesso! +{$pontos_totais} pontos.";
            $_SESSION['flash_type'] = "success";
            header("Location: /supervisor");
            die();
        }
    }

    public function adicionarMembro() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = trim($_POST['nome'] ?? '');

            if (!empty($nome)) {
                $this->membroModel->create($nome, $this->grupo_id);
                $this->historicoModel->log('Adicionou membro', null, null, "Membro: {$nome}", $this->grupo_id);

                $_SESSION['flash_message'] = "Membro '{$nome}' adicionado com sucesso!";
                $_SESSION['flash_type'] = "success";
            } else {
                $_SESSION['flash_message'] = "O nome do membro não pode estar vazio.";
                $_SESSION['flash_type'] = "error";
            }

            header("Location: /supervisor");
            die();
        }
    }

    // --- GERENCIAMENTO DE MEMBROS (SUPERVISOR) ---

    public function membros() {
        $busca = $_GET['q'] ?? null;
        $membros = $this->membroModel->getByGrupo($this->grupo_id, $busca);

        $title = "Meus Membros";
        ob_start();
        include __DIR__ . '/../Views/supervisor/membros.php';
        $content = ob_get_clean();
        include __DIR__ . '/../Views/layouts/main.php';
    }

    public function editarMembro() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $nome = $_POST['nome'];
            $status = $_POST['status'];

            $membroAntigo = $this->membroModel->getById($id);

            // Garante que o supervisor só pode editar membros do seu próprio grupo
            if ($membroAntigo && $membroAntigo['grupo_id'] == $this->grupo_id) {
                $this->membroModel->update($id, [
                    'nome' => $nome,
                    'grupo_id' => $this->grupo_id, // Não permite mudar o grupo
                    'status' => $status
                ]);

                $detalhes = "Nome: {$nome}. Status: {$status}";
                $this->historicoModel->log('Editou membro', null, null, $detalhes, $this->grupo_id);

                $_SESSION['flash_message'] = "Membro editado com sucesso!";
                $_SESSION['flash_type'] = "success";
            } else {
                $_SESSION['flash_message'] = "Erro: Membro não encontrado ou não pertence ao seu grupo.";
                $_SESSION['flash_type'] = "error";
            }

            header("Location: /supervisor/membros");
            die();
        }
    }

    public function excluirMembro() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];

            $membro = $this->membroModel->getById($id);

            // Garante que o supervisor só pode excluir membros do seu próprio grupo
            if ($membro && $membro['grupo_id'] == $this->grupo_id) {
                $this->membroModel->delete($id);
                $this->historicoModel->log('Removeu membro', null, null, "Membro: {$membro['nome']}", $this->grupo_id);

                $_SESSION['flash_message'] = "Membro removido com sucesso!";
                $_SESSION['flash_type'] = "success";
            } else {
                $_SESSION['flash_message'] = "Erro: Membro não encontrado ou não pertence ao seu grupo.";
                $_SESSION['flash_type'] = "error";
            }

            header("Location: /supervisor/membros");
            die();
        }
    }
}
