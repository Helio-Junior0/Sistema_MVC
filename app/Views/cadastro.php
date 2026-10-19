<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Portal Consenso PGE-MT</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background: radial-gradient(circle at top left, #1e3a8a, #1e40af);
            background-attachment: fixed;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        }

        .glass-input {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
        }

        .glass-input:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.5);
            outline: none;
        }

        .glass-input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center py-10">
    <div class="w-full max-w-md p-4">
        <div class="glass-card rounded-2xl p-8">
            <div class="text-center mb-8">
                <h2 class="text-white text-lg font-bold uppercase tracking-[0.2em]">Criar Conta</h2>
            </div>

            <form action="/cadastro" method="POST" class="space-y-5">
                <?php echo csrf_field(); ?>

                <div>
                    <input class="glass-input rounded-lg w-full py-3 px-4"
                        type="text" name="nome" placeholder="Nome completo" required autofocus>
                </div>

                <div>
                    <input class="glass-input rounded-lg w-full py-3 px-4"
                        type="text" name="cpf" placeholder="CPF / CNPJ" required>
                </div>

                <div>
                    <input class="glass-input rounded-lg w-full py-3 px-4"
                        type="email" name="email" placeholder="E-mail">
                </div>

                <div>
                    <input class="glass-input rounded-lg w-full py-3 px-4"
                        type="password" name="senha" placeholder="Senha" required>
                </div>

                <div>
                    <input class="glass-input rounded-lg w-full py-3 px-4"
                        type="password" name="confirmar_senha" placeholder="Confirmar senha" required>
                </div>

                <div>
                    <button class="bg-white hover:bg-blue-50 text-blue-900 font-bold py-3 px-4 rounded-xl w-full transition"
                        type="submit">
                        Cadastrar
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center">
                <a href="/login" class="text-xs text-white/60 hover:text-white transition">
                    Já tem conta? Fazer login
                </a>
            </div>
        </div>
    </div>
</body>

</html>