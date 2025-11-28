<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar - MeuFreela</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="index.php">MeuFreela</a>
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="d-flex justify-content-end mt-4 mb-2">
                    
                </div>
                <div class="card shadow-lg border-0 rounded-lg mt-5">
                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Acessar Conta</h3></div>
                    <div class="card-body">
                        <form action="processa_login.php" method="POST">
                            <div class="form-floating mb-3">
                                <input class="form-control" id="email" name="email" type="email" placeholder="nome@exemplo.com" required />
                                <label for="email">E-mail</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input class="form-control" id="senha" name="senha" type="password" placeholder="Senha" required />
                                <label for="senha">Senha</label>
                            </div>
                            <div class="d-grid"><button class="btn btn-primary btn-lg" type="submit">Entrar</button></div>
                        </form>
                    </div>
                    <div class="card-footer text-center py-3">
                        <div class="small"><a href="cadastro.php">Não tem uma conta? Cadastre-se!</a></div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>