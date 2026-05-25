<?php

namespace Models;

use Core\Database;
use PDO;

class Atividade {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $stmt = $this->db->prepare("SELECT * FROM atividades WHERE deletado_em IS NULL");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM atividades WHERE id = ? AND deletado_em IS NULL");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO atividades (nome, tipo_pontuacao, tipo_entrada, pontos) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $data['nome'],
            $data['tipo_pontuacao'],
            $data['tipo_entrada'],
            $data['pontos']
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE atividades SET nome = ?, tipo_pontuacao = ?, tipo_entrada = ?, pontos = ? WHERE id = ?");
        return $stmt->execute([
            $data['nome'],
            $data['tipo_pontuacao'],
            $data['tipo_entrada'],
            $data['pontos'],
            $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("UPDATE atividades SET deletado_em = NOW() WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
