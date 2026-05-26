<?php

namespace Models;

use Core\Database;
use PDO;

class Presenca {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($membro_id, $registro_item_id) {
        $stmt = $this->db->prepare("INSERT INTO presencas (membro_id, registro_item_id) VALUES (?, ?)");
        return $stmt->execute([$membro_id, $registro_item_id]);
    }

    public function getByRegistroItem($registro_item_id) {
        $stmt = $this->db->prepare("
            SELECT p.*, m.nome as membro_nome
            FROM presencas p
            JOIN membros m ON p.membro_id = m.id
            WHERE p.registro_item_id = ?
        ");
        $stmt->execute([$registro_item_id]);
        return $stmt->fetchAll();
    }
}
