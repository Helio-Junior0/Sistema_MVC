<style>
    body { font-family: sans-serif; font-size: 11px; color: #1f2937; }
    h1 { font-size: 18px; margin-bottom: 4px; }
    .sub { color: #6b7280; margin-bottom: 16px; }
    table { width: 100%; border-collapse: collapse; }
    th { background: #1e3a8a; color: #fff; text-align: left; padding: 6px; }
    td { padding: 6px; border-bottom: 1px solid #e5e7eb; }
</style>

<h1>Relatório de Usuários</h1>
<div class="sub">Gerado em <?php echo date('d/m/Y H:i'); ?> por <?php echo htmlspecialchars($_SESSION['user_nome']); ?></div>

<table>
    <thead>
        <tr>
            <th>Nome</th>
            <th>E-mail</th>
            <th>CPF/CNPJ</th>
            <th>Perfil</th>
            <th>Ativo</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($usuarios as $usuario): ?>
            <tr>
                <td><?php echo htmlspecialchars($usuario['nome']); ?></td>
                <td><?php echo htmlspecialchars($usuario['email'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($usuario['cpf_cnpj']); ?></td>
                <td><?php echo htmlspecialchars($usuario['perfil']); ?></td>
                <td><?php echo $usuario['ativo'] ? 'Sim' : 'Não'; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>