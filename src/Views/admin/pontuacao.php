<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Lançamento Manual de Pontos</h1>
    <p class="text-gray-600">Use esta tela para aplicar bônus por gincanas ou penalidades.</p>
</div>

<div class="max-w-2xl bg-white rounded-lg shadow-sm p-6">
    <form action="/admin/pontuacao/lancar" method="POST">
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Grupo</label>
            <select name="grupo_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                <option value="">Selecione o grupo...</option>
                <?php foreach ($grupos as $g): ?>
                    <option value="<?php echo $g['id']; ?>"><?php echo htmlspecialchars($g['nome']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Valor (use negativo para penalidades)</label>
            <input type="number" name="valor" step="0.01" required class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Ex: 50 ou -20">
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Motivo / Descrição</label>
            <textarea name="motivo" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Ex: Vencedor da dinâmica X"></textarea>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 text-white font-bold py-2 px-6 rounded hover:bg-blue-700">
                Confirmar Lançamento
            </button>
        </div>
    </form>
</div>
