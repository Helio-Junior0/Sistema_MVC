<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Usuarios - Sistema</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">
    <div class="max-w-5xl mx-auto p-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Usuários</h1>
            <a href="/usuarios/criarusuario" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Novo Usuário
            </a>
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
                            <td class="p-4 text-gray-700"><?php echo $usuario['nome']; ?></td>
                            <td class="p-4 text-gray-700"><?php echo $usuario['email']; ?></td>
                            <td class="p-4 text-gray-700"><?php echo $usuario['cpf_cnpj']; ?></td>
                            <td class="p-4 text-gray-700"><?php echo $usuario['perfil']; ?></td>
                            <td class="p-4 text-gray-700"><?php echo $usuario['ativo'] ? 'Sim' : 'Não'; ?></td>
                            <td class="p-4">
                                <a href="/usuarios/editar/<?php echo $usuario['uuid']; ?>" class="text-blue-600 hover:underline mr-4">Editar</a>
                                <a href="/usuarios/excluir/<?php echo $usuario['uuid']; ?>" class="text-red-600 hover:underline" onclick="return confirm('Tem certeza que deseja excluir este usuário?');">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <a href="/dashboard" class="inline-block mt-6 text-gray-600 hover:underline">Voltar</a>
    </div>