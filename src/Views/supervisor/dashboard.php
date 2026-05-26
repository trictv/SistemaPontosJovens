<div class="mb-8 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Painel do Grupo</h1>
        <p class="text-gray-600">Bem-vindo(a), <?php echo htmlspecialchars($_SESSION['user_nome']); ?></p>
    </div>
    <a href="/supervisor/registrar" class="bg-blue-600 text-white px-4 py-2 rounded-md font-bold hover:bg-blue-700 shadow-sm">
        + Novo Registro
    </a>
</div>

<div class="bg-white rounded-lg shadow-sm p-6 mb-8 flex flex-col md:flex-row items-center gap-6" style="border-top: 4px solid <?php echo htmlspecialchars($grupo['cor']); ?>">
    <?php if ($grupo['foto']): ?>
        <img src="<?php echo htmlspecialchars($grupo['foto']); ?>" class="w-24 h-24 rounded-full object-cover border-4" style="border-color: <?php echo htmlspecialchars($grupo['cor']); ?>">
    <?php else: ?>
        <div class="w-24 h-24 rounded-full flex items-center justify-center text-white text-3xl font-bold" style="background-color: <?php echo htmlspecialchars($grupo['cor']); ?>">
            <?php echo substr($grupo['nome'], 0, 1); ?>
        </div>
    <?php endif; ?>

    <div class="flex-1 text-center md:text-left">
        <h2 class="text-3xl font-bold text-gray-800"><?php echo htmlspecialchars($grupo['nome']); ?></h2>
        <?php if ($grupo['versiculo']): ?>
            <p class="text-gray-500 italic mt-1">"<?php echo htmlspecialchars($grupo['versiculo']); ?>"</p>
        <?php endif; ?>
    </div>

    <div class="text-center bg-gray-50 px-8 py-4 rounded-lg">
        <p class="text-sm text-gray-500 uppercase font-bold tracking-wider">Pontuação Total</p>
        <p class="text-4xl font-bold mt-1" style="color: <?php echo htmlspecialchars($grupo['cor']); ?>">
            <?php echo number_format($grupo['pontos'], 0, ',', '.'); ?>
        </p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800">Membros (<?php echo count($membros); ?>)</h3>
        </div>
        <ul class="divide-y divide-gray-100 max-h-96 overflow-y-auto">
            <?php foreach ($membros as $membro): ?>
                <li class="px-6 py-3 flex items-center">
                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-bold mr-3 text-sm">
                        <?php echo substr($membro['nome'], 0, 1); ?>
                    </div>
                    <span class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($membro['nome']); ?></span>
                </li>
            <?php endforeach; ?>
            <?php if (empty($membros)): ?>
                <li class="px-6 py-4 text-sm text-gray-500 italic text-center">Nenhum membro cadastrado.</li>
            <?php endif; ?>
        </ul>
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-800">Últimos Registros</h3>
        </div>
        <ul class="divide-y divide-gray-100 max-h-96 overflow-y-auto">
            <?php foreach ($registros as $registro): ?>
                <li class="px-6 py-4">
                    <div class="flex justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">
                                Registro em <?php echo date('d/m/Y H:i', strtotime($registro['data'])); ?>
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                Lançado por <?php echo htmlspecialchars($registro['criador']); ?>
                            </p>
                        </div>
                        <a href="/supervisor/registro/<?php echo $registro['id']; ?>" class="text-blue-600 hover:text-blue-900 text-sm font-medium">Detalhes</a>
                    </div>
                </li>
            <?php endforeach; ?>
            <?php if (empty($registros)): ?>
                <li class="px-6 py-4 text-sm text-gray-500 italic text-center">Nenhum registro encontrado.</li>
            <?php endif; ?>
        </ul>
    </div>
</div>
