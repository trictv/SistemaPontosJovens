<?php

namespace Models;

use Core\Database;
use PDO;

class Membro {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($nome, $grupo_id) {
        $stmt = $this->db->prepare("INSERT INTO membros (nome, grupo_id) VALUES (?, ?)");
        $stmt->execute([$nome, $grupo_id]);
        return $this->db->lastInsertId();
    }

    public function getByGrupo($grupo_id) {
        $stmt = $this->db->prepare("SELECT * FROM membros WHERE grupo_id = ? AND deletado_em IS NULL");
        $stmt->execute([$grupo_id]);
        return $stmt->fetchAll();
    }

    public function delete($id) {
        $stmt = $this->db->prepare("UPDATE membros SET deletado_em = NOW() WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getCountByGrupo($grupo_id) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM membros WHERE grupo_id = ? AND deletado_em IS NULL");
        $stmt->execute([$grupo_id]);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }
}
