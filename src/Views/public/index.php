<div class="mb-8 card p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between border-l-4 border-blue-500">
    <div class="mb-4 sm:mb-0">
        <h1 class="text-2xl font-black text-gray-800 tracking-tight">Ranking Geral</h1>
        <p class="text-gray-500 text-sm mt-1">Acompanhe a pontuação dos grupos em tempo real</p>
    </div>
    <div class="text-left sm:text-right bg-blue-50 px-6 py-3 rounded-xl border border-blue-100">
        <div class="text-3xl font-black text-blue-600 leading-none"><?php echo $dias_restantes; ?></div>
        <div class="text-xs text-blue-800 uppercase font-bold tracking-wider mt-1">Dias Restantes</div>
    </div>
</div>

<?php if (!empty($ranking)): ?>
    <?php
        // Identifica o maior número de pontos para barra de progresso
        $maxPontos = $ranking[0]['pontos'] > 0 ? $ranking[0]['pontos'] : 1;
    ?>

    <!-- Modern Podium -->
    <div class="flex justify-center items-end gap-2 sm:gap-4 mb-16 h-64 sm:h-72 mt-12">
        <!-- 2nd Place -->
        <?php if (isset($ranking[1])): ?>
            <div class="flex flex-col items-center w-1/3 max-w-[140px] relative z-10 group">
                <div class="absolute -top-16 opacity-0 group-hover:opacity-100 transition duration-300 bg-gray-800 text-white text-xs px-2 py-1 rounded">2º Lugar</div>
                <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-full mb-3 border-4 shadow-md bg-white flex items-center justify-center font-bold text-lg" style="border-color: <?php echo htmlspecialchars($ranking[1]['cor'] ?? '#cbd5e1'); ?>; color: <?php echo htmlspecialchars($ranking[1]['cor'] ?? '#cbd5e1'); ?>;">
                    <?php echo substr($ranking[1]['nome'], 0, 1); ?>
                </div>
                <div class="text-sm sm:text-base font-bold mb-1 truncate w-full text-center" style="color: <?php echo htmlspecialchars($ranking[1]['cor'] ?? '#64748b'); ?>"><?php echo htmlspecialchars($ranking[1]['nome']); ?></div>
                <div class="bg-gradient-to-t from-gray-300 to-gray-200 w-full rounded-t-xl flex flex-col items-center justify-start pt-4 shadow-lg h-32 sm:h-40 border-t border-gray-50">
                    <div class="text-gray-500 mb-1"><i class="fas fa-medal text-3xl drop-shadow-sm"></i></div>
                    <div class="font-black text-lg text-gray-600">2º</div>
                </div>
                <div class="bg-white border border-gray-200 w-full py-2 text-center rounded-b-xl shadow-sm -mt-1 z-20">
                    <div class="font-bold text-sm text-gray-800"><?php echo number_format($ranking[1]['pontos'], 0, ',', '.'); ?></div>
                    <div class="text-[10px] text-gray-400 uppercase font-bold">pts</div>
                </div>
            </div>
        <?php endif; ?>

        <!-- 1st Place -->
        <?php if (isset($ranking[0])): ?>
            <div class="flex flex-col items-center w-1/3 max-w-[160px] relative z-20 group">
                <div class="absolute -top-16 opacity-0 group-hover:opacity-100 transition duration-300 bg-yellow-600 text-white text-xs px-2 py-1 rounded">1º Lugar</div>
                <div class="text-yellow-400 mb-1 animate-bounce"><i class="fas fa-crown text-2xl drop-shadow-md"></i></div>
                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full mb-3 border-4 shadow-lg bg-white flex items-center justify-center font-black text-2xl" style="border-color: <?php echo htmlspecialchars($ranking[0]['cor'] ?? '#fbbf24'); ?>; color: <?php echo htmlspecialchars($ranking[0]['cor'] ?? '#fbbf24'); ?>;">
                    <?php echo substr($ranking[0]['nome'], 0, 1); ?>
                </div>
                <div class="text-base sm:text-lg font-black mb-1 truncate w-full text-center" style="color: <?php echo htmlspecialchars($ranking[0]['cor'] ?? '#d97706'); ?>"><?php echo htmlspecialchars($ranking[0]['nome']); ?></div>
                <div class="bg-gradient-to-t from-yellow-500 to-yellow-400 w-full rounded-t-xl flex flex-col items-center justify-start pt-4 shadow-xl h-40 sm:h-48 border-t-2 border-yellow-300">
                    <div class="text-yellow-100 mb-1"><i class="fas fa-trophy text-4xl drop-shadow-md"></i></div>
                    <div class="font-black text-2xl text-white drop-shadow-md">1º</div>
                </div>
                <div class="bg-white border-2 border-yellow-400 w-full py-3 text-center rounded-b-xl shadow-md -mt-1 z-20">
                    <div class="font-black text-lg text-yellow-600 leading-none"><?php echo number_format($ranking[0]['pontos'], 0, ',', '.'); ?></div>
                    <div class="text-[10px] text-yellow-500 uppercase font-bold">pts</div>
                </div>
            </div>
        <?php endif; ?>

        <!-- 3rd Place -->
        <?php if (isset($ranking[2])): ?>
            <div class="flex flex-col items-center w-1/3 max-w-[140px] relative z-10 group">
                <div class="absolute -top-16 opacity-0 group-hover:opacity-100 transition duration-300 bg-orange-900 text-white text-xs px-2 py-1 rounded">3º Lugar</div>
                <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-full mb-3 border-4 shadow-md bg-white flex items-center justify-center font-bold text-lg" style="border-color: <?php echo htmlspecialchars($ranking[2]['cor'] ?? '#b45309'); ?>; color: <?php echo htmlspecialchars($ranking[2]['cor'] ?? '#b45309'); ?>;">
                    <?php echo substr($ranking[2]['nome'], 0, 1); ?>
                </div>
                <div class="text-sm sm:text-base font-bold mb-1 truncate w-full text-center" style="color: <?php echo htmlspecialchars($ranking[2]['cor'] ?? '#92400e'); ?>"><?php echo htmlspecialchars($ranking[2]['nome']); ?></div>
                <div class="bg-gradient-to-t from-orange-800 to-orange-700 w-full rounded-t-xl flex flex-col items-center justify-start pt-4 shadow-lg h-24 sm:h-32 border-t border-orange-600">
                    <div class="text-orange-300 mb-1"><i class="fas fa-medal text-3xl drop-shadow-sm"></i></div>
                    <div class="font-black text-lg text-orange-200">3º</div>
                </div>
                <div class="bg-white border border-gray-200 w-full py-2 text-center rounded-b-xl shadow-sm -mt-1 z-20">
                    <div class="font-bold text-sm text-gray-800"><?php echo number_format($ranking[2]['pontos'], 0, ',', '.'); ?></div>
                    <div class="text-[10px] text-gray-400 uppercase font-bold">pts</div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Full Ranking List (Mobile friendly cards) -->
    <div class="card overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h2 class="font-bold text-gray-800">Classificação Completa</h2>
            <i class="fas fa-list-ol text-gray-400"></i>
        </div>

        <div class="mobile-cards">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-white">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-20">Pos</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Grupo</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider hidden sm:table-cell w-1/3">Progresso</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider w-32">Pontuação</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-50">
                    <?php foreach ($ranking as $index => $grupo): ?>
                        <tr class="hover:bg-gray-50 transition-colors group <?php echo $index < 3 ? 'bg-blue-50/30' : ''; ?>">
                            <td class="px-6 py-4 whitespace-nowrap" data-label="Posição">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm
                                    <?php
                                        if($index == 0) echo 'bg-yellow-100 text-yellow-700 shadow-sm border border-yellow-200';
                                        elseif($index == 1) echo 'bg-gray-200 text-gray-700 shadow-sm border border-gray-300';
                                        elseif($index == 2) echo 'bg-orange-100 text-orange-800 shadow-sm border border-orange-200';
                                        else echo 'bg-gray-100 text-gray-500';
                                    ?>
                                ">
                                    <?php echo $index + 1; ?>º
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap" data-label="Grupo">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full mr-4 border-2 shadow-sm flex-shrink-0" style="background-color: <?php echo htmlspecialchars($grupo['cor'] ?? '#cbd5e1'); ?>; border-color: white;"></div>
                                    <div>
                                        <div class="text-sm font-bold text-gray-900"><?php echo htmlspecialchars($grupo['nome']); ?></div>
                                        <?php if(isset($grupo['membros_count'])): ?>
                                            <div class="text-[10px] text-gray-500 uppercase tracking-wider"><?php echo $grupo['membros_count']; ?> membros</div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 hidden sm:table-cell align-middle">
                                <?php $percent = $maxPontos > 0 ? min(100, max(0, ($grupo['pontos'] / $maxPontos) * 100)) : 0; ?>
                                <div class="w-full bg-gray-100 rounded-full h-2.5 shadow-inner overflow-hidden">
                                    <div class="h-2.5 rounded-full transition-all duration-1000 ease-out" style="width: <?php echo $percent; ?>%; background-color: <?php echo htmlspecialchars($grupo['cor'] ?? '#3b82f6'); ?>"></div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right" data-label="Pontos">
                                <div class="text-base font-black text-gray-900 group-hover:scale-110 transition-transform origin-right">
                                    <?php echo number_format($grupo['pontos'], 0, ',', '.'); ?> <span class="text-xs text-gray-400 font-bold uppercase">pts</span>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php else: ?>
    <div class="text-center py-16 card">
        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-trophy text-4xl text-gray-300"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-800">Campeonato não iniciado</h3>
        <p class="mt-2 text-gray-500 max-w-md mx-auto">Nenhum grupo cadastrado ou com pontuação até o momento. Volte em breve!</p>
    </div>
<?php endif; ?>
