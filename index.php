<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MeuFreela - Conectando Talentos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="index.php">MeuFreela</a>
            <div class="d-flex">
                <a class="nav-link me-3" href="procurar_vagas.php">Vagas</a>
                <a class="nav-link me-3" href="login.php">Entrar</a>
                <a class="btn btn-primary rounded-pill" href="cadastro.php">Cadastrar</a>
            </div>
        </div>
    </nav>

    <header class="bg-primary text-white text-center py-5">
        <div class="container">
            <h1 class="display-4">Encontre a vaga de freelancer ideal</h1>
            <p class="lead">Oportunidades em Jaraguá do Sul e região</p>
            <form action="procurar_vagas.php" method="GET" class="mt-4">
                <div class="input-group input-group-lg">
                    <input type="text" name="q" class="form-control" placeholder="Digite o cargo ou palavra-chave">
                    <button class="btn btn-dark" type="submit">Buscar Vagas</button>
                </div>
            </form>
        </div>
    </header>

    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">Como Funciona?</h2>
            <div class="row text-center">
                <div class="col-md-4">
                    <h3>1. Cadastre-se</h3>
                    <p>Crie sua conta como freelancer ou contratante de forma rápida e fácil.</p>
                </div>
                <div class="col-md-4">
                    <h3>2. Explore Vagas</h3>
                    <p>Navegue e encontre as oportunidades que mais combinam com seu perfil.</p>
                </div>
                <div class="col-md-4">
                    <h3>3. Candidate-se</h3>
                    <p>Envie sua candidatura para as vagas de interesse com apenas um clique.</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-light text-center py-3 mt-auto">
        <div class="container">
            <p class="mb-0">MeuFreela &copy; 2025</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>