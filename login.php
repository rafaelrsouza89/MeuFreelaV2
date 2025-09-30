<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"><title>Entrar - MeuFreela</title><link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="main-header">
        <div class="container">
            <a href="index.php" class="logo">MeuFreela</a>
            <nav class="main-nav">
                <a href="procurar_vagas.php">Vagas</a>
                <a href="cadastro.php" class="button-primary">Cadastrar</a>
            </nav>
        </div>
    </header>
    <main>
        <div class="container">
            <div class="job-listing-panel" style="max-width: 500px; margin: 2rem auto;">
                <h1 style="text-align: center;">Acessar minha conta</h1>
                <form action="processa_login.php" method="POST">
                    <label for="email">E-mail:</label>
                    <input type="email" id="email" name="email" required>
                    <label for="senha">Senha:</label>
                    <input type="password" id="senha" name="senha" required>
                    <input type="submit" value="Entrar" style="border-radius: 5px; width: 100%;">
                </form>
                <p style="text-align: center; margin-top: 1rem;"><a href="recuperar_senha.php">Esqueceu sua senha?</a></p>
            </div>
        </div>
    </main>
</body>
</html>