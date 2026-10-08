<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Perfis - Sistema</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">
    <div class="max-w-4xl mx-auto p-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Perfis</h1>
            <a href="/perfis/criarperfil" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Novo Perfil
            </a>
        </div>

        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="text-left p-4 text-sm font-semibold text-gray-600">ID</th>
                        <th class="text-left p-4 text-sm font-semibold text-gray-600">Nome</th>
                        <th class="text-left p-4 text-sm font-semibold text-gray-600">Descricao</th>
                        <th class="text-left p-4 text-sm font-semibold text-gray-600">Ativo</th>
                        <th class="text-left p-4 text-sm font-semibold text-gray-600">Acoes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($perfis as $perfil): ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-4 text-gray-700"><?php echo $perfil['id']; ?></td>
                            <td class="p-4 text-gray-700"><?php echo $perfil['nome']; ?></td>
                            <td class="p-4 text-gray-700"><?php echo $perfil['descricao']; ?></td>
                            <td class="p-4 text-gray-700"><?php echo $perfil['ativo'] ? 'Sim' : 'Nao'; ?></td>
                            <td class="p-4">
                                <a href="/perfis/editar/<?php echo $perfil['uuid']; ?>" class="text-blue-600 hover:underline mr-4">Editar</a>
                                <a href="/perfis/excluir/<?php echo $perfil['uuid']; ?>" class="text-red-600 hover:underline" onclick="return confirm('Tem certeza que deseja excluir este perfil?');">Excluir</a>
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