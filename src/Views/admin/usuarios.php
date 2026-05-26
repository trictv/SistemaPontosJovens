<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-800">Gerenciar Usuários</h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Novo Usuário</h2>
            <form action="/admin/usuarios/criar" method="POST">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
                    <input type="text" name="nome" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
                    <input type="email" name="email" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Senha Inicial</label>
                    <input type="password" name="senha" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Acesso</label>
                    <select name="tipo" id="tipoSelect" onchange="toggleGrupoSelect()" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        <option value="supervisor">Supervisor de Grupo</option>
                        <option value="admin">Administrador Geral</option>
                    </select>
                </div>
                <div class="mb-4" id="grupoDiv">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Grupo Vinculado</label>
                    <select name="grupo_id" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        <option value="">Selecione um grupo...</option>
                        <?php foreach ($grupos as $g): ?>
                            <option value="<?php echo $g['id']; ?>"><?php echo htmlspecialchars($g['nome']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">
                    Salvar Usuário
                </button>
            </form>
        </div>
    </div>

    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nome / E-mail</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Grupo</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($usuarios as $user): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($user['nome']); ?></div>
                                <div class="text-sm text-gray-500"><?php echo htmlspecialchars($user['email']); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $user['tipo'] === 'admin' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'; ?>">
                                    <?php echo ucfirst($user['tipo']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo htmlspecialchars($user['grupo_nome'] ?? '-'); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function toggleGrupoSelect() {
    const tipo = document.getElementById('tipoSelect').value;
    const grupoDiv = document.getElementById('grupoDiv');
    if (tipo === 'admin') {
        grupoDiv.style.display = 'none';
    } else {
        grupoDiv.style.display = 'block';
    }
}
document.addEventListener('DOMContentLoaded', toggleGrupoSelect);
</script>
