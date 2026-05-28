<div class="mb-6 flex items-center gap-3">
    <a href="/supervisor" class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm border border-gray-200 text-gray-600 hover:text-blue-600 hover:border-blue-300 transition">
        <i class="fas fa-arrow-left"></i>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Registrar Atividades</h1>
        <p class="text-xs text-gray-500 uppercase tracking-wider font-bold">Culto / Encontro / Escola Bíblica</p>
    </div>
</div>

<form action="/supervisor/salvar-registro" method="POST" class="pb-24">
    <!-- Seção 1: Informações Gerais -->
    <div class="card p-6 mb-6">
        <div class="flex items-center gap-2 mb-4 text-blue-600">
            <i class="fas fa-info-circle"></i>
            <h2 class="text-lg font-bold text-gray-800">Informações Básicas</h2>
        </div>

        <div class="mb-2">
            <label class="block text-sm font-bold text-gray-700 mb-2">Data e Hora do Encontro</label>
            <input type="datetime-local" name="data" value="<?php echo date('Y-m-d\TH:i'); ?>" required class="w-full sm:w-1/2">
        </div>
    </div>

    <!-- Seção 2: Atividades Dinâmicas -->
    <?php foreach ($atividades as $index => $atividade): ?>
        <div class="card p-6 mb-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        <span class="bg-blue-100 text-blue-600 text-xs w-6 h-6 rounded-full flex items-center justify-center font-black"><?php echo $index + 1; ?></span>
                        <?php echo htmlspecialchars($atividade['nome']); ?>
                    </h3>
                    <p class="text-sm text-gray-500 mt-1 ml-8">
                        <span class="inline-block bg-gray-100 text-gray-600 text-[10px] uppercase font-bold px-2 py-0.5 rounded">
                            <?php echo $atividade['tipo_pontuacao'] === 'proporcional' ? "Até " . number_format($atividade['pontos'], 2, ',', '.') . " pts (Proporcional)" : number_format($atividade['pontos'], 2, ',', '.') . " pts/item"; ?>
                        </span>
                    </p>
                </div>
            </div>

            <div class="ml-0 sm:ml-8 mt-4">
                <?php if ($atividade['tipo_entrada'] === 'check_membros'): ?>

                    <?php
                        $membrosAtivos = array_filter($membros, fn($m) => $m['status'] == 'ativo');
                    ?>
                    <div class="bg-gray-50 p-3 rounded-lg mb-3 flex justify-between items-center text-sm border border-gray-200">
                        <span class="font-medium text-gray-700">Selecione quem participou:</span>
                        <span class="text-xs text-gray-500"><?php echo count($membrosAtivos); ?> membros ativos</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        <?php foreach ($membrosAtivos as $membro): ?>
                            <label class="checkbox-wrapper cursor-pointer shadow-sm">
                                <input type="checkbox" name="atividades[<?php echo $atividade['id']; ?>][membros][]" value="<?php echo $membro['id']; ?>">
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-gray-800"><?php echo htmlspecialchars($membro['nome']); ?></span>
                                </div>
                            </label>
                        <?php endforeach; ?>
                        <?php if (empty($membrosAtivos)): ?>
                            <p class="text-sm text-red-500 italic p-2">Você precisa cadastrar membros ativos no grupo primeiro.</p>
                        <?php endif; ?>
                    </div>

                <?php elseif ($atividade['tipo_entrada'] === 'quantidade'): ?>
                    <div class="flex items-center gap-4 bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-700">Informe a quantidade total:</p>
                        </div>
                        <input type="number" min="0" name="atividades[<?php echo $atividade['id']; ?>][quantidade]" class="w-24 text-center font-bold text-lg" placeholder="0">
                    </div>

                <?php elseif ($atividade['tipo_entrada'] === 'lista_nomes'): ?>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Digite os nomes (um por linha):</label>
                        <textarea name="atividades[<?php echo $atividade['id']; ?>][visitantes]" rows="4" class="font-mono text-sm" placeholder="João da Silva&#10;Maria Santos"></textarea>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Seção 3: Observações Livres -->
    <div class="card p-6 mb-6 border-l-4 border-l-yellow-400">
        <div class="flex items-center gap-2 mb-4 text-yellow-600">
            <i class="fas fa-comment-alt"></i>
            <h2 class="text-lg font-bold text-gray-800">Observações (Opcional)</h2>
        </div>
        <textarea name="observacoes" rows="3" maxlength="1000" placeholder="Ex: O grupo evangelizou na praça antes do culto..." class="bg-yellow-50 focus:bg-white border-yellow-200 focus:border-yellow-400"></textarea>
        <p class="text-xs text-gray-500 mt-2 text-right">Máximo 1000 caracteres. Ficará visível no histórico público.</p>
    </div>

    <!-- Barra de ação inferior fixa (Mobile Friendly) -->
    <div class="fixed bottom-[70px] sm:bottom-0 left-0 w-full bg-white border-t border-gray-200 p-4 z-30 shadow-[0_-10px_15px_-3px_rgba(0,0,0,0.1)]">
        <div class="max-w-7xl mx-auto flex justify-between items-center px-2 sm:px-6 lg:px-8">
            <div class="hidden sm:block">
                <p class="text-sm font-bold text-gray-800">Finalizar Registro</p>
                <p class="text-xs text-gray-500">Revise os dados informados.</p>
            </div>
            <button type="submit" class="w-full sm:w-auto btn-primary bg-green-600 hover:bg-green-700 shadow-lg text-lg">
                <i class="fas fa-save"></i> Salvar e Lançar Pontos
            </button>
        </div>
    </div>
</form>
