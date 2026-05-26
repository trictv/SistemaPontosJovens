<?php

namespace Models;

use Core\Database;
use PDO;

class Visitante {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($nome, $registro_item_id) {
        $stmt = $this->db->prepare("INSERT INTO visitantes (nome, registro_item_id) VALUES (?, ?)");
        return $stmt->execute([$nome, $registro_item_id]);
    }

    public function getByRegistroItem($registro_item_id) {
        $stmt = $this->db->prepare("SELECT * FROM visitantes WHERE registro_item_id = ?");
        $stmt->execute([$registro_item_id]);
        return $stmt->fetchAll();
    }
}
