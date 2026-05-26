<?php

namespace Controllers;

use Models\Grupo;
use Config\Config;

class PublicController {
    private $grupoModel;

    public function __construct() {
        $this->grupoModel = new Grupo();
    }

    public function index() {
        $grupos = $this->grupoModel->getAll();

        $ranking = [];
        foreach ($grupos as $grupo) {
            $grupo['pontos'] = $this->grupoModel->getPontuacaoTotal($grupo['id']);
            $ranking[] = $grupo;
        }

        usort($ranking, function($a, $b) {
            return $b['pontos'] <=> $a['pontos'];
        });

        $campeonato_fim = Config::get('campeonato_fim');
        $dias_restantes = 0;
        if ($campeonato_fim) {
            $fim = new \DateTime($campeonato_fim);
            $hoje = new \DateTime();
            if ($fim > $hoje) {
                $dias_restantes = $hoje->diff($fim)->days;
            }
        }

        $title = "Dashboard Geral";
        ob_start();
        include __DIR__ . '/../Views/public/index.php';
        $content = ob_get_clean();
        include __DIR__ . '/../Views/layouts/main.php';
    }
}
