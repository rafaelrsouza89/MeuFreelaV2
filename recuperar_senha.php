<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Senha - MeuFreela</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="panel">
        <h1>Recuperar Senha</h1>
        <p style="text-align: center;">Insira seu e-mail para receber um link de recuperação.</p>

        <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
            <p style="color: green; text-align:center;">Se o e-mail existir, um link foi enviado.</p>
        <?php endif; ?>
        
        <form action="processa_recuperacao.php" method="POST">
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required>
            <input type="submit" value="Enviar Link">
        </form>

        <p style="text-align: center; margin-top: 15px;">
            <a href="login.php">Voltar para o Login</a>
        </p>
    </div>
</body>
</html>