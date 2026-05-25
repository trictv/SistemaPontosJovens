<?php

namespace Models;

use Core\Database;
use PDO;

class Registro {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($grupo_id, $criado_por, $data) {
        $stmt = $this->db->prepare("INSERT INTO registros (grupo_id, criado_por, data) VALUES (?, ?, ?)");
        $stmt->execute([$grupo_id, $criado_por, $data]);
        return $this->db->lastInsertId();
    }

    public function getRecentesByGrupo($grupo_id, $limit = 10) {
        $stmt = $this->db->prepare("
            SELECT r.*, u.nome as criador
            FROM registros r
            JOIN usuarios u ON r.criado_por = u.id
            WHERE r.grupo_id = ? AND r.deletado_em IS NULL
            ORDER BY r.data DESC LIMIT ?
        ");
        $stmt->bindValue(1, $grupo_id, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByGrupo($grupo_id) {
        $stmt = $this->db->prepare("SELECT * FROM registros WHERE grupo_id = ? AND deletado_em IS NULL ORDER BY data DESC");
        $stmt->execute([$grupo_id]);
        return $stmt->fetchAll();
    }
}
