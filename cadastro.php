<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"><title>Cadastrar - MeuFreela</title><link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="panel">
        <h1>Criar Nova Conta</h1>
        <form action="processa_cadastro.php" method="POST">
            <label for="nome">Nome Completo:</label><input type="text" id="nome" name="nome" required>
            <label for="email">E-mail:</label><input type="email" id="email" name="email" required>
            <label for="senha">Senha:</label><input type="password" id="senha" name="senha" required>
            <label for="telefone">Telefone:</label><input type="text" id="telefone" name="telefone" required>
            <label for="tipo_usuario">Eu sou:</label>
            <select id="tipo_usuario" name="tipo_usuario" required>
                <option value="">Selecione...</option><option value="freelancer">Freelancer</option><option value="contratante">Contratante</option><option value="ambos">Ambos</option>
            </select>
            <input type="submit" value="Criar Conta">
        </form>
        <a href="index.php" class="button button-secondary">Voltar ao Início</a>
    </div>
</body>
</html>