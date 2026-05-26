<?php

namespace Config;

class Config {
    public static function get($key) {
        $db = \Core\Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT valor FROM configuracoes WHERE chave = ?");
        $stmt->execute([$key]);
        $result = $stmt->fetch();
        return $result ? $result['valor'] : null;
    }

    public static function set($key, $value) {
        $db = \Core\Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO configuracoes (chave, valor) VALUES (?, ?) ON DUPLICATE KEY UPDATE valor = ?");
        $stmt->execute([$key, $value, $value]);
    }
}
