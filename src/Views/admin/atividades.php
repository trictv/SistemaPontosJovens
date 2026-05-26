<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-800">Gerenciar Atividades</h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Nova Atividade</h2>
            <form action="/admin/atividades/criar" method="POST">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome da Atividade</label>
                    <input type="text" name="nome" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Pontuação</label>
                    <select name="tipo_pontuacao" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        <option value="fixo">Fixo (ex: 50 pts por ação)</option>
                        <option value="proporcional">Proporcional (ex: % de presença)</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Entrada</label>
                    <select name="tipo_entrada" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        <option value="check_membros">Check de Membros (Lista com checkbox)</option>
                        <option value="lista_nomes">Lista de Nomes (Digitados 1 a 1)</option>
                        <option value="quantidade">Quantidade Numérica (ex: nº de bíblias)</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pontos Base</label>
                    <input type="number" name="pontos" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">
                    Salvar Atividade
                </button>
            </form>
        </div>
    </div>

    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Atividade</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pontuação</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Entrada</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Pontos</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($atividades as $atividade): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <?php echo htmlspecialchars($atividade['nome']); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo ucfirst($atividade['tipo_pontuacao']); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo str_replace('_', ' ', ucfirst($atividade['tipo_entrada'])); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-blue-600">
                                <?php echo $atividade['pontos']; ?> pts
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
