<?php

namespace Models;

use Core\Database;
use PDO;

class Usuario {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = ? AND deletado_em IS NULL");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE id = ? AND deletado_em IS NULL");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getAll() {
        $stmt = $this->db->prepare("
            SELECT u.*, g.nome as grupo_nome
            FROM usuarios u
            LEFT JOIN grupos g ON u.grupo_id = g.id
            WHERE u.deletado_em IS NULL
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO usuarios (nome, email, senha, tipo, grupo_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['nome'],
            $data['email'],
            password_hash($data['senha'], PASSWORD_DEFAULT),
            $data['tipo'],
            $data['grupo_id'] ?? null
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $query = "UPDATE usuarios SET nome = ?, email = ?, tipo = ?, grupo_id = ?";
        $params = [$data['nome'], $data['email'], $data['tipo'], $data['grupo_id'] ?? null];

        if (!empty($data['senha'])) {
            $query .= ", senha = ?";
            $params[] = password_hash($data['senha'], PASSWORD_DEFAULT);
        }

        $query .= " WHERE id = ?";
        $params[] = $id;

        $stmt = $this->db->prepare($query);
        return $stmt->execute($params);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("UPDATE usuarios SET deletado_em = NOW() WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function setResetToken($email, $token) {
        $stmt = $this->db->prepare("UPDATE usuarios SET token_recuperacao = ?, token_expiracao = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = ?");
        return $stmt->execute([$token, $email]);
    }

    public function verifyResetToken($token) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE token_recuperacao = ? AND token_expiracao > NOW() AND deletado_em IS NULL");
        $stmt->execute([$token]);
        return $stmt->fetch();
    }

    public function updatePasswordByToken($token, $newPassword) {
        $stmt = $this->db->prepare("UPDATE usuarios SET senha = ?, token_recuperacao = NULL, token_expiracao = NULL WHERE token_recuperacao = ?");
        return $stmt->execute([password_hash($newPassword, PASSWORD_DEFAULT), $token]);
    }
}
