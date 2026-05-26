<div class="mb-8 bg-white rounded-lg shadow-sm p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-2">Histórico de Atividades</h1>
    <p class="text-gray-500 mb-6">Acompanhe os últimos registros e lançamentos de pontuação.</p>

    <!-- Filtros -->
    <form action="/historico" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Grupo</label>
            <select name="grupo" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                <option value="">Todos os grupos</option>
                <?php foreach ($grupos as $g): ?>
                    <option value="<?php echo $g['id']; ?>" <?php echo (isset($_GET['grupo']) && $_GET['grupo'] == $g['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($g['nome']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Data Início</label>
            <input type="date" name="inicio" value="<?php echo htmlspecialchars($_GET['inicio'] ?? ''); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Data Fim</label>
            <input type="date" name="fim" value="<?php echo htmlspecialchars($_GET['fim'] ?? ''); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
        </div>
        <div class="flex items-end">
            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded-md hover:bg-blue-700 transition">
                Filtrar
            </button>
        </div>
    </form>

    <!-- Linha do Tempo -->
    <div class="relative wrap overflow-hidden p-2">
        <div class="border-2-2 absolute border-opacity-20 border-gray-400 h-full border" style="left: 20px"></div>

        <div class="space-y-6">
            <?php foreach ($historicoCompleto as $item): ?>
                <?php
                    $isBonus = ($item['pontos'] > 0);
                    $isPenalidade = ($item['pontos'] < 0);
                    $isRegistro = ($item['tipo'] == 'registro');

                    $icon = 'fa-check-circle';
                    $iconColor = 'text-blue-500';
                    $bgColor = 'bg-white';

                    if ($isPenalidade) {
                        $icon = 'fa-exclamation-triangle';
                        $iconColor = 'text-red-500';
                        $bgColor = 'bg-red-50';
                    } elseif ($isBonus && !$isRegistro) {
                        $icon = 'fa-star';
                        $iconColor = 'text-yellow-500';
                        $bgColor = 'bg-yellow-50';
                    } elseif ($isRegistro) {
                        $icon = 'fa-clipboard-list';
                        $iconColor = 'text-green-500';
                    }
                ?>
                <div class="mb-4 flex justify-between items-start w-full">
                    <div class="z-20 flex items-center shadow bg-white w-10 h-10 rounded-full justify-center border-2 border-white <?php echo $iconColor; ?>">
                        <i class="fas <?php echo $icon; ?>"></i>
                    </div>
                    <div class="ml-6 flex-1 <?php echo $bgColor; ?> rounded-lg shadow-sm border border-gray-100 p-4">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-2">
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full mr-2" style="background-color: <?php echo htmlspecialchars($item['grupo_cor']); ?>"></div>
                                <h3 class="font-bold text-gray-800 text-lg"><?php echo htmlspecialchars($item['grupo_nome']); ?></h3>
                            </div>
                            <span class="text-xs text-gray-500 font-medium">
                                <i class="far fa-clock mr-1"></i> <?php echo date('d/m/Y H:i', strtotime($item['data'])); ?>
                            </span>
                        </div>

                        <div class="flex flex-col sm:flex-row justify-between items-start">
                            <div class="mb-2 sm:mb-0">
                                <p class="text-sm font-semibold text-gray-700"><?php echo htmlspecialchars($item['acao']); ?></p>

                                <?php if (!empty($item['motivo'])): ?>
                                    <p class="text-sm text-gray-600 mt-1"><span class="font-medium">Motivo:</span> <?php echo htmlspecialchars($item['motivo']); ?></p>
                                <?php endif; ?>

                                <?php if (!empty($item['observacoes'])): ?>
                                    <p class="text-sm text-gray-600 mt-1 italic"><i class="fas fa-quote-left text-gray-300 mr-1"></i> <?php echo htmlspecialchars($item['observacoes']); ?></p>
                                <?php endif; ?>

                                <p class="text-xs text-gray-400 mt-2">Por: <?php echo htmlspecialchars($item['usuario_nome']); ?></p>
                            </div>

                            <div class="mt-2 sm:mt-0 text-right">
                                <?php if ($item['pontos'] !== null && $item['pontos'] != 0): ?>
                                    <span class="inline-block px-3 py-1 rounded-full text-sm font-bold <?php echo $item['pontos'] > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                        <?php echo $item['pontos'] > 0 ? '+' : ''; ?><?php echo $item['pontos']; ?> pts
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (empty($historicoCompleto)): ?>
                <div class="text-center py-8">
                    <i class="fas fa-history text-4xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500">Nenhuma atividade registrada no período selecionado.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
