<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar - MeuFreela</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">
    <!-- Botão de Voltar estilizado -->
    <button type="button" class="btn btn-outline-secondary mt-4 mb-2 d-inline-block" style="border-radius: 20px; font-weight: 500;" onclick="history.back();">
        &larr; Voltar
    </button>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card shadow-lg border-0 rounded-lg mt-2">
                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Criar Conta</h3></div>
                    <div class="card-body">
                        <form action="processa_cadastro.php" method="POST">
                            <div class="form-floating mb-3"><input class="form-control" id="nome" name="nome" type="text" placeholder="Nome Completo" required /><label for="nome">Nome Completo</label></div>
                            <div class="form-floating mb-3"><input class="form-control" id="email" name="email" type="email" placeholder="nome@exemplo.com" required /><label for="email">E-mail</label></div>
                            <div class="form-floating mb-3"><input class="form-control" id="senha" name="senha" type="password" placeholder="Senha" required /><label for="senha">Senha</label></div>
                            <div class="form-floating mb-3"><input class="form-control" id="telefone" name="telefone" type="text" placeholder="Telefone" required /><label for="telefone">Telefone</label></div>
                            <div class="mb-3">
                                <label for="tipo_usuario" class="form-label">Tipo de Usuário</label>
                                <select class="form-select" id="tipo_usuario" name="tipo_usuario" required>
                                    <option value="freelance">Freelancer</option>
                                    <option value="contratante">Contratante</option>
                                    <option value="ambos">Ambos</option>
                                </select>
                            </div>
                            <div class="mt-4 mb-0"><div class="d-grid"><button class="btn btn-primary btn-lg" type="submit">Criar Conta</button></div></div>
                        </form>
                    </div>
                    <div class="card-footer text-center py-3"><div class="small"><a href="login.php">Já tem uma conta? Faça login</a></div></div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>