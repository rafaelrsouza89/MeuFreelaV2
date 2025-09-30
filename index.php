<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MeuFreela - Encontre Vagas na sua Região</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <header class="main-header">
        <div class="container">
            <a href="index.php" class="logo">MeuFreela</a>
            <nav class="main-nav">
                <a href="procurar_vagas.php">Vagas</a>
                <a href="login.php">Entrar</a>
                <a href="cadastro.php" class="button-primary">Cadastrar</a>
            </nav>
        </div>
    </header>

    <main>
        <section class="hero-section">
            <div class="container">
                <h1>Encontre a vaga de freelancer ideal para você</h1>
                <p>Oportunidades em Jaraguá do Sul e região</p>
                <div class="search-container">
                    <form action="procurar_vagas.php" method="GET" style="width:100%; display:flex; justify-content:center;">
                        <input type="text" name="q" placeholder="Digite o cargo ou palavra-chave">
                        <button type="submit">Buscar</button>
                    </form>
                </div>
            </div>
        </section>

        <div class="container" style="margin-top: 2rem; text-align: center;">
            <h2>Vagas Recentes</h2>
            </div>
    </main>

</body>
</html>