<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Usuários</h1>
        <div class="flex gap-3">
        <a href="/usuarios/pdf" target="_blank" class="bg-gray-700 text-white px-4 py-2 rounded-lg hover:bg-gray-800">
            Gerar PDF
        </a>
        <a href="/usuarios/criarusuario" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Novo Usuário
        </a>
    </div>
</div>

<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="text-left p-4 text-sm font-semibold text-gray-600">Nome</th>
                <th class="text-left p-4 text-sm font-semibold text-gray-600">E-mail</th>
                <th class="text-left p-4 text-sm font-semibold text-gray-600">CPF/CNPJ</th>
                <th class="text-left p-4 text-sm font-semibold text-gray-600">Perfil</th>
                <th class="text-left p-4 text-sm font-semibold text-gray-600">Ativo</th>
                <th class="text-left p-4 text-sm font-semibold text-gray-600">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario): ?>
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-4 text-gray-700"><?php echo htmlspecialchars($usuario['nome']); ?></td>
                    <td class="p-4 text-gray-700"><?php echo htmlspecialchars($usuario['email'] ?? ''); ?></td>
                    <td class="p-4 text-gray-700"><?php echo htmlspecialchars($usuario['cpf_cnpj']); ?></td>
                    <td class="p-4 text-gray-700"><?php echo htmlspecialchars($usuario['perfil']); ?></td>
                    <td class="p-4 text-gray-700"><?php echo $usuario['ativo'] ? 'Sim' : 'Não'; ?></td>
                    <td class="p-4">
                        <a href="/usuarios/editar/<?php echo $usuario['uuid']; ?>" class="text-blue-600 hover:underline mr-4">Editar</a>
                        <a href="/usuarios/excluir/<?php echo $usuario['uuid']; ?>" class="text-red-600 hover:underline"
                           onclick="return confirm('Tem certeza que deseja excluir este usuário?');">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>