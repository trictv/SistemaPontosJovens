<?php

namespace Models;

use Core\Database;
use PDO;

class Membro {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM membros WHERE id = ? AND deletado_em IS NULL");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getAll($busca = null) {
        $query = "
            SELECT m.*, g.nome as grupo_nome
            FROM membros m
            LEFT JOIN grupos g ON m.grupo_id = g.id
            WHERE m.deletado_em IS NULL
        ";

        $params = [];
        if ($busca) {
            $query .= " AND (m.nome LIKE ? OR g.nome LIKE ?)";
            $params[] = "%$busca%";
            $params[] = "%$busca%";
        }

        $query .= " ORDER BY m.nome ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function create($nome, $grupo_id, $status = 'ativo') {
        $stmt = $this->db->prepare("INSERT INTO membros (nome, grupo_id, status) VALUES (?, ?, ?)");
        $stmt->execute([$nome, $grupo_id, $status]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE membros SET nome = ?, grupo_id = ?, status = ? WHERE id = ?");
        return $stmt->execute([
            $data['nome'],
            $data['grupo_id'],
            $data['status'] ?? 'ativo',
            $id
        ]);
    }

    public function getByGrupo($grupo_id, $busca = null) {
        $query = "SELECT * FROM membros WHERE grupo_id = ? AND deletado_em IS NULL";
        $params = [$grupo_id];

        if ($busca) {
            $query .= " AND nome LIKE ?";
            $params[] = "%$busca%";
        }

        $query .= " ORDER BY nome ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function delete($id) {
        $stmt = $this->db->prepare("UPDATE membros SET deletado_em = NOW() WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getCountByGrupo($grupo_id) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM membros WHERE grupo_id = ? AND status = 'ativo' AND deletado_em IS NULL");
        $stmt->execute([$grupo_id]);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }
}
