<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar - MeuFreela</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="panel">
        <h1>Entrar na Conta</h1>
        
        <?php if (isset($_GET['error'])): ?>
            <p style="color: red; text-align: center;">E-mail ou senha inválidos.</p>
        <?php endif; ?>

        <form action="processa_login.php" method="POST">
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required>

            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>

            <input type="submit" value="Entrar">
        </form>
        
        <p style="text-align: center; margin-top: 15px;">
            <a href="recuperar_senha.php">Esqueceu sua senha?</a>
        </p>
        <p style="text-align: center; margin-top: 15px;">
            Não tem uma conta? <a href="cadastro.php">Cadastre-se</a>
        </p>
    </div>

</body>
</html>