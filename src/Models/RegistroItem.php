<?php

namespace Models;

use Core\Database;
use PDO;

class RegistroItem {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO registro_itens
            (registro_id, atividade_id, quantidade, valor_manual, motivo, pontos_calculados)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['registro_id'],
            $data['atividade_id'] ?? null,
            $data['quantidade'] ?? null,
            $data['valor_manual'] ?? null,
            $data['motivo'] ?? null,
            $data['pontos_calculados']
        ]);
        return $this->db->lastInsertId();
    }

    public function getByRegistro($registro_id) {
        $stmt = $this->db->prepare("
            SELECT ri.*, a.nome as atividade_nome
            FROM registro_itens ri
            LEFT JOIN atividades a ON ri.atividade_id = a.id
            WHERE ri.registro_id = ? AND ri.deletado_em IS NULL
        ");
        $stmt->execute([$registro_id]);
        return $stmt->fetchAll();
    }
}
