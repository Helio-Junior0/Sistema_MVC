<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($titulo ?? 'Sistema'); ?> - Sistema</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex min-h-screen">

        <aside class="w-64 bg-blue-900 text-white flex flex-col">
            <div class="h-16 flex items-center px-6 text-lg font-bold border-b border-blue-800">
                Sistema
            </div>

            <nav class="flex-1 p-3 space-y-1">
                <a href="/dashboard" class="block px-3 py-2 rounded-lg hover:bg-blue-800">Dashboard</a>

                <?php if (tem_permissao('usuarios.gerenciar')): ?>
                    <a href="/usuarios" class="block px-3 py-2 rounded-lg hover:bg-blue-800">Usuários</a>
                <?php endif; ?>

                <?php if (tem_permissao('perfis.gerenciar')): ?>
                    <a href="/perfis" class="block px-3 py-2 rounded-lg hover:bg-blue-800">Perfis</a>
                <?php endif; ?>

                <?php if (tem_permissao('permissoes.gerenciar')): ?>
                    <a href="/permissoes" class="block px-3 py-2 rounded-lg hover:bg-blue-800">Permissões</a>
                <?php endif; ?>
            </nav>
        </aside>

        <div class="flex-1 flex flex-col">

            <header class="h-16 bg-white shadow-sm flex items-center justify-end gap-6 px-6">
                <button class="relative text-gray-500 hover:text-gray-700" title="Notificações">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </button>

                <span class="text-sm text-gray-700">
                    <?php echo htmlspecialchars($_SESSION['user_nome'] ?? ''); ?>
                </span>

                <a href="/logout" class="text-sm text-red-600 hover:underline">Sair</a>
            </header>

            <main class="flex-1 p-8">
                <?php echo $conteudo; ?>
            </main>
        </div>
    </div>
</body>
</html>