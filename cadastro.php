<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar - MeuFreela</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="panel">
        <h1>Criar Nova Conta</h1>

        <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
            <p style="color: green; text-align:center;">Cadastro realizado com sucesso!</p>
        <?php endif; ?>

        <form action="processa_cadastro.php" method="POST">
            <label for="nome">Nome Completo:</label>
            <input type="text" id="nome" name="nome" required>

            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required>

            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>

            <label for="telefone">Telefone:</label>
            <input type="text" id="telefone" name="telefone" required>

            <label for="tipo_usuario">Eu sou:</label>
            <select id="tipo_usuario" name="tipo_usuario" required>
                <option value="">Selecione...</option>
                <option value="freelancer">Freelancer</option>
                <option value="contratante">Contratante</option>
                <option value="ambos">Ambos</option>
            </select>

            <input type="submit" value="Criar Conta">
        </form>

        <p style="text-align: center; margin-top: 15px;">
            Já tem uma conta? <a href="login.php">Faça login</a>
        </p>
    </div>

</body>
</html>