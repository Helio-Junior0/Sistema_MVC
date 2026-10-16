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
                <?php $notif = notificacoes_nao_lidas(); ?>
                <details class="relative">
                    <summary class="list-none [&::-webkit-details-marker]:hidden cursor-pointer relative text-gray-500 hover:text-gray-700" title="Notificações">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <?php if ($notif['total'] > 0): ?>
                            <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                                <?php echo $notif['total'] > 9 ? '9+' : $notif['total']; ?>
                            </span>
                        <?php endif; ?>
                    </summary>

                    <div class="absolute right-0 mt-3 w-80 bg-white rounded-xl shadow-lg border border-gray-100 z-20">
                        <div class="px-4 py-3 border-b font-semibold text-gray-700">Notificações</div>

                        <?php if (empty($notif['itens'])): ?>
                            <p class="px-4 py-6 text-sm text-gray-500 text-center">Nenhuma notificação nova.</p>
                        <?php else: ?>
                            <?php foreach ($notif['itens'] as $n): ?>
                                <a href="/notificacoes/abrir/<?php echo $n['uuid']; ?>"
                                    class="block px-4 py-3 border-b last:border-0 hover:bg-gray-50">
                                    <p class="text-sm font-semibold text-gray-800"><?php echo htmlspecialchars($n['titulo']); ?></p>
                                    <p class="text-sm text-gray-600 truncate"><?php echo htmlspecialchars($n['mensagem']); ?></p>
                                    <p class="text-xs text-gray-400 mt-1"><?php echo date('d/m/Y H:i', strtotime($n['criado_em'])); ?></p>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </details>

                <a href="/meu-perfil" class="text-sm text-gray-700 hover:text-blue-600">
                    <?php echo htmlspecialchars($_SESSION['user_nome'] ?? ''); ?>
                </a>

                <a href="/logout" class="text-sm text-red-600 hover:underline">Sair</a>
            </header>

            <main class="flex-1 p-8">
                <?php echo $conteudo; ?>
            </main>
        </div>
    </div>
</body>

</html>