<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Permissões - Sistema</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">
    <div class="max-w-4xl mx-auto p-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Permissões</h1>
            <a href="/permissoes/criarpermissao" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Nova Permissão
            </a>
        </div>

        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="text-left p-4 text-sm font-semibold text-gray-600">Grupo</th>
                        <th class="text-left p-4 text-sm font-semibold text-gray-600">Chave</th>
                        <th class="text-left p-4 text-sm font-semibold text-gray-600">Nome de exibição</th>
                        <th class="text-left p-4 text-sm font-semibold text-gray-600">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($permissoes as $permissao): ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-4 text-gray-700"><?php echo htmlspecialchars($permissao['grupo'] ?? ''); ?></td>
                            <td class="p-4 text-gray-700 font-mono text-sm"><?php echo htmlspecialchars($permissao['chave']); ?></td>
                            <td class="p-4 text-gray-700"><?php echo htmlspecialchars($permissao['nome_exibicao']); ?></td>
                            <td class="p-4">
                                <a href="/permissoes/editar/<?php echo $permissao['uuid']; ?>" class="text-blue-600 hover:underline mr-4">Editar</a>
                                <form action="/permissoes/excluir/<?php echo $permissao['uuid']; ?>" method="POST" class="inline"
                                    onsubmit="return confirm('Excluir esta permissão vai removê-la de todos os perfis. Continuar?');">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="text-red-600 hover:underline">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <a href="/dashboard" class="inline-block mt-6 text-gray-600 hover:underline">Voltar</a>
    </div>
</body>

</html>