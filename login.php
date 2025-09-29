<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"><title>Entrar - MeuFreela</title><link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="panel">
        <h1>Entrar na Conta</h1>
        <form action="processa_login.php" method="POST">
            <label for="email">E-mail:</label><input type="email" id="email" name="email" required>
            <label for="senha">Senha:</label><input type="password" id="senha" name="senha" required>
            <input type="submit" value="Entrar">
        </form>
        <p style="text-align: center; margin-top: 1rem;"><a href="recuperar_senha.php">Esqueceu sua senha?</a></p>
        <a href="index.php" class="button button-secondary">Voltar ao Início</a>
    </div>
</body>
</html>