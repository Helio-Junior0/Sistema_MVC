<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login da pagina</title>
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
        .glass-input::placeholder { color: rgba(255, 255, 255, 0.5); }
    </style>
</head>
<body class="h-screen flex items-center justify-center">
    <div class="w-full max-w-md p-4">
        <div class="glass-card rounded-2xl p-8">
            <div class="text-center mb-10">
                <h2 class="text-white text-lg font-bold uppercase tracking-[0.2em]">LOGIN</h2>
            </div>

            <!-- TEMPORARIO: action vai virar base_url('/login') -->
            <form action="/login" method="POST" class="space-y-6">

                <!-- TEMPORARIO: aqui vai entrar o csrf_field() -->

                <div>
                    <input class="glass-input rounded-lg w-full py-3 px-4" 
                           id="cpf" type="text" name="cpf" placeholder="CPF" required autofocus>
                </div>

                <div>
                    <input class="glass-input rounded-lg w-full py-3 px-4" 
                           id="senha" type="password" name="senha" placeholder="Senha" required>
                </div>

                <div>
                    <button class="bg-white hover:bg-blue-50 text-blue-900 font-bold py-3 px-4 rounded-xl w-full transition" 
                            type="submit">
                        Entrar no Sistema
                    </button>

                </div>
            </form>

            <div class="mt-10 pt-6 border-t border-white/10 text-center">
                <p class="text-[10px] text-white/40 uppercase tracking-tight">
                    Desenvolvido por PGE-MT &copy; <?php echo date('Y'); ?>
                </p>
            </div>
        </div>
    </div>
</body>
</html>