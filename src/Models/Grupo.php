<?php

namespace Models;

use Core\Database;
use PDO;

class Grupo {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $stmt = $this->db->prepare("SELECT * FROM grupos WHERE deletado_em IS NULL");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM grupos WHERE id = ? AND deletado_em IS NULL");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO grupos (nome, foto, cor, versiculo, descricao) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['nome'],
            $data['foto'] ?? null,
            $data['cor'] ?? null,
            $data['versiculo'] ?? null,
            $data['descricao'] ?? null
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE grupos SET nome = ?, foto = ?, cor = ?, versiculo = ?, descricao = ? WHERE id = ?");
        return $stmt->execute([
            $data['nome'],
            $data['foto'] ?? null,
            $data['cor'] ?? null,
            $data['versiculo'] ?? null,
            $data['descricao'] ?? null,
            $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("UPDATE grupos SET deletado_em = NOW() WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getPontuacaoTotal($id) {
        $stmt = $this->db->prepare("
            SELECT SUM(ri.pontos_calculados) as total
            FROM registros r
            JOIN registro_itens ri ON r.id = ri.registro_id
            WHERE r.grupo_id = ? AND r.deletado_em IS NULL AND ri.deletado_em IS NULL
        ");
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }
}
