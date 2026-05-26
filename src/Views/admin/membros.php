<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <h1 class="text-2xl font-bold text-gray-800">Gerenciar Membros</h1>

    <form action="" method="GET" class="flex w-full sm:w-auto">
        <input type="text" name="q" value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>" placeholder="Buscar por nome ou grupo..." class="flex-1 px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
        <button type="submit" class="bg-gray-100 text-gray-600 px-4 py-2 rounded-r-md border border-l-0 border-gray-300 hover:bg-gray-200">
            <i class="fas fa-search"></i>
        </button>
    </form>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4" id="formTitle">Novo Membro</h2>
            <form action="/admin/membros/criar" method="POST" id="membroForm">
                <input type="hidden" name="id" id="membroId">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome Completo</label>
                    <input type="text" name="nome" id="membroNome" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Grupo</label>
                    <select name="grupo_id" id="membroGrupo" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        <option value="">Selecione o grupo...</option>
                        <?php foreach ($grupos as $g): ?>
                            <option value="<?php echo $g['id']; ?>"><?php echo htmlspecialchars($g['nome']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" id="membroStatus" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        <option value="ativo">Ativo</option>
                        <option value="inativo">Inativo</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700" id="btnSalvar">
                        Salvar
                    </button>
                    <button type="button" onclick="cancelarEdicao()" class="hidden flex-1 bg-gray-500 text-white font-bold py-2 px-4 rounded hover:bg-gray-600" id="btnCancelar">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-2">Estatísticas</h3>
            <p class="text-3xl font-bold text-gray-800"><?php echo count($membros); ?></p>
            <p class="text-sm text-gray-500 mt-1">Total de membros no sistema</p>
        </div>
    </div>

    <div class="lg:col-span-3">
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Membro</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grupo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cadastro</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($membros as $membro): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($membro['nome']); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?php echo htmlspecialchars($membro['grupo_nome']); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $membro['status'] === 'ativo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                        <?php echo ucfirst($membro['status']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo date('d/m/Y', strtotime($membro['data_cadastro'])); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button onclick="editarMembro(<?php echo htmlspecialchars(json_encode([
                                        'id' => $membro['id'],
                                        'nome' => $membro['nome'],
                                        'grupo_id' => $membro['grupo_id'],
                                        'status' => $membro['status']
                                    ])); ?>)" class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <form action="/admin/membros/excluir" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir este membro?');">
                                        <input type="hidden" name="id" value="<?php echo $membro['id']; ?>">
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($membros)): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Nenhum membro encontrado.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function editarMembro(membro) {
    document.getElementById('formTitle').innerText = 'Editar Membro';
    document.getElementById('membroForm').action = '/admin/membros/editar';

    document.getElementById('membroId').value = membro.id;
    document.getElementById('membroNome').value = membro.nome;
    document.getElementById('membroGrupo').value = membro.grupo_id;
    document.getElementById('membroStatus').value = membro.status;

    document.getElementById('btnCancelar').classList.remove('hidden');
    document.getElementById('membroNome').focus();
}

function cancelarEdicao() {
    document.getElementById('formTitle').innerText = 'Novo Membro';
    document.getElementById('membroForm').action = '/admin/membros/criar';

    document.getElementById('membroForm').reset();
    document.getElementById('membroId').value = '';

    document.getElementById('btnCancelar').classList.add('hidden');
}
</script>
