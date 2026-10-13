<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Dashboard - Sistema</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">
    <div class="max-w-4xl mx-auto p-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Dashboard</h1>

        <div class="bg-white rounded-xl shadow p-6">
            <p class="text-lg text-gray-700">
                Bem-vindo, <strong><?php echo $_SESSION['user_nome']; ?></strong>!
            </p>
            <p class="text-sm text-gray-500 mt-2">
                Voce esta logado. Perfil ID: <?php echo $_SESSION['user_perfil']; ?>
            </p>

            <a href="/logout" class="inline-block mt-6 text-red-600 hover:underline">
                Sair
            </a>
        </div>
        <div class="flex gap-4 mt-6">
            <a href="/usuarios" class="text-blue-600 hover:underline">Usuários</a>
            <a href="/perfis" class="text-blue-600 hover:underline">Perfis</a>
            <a href="/permissoes" class="text-blue-600 hover:underline">Permissões</a>
        </div>
    </div>
</body>

</html>