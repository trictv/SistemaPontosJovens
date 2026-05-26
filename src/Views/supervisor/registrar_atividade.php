<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-800">Registrar Atividades do Culto/Encontro</h1>
    <a href="/supervisor" class="text-gray-500 hover:text-gray-700">Voltar</a>
</div>

<form action="/supervisor/salvar-registro" method="POST" class="space-y-6">
    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
        <label class="block text-sm font-bold text-gray-700 mb-2">Data do Encontro</label>
        <input type="datetime-local" name="data" value="<?php echo date('Y-m-d\TH:i'); ?>" required class="w-full md:w-1/3 px-3 py-2 border border-gray-300 rounded-md">
    </div>

    <?php foreach ($atividades as $atividade): ?>
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-800"><?php echo htmlspecialchars($atividade['nome']); ?></h3>
                    <p class="text-sm text-gray-500">
                        <?php echo $atividade['tipo_pontuacao'] === 'proporcional' ? "Até {$atividade['pontos']} pts (proporcional)" : "{$atividade['pontos']} pts por item"; ?>
                    </p>
                </div>
            </div>

            <?php if ($atividade['tipo_entrada'] === 'check_membros'): ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    <?php foreach ($membros as $membro): ?>
                        <label class="flex items-center space-x-3 p-3 border rounded-md hover:bg-gray-50 cursor-pointer">
                            <input type="checkbox" name="atividades[<?php echo $atividade['id']; ?>][membros][]" value="<?php echo $membro['id']; ?>" class="h-5 w-5 text-blue-600 border-gray-300 rounded">
                            <span class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($membro['nome']); ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>

            <?php elseif ($atividade['tipo_entrada'] === 'quantidade'): ?>
                <div class="flex items-center space-x-4">
                    <label class="text-sm font-medium text-gray-700">Quantidade:</label>
                    <input type="number" min="0" name="atividades[<?php echo $atividade['id']; ?>][quantidade]" class="w-24 px-3 py-2 border border-gray-300 rounded-md text-center" placeholder="0">
                </div>

            <?php elseif ($atividade['tipo_entrada'] === 'lista_nomes'): ?>
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Lista de Nomes (um por linha)</label>
                    <textarea name="atividades[<?php echo $atividade['id']; ?>][visitantes]" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="João da Silva&#10;Maria Santos"></textarea>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
        <label class="block text-sm font-bold text-gray-700 mb-2">Observações (Opcional)</label>
        <textarea name="observacoes" rows="3" maxlength="1000" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="João trouxe dois visitantes e o grupo participou da oração antes do culto..."></textarea>
        <p class="text-xs text-gray-500 mt-1">Máximo 1000 caracteres. Visível no histórico público.</p>
    </div>

    <div class="sticky bottom-4 bg-white p-4 rounded-lg shadow-lg border border-gray-200 flex justify-between items-center z-10">
        <p class="text-sm text-gray-500">Revise os dados antes de salvar. Os cálculos serão feitos automaticamente.</p>
        <button type="submit" class="bg-green-600 text-white font-bold py-3 px-8 rounded-md hover:bg-green-700 shadow-md text-lg">
            Salvar Lançamentos
        </button>
    </div>
</form>
