<?php

namespace Models;

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
            VALUES (?, ?, ?, ?, ?, ?, NOW())
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
}
