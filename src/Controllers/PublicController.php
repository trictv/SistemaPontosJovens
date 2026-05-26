<?php

namespace Controllers;

use Models\Grupo;
use Config\Config;

class PublicController {
    private $grupoModel;

    public function __construct() {
        $this->grupoModel = new Grupo();
    }

    public function index() {
        $grupos = $this->grupoModel->getAll();

        $ranking = [];
        foreach ($grupos as $grupo) {
            $grupo['pontos'] = $this->grupoModel->getPontuacaoTotal($grupo['id']);
            $ranking[] = $grupo;
        }

        usort($ranking, function($a, $b) {
            return $b['pontos'] <=> $a['pontos'];
        });

        $campeonato_fim = Config::get('campeonato_fim');
        $dias_restantes = 0;
        if ($campeonato_fim) {
            $fim = new \DateTime($campeonato_fim);
            $hoje = new \DateTime();
            if ($fim > $hoje) {
                $dias_restantes = $hoje->diff($fim)->days;
            }
        }

        $title = "Dashboard Geral";
        ob_start();
        include __DIR__ . '/../Views/public/index.php';
        $content = ob_get_clean();
        include __DIR__ . '/../Views/layouts/main.php';
    }

    public function historico() {
        $filtros = [
            'grupo_id' => $_GET['grupo'] ?? null,
            'data_inicio' => $_GET['inicio'] ?? null,
            'data_fim' => $_GET['fim'] ?? null
        ];

        $historicoModel = new \Models\Historico();
        $logs = $historicoModel->getPublico($filtros, 50, 0);

        // Pega registros com observações para mostrar no histórico
        $registroModel = new \Models\Registro();
        $db = \Core\Database::getInstance()->getConnection();

        $queryReg = "
            SELECT r.id, r.data, r.observacoes,
                   g.nome as grupo_nome, g.cor as grupo_cor,
                   u.nome as usuario_nome,
                   (SELECT SUM(ri.pontos_calculados) FROM registro_itens ri WHERE ri.registro_id = r.id) as total_pontos
            FROM registros r
            JOIN grupos g ON r.grupo_id = g.id
            JOIN usuarios u ON r.criado_por = u.id
            WHERE r.deletado_em IS NULL
        ";

        $paramsReg = [];
        if (!empty($filtros['grupo_id'])) {
            $queryReg .= " AND r.grupo_id = ?";
            $paramsReg[] = $filtros['grupo_id'];
        }
        if (!empty($filtros['data_inicio'])) {
            $queryReg .= " AND DATE(r.data) >= ?";
            $paramsReg[] = $filtros['data_inicio'];
        }
        if (!empty($filtros['data_fim'])) {
            $queryReg .= " AND DATE(r.data) <= ?";
            $paramsReg[] = $filtros['data_fim'];
        }

        $queryReg .= " ORDER BY r.data DESC LIMIT 50";
        $stmtReg = $db->prepare($queryReg);
        $stmtReg->execute($paramsReg);
        $registros = $stmtReg->fetchAll();

        // Combina e ordena tudo por data
        $historicoCompleto = [];

        foreach ($logs as $log) {
            if ($log['acao'] == 'Login no sistema' || $log['acao'] == 'Logout do sistema' || strpos($log['acao'], 'membro') !== false || strpos($log['acao'], 'grupo') !== false || strpos($log['acao'], 'usuário') !== false || strpos($log['acao'], 'atividade') !== false) {
                continue; // Ignora logs administrativos puramente no histórico público
            }

            if ($log['acao'] == 'Criou registro de atividades') {
                continue; // Já vamos pegar da tabela de registros com mais detalhes
            }

            $historicoCompleto[] = [
                'tipo' => 'manual',
                'data' => $log['data'],
                'acao' => $log['acao'],
                'grupo_nome' => $log['grupo_nome'],
                'grupo_cor' => $log['grupo_cor'],
                'usuario_nome' => $log['usuario_nome'],
                'pontos' => $log['valor'],
                'motivo' => $log['motivo']
            ];
        }

        foreach ($registros as $reg) {
            // Busca detalhes dos itens deste registro
            $queryItens = "
                SELECT ri.id, ri.quantidade, ri.pontos_calculados, a.nome as atividade_nome, a.tipo_entrada
                FROM registro_itens ri
                LEFT JOIN atividades a ON ri.atividade_id = a.id
                WHERE ri.registro_id = ? AND ri.deletado_em IS NULL
            ";
            $stmtItens = $db->prepare($queryItens);
            $stmtItens->execute([$reg['id']]);
            $itens = $stmtItens->fetchAll();

            $detalhes = [];
            foreach ($itens as $item) {
                if (empty($item['atividade_nome'])) continue;

                $detalheItem = [
                    'nome' => $item['atividade_nome'],
                    'pontos' => $item['pontos_calculados'],
                    'tipo' => $item['tipo_entrada'],
                    'pessoas' => []
                ];

                if ($item['tipo_entrada'] === 'check_membros') {
                    // Busca membros presentes
                    $stmtPresencas = $db->prepare("
                        SELECT m.nome FROM presencas p
                        JOIN membros m ON p.membro_id = m.id
                        WHERE p.registro_item_id = ?
                    ");
                    $stmtPresencas->execute([$item['id']]);
                    $membrosPresentes = $stmtPresencas->fetchAll(\PDO::FETCH_COLUMN);
                    $detalheItem['pessoas'] = $membrosPresentes;
                    $detalheItem['quantidade'] = count($membrosPresentes);
                } elseif ($item['tipo_entrada'] === 'lista_nomes') {
                    // Busca visitantes
                    $stmtVisitantes = $db->prepare("SELECT nome FROM visitantes WHERE registro_item_id = ?");
                    $stmtVisitantes->execute([$item['id']]);
                    $visitantes = $stmtVisitantes->fetchAll(\PDO::FETCH_COLUMN);
                    $detalheItem['pessoas'] = $visitantes;
                    $detalheItem['quantidade'] = count($visitantes);
                } elseif ($item['tipo_entrada'] === 'quantidade') {
                    $detalheItem['quantidade'] = $item['quantidade'];
                }

                $detalhes[] = $detalheItem;
            }

            $historicoCompleto[] = [
                'tipo' => 'registro',
                'data' => $reg['data'],
                'acao' => 'Registro de Atividades',
                'grupo_nome' => $reg['grupo_nome'],
                'grupo_cor' => $reg['grupo_cor'],
                'usuario_nome' => $reg['usuario_nome'],
                'pontos' => $reg['total_pontos'],
                'observacoes' => $reg['observacoes'],
                'detalhes' => $detalhes
            ];
        }

        usort($historicoCompleto, function($a, $b) {
            return strtotime($b['data']) - strtotime($a['data']);
        });

        // Limita os resultados finais
        $historicoCompleto = array_slice($historicoCompleto, 0, 50);
        $grupos = $this->grupoModel->getAll();

        $title = "Histórico de Atividades";
        ob_start();
        include __DIR__ . '/../Views/public/historico.php';
        $content = ob_get_clean();
        include __DIR__ . '/../Views/layouts/main.php';
    }
}
