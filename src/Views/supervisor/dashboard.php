<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Meu Grupo</h1>
        <p class="text-gray-500 text-sm">Olá, <?php echo htmlspecialchars($_SESSION['user_nome']); ?></p>
    </div>
    <!-- Desktop add button -->
    <a href="/supervisor/registrar" class="hidden sm:inline-flex btn-primary shadow-lg hover:shadow-xl">
        <i class="fas fa-plus"></i> Registrar Atividades
    </a>
</div>

<!-- Mobile FAB -->
<a href="/supervisor/registrar" class="sm:hidden fab shadow-lg active:scale-95 transition-transform" aria-label="Registrar Atividades">
    <i class="fas fa-plus"></i>
</a>

<!-- Group Hero Card -->
<div class="card overflow-hidden mb-8 relative">
    <div class="h-24 w-full" style="background-color: <?php echo htmlspecialchars($grupo['cor']); ?>; opacity: 0.85;"></div>

    <div class="px-6 pb-6 relative">
        <div class="flex justify-between items-end -mt-12 mb-4">
            <?php if ($grupo['foto']): ?>
                <img src="<?php echo htmlspecialchars($grupo['foto']); ?>" class="w-24 h-24 rounded-xl object-cover border-4 border-white shadow-sm bg-white">
            <?php else: ?>
                <div class="w-24 h-24 rounded-xl flex items-center justify-center text-white text-4xl font-bold border-4 border-white shadow-sm" style="background-color: <?php echo htmlspecialchars($grupo['cor']); ?>">
                    <?php echo substr($grupo['nome'], 0, 1); ?>
                </div>
            <?php endif; ?>

            <div class="text-center bg-blue-50 border border-blue-100 px-4 py-2 rounded-lg shadow-sm">
                <p class="text-xs text-blue-600 uppercase font-bold tracking-wider mb-0.5">Pontos</p>
                <p class="text-3xl font-extrabold text-blue-700 leading-none">
                    <?php echo number_format($grupo['pontos'], 0, ',', '.'); ?>
                </p>
            </div>
        </div>

        <h2 class="text-2xl font-bold text-gray-800"><?php echo htmlspecialchars($grupo['nome']); ?></h2>
        <?php if ($grupo['versiculo']): ?>
            <p class="text-gray-500 italic mt-2 text-sm bg-gray-50 p-3 rounded-lg border border-gray-100">
                <i class="fas fa-quote-left text-gray-300 mr-1"></i> <?php echo htmlspecialchars($grupo['versiculo']); ?>
            </p>
        <?php endif; ?>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Membros Quick Info -->
    <div class="card p-0 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-users text-blue-500"></i> Membros (<?php echo count($membros); ?>)
            </h3>
            <a href="/supervisor/membros" class="text-sm font-bold text-blue-600 hover:text-blue-800 bg-blue-50 px-3 py-1 rounded-full transition">
                Gerenciar
            </a>
        </div>

        <div class="p-6">
            <!-- Form to add member directly -->
            <form action="/supervisor/membro/adicionar" method="POST" class="mb-4 flex gap-2">
                <input type="text" name="nome" placeholder="Adicionar novo membro..." required class="flex-1 text-sm bg-gray-50 focus:bg-white transition-colors">
                <button type="submit" class="bg-blue-600 text-white px-4 rounded-lg hover:bg-blue-700 font-medium active:bg-blue-800 transition">
                    <i class="fas fa-plus sm:mr-1"></i><span class="hidden sm:inline">Adicionar</span>
                </button>
            </form>

            <ul class="divide-y divide-gray-50 max-h-60 overflow-y-auto pr-2">
                <?php foreach (array_slice($membros, 0, 5) as $membro): ?>
                    <li class="py-3 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xs shadow-inner">
                                <?php echo substr($membro['nome'], 0, 1); ?>
                            </div>
                            <span class="text-sm font-medium text-gray-800"><?php echo htmlspecialchars($membro['nome']); ?></span>
                        </div>
                        <?php if($membro['status'] == 'inativo'): ?>
                            <span class="text-[10px] bg-red-50 text-red-600 px-2 py-0.5 rounded-full font-bold uppercase">Inativo</span>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
                <?php if (count($membros) > 5): ?>
                    <li class="py-3 text-center">
                        <a href="/supervisor/membros" class="text-sm text-blue-500 font-medium hover:underline">Ver todos os <?php echo count($membros); ?> membros...</a>
                    </li>
                <?php endif; ?>
                <?php if (empty($membros)): ?>
                    <li class="py-4 text-sm text-gray-500 italic text-center">O grupo ainda não possui membros.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <!-- Atividades Quick Info -->
    <div class="card p-0 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-clipboard-list text-green-500"></i> Últimos Registros
            </h3>
        </div>

        <ul class="divide-y divide-gray-50 p-2">
            <?php foreach (array_slice($registros, 0, 5) as $registro): ?>
                <li class="p-4 hover:bg-gray-50 rounded-lg transition-colors group cursor-pointer">
                    <div class="flex items-start gap-4">
                        <div class="bg-green-100 text-green-600 w-10 h-10 rounded-full flex items-center justify-center shrink-0">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-gray-800">
                                Culto / Encontro
                            </p>
                            <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                <i class="far fa-calendar-alt"></i> <?php echo date('d/m/Y \à\s H:i', strtotime($registro['data'])); ?>
                            </p>
                            <p class="text-[10px] text-gray-400 mt-1">Por <?php echo htmlspecialchars($registro['criador']); ?></p>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
            <?php if (empty($registros)): ?>
                <li class="p-6 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-folder-open text-2xl text-gray-400"></i>
                    </div>
                    <p class="text-sm text-gray-500 mb-4">Nenhuma atividade foi registrada ainda.</p>
                    <a href="/supervisor/registrar" class="text-sm font-bold text-blue-600 hover:underline">Registrar primeiro encontro</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</div>
