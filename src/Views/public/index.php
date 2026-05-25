<div class="mb-8 bg-white rounded-lg shadow-sm p-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Ranking Geral</h1>
        <p class="text-gray-500">Acompanhe a pontuação dos grupos em tempo real</p>
    </div>
    <div class="text-right">
        <div class="text-3xl font-bold text-blue-600"><?php echo $dias_restantes; ?></div>
        <div class="text-sm text-gray-500 uppercase tracking-wide">Dias Restantes</div>
    </div>
</div>

<?php if (!empty($ranking)): ?>
    <!-- Podium -->
    <div class="flex justify-center items-end gap-4 mb-12 h-64">
        <?php if (isset($ranking[1])): ?>
            <div class="flex flex-col items-center w-1/3 max-w-xs">
                <div class="text-lg font-bold mb-2 truncate w-full text-center" style="color: <?php echo htmlspecialchars($ranking[1]['cor'] ?? '#666'); ?>"><?php echo htmlspecialchars($ranking[1]['nome']); ?></div>
                <div class="bg-gray-300 w-full rounded-t-lg flex items-center justify-center font-bold text-2xl h-32 relative">
                    <span class="absolute -top-6 text-gray-500"><i class="fas fa-medal text-3xl"></i></span>
                    2º
                </div>
                <div class="font-bold mt-2 text-xl"><?php echo number_format($ranking[1]['pontos'], 0, ',', '.'); ?> pts</div>
            </div>
        <?php endif; ?>

        <?php if (isset($ranking[0])): ?>
            <div class="flex flex-col items-center w-1/3 max-w-xs">
                <div class="text-xl font-bold mb-2 truncate w-full text-center" style="color: <?php echo htmlspecialchars($ranking[0]['cor'] ?? '#fbbf24'); ?>"><?php echo htmlspecialchars($ranking[0]['nome']); ?></div>
                <div class="bg-yellow-400 w-full rounded-t-lg flex items-center justify-center font-bold text-3xl text-white h-48 relative shadow-lg z-10">
                    <span class="absolute -top-8 text-yellow-500"><i class="fas fa-trophy text-5xl"></i></span>
                    1º
                </div>
                <div class="font-bold mt-2 text-2xl text-yellow-600"><?php echo number_format($ranking[0]['pontos'], 0, ',', '.'); ?> pts</div>
            </div>
        <?php endif; ?>

        <?php if (isset($ranking[2])): ?>
            <div class="flex flex-col items-center w-1/3 max-w-xs">
                <div class="text-lg font-bold mb-2 truncate w-full text-center" style="color: <?php echo htmlspecialchars($ranking[2]['cor'] ?? '#b45309'); ?>"><?php echo htmlspecialchars($ranking[2]['nome']); ?></div>
                <div class="bg-yellow-700 w-full rounded-t-lg flex items-center justify-center font-bold text-xl text-white h-24 relative">
                    <span class="absolute -top-6 text-yellow-800"><i class="fas fa-medal text-3xl"></i></span>
                    3º
                </div>
                <div class="font-bold mt-2 text-xl"><?php echo number_format($ranking[2]['pontos'], 0, ',', '.'); ?> pts</div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Full Ranking Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Posição</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grupo</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Pontuação</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($ranking as $index => $grupo): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-sm font-bold rounded-full <?php echo $index < 3 ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800'; ?>">
                                <?php echo $index + 1; ?>º
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-4 h-4 rounded-full mr-3" style="background-color: <?php echo htmlspecialchars($grupo['cor'] ?? '#ccc'); ?>"></div>
                                <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($grupo['nome']); ?></div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-gray-900">
                            <?php echo number_format($grupo['pontos'], 0, ',', '.'); ?> pts
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

<?php else: ?>
    <div class="text-center py-12 bg-white rounded-lg shadow">
        <i class="fas fa-info-circle text-4xl text-gray-400 mb-4"></i>
        <h3 class="text-lg font-medium text-gray-900">Nenhum grupo cadastrado</h3>
        <p class="mt-1 text-sm text-gray-500">O campeonato ainda não possui grupos participantes.</p>
    </div>
<?php endif; ?>
