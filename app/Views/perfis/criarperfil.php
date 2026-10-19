<div class="max-w-2xl">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Novo Perfil</h1>

    <div class="bg-white rounded-xl shadow p-6">
        <form action="/perfis/criarperfil" method="POST" class="space-y-4">

        <?php echo csrf_field(); ?>
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Nome</label>
                <input type="text" name="nome" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Descrição</label>
                <textarea name="descricao" rows="3"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500"></textarea>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="ativo" id="ativo" checked class="w-4 h-4">
                <label for="ativo" class="text-sm text-gray-700">Ativo</label>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700">Salvar</button>
                <a href="/perfis" class="px-5 py-2 rounded-lg text-gray-600 hover:bg-gray-100">Cancelar</a>
            </div>
        </form>
    </div>
</div>