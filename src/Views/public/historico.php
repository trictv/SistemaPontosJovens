<div class="mb-6 card p-6 border-l-4 border-purple-500">
    <h1 class="text-2xl font-black text-gray-800 tracking-tight">Histórico de Atividades</h1>
    <p class="text-gray-500 text-sm mt-1">Timeline em tempo real das pontuações registradas</p>
</div>

<!-- Modern Filters -->
<div class="card p-4 mb-8">
    <form action="/historico" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
        <div class="w-full md:w-1/3">
            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1 ml-1"><i class="fas fa-users mr-1"></i> Filtrar por Grupo</label>
            <select name="grupo" class="w-full bg-gray-50 border-gray-200">
                <option value="">Todos os grupos</option>
                <?php foreach ($grupos as $g): ?>
                    <option value="<?php echo $g['id']; ?>" <?php echo (isset($_GET['grupo']) && $_GET['grupo'] == $g['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($g['nome']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="w-full md:w-1/4">
            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1 ml-1"><i class="far fa-calendar-alt mr-1"></i> Data Inicial</label>
            <input type="date" name="inicio" value="<?php echo htmlspecialchars($_GET['inicio'] ?? ''); ?>" class="w-full bg-gray-50 border-gray-200">
        </div>
        <div class="w-full md:w-1/4">
            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1 ml-1"><i class="far fa-calendar-check mr-1"></i> Data Final</label>
            <input type="date" name="fim" value="<?php echo htmlspecialchars($_GET['fim'] ?? ''); ?>" class="w-full bg-gray-50 border-gray-200">
        </div>
        <div class="w-full md:w-auto">
            <button type="submit" class="w-full btn-primary h-[46px]">
                <i class="fas fa-filter"></i> <span class="md:hidden lg:inline">Aplicar Filtros</span>
            </button>
        </div>
    </form>
</div>

<!-- Modern Timeline -->
<div class="relative py-4 px-2 sm:px-6">
    <!-- Center Line -->
    <div class="hidden sm:block absolute left-8 top-0 bottom-0 w-0.5 bg-gray-200 ml-3"></div>
    <div class="sm:hidden absolute left-6 top-0 bottom-0 w-0.5 bg-gray-200 ml-1"></div>

    <div class="space-y-8">
        <?php foreach ($historicoCompleto as $item): ?>
            <?php
                $isBonus = ($item['pontos'] > 0 && $item['tipo'] == 'manual');
                $isPenalidade = ($item['pontos'] < 0);
                $isRegistro = ($item['tipo'] == 'registro');

                $icon = 'fa-check';
                $iconColor = 'text-green-500';
                $iconBg = 'bg-green-100';
                $borderColor = 'border-green-500';
                $cardBorder = 'border-l-4 border-l-green-400';

                if ($isPenalidade) {
                    $icon = 'fa-exclamation-triangle';
                    $iconColor = 'text-red-600';
                    $iconBg = 'bg-red-100';
                    $borderColor = 'border-red-500';
                    $cardBorder = 'border-l-4 border-l-red-500';
                } elseif ($isBonus) {
                    $icon = 'fa-star';
                    $iconColor = 'text-yellow-600';
                    $iconBg = 'bg-yellow-100';
                    $borderColor = 'border-yellow-400';
                    $cardBorder = 'border-l-4 border-l-yellow-400';
                } elseif ($isRegistro) {
                    $icon = 'fa-clipboard-check';
                    $iconColor = 'text-blue-600';
                    $iconBg = 'bg-blue-100';
                    $borderColor = 'border-blue-500';
                    $cardBorder = 'border-l-4 border-l-blue-400';
                }
            ?>

            <div class="relative flex items-start gap-4 sm:gap-8 group">
                <!-- Timeline dot/icon -->
                <div class="relative z-10 w-10 h-10 sm:w-12 sm:h-12 rounded-full border-4 border-white <?php echo $iconBg; ?> flex items-center justify-center shadow-sm shrink-0 transition-transform group-hover:scale-110">
                    <i class="fas <?php echo $icon; ?> <?php echo $iconColor; ?> text-lg"></i>
                </div>

                <!-- Content Card -->
                <div class="flex-1 min-w-0 card p-0 overflow-hidden <?php echo $cardBorder; ?> hover:shadow-md transition-shadow">
                    <!-- Card Header -->
                    <div class="px-5 py-3 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                        <div class="flex items-center gap-2 min-w-0">
                            <div class="w-3 h-3 rounded-full shrink-0 shadow-sm" style="background-color: <?php echo htmlspecialchars($item['grupo_cor']); ?>"></div>
                            <span class="font-bold text-gray-800 truncate text-sm sm:text-base"><?php echo htmlspecialchars($item['grupo_nome']); ?></span>
                        </div>
                        <div class="text-[10px] sm:text-xs text-gray-500 font-bold uppercase tracking-wider shrink-0 bg-white px-2 py-1 rounded shadow-sm border border-gray-100">
                            <?php echo date('d M Y, H:i', strtotime($item['data'])); ?>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="px-5 py-4">
                        <div class="flex justify-between items-start gap-4">
                            <div>
                                <h4 class="font-bold text-gray-800 text-base mb-1">
                                    <?php echo htmlspecialchars($item['acao']); ?>
                                </h4>

                                <?php if (!empty($item['motivo'])): ?>
                                    <p class="text-sm text-gray-600 bg-gray-50 p-2 rounded border border-gray-100 mt-2">
                                        <span class="font-bold text-gray-700 text-xs uppercase">Motivo:</span>
                                        <?php echo htmlspecialchars($item['motivo']); ?>
                                    </p>
                                <?php endif; ?>

                                <?php if (!empty($item['detalhes'])): ?>
                                    <div class="mt-4 space-y-3">
                                        <?php foreach ($item['detalhes'] as $detalhe): ?>
                                            <div class="bg-white border border-gray-100 rounded-md p-3 shadow-sm">
                                                <div class="flex justify-between items-center mb-1">
                                                    <span class="font-bold text-gray-700 text-sm flex items-center gap-1.5">
                                                        <i class="fas fa-caret-right text-gray-400"></i>
                                                        <?php echo htmlspecialchars($detalhe['nome']); ?>
                                                        <?php if (isset($detalhe['quantidade']) && $detalhe['quantidade'] > 0): ?>
                                                            <span class="bg-gray-100 text-gray-600 text-[10px] px-1.5 py-0.5 rounded ml-1"><?php echo $detalhe['quantidade']; ?></span>
                                                        <?php endif; ?>
                                                    </span>
                                                    <span class="text-xs font-bold <?php echo $detalhe['pontos'] > 0 ? 'text-green-600' : 'text-gray-500'; ?>">
                                                        <?php echo $detalhe['pontos'] > 0 ? '+' : ''; ?><?php echo number_format($detalhe['pontos'], 2, ',', '.'); ?> pts
                                                    </span>
                                                </div>

                                                <?php if (!empty($detalhe['pessoas'])): ?>
                                                    <div class="mt-2 text-xs text-gray-500 flex flex-wrap gap-1">
                                                        <?php foreach ($detalhe['pessoas'] as $pessoa): ?>
                                                            <span class="bg-gray-50 border border-gray-100 px-2 py-0.5 rounded text-gray-600"><?php echo htmlspecialchars($pessoa); ?></span>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($item['observacoes'])): ?>
                                    <div class="mt-4 flex items-start gap-2 bg-blue-50/50 p-3 rounded-lg border border-blue-100/50">
                                        <i class="fas fa-quote-left text-blue-300 mt-1 shrink-0"></i>
                                        <p class="text-sm text-gray-600 italic leading-relaxed">
                                            <?php echo htmlspecialchars($item['observacoes']); ?>
                                        </p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Points Badge -->
                            <?php if ($item['pontos'] !== null && $item['pontos'] != 0): ?>
                                <div class="shrink-0 text-center">
                                    <div class="inline-flex items-center justify-center px-3 py-2 rounded-lg font-black text-lg shadow-sm border
                                        <?php echo $item['pontos'] > 0 ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-700 border-red-200'; ?>">
                                        <?php echo $item['pontos'] > 0 ? '+' : ''; ?><?php echo number_format($item['pontos'], 2, ',', '.'); ?>
                                    </div>
                                    <div class="text-[10px] uppercase font-bold text-gray-400 mt-1">Pontos</div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="mt-4 pt-3 border-t border-gray-50 flex items-center text-xs text-gray-400 font-medium">
                            <i class="fas fa-user-edit mr-1.5"></i> Lançado por <?php echo htmlspecialchars($item['usuario_nome']); ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (empty($historicoCompleto)): ?>
            <div class="text-center py-16 card border-dashed border-2 border-gray-200 bg-gray-50/50 shadow-none">
                <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm border border-gray-100">
                    <i class="fas fa-history text-3xl text-gray-300"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-700">Nenhum registro encontrado</h3>
                <p class="text-sm text-gray-500 mt-1">Tente mudar os filtros de busca acima.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
