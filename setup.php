<?php

$host = '127.0.0.1';
$dbname = 'u769560658_Pontos';
$user = 'u769560658_Pontos'; // Change as needed
$pass = 'm1401M!$';     // Change as needed

try {
    // Conecta diretamente ao banco existente
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $user,
        $pass
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Conectado ao banco '$dbname' com sucesso.<br>";

    // Tabela: configuracoes
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS configuracoes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            chave VARCHAR(50) NOT NULL UNIQUE,
            valor VARCHAR(255) NOT NULL
        )
    ");
    echo "Tabela 'configuracoes' criada.<br>";

    // Tabela: grupos
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS grupos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(100) NOT NULL,
            foto VARCHAR(255),
            cor VARCHAR(20),
            versiculo TEXT,
            descricao TEXT,
            deletado_em DATETIME NULL
        )
    ");
    echo "Tabela 'grupos' criada.<br>";

    // Tabela: usuarios
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS usuarios (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(100) NOT NULL,
            email VARCHAR(100) UNIQUE,
            senha VARCHAR(255),
            tipo ENUM('admin', 'supervisor') NOT NULL,
            grupo_id INT NULL,
            token_recuperacao VARCHAR(100) NULL,
            token_expiracao DATETIME NULL,
            deletado_em DATETIME NULL,
            FOREIGN KEY (grupo_id) REFERENCES grupos(id)
        )
    ");
    echo "Tabela 'usuarios' criada.<br>";

    // Tabela: membros
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS membros (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(100) NOT NULL,
            grupo_id INT NOT NULL,
            status ENUM('ativo', 'inativo') DEFAULT 'ativo',
            data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP,
            deletado_em DATETIME NULL,
            FOREIGN KEY (grupo_id) REFERENCES grupos(id)
        )
    ");
    echo "Tabela 'membros' criada.<br>";

    // Tabela: atividades
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS atividades (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(100) NOT NULL,
            tipo_pontuacao ENUM('fixo', 'proporcional', 'bonus_penalidade') NOT NULL,
            tipo_entrada ENUM('check_membros', 'lista_nomes', 'quantidade', 'valor_manual') NOT NULL,
            pontos DECIMAL(10,2) NOT NULL,
            deletado_em DATETIME NULL
        )
    ");
    echo "Tabela 'atividades' criada.<br>";

    // Tabela: registros
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS registros (
            id INT AUTO_INCREMENT PRIMARY KEY,
            grupo_id INT NOT NULL,
            status ENUM('ativo', 'inativo') DEFAULT 'ativo',
            data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP,
            criado_por INT NOT NULL,
            data DATETIME NOT NULL,
            observacoes TEXT NULL,
            deletado_em DATETIME NULL,
            FOREIGN KEY (grupo_id) REFERENCES grupos(id),
            FOREIGN KEY (criado_por) REFERENCES usuarios(id)
        )
    ");
    echo "Tabela 'registros' criada.<br>";

    // Tabela: registro_itens
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS registro_itens (
            id INT AUTO_INCREMENT PRIMARY KEY,
            registro_id INT NOT NULL,
            atividade_id INT NULL,
            quantidade INT NULL,
            valor_manual DECIMAL(10,2) NULL,
            motivo VARCHAR(255) NULL,
            pontos_calculados DECIMAL(10,2) NOT NULL,
            deletado_em DATETIME NULL,
            FOREIGN KEY (registro_id) REFERENCES registros(id),
            FOREIGN KEY (atividade_id) REFERENCES atividades(id)
        )
    ");
    echo "Tabela 'registro_itens' criada.<br>";

    // Tabela: visitantes
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS visitantes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(100) NOT NULL,
            registro_item_id INT NOT NULL,
            FOREIGN KEY (registro_item_id) REFERENCES registro_itens(id)
        )
    ");
    echo "Tabela 'visitantes' criada.<br>";

    // Tabela: presencas
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS presencas (
            id INT AUTO_INCREMENT PRIMARY KEY,
            membro_id INT NOT NULL,
            registro_item_id INT NOT NULL,
            FOREIGN KEY (membro_id) REFERENCES membros(id),
            FOREIGN KEY (registro_item_id) REFERENCES registro_itens(id)
        )
    ");
    echo "Tabela 'presencas' criada.<br>";

    // Tabela: historico
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS historico (
            id INT AUTO_INCREMENT PRIMARY KEY,
            usuario_id INT NULL,
            grupo_id INT NULL,
            acao VARCHAR(100) NOT NULL,
            valor DECIMAL(10,2) NULL,
            motivo TEXT NULL,
            detalhes TEXT NULL,
            data DATETIME NOT NULL,
            observacoes TEXT NULL,
            FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
            FOREIGN KEY (grupo_id) REFERENCES grupos(id)
        )
    ");
    echo "Tabela 'historico' criada.<br>";

    // Modificar colunas caso as tabelas já existam
    $pdo->exec("ALTER TABLE atividades MODIFY COLUMN pontos DECIMAL(10,2) NOT NULL");
    $pdo->exec("ALTER TABLE registro_itens MODIFY COLUMN valor_manual DECIMAL(10,2) NULL");
    $pdo->exec("ALTER TABLE registro_itens MODIFY COLUMN pontos_calculados DECIMAL(10,2) NOT NULL");
    $pdo->exec("ALTER TABLE historico MODIFY COLUMN valor DECIMAL(10,2) NULL");
    echo "Colunas de pontuação ajustadas para DECIMAL(10,2).<br>";

    // Dados iniciais
    $pdo->exec("INSERT IGNORE INTO configuracoes (chave, valor) VALUES ('campeonato_inicio', '2025-01-01'), ('campeonato_fim', '2025-12-31')");

    // Usuário admin inicial (senha: 123456)
    $senhaHash = password_hash('123456', PASSWORD_DEFAULT);
    $pdo->exec("INSERT IGNORE INTO usuarios (nome, email, senha, tipo) VALUES ('Administrador', 'admin@admin.com', '$senhaHash', 'admin')");

    echo "Tabelas e dados iniciais inseridos com sucesso!<br>";

} catch (PDOException $e) {
    die("Erro ao conectar ou criar tabelas: " . $e->getMessage());
}
