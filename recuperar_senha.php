<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"><title>Recuperar Senha - MeuFreela</title><link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="main-header"><div class="container"><a href="index.php" class="logo">MeuFreela</a><nav class="main-nav"><a href="login.php">Entrar</a></nav></div></header>
    <main><div class="container"><div class="job-listing-panel" style="max-width: 500px; margin: 2rem auto;">
        <h1 style="text-align: center;">Recuperar Senha</h1>
        <form action="processa_recuperacao.php" method="POST">
            <label for="email">Seu E-mail:</label><input type="email" id="email" name="email" required>
            <input type="submit" value="Enviar Link de Recuperação" style="border-radius: 5px; width: 100%;">
        </form>
    </div></div></main>
</body>
</html>