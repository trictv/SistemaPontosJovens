<div class="mb-8 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-800">Painel Administrativo</h1>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm uppercase font-bold">Grupos</p>
                <p class="text-3xl font-bold text-gray-800"><?php echo count($grupos); ?></p>
            </div>
            <i class="fas fa-users text-4xl text-blue-200"></i>
        </div>
        <div class="mt-4">
            <a href="/admin/grupos" class="text-blue-600 text-sm font-medium hover:underline">Gerenciar grupos &rarr;</a>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm uppercase font-bold">Atividades</p>
                <p class="text-3xl font-bold text-gray-800"><?php echo count($atividades); ?></p>
            </div>
            <i class="fas fa-tasks text-4xl text-green-200"></i>
        </div>
        <div class="mt-4">
            <a href="/admin/atividades" class="text-green-600 text-sm font-medium hover:underline">Gerenciar atividades &rarr;</a>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-purple-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm uppercase font-bold">Usuários</p>
                <p class="text-3xl font-bold text-gray-800"><?php echo count($usuarios); ?></p>
            </div>
            <i class="fas fa-user-shield text-4xl text-purple-200"></i>
        </div>
        <div class="mt-4">
            <a href="/admin/usuarios" class="text-purple-600 text-sm font-medium hover:underline">Gerenciar usuários &rarr;</a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Configurações Rápidas -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Ações Rápidas</h2>
        <div class="grid grid-cols-2 gap-4">
            <a href="/admin/pontuacao" class="flex flex-col items-center justify-center p-4 bg-yellow-50 rounded-lg border border-yellow-100 hover:bg-yellow-100 transition">
                <i class="fas fa-star text-2xl text-yellow-500 mb-2"></i>
                <span class="text-sm font-medium text-yellow-800">Lançar Bônus</span>
            </a>
        </div>
    </div>

    <!-- Histórico Recente -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Últimas Ações</h2>
        <div class="space-y-4">
            <?php foreach ($historico as $log): ?>
                <div class="flex items-start border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                    <div class="flex-shrink-0 mt-1">
                        <i class="fas fa-history text-gray-400"></i>
                    </div>
                    <div class="ml-3 w-full">
                        <p class="text-sm text-gray-800">
                            <span class="font-bold"><?php echo htmlspecialchars($log['usuario_nome'] ?? 'Sistema'); ?></span>
                            <?php echo htmlspecialchars($log['acao']); ?>
                        </p>
                        <div class="flex justify-between items-center mt-1">
                            <span class="text-xs text-gray-500"><?php echo date('d/m/Y H:i', strtotime($log['data'])); ?></span>
                            <?php if ($log['valor'] !== null): ?>
                                <span class="text-xs font-bold <?php echo $log['valor'] > 0 ? 'text-green-600' : 'text-red-600'; ?>">
                                    <?php echo $log['valor'] > 0 ? '+' : ''; ?><?php echo $log['valor']; ?> pts
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if (empty($historico)): ?>
                <p class="text-sm text-gray-500 italic">Nenhum registro encontrado.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
