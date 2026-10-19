<div class="max-w-2xl space-y-6">
    <h1 class="text-3xl font-bold text-gray-800">Meu perfil</h1>

    <?php if (!empty($_SESSION['sucesso'])): ?>
        <div class="bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3">
            <?php echo htmlspecialchars($_SESSION['sucesso']);
            unset($_SESSION['sucesso']); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['erro'])): ?>
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-lg px-4 py-3">
            <?php echo htmlspecialchars($_SESSION['erro']);
            unset($_SESSION['erro']); ?>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Meus dados</h2>

        <form action="/meu-perfil/dados" method="POST" class="space-y-4">

            <?php echo csrf_field(); ?>
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Nome</label>
                <input type="text" name="nome" required
                    value="<?php echo htmlspecialchars($usuario['nome']); ?>"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">E-mail</label>
                <input type="email" name="email"
                    value="<?php echo htmlspecialchars($usuario['email'] ?? ''); ?>"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-1">CPF/CNPJ</label>
                    <input type="text" disabled value="<?php echo htmlspecialchars($usuario['cpf_cnpj']); ?>"
                        class="w-full border border-gray-200 bg-gray-50 text-gray-500 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-1">Perfil</label>
                    <input type="text" disabled value="<?php echo htmlspecialchars($usuario['perfil']); ?>"
                        class="w-full border border-gray-200 bg-gray-50 text-gray-500 rounded-lg px-3 py-2">
                </div>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700">Salvar dados</button>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Alterar senha</h2>

        <form action="/meu-perfil/senha" method="POST" class="space-y-4">

            <?php echo csrf_field(); ?>
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Senha atual</label>
                <input type="password" name="senha_atual" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Nova senha</label>
                <input type="password" name="nova_senha" required minlength="6"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Confirmar nova senha</label>
                <input type="password" name="confirmar_senha" required minlength="6"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500">
            </div>

            <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700">Alterar senha</button>
        </form>
    </div>
</div>