<?php

namespace Models;
date_default_timezone_set('America/Sao_Paulo');
use Core\Database;
use PDO;

class Historico {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function log($acao, $valor = null, $motivo = null, $detalhes = null, $grupo_id = null) {
        $usuario_id = $_SESSION['user_id'] ?? null;

        $stmt = $this->db->prepare("
            INSERT INTO historico (usuario_id, grupo_id, acao, valor, motivo, detalhes, data)
            VALUES (?, ?, ?, ?, ?, ?, '" . date('Y-m-d H:i:s') . "')
        ");
        return $stmt->execute([
            $usuario_id,
            $grupo_id,
            $acao,
            $valor,
            $motivo,
            $detalhes
        ]);
    }

    public function getRecentes($limit = 50) {
        $stmt = $this->db->prepare("
            SELECT h.*, u.nome as usuario_nome, g.nome as grupo_nome
            FROM historico h
            LEFT JOIN usuarios u ON h.usuario_id = u.id
            LEFT JOIN grupos g ON h.grupo_id = g.id
            ORDER BY h.data DESC LIMIT ?
        ");
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getPublico($filtros = [], $limite = 50, $offset = 0) {
        // Exibe histórico público combinando informações de registros e histórico manual
        $query = "
            SELECT
                h.id, h.acao, h.valor, h.motivo, h.detalhes, h.data,
                g.nome as grupo_nome, g.cor as grupo_cor,
                u.nome as usuario_nome
            FROM historico h
            LEFT JOIN grupos g ON h.grupo_id = g.id
            LEFT JOIN usuarios u ON h.usuario_id = u.id
            WHERE 1=1
        ";

        $params = [];

        if (!empty($filtros['grupo_id'])) {
            $query .= " AND h.grupo_id = ?";
            $params[] = $filtros['grupo_id'];
        }

        if (!empty($filtros['data_inicio'])) {
            $query .= " AND DATE(h.data) >= ?";
            $params[] = $filtros['data_inicio'];
        }

        if (!empty($filtros['data_fim'])) {
            $query .= " AND DATE(h.data) <= ?";
            $params[] = $filtros['data_fim'];
        }

        $query .= " ORDER BY h.data DESC LIMIT ? OFFSET ?";

        $stmt = $this->db->prepare($query);

        $paramIndex = 1;
        foreach ($params as $param) {
            $stmt->bindValue($paramIndex++, $param);
        }
        $stmt->bindValue($paramIndex++, $limite, PDO::PARAM_INT);
        $stmt->bindValue($paramIndex, $offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll();
    }
}
