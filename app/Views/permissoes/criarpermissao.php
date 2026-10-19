<div class="max-w-2xl">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Nova Permissão</h1>

    <div class="bg-white rounded-xl shadow p-6">
        <form action="/permissoes/criarpermissao" method="POST" class="space-y-4">

            <?php echo csrf_field(); ?>
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Chave</label>
                <input type="text" name="chave" required placeholder="ex: usuarios.gerenciar"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Nome de exibição</label>
                <input type="text" name="nome_exibicao" required placeholder="ex: Gerenciar Usuários"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Grupo</label>
                <input type="text" name="grupo" placeholder="ex: Administração"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500">
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700">Salvar</button>
                <a href="/permissoes" class="px-5 py-2 rounded-lg text-gray-600 hover:bg-gray-100">Cancelar</a>
            </div>
        </form>
    </div>
</div>