<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha - MeuFreela</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="card shadow-lg border-0 rounded-lg mt-5">
                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Recuperar Senha</h3></div>
                    <div class="card-body">
                        <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
                            <div class="alert alert-success" role="alert">
                                Se o e-mail existir em nosso sistema, um link de recuperação foi enviado.
                            </div>
                        <?php endif; ?>
                        <p class="text-muted text-center small mb-4">Insira seu e-mail e enviaremos um link para você redefinir sua senha.</p>
                        <form action="processa_recuperacao.php" method="POST">
                            <div class="form-floating mb-3">
                                <input class="form-control" id="email" name="email" type="email" placeholder="nome@exemplo.com" required />
                                <label for="email">E-mail</label>
                            </div>
                            <div class="d-grid mt-4">
                                <button class="btn btn-primary btn-lg" type="submit">Enviar Link</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center py-3">
                        <div class="small"><a href="login.php">Lembrou a senha? Voltar para o login</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
<a href="index.php" class="btn btn-outline-secondary mt-4 mb-2 d-inline-block">
    &larr; Voltar
</a>
</html>